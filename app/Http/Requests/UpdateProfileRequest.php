<?php

namespace App\Http\Requests;

class UpdateProfileRequest extends BaseProfileRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'sometimes|nullable|string|max:50',
            'address' => 'sometimes|nullable|string|max:100',
            'date_of_birth' => 'sometimes|nullable|date',
            'bio' => 'sometimes|nullable|string',
        ];
    }
}
