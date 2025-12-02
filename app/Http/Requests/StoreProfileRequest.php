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
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,gif|max:2048',
        ];
    }
}
