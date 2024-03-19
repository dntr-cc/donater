<?php

namespace App\Services;

use App\Models\FundraisingShortCode;

class FundraisingShortCodeService
{
    public const string CODE_TEMPLATE = 'dntr.cc/f/:code';
    public const int CODE_LENGTH = 5;

    public function createFundraisingShortCode(int $fundraisingId, string $code)
    {
        return FundraisingShortCode::create([
            'code'    => $code,
            'fundraising_id' => $fundraisingId,
        ]);
    }

    public function createCode(string $code = ''): string
    {
        if ($code && !$this->isUsedCode($code)) {
            return $code;
        }
        $vocabulary = array_merge(range('a', 'z'), range(0, 9));
        $vocabularyIndexes = count($vocabulary) - 1;
        $code = '';
        do {
            $length = self::CODE_LENGTH;
            while ($length--) {
                $code .= $vocabulary[mt_rand(0, $vocabularyIndexes)];
            }
        } while ($this->isUsedCode($code));

        return $code;
    }

    public function getShortLink(int $fundraisingId, string $code = ''): string
    {
        $item = $this->getFundraisingShortCode($fundraisingId);
        if (!$item) {
            $item = $this->createFundraisingShortCode($fundraisingId, $this->createCode($code));
        }

        return strtr(self::CODE_TEMPLATE, [':code' => $item->getCode()]);
    }

    /**
     * @param int $fundraisingId
     * @return FundraisingShortCode|null
     */
    public function getFundraisingShortCode(int $fundraisingId): ?FundraisingShortCode
    {
        return FundraisingShortCode::query()->where('fundraising_id', '=', $fundraisingId)->get()->first();
    }

    public function isUsedCode(string $code): bool
    {
        return FundraisingShortCode::hasCode($code);
    }
}
