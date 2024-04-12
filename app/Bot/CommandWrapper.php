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
use App\Services\UserCodeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;
use Throwable;

class CommandWrapper
{
    public function handle(Update $update)
    {
        $message = $update->getMessage();
        try {
            $chat = $message?->getChat();
            if (!$chat) {
                return;
            }
            $from = $message?->getFrom();
            if (!$from) {
                return;
            }
            $chatId = $chat->getId();
        } catch (Throwable $t) {
            Log::error($t->getMessage());
            return;
        }
        if (Cache::get('ru' . $chatId)) {
            Cache::delete('ru' . $chatId);
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text'    => '🇺🇦 РЕЖИМ ЛАГІДНОЇ УКРАЇНІЗАЦІЇ ДЕАКТИВОВАНО.',
            ]);
        }
        Telegram::sendChatAction([
            'chat_id' => $chatId,
            'action'  => Actions::TYPING,
        ]);
        $text = $message?->getText();
        if ($this->chatForBraveryUkrainian(mb_strtolower((string)$text), (string)$chatId)) {
            return;
        }
        $isExist = Cache::pull('login:start:' . $text);
        if ('ru' === $from->getLanguageCode()) {
            Cache::set('ru' . $chatId, true);
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text'    => '🇺🇦 РЕЖИМ ЛАГІДНОЇ УКРАЇНІЗАЦІЇ АКТИВОВАНО. Для продовження змінить мову в додатку Телеграм. У разі відсутності подальших взаємодій з додатку з українською мовою ваш telegram user id буде додано в базу даних Служби Безпеки України 🚩',
            ]);
            return;
        }
        if ($isExist) {
            Cache::set('login:end:' . $text, $from->toJson(), 60);
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text'    => StartCommand::AUTHORISE_SUCCESS,
            ]);
            return;
        }
        $matches = [];
        preg_match('/deep[0-9a-f]+/', (string)$text, $matches);
        if (isset($matches[0]) && Cache::has($matches[0])) {
            Cache::forget($matches[0]);
            $deepLink = DeepLink::query()->where('hash', '=', strtr((string)$matches[0], ['deep' => '']))->first();
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
            return;
        }
        preg_match('/login-[0-9a-f]{' . UserCodeService::CODE_LENGTH . '}/', (string)$text, $matches);
        $responseText = isset($matches[0]) ?
            "Ключ входу `{$text}` не знайдено! Спробуйте оновити сторінку donater.com.ua/login" :
            'Я не розумію що ви мені надіслали. Сайт проекту donater.com.ua. Слава Україні!';
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text'    => $responseText,
        ]);
    }

    public function chatForBraveryUkrainian(string $text, string $chatId): bool
    {
        $text = trim(strtr($text, ['!' => '', '1' => '']));
        $answer = match ($text) {
            'слава україні' => 'Героям Слава',
            'слава нації' => 'Смерть ворогам',
            'україна' => 'Понад усе',
            'героям слава' => 'Слава Нації',
            'смерть ворогам' => 'Україна!',
            default => '',
        };
        if (!empty($answer)) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text'    => $answer,
            ]);
            return true;
        }

        return false;
    }
}
