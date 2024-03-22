<?php

namespace App\Listeners;

use App\Events\OpenGraphRegenerateEvent;
use App\Models\Fundraising;
use App\Models\User;
use App\Services\OpenGraphImageService;
use Illuminate\Contracts\Queue\ShouldQueue;

class OpenGraphRegenerate implements ShouldQueue
{
    private OpenGraphImageService $service;

    /**
     * @param OpenGraphImageService $service
     */
    public function __construct(OpenGraphImageService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     */
    public function handle(OpenGraphRegenerateEvent $event): void
    {
        match ($event->getType()) {
            OpenGraphRegenerateEvent::TYPE_USER => $this->service->getUserImage(User::find($event->getId()), false),
            OpenGraphRegenerateEvent::TYPE_FUNDRAISING => $this->service->getFundraisingImage(Fundraising::find($event->getId()), false),
            default => ''
        };
    }
}
