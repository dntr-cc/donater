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
use Throwable;

class LoginService
{
    public const string LOGIN_START = 'login:start:key';
    public const string LOGIN_END = 'login:end:key';
    const string WELCOME_TEXT = <<<'MD'
Вітаємо вас з реєстрацією! Ваш профіль на сайті автоматично заповнило даними з телеграму.

Якщо ви волонтер, то ми зняли відео як користуватися платформою та додати свій збір: https://youtu.be/s6El9IK88LI (12хв, можна дивитися х2).
Після додавання збору запрошуйте своїх донатерів підписуватися на вас на регулярні донати, вони зможуть обрати суму та періодичність. Вам приде повідомлення, коли хтось підпишеться.
Телеграм бот буде присилати їм посилання на банку на відкритий вами збір. Якщо ви його закриєте, та відкриєте наступний - їм нічого не треба робити, вони постійно отримують посилання на монобанку актуального відкритого вами збору.
Якщо відкритого збору немає - вони не отримають повідомлення з нагадуванням. Як зупиняти та запускати збір ви зможете побачити на відео.

Якщо ви донатер/ка - скоріш підписуйтеся на свого волонтера! Дякуємо вам за підтримку Сил Оборони України!
MD;


    public function getNewLoginHash(): string
    {
        $uid = app(UserCodeService::class)->createLoginCode();
        Cache::set(strtr(self::LOGIN_START, ['key' => $uid]), $uid, 60 * 60 * 24 * 7);

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
     * @throws Throwable
     */
    public function getOrCreateUser(array $data, bool $tryGetPhoto = true): User
    {
        $telegramId = $data['id'] ?? 0;
        $user = User::where('telegram_id', $telegramId)->first();
        $avatar = '/images/avatars/avatar.jpeg';
        if (!$user) {
            $username = $data['username'] ?? $this->generateUniqueUserName();
            if ($tryGetPhoto) {
                {
                    $photos = Telegram::getUserProfilePhotos([
                        'user_id' => $telegramId,
                        'limit'   => 1,
                    ]);
                    if ($fileId = $photos->getPhotos()?->first()?->first()['file_id'] ?? null) {
                        $avatar = Telegram::downloadFile(Telegram::getFile(['file_id' => $fileId]), "/tmp/{$username}/");
                        $filesystem = Storage::disk('avatars');
                        $file = new File($avatar);
                        $filesystem->put($username, $file);
                        $file = $filesystem->files($username)[0] ?? '';
                        $avatar = '/images/avatars/' . $file;
                    }
                }
            }
            $user = User::create([
                'username'    => $username,
                'telegram_id' => $telegramId,
                'first_name'  => $data['first_name'] ?? '',
                'last_name'   => $data['last_name'] ?? '',
                'is_premium'  => $data['is_premium'] ?? false,
                'avatar'      => $avatar,
            ]);

            if ($fatherId = Cache::pull('referral_fg:' . sha1(request()->userAgent() . implode(request()->ips())))) {
                Referral::create([
                    'user_id'     => $fatherId,
                    'referral_id' => $user->getId(),
                ]);
            }
            $user->sendBotMessage(self::WELCOME_TEXT);
        }

        if ($user->isForget()) {
            $user->update(['forget' => false]);
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
