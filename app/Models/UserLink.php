<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $link
 * @property string $name
 * @property string $icon
 */
class UserLink extends Model
{
    use HasFactory;

    public const ICONS = [
        'bi bi-globe'           => 'globe',
        'bi bi-alexa'           => 'alexa',
        'bi bi-behance'         => 'behance',
        'bi bi-discord'         => 'discord',
        'bi bi-dribbble'        => 'dribbble',
        'bi bi-facebook'        => 'facebook',
        'bi bi-github'          => 'github',
        'bi bi-gitlab'          => 'gitlab',
        'bi bi-google'          => 'google',
        'bi bi-instagram'       => 'instagram',
        'bi bi-line'            => 'line',
        'bi bi-linkedin'        => 'linkedin',
        'bi bi-mastodon'        => 'mastodon',
        'bi bi-medium'          => 'medium',
        'bi bi-messenger'       => 'messenger',
        'bi bi-microsoft-teams' => 'microsoft-teams',
        'bi bi-opencollective'  => 'opencollective',
        'bi bi-paypal'          => 'paypal',
        'bi bi-pinterest'       => 'pinterest',
        'bi bi-quora'           => 'quora',
        'bi bi-reddit'          => 'reddit',
        'bi bi-signal'          => 'signal',
        'bi bi-sina-weibo'      => 'sina-weibo',
        'bi bi-skype'           => 'skype',
        'bi bi-slack'           => 'slack',
        'bi bi-snapchat'        => 'snapchat',
        'bi bi-sourceforge'     => 'sourceforge',
        'bi bi-spotify'         => 'spotify',
        'bi bi-stack-overflow'  => 'stack-overflow',
        'bi bi-strava'          => 'strava',
        'bi bi-substack'        => 'substack',
        'bi bi-telegram'        => 'telegram',
        'bi bi-tencent-qq'      => 'tencent-qq',
        'bi bi-threads'         => 'threads',
        'bi bi-threads-fill'    => 'threads-fill',
        'bi bi-tiktok'          => 'tiktok',
        'bi bi-twitch'          => 'twitch',
        'bi bi-twitter'         => 'twitter',
        'bi bi-twitter-x'       => 'twitter-x',
        'bi bi-vimeo'           => 'vimeo',
        'bi bi-wechat'          => 'wechat',
        'bi bi-whatsapp'        => 'whatsapp',
        'bi bi-wordpress'       => 'wordpress',
        'bi bi-yelp'            => 'yelp',
        'bi bi-youtube'         => 'youtube',
    ];
    protected $fillable = [
        'user_id',
        'link',
        'name',
        'icon',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

}
