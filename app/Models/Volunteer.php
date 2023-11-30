<?php

namespace App\Models;

use App\Collections\DonateCollection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string $link
 * @property string $page
 * @property string $avatar
 * @property string $description
 * @property string $spreadsheet_id
 * @property bool $is_enabled
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property DonateCollection|null $donates
 */
class Volunteer extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'key',
        'name',
        'link',
        'page',
        'avatar',
        'description',
        'is_enabled',
        'user_id',
        'spreadsheet_id',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'key';
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('donates_count', static function (Builder $builder) {
            $builder->withCount('donates');
        });
        static::addGlobalScope('order', static function (Builder $builder) {
            $builder->withCount('donates')->orderBy('donates_count', 'desc')
                ->orderBy('is_enabled', 'desc');
        });
    }

    /**
     * @return Collection|self[]
     */
    public static function getActual(): Collection
    {
        return static::query()->where('is_enabled', '=', true)->get();
    }

    /**
     * @return HasMany
     */
    public function donates(): HasMany
    {
        return $this->hasMany(Donate::class, 'volunteer_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function donatesWithAmount(): HasMany
    {
        return $this->hasMany(Donate::class, 'volunteer_id', 'id')->where('amount', '>', 0);
    }

    /**
     * @return HasOne|User
     */
    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPage(): string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function setEnabled(bool $isEnabled): self
    {
        $this->is_enabled = $isEnabled;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSpreadsheetId(): string
    {
        return $this->spreadsheet_id;
    }

    public function getSpreadsheetLink(): string
    {
        return strtr('https://docs.google.com/spreadsheets/d/:spreadsheetId/edit#gid=0', [':spreadsheetId' => $this->getSpreadsheetId()]);
    }

    public function setSpreadsheetId(string $spreadsheetId): self
    {
        $this->spreadsheet_id = $spreadsheetId;

        return $this;
    }

    public function getDonates(): ?DonateCollection
    {
        return $this->donates;
    }
}
