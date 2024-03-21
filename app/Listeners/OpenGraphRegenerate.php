<?php

namespace App\Listeners;

use App\Events\OpenGraphRegenerateEvent;
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
        \Log::critical('as');
        match ($event->getType()) {
            OpenGraphRegenerateEvent::TYPE_USER => $this->service->getUserImage(User::find($event->getId()), false),
            default => ''
        };
    }
}
