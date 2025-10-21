<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => [
                'email',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('name', $this->email)->where('email', '!=', $this->user()->email);
                }),
            ],
            'ktp' => ['required'],
            'phone_number' => ['required'],
            'address' => ['nullable'],
        ];
    }
}
