<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Chypriote\UniqueNames\Generator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cache;
use Storage;
use Telegram\Bot\Laravel\Facades\Telegram;

class LoginService
{
    public const LOGIN_START = 'login:start:key';
    public const LOGIN_END = 'login:end:key';

    public function getNewLoginHash(): string
    {
        $uid = uniqid();
        Cache::set(strtr(self::LOGIN_START, ['key' => $uid]), $uid, 60);

        return $uid;
    }

    /**
     * @param string $key
     * @return array
     */
    public function getCachedLoginData(string $key): array
    {
        return json_decode(Cache::pull(strtr(self::LOGIN_END, ['key' => $key]), fn() => '{}'), true);
    }

    /**
     * @param array $data
     * @param bool $tryGetPhoto
     * @return User
     */
    public function getOrCreateUser(array $data, bool $tryGetPhoto = true): User
    {
        $telegramId = $data['id'] ?? 0;
        $user       = User::where('telegram_id', $telegramId)->first();
        $avatar     = '/images/avatars/avatar.jpeg';
        if (!$user) {
            $username = $data['username'] ?? $this->generateUniqueUserName();
            if ($tryGetPhoto) {{
                $photos   = Telegram::getUserProfilePhotos([
                    'user_id' => $telegramId,
                    'limit'   => 1,
                ]);
                if ($fileId = $photos->getPhotos()?->first()?->first()['file_id'] ?? null) {
                    $avatar = Telegram::downloadFile(Telegram::getFile(['file_id' => $fileId]), "/tmp/{$username}/");
                    $filesystem = Storage::disk('avatars');
                    $file       = new File($avatar);
                    $filesystem->put($username, $file);
                    $file = $filesystem->files($username)[0] ?? '';
                    $avatar = '/images/avatars/' . $file;
                }
            }}
            $user = User::create([
                'username'    => $username,
                'telegram_id' => $telegramId,
                'first_name'  => $data['first_name'] ?? '',
                'last_name'   => $data['last_name'] ?? '',
                'is_premium'  => $data['is_premium'] ?? false,
                'avatar'      => $avatar,
            ]);
        }

        $user->setIsPremium($data['is_premium'] ?? false)->save();

        return $user;
    }

    /**
     * @return string
     */
    protected function generateUniqueUserName(): string
    {
        $generator = (new Generator())->setDictionaries(['colors', 'adjectives', 'animals']);
        $it = 0;
        while (1) {
            $username = $generator->generate();
            if (0 === User::query()->where('username', '=', $username)->count()) {
                return $username;
            }
            $username = $username . $it++;
            if (0 === User::query()->where('username', '=', $username)->count()) {
                return $username;
            }
        }
    }
}
