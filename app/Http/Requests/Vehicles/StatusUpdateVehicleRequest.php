<?php

namespace App\Http\Requests\Vehicles;

use Illuminate\Foundation\Http\FormRequest;

class StatusUpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        $business = $user->business()->first();
        return $business !== null && $business->active_at !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:active,inactive,maintenance,repair,other',
            'other_status' => 'nullable|string|max:100',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'The status is required.',
            'status.in' => 'The selected status is invalid.',
            'other_status.string' => 'The other status must be a string.',
            'other_status.max' => 'The other status may not be greater than 100 characters.',
        ];
    }
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'other_status' => $this->status === 'other' ? $this->other_status : null,
        ]);
    }
}
