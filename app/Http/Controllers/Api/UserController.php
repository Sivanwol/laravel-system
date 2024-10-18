<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehicles\SupportedLanguagesUpdateUserRequest;
use App\Models\User;
use BaseApiController;
use Clockwork;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Laravel\Telescope\AuthorizesRequests;
use Log;
use UserHelperTrait;

class UserController extends BaseApiController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use UserHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function me()
    {
        //
    }

    public function profile(int $userId)
    {
        //
    }

    public function update(Request $request, int $userId)
    {
        if (auth()->user()->id !== $userId || !$this->hasRole(config('permission.roles.super-admin'))) {
            return $this->errorResponse('unauthorized action', 403);
        }
        //
    }

    public function updateUserSupportedLanguage(SupportedLanguagesUpdateUserRequest $request, int $userId)
    {
        Log::info('request update user supported languages', ['user_id' => $userId, 'language_ids' => $request->language_ids]);
        Clockwork::info('request update user supported languages', ['user_id' => $userId, 'language_ids' => $request->language_ids]);
        try {
            if (auth()->user()->id !== $userId || !$this->hasRole(config('permission.roles.super-admin'))) {
                return $this->errorResponse('unauthorized action', 403);
            }
            $validated = $request->validated();
            if (!$this->validateSupportedLanguages($validated['language_ids'])) {
                return $this->errorResponse('invalid language IDs', 400);
            }
            if ($this->hasRole(config('permission.roles.super-admin'))) {
                Clockwork::info('super admin updating user supported languages', ['user_id' => $userId, 'language_ids' => $request->language_ids]);
            }
            $user = User::findOrFail($userId);
            $user->languages()->sync($request->language_ids);
            return $this->successResponse('user supported languages updated successfully');
        } catch (\Exception $e) {
            Log::error('error updating user supported languages', ['user_id' => $userId, 'language_ids' => $request->language_ids, 'error' => $e->getMessage()]);
            Clockwork::error('error updating user supported languages', ['user_id' => $userId, 'language_ids' => $request->language_ids, 'error' => $e->getMessage()]);
            return $this->errorResponse('error updating user supported languages', 500);
        }
    }
}
