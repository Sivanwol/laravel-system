<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use BaseApiController;
use DB;
use Illuminate\Http\Request;
use Log;

class CommonController extends BaseApiController
{
    public function getCountries()
    {
        Log::info('Countries request received');
        $countries = countries();
        return $this->successResponse($countries);
    }

    public function getPlatformSettings()
    {
        Log::info('Platform settings request received');
        $settings = new \stdClass();
        return $this->successResponse($settings);
    }

    public function getSupportedLanguages()
    {
        Log::info('Supported languages request received');
        $languages = DB::table('languages')->where('is_supported', '=', true)->get();
        return $this->successResponse($languages);
    }
}
