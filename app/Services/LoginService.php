<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Referral;
use App\Models\User;
use Chypriote\UniqueNames\Generator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cache;
use Storage;
use Telegram\Bot\Laravel\Facades\Telegram;

class LoginService
{
    public const string LOGIN_START = 'login:start:key';
    public const string LOGIN_END = 'login:end:key';

    public function getNewLoginHash(): string
    {
        $uid = app(UserCodeService::class)->createLoginCode();
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
        $fatherId   = Cache::pull('fg:' . sha1(request()->userAgent() . implode(request()->ips())));
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
            if ($fatherId) {
                Referral::create([
                    'user_id' => $fatherId,
                    'referral_id' => $user->getId(),
                ]);
            }
        }

        $user->setIsPremium($data['is_premium'] ?? false)->save();

        return $user;
    }

    /**
     * @return string
     */
    protected function generateUniqueUserName(): string
    {
        $donaters = ['dntr.cc/8SC8c', 'dntr.cc/RertD', 'dntr.cc/GqBfa', 'dntr.cc/8EDoz', 'dntr.cc/oMkRA', 'dntr.cc/T4i15', 'dntr.cc/8o1wk', 'dntr.cc/kGxNF', 'dntr.cc/rdxaa',];
        echo $donaters[mt_rand(0, count($donaters) - 1)];

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
