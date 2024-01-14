<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\Subscribe;
use App\Services\UserCodeService;
use Illuminate\Console\Command;

class SubscribeProcessCommand extends Command
{
    protected $signature = 'subscribe:process {id}';

    protected $description = 'Command description';

    protected UserCodeService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = app(UserCodeService::class);
    }

    public function getService(): UserCodeService
    {
        return $this->service;
    }

    public function handle(): void
    {
        if ($id = $this->argument('id')) {
            $subscribe = Subscribe::find($id);
            $donater = $subscribe->getDonater();
            $volunteer = $subscribe->getVolunteer();
            $template = <<<'MD'
                Ваш волонтер @:volunteerKey чекає на ваш донат в :amount грн.

                Будь ласка, зробіть донат по посиланню :jarLink

                Не забудьте в коментарі додати ваш код донатера `:donaterCode`
                MD;
            /** @var Fundraising $randomFundraising */
            $randomFundraising = $volunteer->getRandomFundraising();
            if (!$randomFundraising && !$subscribe->isUseRandom()) {
                return;
            }
            if (!$randomFundraising && $subscribe->isUseRandom()) {
                $randomFundraising = Fundraising::getRandom();
                $template = strtr(strtr(<<<'MD'
                    Ваш волонтер @:volunteerKey не має відкритого збору. Ми пропонуємо вам зробити донат @:newVolunteerKey в розмірі :amount грн.

                    Будь ласка, зробіть донат по посиланню :jarLink

                    Не забудьте в коментарі додати ваш код донатера `:donaterCode`
                    MD, [':volunteerKey' => $volunteer->getUsername()]), [':newVolunteerKey' => ':volunteerKey']);
                $volunteer = $randomFundraising->volunteer()->get()->first();
            }
            $message = strtr($template, [
                '  ' => '',
                ':volunteerKey' => $volunteer->getUsername(),
                ':jarLink' => $randomFundraising->getJarLink() . '?t=' . $donater->getUserCode() . '&a=' . $subscribe->getAmount(),
                ':amount' => $subscribe->getAmount(),
                ':donaterCode' => $donater->getUserCode(),
            ]);
            $donater->sendBotMessage($message);
        }
    }
}
