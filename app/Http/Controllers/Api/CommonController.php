<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Log;

class CommonController extends Controller
{
    use ApiResponseTrait;
    public function get_countries()
    {
        Log::info('Countries request received');
        $countries = countries();
        return $this->successResponse($countries);
    }

    public function get_platform_settings()
    {
        Log::info('Platform settings request received');
        $settings = new \stdClass();
        return $this->successResponse($settings);
    }
}
