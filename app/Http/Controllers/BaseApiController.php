<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use App\Traits\AuthHelperTrait;

abstract class BaseApiController extends Controller
{
    use ApiResponseTrait, AuthHelperTrait;

}
