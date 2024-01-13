<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscribeRequestUpdate extends FormRequest
{
    public function rules(): array
    {
        $request = request();

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::exists('subscribes')->where(function ($query) use ($request)  {
                    return $query->where('user_id', $request->get('user_id'))
                        ->where('volunteer_id', $request->get('volunteer_id'));
                }),
            ],
            'volunteer_id' => ['required', 'integer', 'exists:fundraisings,user_id'],
            'amount'       => ['required', 'integer'],
            'scheduled_at' => ['required', 'date_format:H:i'],
            'use_random'   => ['boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
