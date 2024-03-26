<?php

namespace App\Http\Requests;

use App\Models\SubscribesMessage;
use App\Rules\SubscribeFrequencyRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscribeRequestCreate extends FormRequest
{
    public function rules(): array
    {
        $request = request();

        return [
            'user_id'          => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('subscribes')->where(function (Builder $query) use ($request) {
                    return $query->where('user_id', $request->get('user_id'))
                        ->where('volunteer_id', $request->get('volunteer_id'))
                        ->whereNull('deleted_at');
                }),
            ],
            'frequency'        => ['required', 'in:' . implode(',', array_keys(SubscribesMessage::FREQUENCY_NAME_MAP))],
            'volunteer_id'     => ['required', 'integer', 'exists:fundraisings,user_id'],
            'amount'           => ['required', 'integer', 'min:1'],
            'first_message_at' => ['required', 'date_format:Y-m-d H:i', new SubscribeFrequencyRule()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
