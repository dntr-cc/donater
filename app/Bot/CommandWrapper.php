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
