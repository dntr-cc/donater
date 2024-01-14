<?php

namespace App\Services;

use App\Models\UserCode;
use Illuminate\Support\Facades\Cache;

class UserCodeService
{
    public const string DONATE_PREFIX = 'dntr.cc/';
    public const string LOGIN_PREFIX = 'login-';
    public const int CODE_LENGTH = 5;

    public function createUserCode(int $userId, string $hash)
    {
        return UserCode::create([
            'hash'    => $hash,
            'user_id' => $userId,
        ]);
    }

    public function createCode(string $prefix = self::DONATE_PREFIX): string
    {
        $vocabulary = array_merge(range('a', 'z'), range(0, 9));
        $vocabularyIndexes = count($vocabulary) - 1;
        $hash = '';
        do {
            $length = self::CODE_LENGTH;
            while ($length--) {
                $hash .= $vocabulary[mt_rand(0, $vocabularyIndexes)];
            }
        } while ($this->isUsedCode($prefix, $hash));

        return $hash;
    }

    public function createLoginCode(): string
    {
        $loginPrefix = self::LOGIN_PREFIX;

        return $loginPrefix . $this->createCode($loginPrefix);
    }

    public function isUsedCode(string $prefix, string $hash): bool
    {
        return match ($prefix) {
            self::LOGIN_PREFIX => $this->isUsedLoginCode($hash),
            self::DONATE_PREFIX => $this->isUsedDonateCode($hash),
            default => true,
        };
    }

    public function isUsedLoginCode(string $hash): bool
    {
        return Cache::has(self::LOGIN_PREFIX . $hash);
    }

    public function isUsedDonateCode(string $hash): bool
    {
        return UserCode::hasHash($hash);
    }

    public function getUserDonateCode(int $userId): string
    {
        $item = $this->getCode($userId);
        if (!$item || $item?->isOldCode()) {
            $item = $this->createUserCode($userId, $this->createCode());
        }

        return self::DONATE_PREFIX . $item->getHash();
    }

    public function getUserIdByCode(string $code): int
    {
        $key = 'userId:code:' . $code;
        if (Cache::has($key)) {
            return (int)Cache::get($key);
        }

        $item = $this->getUserCodeItem($code);
        if (!$item) {
            return 0;
        }
        $userId = $item->getUserId();
        Cache::set($key, $userId, 60 * 60 * 24);

        return $userId;
    }

    /**
     * @param int $userId
     * @return UserCode|null
     */
    public function getCode(int $userId): ?UserCode
    {
        return UserCode::getUserCode($userId);
    }

    /**
     * @param string $code
     * @return ?UserCode
     */
    public function getUserCodeItem(string $code): ?UserCode
    {
        return UserCode::query()->where('hash', '=', $code)
            ->orWhere('hash', '=', mb_strtolower($code))->first();
    }
}
