<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\Subscribe;
use App\Models\SubscribesMessage;
use App\Services\Metrics;
use App\Services\UserCodeService;
use Carbon\Carbon;

class SubscribeNotifyCommand extends DefaultCommand
{
    protected $signature = 'subscribe:notify {id} {code}';

    protected $description = 'Command that sends a specific subscriber (identified by their ID and a unique code) a notification message, encouraging them to make a donation to a randomly selected opened fundraising event.';

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
            $nextMessage = $subscribe->getNextSubscribesMessage();
            $openFundraisings = $subscribe->getVolunteer()->getFundraisings()
                ->filter(fn(Fundraising $fundraising) => $fundraising->isEnabled())
                ->count();
            $nextMessage->update(['need_send' => (bool)$openFundraisings]);
            $code = $nextMessage->getNotificationCode();
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
            $nextScheduledAt = $nextMessage->getScheduledAt()->modify($subscribe->getModifier($nextMessage->getFrequency()));
            $time = (new Carbon())->setTimezone(config('app.timezone'));
            while ($nextScheduledAt < $time) {
                $nextScheduledAt = $nextScheduledAt->modify($subscribe->getModifier($nextMessage->getFrequency()));
            }
            SubscribesMessage::create([
                'subscribes_id' => $subscribe->getId(),
                'frequency' => $nextMessage->getFrequency(),
                'scheduled_at' => $nextScheduledAt,
                'need_send' => (bool)$openFundraisings,
                'hash' => SubscribesMessage::generateHash($subscribe->getId(), $nextScheduledAt->format('Y-m-d H:i:s')),
            ]);
            $this->saveMetric(Metrics::SUBSCRIBE_NOTIFY);
        }
    }
}
