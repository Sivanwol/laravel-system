<?php
namespace App\Traits;

use App\Models\Language;
use App\Models\User;
trait UserHelperTrait {
    public function validateSupportedLanguages( $language_ids) {
        $supportedLanguages = Language::all()->pluck('code')->where('is_supported', '=', true)->toArray();
        foreach ($language_ids as $language_id) {
            if (!in_array($language_id, $supportedLanguages)) {
                return false;
            }
        }
        return true;
    }

    public function getUserProfile(int $userId, bool $extendProfile = false): array{
        $user = User::findOrFail($userId);
        $supportedLanguages = $user->languages()->get()->pluck('code')->toArray();

        $profile = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'email' => $user->email,
            'supported_languages' => $supportedLanguages,
            'role' => $user->role,
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        ];
        if ($extendProfile) {
            $profile['extended'] = true;
            $profile['has_business'] = $user->business()->exists();
            $profile['business_id'] = $user->business()->value('id');
            $profile['business_name'] = $user->business()->value('name');
            $profile['created_at'] = $user->created_at;
        }
        return $profile;
    }
}
