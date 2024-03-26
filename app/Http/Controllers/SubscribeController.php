<?php

namespace App\Http\Controllers;

use App\Events\OpenGraphRegenerateEvent;
use App\Http\Requests\SubscribeRequestCreate;
use App\Http\Requests\SubscribeRequestUpdate;
use App\Models\Subscribe;
use App\Models\SubscribesMessage;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;

class SubscribeController extends Controller
{
    public const string SUBSCRIPTION_CREATE_MESSAGE = 'Створена нова підписка! Очікуйте :amount грн. від @:donater вперше в :first, а потім :frequency';
    public const string SUBSCRIPTION_UPDATE_MESSAGE = 'Підписка буда змінена! Очікуйте :amount грн. від @:donater вперше в :first, а потім :frequency';
    public const string SUBSCRIPTION_DELETED_MESSAGE = 'Підписка буда видалена! Ви більше не будете отримувати :amount грн. від @:donater';

    public function store(SubscribeRequestCreate $request)
    {
        $this->authorize('create', Subscribe::class);

        $subscribe = Subscribe::createOrRestore($request->validated());
        $volunteer = $subscribe->getVolunteer();
        $this->subscribe($subscribe, $volunteer);
        $this->notifyVolunteer($volunteer, $subscribe, self::SUBSCRIPTION_CREATE_MESSAGE);

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()]);
    }

    public function update(SubscribeRequestUpdate $request, Subscribe $subscribe)
    {
        $this->authorize('update', $subscribe);

        $subscribe->update($request->validated());
        $volunteer = $subscribe->getVolunteer();
        $this->subscribe($subscribe, $volunteer);
        $this->notifyVolunteer($volunteer, $subscribe, self::SUBSCRIPTION_UPDATE_MESSAGE);

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()]);
    }

    public function destroy(Subscribe $subscribe)
    {
        $this->authorize('delete', $subscribe);
        $volunteer = $subscribe->getVolunteer();
        $id = $subscribe->getDonater()->getId();
        $this->notifyVolunteer($volunteer, $subscribe, self::SUBSCRIPTION_DELETED_MESSAGE);
        $subscribe->delete();
        OpenGraphRegenerateEvent::dispatch($volunteer->getId(), OpenGraphRegenerateEvent::TYPE_USER);
        OpenGraphRegenerateEvent::dispatch($id, OpenGraphRegenerateEvent::TYPE_USER);

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()]);
    }

    private function notifyVolunteer(User $volunteer, Subscribe $subscribe, string $messageTemplate)
    {
        if (!$volunteer->settings->hasSetting(UserSetting::DONT_SEND_SUBSCRIBERS_INFORMATION)) {
            $donater = $subscribe->getDonater();
            $message = strtr($messageTemplate, [
                ':amount'    => $subscribe->getAmount(),
                ':donater'   => $donater->getUsername(),
                ':first'     => $subscribe->getFirstMessageAt(),
                ':frequency' => mb_strtolower(SubscribesMessage::FREQUENCY_NAME_MAP[$subscribe->getFrequency() ?? ''] ?? ''),
            ]);

            $volunteer->sendBotMessage($message);
        }
    }

    /**
     * @param Subscribe $subscribe
     * @param User $volunteer
     * @return void
     */
    protected function subscribe(Subscribe $subscribe, User $volunteer): void
    {
        $subscribe->getNextSubscribesMessage()?->update(['has_open_fundraisings' => false]);
        SubscribesMessage::create([
            'subscribes_id'         => $subscribe->getId(),
            'frequency'             => $subscribe->getFrequency(),
            'scheduled_at'          => $subscribe->getFirstMessageAt(),
            'has_open_fundraisings' => true,
        ]);
        OpenGraphRegenerateEvent::dispatch($volunteer->getId(), OpenGraphRegenerateEvent::TYPE_USER);
        OpenGraphRegenerateEvent::dispatch($subscribe->getDonater()->getId(), OpenGraphRegenerateEvent::TYPE_USER);
    }
}
