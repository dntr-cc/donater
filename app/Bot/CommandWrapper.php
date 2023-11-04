<?php

declare(strict_types=1);

namespace App\Bot;

use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class CommandWrapper
{
    public function handle(Update $update)
    {
        $message = $update->getMessage();
        $chat    = $message->getChat();
        $from    = $message->getFrom();
        $chatId  = $chat->getId();
        Telegram::sendChatAction([
            'chat_id' => $chatId,
            'action' => Actions::TYPING,
        ]);
        $text = $message->getText();
        $isExist = Cache::pull('login:start:' . $text);
        if ('ru' === $from->getLanguageCode()) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'Режим лагідної українізації активовано. Для продовження змінить мову в додатку Телеграм.',
            ]);
            return;
        }
        if ($isExist) {
            Cache::set('login:end:' . $text, $from->toJson(), 60);
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'Вхід дозволено!',
            ]);
            return;
        }
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "Ключ входу `{$text}` не знайдено! Спробуйте оновити сторінку donater.com.ua/login",
        ]);
    }
}
