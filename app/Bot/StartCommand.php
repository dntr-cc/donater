<?php

declare(strict_types=1);

namespace App\Bot;

use App\Events\OpenGraphRegenerateEvent;
use App\Http\Controllers\SubscribeController;
use App\Models\DeepLink;
use App\Models\Subscribe;
use App\Models\SubscribesMessage;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\LoginService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class StartCommand extends Command
{
    public const string AUTHORISE_SUCCESS = 'Вас авторизовано на сайті. Оновіть сторінку, якщо це не сталося автоматично';
    protected string $name = 'start';

    /**
     * @var string Command Description
     */
    protected string $description = 'Привітання';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        // Check if the start command has been issued with a login code
        $message = $this->getUpdate()->getMessage();
        $text = str_replace('/start ', '', $message->getText());
        if (!empty($text)) {
            $isExist = Cache::pull('login:start:' . $text);
            if ($isExist) {
                $from = $message?->getFrom();
                Cache::set('login:end:' . $text, $from->toJson(), 60);
                $this->replyWithMessage(['text' => self::AUTHORISE_SUCCESS]);
            }
            $matches = [];
            preg_match('/deep[0-9a-z\-\_]+/', (string)$text, $matches);
            $code = strtr((string)$matches[0], ['deep' => '']);
            if (!empty($code) && Cache::has(':deep' . $code)) {
                $from = $message?->getFrom();
                $chatId = $message?->getChat()?->getId();
                if (!$chatId || !$from) {
                    return;
                }
                Cache::forget($matches[0]);
                $deepLink = DeepLink::query()->where('hash', '=', $code)->first();
                if (!$deepLink->exists()) {
                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text'    => 'Діп-лінк не знайдено!',
                    ]);
                    return;
                }
                /** @var User|null $volunteer */
                $volunteer = $deepLink->volunteer()->first();
                if (!$volunteer || !$volunteer->exists()) {
                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text'    => 'Волонтера не знайдено!',
                    ]);
                    return;
                }
                $user = app(LoginService::class)->getOrCreateUser($from->toArray());
                $date = Carbon::parse(date('Y-m-d') . ' ' . $deepLink->getStartedAt() . ':00')->setTimezone(config('app.timezone'));
                $data = [
                    'user_id'          => $user->getId(),
                    'frequency'        => SubscribesMessage::DAILY_NAME,
                    'volunteer_id'     => $volunteer->getId(),
                    'amount'           => $deepLink->getAmount(),
                    'first_message_at' => $date->isPast() ? $date->addDay() : $date,
                ];
                $subscribe = Subscribe::createOrRestore($data);
                $subscribesMessage = $subscribe->getNextSubscribesMessage();
                $subscribesMessage?->update(['need_send' => false]);
                SubscribesMessage::create([
                    'subscribes_id' => $subscribe->getId(),
                    'frequency'     => $subscribe->getFrequency(),
                    'scheduled_at'  => $subscribe->getFirstMessageAt(),
                    'need_send'     => true,
                    'hash'          => SubscribesMessage::generateHash($subscribe->getId(), $subscribe->getFirstMessageAt()->format('Y-m-d H:i:s')),
                ]);
                OpenGraphRegenerateEvent::dispatch($volunteer->getId(), OpenGraphRegenerateEvent::TYPE_USER);
                OpenGraphRegenerateEvent::dispatch($subscribe->getDonater()->getId(), OpenGraphRegenerateEvent::TYPE_USER);
                if (!$volunteer->settings->hasSetting(UserSetting::DONT_SEND_SUBSCRIBERS_INFORMATION)) {
                    $donater = $subscribe->getDonater();
                    $message = strtr(SubscribeController::SUBSCRIPTION_CREATE_MESSAGE, [
                        ':amount'    => $subscribe->getAmount(),
                        ':donater'   => $donater->getUsername(),
                        ':first'     => $subscribe->getFirstMessageAt(),
                        ':frequency' => mb_strtolower(SubscribesMessage::FREQUENCY_NAME_MAP[$subscribe->getFrequency() ?? ''] ?? ''),
                    ]);

                    $volunteer->sendBotMessage($message);
                }
                $textForUser = <<<'TXT'
                Вітаємо! Ви скористалися швидкою підпискою на волонтера :volunteer.

                Вас автоматично залогінило на сайті donater.com.ua, якщо ви раніше не користувалися цим сайтом, вам автоматично створено профіль.

                Посилання на ваш профіль :userLink

                Також вас автоматично підписано на вашого волонтера з наступними умовами:
                - перше нагадування ви отримаєте :time
                - сума донату :amount грн.

                В назначений час вам будуть приходити повідомлення з посиланням на банку актуального відккритого збору вашого волонтеру.

                В посиланні вже буде прописана сума донату та ваш код донатера, а також додатковий код, який допоможе слідкувати чи дотримуєтеся ви умов підписки.

                Якщо посилання не відкрилося одразу в моно - зверху буде кнопка "відкривати такі посилання в моно". Після цього ви зможете в один клік попадати в додаток моно, а сума та коментар вже будуть заповенені.

                Якщо волонтер не має відкритого збору - ви не отримає повідомлення. Однак якщо волонтер відкриє новий збір, ви одразу отримаєте нагадування задонатити. Після цього повідомлення наступні вже будуть за розкладом.

                Якщо ви хочете змінити умови підписки - скористайтеся сайтом donater.com.ua/login

                Якщо ви випадково відкрили це посилання - просто заблокуйте бота.

                Дякуємо вам за підтримку волонтерського руху Україні🇺🇦❤️!
                TXT;
                $user->sendBotMessage(strtr($textForUser, [
                    ':volunteer' => $volunteer->getUserLink(),
                    ':userLink' => $user->getUserLink(),
                    ':time' => $subscribe->getFirstMessageAt(),
                    ':amount' => $subscribe->getAmount(),
                ]));
            }
        } else {
            $this->replyWithMessage(['text' => 'Вітаю! Введіть код з сайту для авторизації ⬇️']);
        }
    }
}
