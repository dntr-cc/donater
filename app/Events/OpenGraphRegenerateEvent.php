<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class OpenGraphRegenerateEvent
{
    use Dispatchable;

    public const string TYPE_USER = 'user';
    public const string TYPE_FUNDRAISING = 'fundraising';

    protected int $id;
    protected string $type;

    public function __construct(int $id, string $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
