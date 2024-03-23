<?php

namespace App\Console\Commands;

use App\Events\OpenGraphRegenerateEvent;
use App\Models\Donate;
use App\Models\Fundraising;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\GoogleServiceSheets;
use App\Services\UserCodeService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class DonatesValidateCommand extends Command
{
    protected $signature = 'donates:validate {id}';

    protected $description = 'Command description';
    private GoogleServiceSheets $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = app(GoogleServiceSheets::class);
    }

    public function getService(): GoogleServiceSheets
    {
        return $this->service;
    }

    public function handle(): void
    {
        if ($id = $this->argument('id')) {
            $userCodesService = app(UserCodeService::class);
            $fundraising = Fundraising::find($id);
            if (!$fundraising) {
                return;
            }
            $fundraisingId = $fundraising->getId();
            $rows = $this->service->getRowCollection($fundraising->getSpreadsheetId(), $fundraisingId);
            foreach ($rows->all() as $item) {
                $amount = round((float)$item->getAmount(), 2);
                if ($amount > 0 && !$item->isOwnerTransaction() && preg_match('/^[0-9]{2}.[0-9]{2}.[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $item->getDate())) {
                    $code = $item->extractCode($item->getComment());
                    if (empty($code)) {
                        continue;
                    }
                    $userId = $userCodesService->getUserIdByCode($code);
                    if (!$userId) {
                        continue;
                    }
                    $createdAt = new Carbon(strtotime($item->getDate()));
                    $donateExist = Donate::query()->where('created_at', '=', $createdAt)
                        ->where('user_id', '=', $userId)
                        ->exists();
                    if ($donateExist) {
                        continue;
                    }
                    $donate = Donate::create([
                        'user_id'        => $userId,
                        'amount'         => $amount,
                        'hash'           => $code,
                        'fundraising_id' => $fundraisingId,
                        'created_at'     => $createdAt,
                    ]);
                    $user = User::find($userId);
                    OpenGraphRegenerateEvent::dispatch($user->getId(), OpenGraphRegenerateEvent::TYPE_USER);
                    OpenGraphRegenerateEvent::dispatch($fundraising->getVolunteer()->getId(), OpenGraphRegenerateEvent::TYPE_USER);
                    $telegramId = $user->getTelegramId();
                    if ($user->settings->hasSetting(UserSetting::DONT_SEND_MARKETING_MESSAGES)) {
                        continue;
                    }
                    if ($telegramId) {
                        $strtr = strtr('Ваш внесок в :amountгрн. за :date було завалідовано! Подивитися звіт: :url', [
                            ':amount' => $amount,
                            ':date'   => $createdAt->toString(),
                            ':code'   => $donate->getHash(),
                            ':url'    => route('fundraising.show', ['fundraising' => $fundraising->getKey()]),
                        ]);
                        $this->output->info($strtr);
                        Telegram::sendMessage([
                            'chat_id' => $telegramId,
                            'text'    => $strtr,
                        ]);
                    }
                }
            }
        }
    }
}
