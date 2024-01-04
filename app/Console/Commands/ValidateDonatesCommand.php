<?php

namespace App\Console\Commands;

use App\Models\Donate;
use App\Models\Fundraising;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use App\Services\UserCodeService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class ValidateDonatesCommand extends Command
{
    protected $signature = 'validate:donates {id}';

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
            $fundraisingId = $fundraising->getId();
            $rows = $this->service->getRowCollection($fundraising->getSpreadsheetId(), $fundraisingId);
            foreach ($rows->all() as $item) {
                $this->output->info("{$item->getDate()} !!!");
                $amount = round((float)$item->getAmount(), 2);
                if ($amount > 0 && !$item->isOwnerTransaction()) {
                    $code = $item->extractCode($item->getComment());
                    $userId = $userCodesService->getUserIdByCode($code);
                    if (!$userId) {
                        $this->output->info("+$code $userId continue");
                        continue;
                    }
                    $createdAt = new Carbon(strtotime($item->getDate()));
                    $donateExist = Donate::query()->where('created_at', '=', $createdAt)
                        ->where('user_id', '=', $userId)
                        ->exists();
                    if ($donateExist) {
                        $this->output->info("-$donateExist continue");
                        continue;
                    }
                    $donate = Donate::create([
                        'user_id'        => $userId,
                        'amount'         => $amount,
                        'hash'           => $code,
                        'fundraising_id' => $fundraisingId,
                        'created_at'     => $createdAt,
                    ]);
                    $this->output->info('Validated object: ' . $donate->toJson());
                    $telegramId = User::find($userId)->getTelegramId();
                    if ($telegramId) {
                        $strtr = strtr('Ваш внесок в :amount за :date було завалідовано! Подивитися звіт: :url', [
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
