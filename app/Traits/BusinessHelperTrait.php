<?php
namespace App\Traits;

use App\Models\Business;
trait BusinessHelperTrait {
    public function isBusinessOwner(int $business_id) {
        return Business::hasUserIsOwner($business_id, auth()->id());
    }
}
