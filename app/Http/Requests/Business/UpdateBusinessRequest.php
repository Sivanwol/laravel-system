<?php

namespace App\Http\Requests\Business;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBusinessRequest extends FormRequest
{
    use ApiResponseTrait;
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
        // Allow access if the user has no business or the business is inactive (active_at is null)
        return $business === null || $business->active_at === null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'business_id' => 'required|integer',              // Business ID is required, must be an integer
            'name' => 'nullable|string|max:255',              // Business name is required, max 255 characters
            'description' => 'nullable|string',               // Description is optional
            'website' => 'nullable|url',                      // Website is optional, but must be a valid URL
            'facebook' => 'nullable|url|max:500',             // Optional valid URL, max 500 characters
            'twitter' => 'nullable|url|max:500',              // Optional valid URL, max 500 characters
            'instagram' => 'nullable|url|max:500',            // Optional valid URL, max 500 characters
            'linkedin' => 'nullable|url|max:500',             // Optional valid URL, max 500 characters
            'youtube' => 'nullable|url|max:500',              // Optional valid URL, max 500 characters
            'whatapp' => 'nullable|string|max:500',           // WhatsApp is optional, max 500 characters
            'phone' => 'nullable|string|max:20',              // Phone number is optional, max 20 characters
            'email' => 'nullable|email|max:255',              // Email is optional, but must be a valid email address
            'country' => [
                'nullable',
                'string',
                'max:4',
                function ($attribute, $value, $fail) {
                    // Use Rinvex Country to check if the country code is valid
                    $countries = countries();
                    if (!array_key_exists(strtoupper($value), $countries)) {
                        $fail('The selected country is invalid.');
                    }
                }
            ],
            'city' => 'nullable|string|max:255',              // City is optional, max 255 characters
            'address' => 'nullable|string|max:255',           // Address is optional, max 255 characters
            'zip' => 'nullable|string|max:20',                // ZIP code is optional, max 20 characters
            'latitude' => 'nullable|numeric',                 // Latitude is optional, must be a numeric value
            'longitude' => 'nullable|numeric',                // Longitude is optional, must be a numeric value
            'timezone' => 'required|string|max:50',           // Timezone is required, max 50 characters
            'business-size' => 'nullable|in:1-10,11-50,51-200,201-500,501-1000,1001-5000,5001-10000,10001+',
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'business_id.required' => 'The business ID is required.',
            'name.required' => 'The business name is required.',
            'website.url' => 'The website must be a valid URL.',
            'email.email' => 'The email must be a valid email address.',
            'country.in' => 'The selected country code is invalid.',
            'timezone.required' => 'The timezone is required.',
            'business-size.required' => 'Please select a valid business size.',
        ];
    } /**
      * Handle a failed validation attempt.
      *
      * @param Validator $validator
      * @throws HttpResponseException
      */
    protected function failedValidation(Validator $validator)
    {
        // Return a detailed JSON response with the validation errors
        throw new HttpResponseException(
            $this->validationErrorResponse($validator->errors()->all(), 'Validation errors', 401)
        );
    }
}
