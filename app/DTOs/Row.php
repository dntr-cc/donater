<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Donate;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Support\Arrayable;
use Override;

class Row implements Arrayable
{
    protected string $date;
    protected string $category;
    protected string $from;
    protected string $comment;
    protected string $amount;
    protected string $currency;
    protected string $sum;
    private array $donates;
    /**
     * @var UserRepository|(UserRepository&\Illuminate\Contracts\Foundation\Application)|\Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|mixed
     */
    protected mixed $userRepository;

    /**
     * @param array $row
     * @param array $donates |Donates[]
     */
    public function __construct(array $row, array $donates = [])
    {
        $this->date = $row[0] ?? '';
        $this->category = $row[1] ?? '';
        $this->from = $row[3] ?? '';
        $this->comment = $row[3] ?? '';
        $this->amount = $row[4] ?? '';
        $this->currency = $row[5] ?? '';
        $this->sum = $row[6] ?? '';
        $this->donates = $donates;
        $this->userRepository = app(UserRepository::class);
    }

    public static function hasCode(string $comment): bool
    {
        return (bool)static::getCode($comment);
    }

    public function getDate(): string
    {
        return trim($this->date);
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getSum(): string
    {
        return $this->sum;
    }

    public function getDonater(string $comment): ?User
    {
        $code = $this->extractCode($comment);
        /** @var Donate $donate */
        $donate = $this->donates[$code] ?? $this->donates[mb_strtolower($code)] ?? null;
        if ($donate) {
            return $this->userRepository->find($donate->getUserId());
        }

        return null;
    }

    /**
     * @param string $comment
     * @return string
     */
    public function extractCode(string $comment): string
    {
        return static::getCode($comment);
    }

    public static function getCode(string $comment): string
    {
        $matches1 = $matches2 = $matches3 = [];
        preg_match('/dntr.cc\/[a-zA-Z0-9]+/', $comment, $matches1);
        preg_match('/[a-zA-Z0-9]{14}[.][0-9]{8}/', $comment, $matches2);
        preg_match('/[a-zA-Z0-9]{13}/', $comment, $matches3);
        $matches1 = explode('/', $matches1[0] ?? '');

        return $matches1[1] ?? $matches2[0] ?? $matches3[0] ?? '';
    }

    public function isOwnerTransaction(): bool
    {
        return 'Разові поповнення' === $this->getCategory();
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $comment
     * @return string
     */
    public function extractTrustCode(string $comment): string
    {
        return static::getTrustCode($comment);
    }

    public static function getTrustCode(string $comment): string
    {
        $matches = [];
        preg_match('/code:[0-9a-f]{8}/', $comment, $matches);

        return $matches[0] ?? '';
    }

    #[Override] public function toArray(): array
    {
        return [
            'date'     => $this->date,
            'category' => $this->category,
            'from'     => $this->from,
            'comment'  => $this->comment,
            'amount'   => $this->amount,
            'currency' => $this->currency,
            'sum'      => $this->sum,
        ];
    }
}
