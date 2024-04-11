<?php

namespace App\Listeners;

use App\Events\OpenGraphRegenerateEvent;
use App\Models\Fundraising;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\OpenGraphImageService;
use Illuminate\Contracts\Queue\ShouldQueue;

class OpenGraphRegenerate implements ShouldQueue
{
    private OpenGraphImageService $service;
    private UserRepository $repository;

    /**
     * @param OpenGraphImageService $service
     * @param UserRepository $repository
     */
    public function __construct(OpenGraphImageService $service, UserRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     */
    public function handle(OpenGraphRegenerateEvent $event): void
    {
        match ($event->getType()) {
            OpenGraphRegenerateEvent::TYPE_USER => $this->service->getUserImage($this->repository->find($event->getId(), true), false),
            OpenGraphRegenerateEvent::TYPE_FUNDRAISING => $this->service->getFundraisingImage(Fundraising::find($event->getId()), false),
            default => ''
        };
    }
}
