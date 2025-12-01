<?php

namespace App\Http\Requests;

class StoreProfileRequest extends BaseProfileRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string',
        ];
    }
}
