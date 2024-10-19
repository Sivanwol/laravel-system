<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Vehicles\SupportedLanguagesUpdateUserRequest;
use App\Models\User;
use App\Traits\UserHelperTrait;
use Clockwork;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Laravel\Telescope\AuthorizesRequests;
use Log;

class UserController extends BaseApiController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use UserHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function me()
    {
        Log::info('request my profile', ['user_id' => auth()->id()]);
        Clockwork::info('request my profile', ['user_id' => auth()->id()]);
        try {
            return $this->successResponse($this->getUserProfile(auth()->id(), trait_uses_recursive));
        } catch (\Exception $e) {
            Log::error('error getting my profile', ['user_id' => auth()->id(), 'error' => $e->getMessage()]);
            Clockwork::error('error getting my profile', ['user_id' => auth()->id(), 'error' => $e->getMessage()]);
            return $this->errorResponse('error getting my profile', 500);
        }
    }

    public function profile(int $userId)
    {
        Log::info('request user profile', ['user_id' => $userId]);
        Clockwork::info('request user profile', ['user_id' => $userId]);
        try {
            $extendProfile = $this->hasRole(config('permission.roles.super-admin'));
            if (auth()->user()->id !== $userId) {
                if (!$this->hasRole(config('permission.roles.super-admin'))) {
                    $extendProfile  = false;
                }
            }
            if ($this->hasRole(config('permission.roles.super-admin'))) {
                Clockwork::info('super admin getting user profile', ['user_id' => $userId]);
            }
            return $this->successResponse($this->getUserProfile($userId, $extendProfile));
        } catch (\Exception $e) {
            Log::error('error getting user profile', ['user_id' => $userId, 'error' => $e->getMessage()]);
            Clockwork::error('error getting user profile', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return $this->errorResponse('error getting user profile', 500);
        }
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
