<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\Subscribe;
use App\Services\Metrics;
use App\Services\UserCodeService;
use Illuminate\Console\Command;

class SubscribeNotifyCommand extends DefaultCommand
{
    protected $signature = 'subscribe:notify {id} {code}';

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
        $id = $this->argument('id');
        $code = $this->argument('code');
        if ($id && $code) {
            $subscribe = Subscribe::find($id);
            $donater = $subscribe->getDonater();
            $volunteer = $subscribe->getVolunteer();
            $codeText = $donater->getUserCode() . '%20' . $code;
            $template = <<<'MD'
                Ваш волонтер @:volunteerKey чекає на ваш донат в :amount ₴ на збір :fundLink

                Будь ласка, зробіть донат по посиланню :jarLink

                Зверніть увагу, що коментар має формат `:codeText` - це для розрахунків надійності вас як серійного донатера по підписці.
                MD;
            /** @var Fundraising $randomFundraising */
            $randomFundraising = $volunteer->getRandomFundraising();
            if (!$randomFundraising) {
                return;
            }
            $message = strtr($template, [
                '  ' => '',
                ':volunteerKey' => $volunteer->getUsername(),
                ':codeText' => $donater->getUserCode() . ' ' . $code,
                ':jarLink' => $randomFundraising->getJarLink() . '?t=' . $codeText . '&a=' . $subscribe->getAmount(),
                ':amount' => $subscribe->getAmount(),
                ':fundLink' => $randomFundraising->getShortLink(),
            ]);
            $callToAction = "\n\nВи можете скопіювати запрошення для ваших друзів робити як ви нижче (копіює по кліку)\n\n`Я підтримую :volunteer донатом за моїм розкладом. Мені в телеграм кожен день приходить посилання в обраний мною час. Прошу вас робити так само. Донатьте. Будь ласка ❤️`";
            $donater->sendBotMessage($message . strtr($callToAction, [':volunteer' => $randomFundraising->getShortLink()]));
            $this->saveMetric(Metrics::SUBSCRIBE_SCHEDULER);
        }
    }
}
