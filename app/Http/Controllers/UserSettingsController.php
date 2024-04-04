<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSettingsController extends Controller
{
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $settings = $request->get('settings', []);
        foreach (array_keys(UserSetting::SETTINGS_MAP[$user->isVolunteer()]) as $key) {
            if (boolval($settings[$key]) ?? false) {
                UserSetting::createOrFirst(['setting' => $key, 'user_id' => $user->getId()]);
            } else {
                $first = UserSetting::query()->where('user_id', '=', $user->getId())
                    ->where('setting', '=', $key)->get()->first();
                $first?->delete();
            }
        }

        return new JsonResponse(['url' => route('user', compact('user')), 'csrf' => $this->getNewCSRFToken()]);
    }
}
