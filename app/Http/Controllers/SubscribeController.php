<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequestCreate;
use App\Http\Requests\SubscribeRequestUpdate;
use App\Models\Subscribe;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;

class SubscribeController extends Controller
{
    public const string SUBSCRIPTION_CREATE_MESSAGE = 'Створена нова підписка! Очікуйте :amount грн. від @:donater щодня о :time';
    public const string SUBSCRIPTION_UPDATE_MESSAGE = 'Підписка буда змінена! Очікуйте :amount грн. від @:donater щодня о :time';
    public const string SUBSCRIPTION_DELETED_MESSAGE = 'Підписка буда видалена! Ви більше не будете отримувати :amount грн. від @:donater щодня о :time';

    public function store(SubscribeRequestCreate $request)
    {
        $this->authorize('create', Subscribe::class);

        $subscribe = Subscribe::createOrRestore($request->validated());
        $volunteer = $subscribe->getVolunteer();
        $this->notifyVolunteer($volunteer, $subscribe, self::SUBSCRIPTION_CREATE_MESSAGE);

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()]);
    }

    public function update(SubscribeRequestUpdate $request, Subscribe $subscribe)
    {
        $this->authorize('update', $subscribe);

        $subscribe->update($request->validated());
        $volunteer = $subscribe->getVolunteer();
        $this->notifyVolunteer($volunteer, $subscribe, self::SUBSCRIPTION_UPDATE_MESSAGE);

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()]);
    }

    public function destroy(Subscribe $subscribe)
    {
        $this->authorize('delete', $subscribe);
        $volunteer = $subscribe->getVolunteer();
        $this->notifyVolunteer($volunteer, $subscribe, self::SUBSCRIPTION_DELETED_MESSAGE);
        $subscribe->delete();

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()]);
    }

    private function notifyVolunteer($volunteer, $subscribe, $messageTemplate)
    {
        if (!$volunteer->settings->hasSetting(UserSetting::DONT_SEND_SUBSCRIBERS_INFORMATION)) {
            $donater = $subscribe->getDonater();
            $message = strtr($messageTemplate, [
                ':amount' => $subscribe->getAmount(),
                ':donater' => $donater->getUsername(),
                ':time' => $subscribe->getScheduledAt(),
            ]);

            $volunteer->sendBotMessage($message);
        }
    }
}
