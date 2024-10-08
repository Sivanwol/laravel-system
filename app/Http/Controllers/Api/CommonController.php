<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    use ApiResponseTrait;
    public function get_countries()
    {
        $countries = countries();
        return $this->successResponse($countries);
    }

    public function get_platform_settings()
    {
        $settings = new \stdClass();
        return $this->successResponse($settings);
    }
}
