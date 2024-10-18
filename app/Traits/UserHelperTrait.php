<?
trait UserHelperTrait {
    public function validateSupportedLanguages( $language_ids) {
        $supportedLanguages = Languages::all()->pluck('code')->where('is_supported', '=', true)->toArray();
        foreach ($language_ids as $language_id) {
            if (!in_array($language_id, $supportedLanguages)) {
                return false;
            }
        }
        return true;
    }
}
