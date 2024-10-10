<?php

namespace App\Http\Requests\Vehicles;

use Illuminate\Foundation\Http\FormRequest;

class RegisterVehicleRequest extends FormRequest
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
            'vehicle_type' => 'required|in:e-bicycle,bike,car,van,small-truck,truck,sami-truck,bus,other',
            'other_vehicle_type' => 'nullable|string|max:100',
            'required_driver_license' => 'required|in:A,A1,A2,B,C,C1,D,C+E',
            'license_plate' => 'required|string|max:40|unique:vehicles,license_plate',
            'is_manual' => 'boolean',
            'is_electric' => 'boolean',
            'max_km_per_run' => 'required|integer|min:0',
            'max_weight' => 'required|integer|min:0',
            'has_cooling' => 'boolean',
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
            'vehicle_type.required' => 'The vehicle type is required.',
            'vehicle_type.in' => 'The selected vehicle type is invalid.',
            'other_vehicle_type.string' => 'The other vehicle type must be a string.',
            'other_vehicle_type.max' => 'The other vehicle type may not be greater than 100 characters.',
            'required_driver_license.required' => 'The driver license type is required.',
            'required_driver_license.in' => 'The selected driver license type is invalid.',
            'license_plate.required' => 'The license plate is required.',
            'license_plate.string' => 'The license plate must be a string.',
            'license_plate.max' => 'The license plate may not be greater than 40 characters.',
            'license_plate.unique' => 'The license plate has already been taken.',
            'is_manual.boolean' => 'The is manual field must be true or false.',
            'is_electric.boolean' => 'The is electric field must be true or false.',
            'max_km_per_run.required' => 'The maximum kilometers per run is required.',
            'max_km_per_run.integer' => 'The maximum kilometers per run must be an integer.',
            'max_km_per_run.min' => 'The maximum kilometers per run must be at least 0.',
            'max_weight.required' => 'The maximum weight is required.',
            'max_weight.integer' => 'The maximum weight must be an integer.',
            'max_weight.min' => 'The maximum weight must be at least 0.',
            'has_cooling.boolean' => 'The has cooling field must be true or false.',
        ];
    }

        /**
         * Prepare the data for validation.
         */
        protected function prepareForValidation()
        {
            $this->merge([
                'is_manual' => $this->is_manual ?? false,
                'is_electric' => $this->is_electric ?? false,
                'has_cooling' => $this->has_cooling ?? false,
                'other_vehicle_type' => $this->other_vehicle_type ?? null,
            ]);
        }
}
