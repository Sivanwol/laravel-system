<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterBusinessRequest;
use App\Traits\ApiResponseTrait;
use Business;
use Clockwork;
use Exception;
use Illuminate\Http\Request;
use Log;

class BusinessController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterBusinessRequest $request)
    {
        Log::info('Business creation request received', ['request' => $request->all()]);
        Clockwork::info('Business creation request received', ['request' => $request->all()]);

        try {
            $validatedRequest = $request->validated();
            $user = $request->user();
            $business = $user->business()->first();

            // Check if the user already has an active business
            if ($business && $business->active_at) {
                Clockwork::info('Business creation failed: User already has an active business.', [
                    'user_id' => $user->id,
                    'business_id' => $business->id,
                ]);
                return $this->errorResponse('You already have a business registered.', 400);
            }

            // If no business exists, create a new one
            if (!$business) {
                Clockwork::info('Creating a new business for the user', ['user_id' => $user->id]);
                $business = Business::create($validatedRequest);
            } else {
                // Update the existing business and mark it as active
                Clockwork::info('Updating existing business', ['business_id' => $business->id]);
                $validatedRequest['active_at'] = now(); // Set current time for active_at
                $business->update($validatedRequest);
            }

            return $this->successResponse($business, 'Business successfully registered.', 201);
        } catch (Exception $e) {
            // Log the detailed exception for internal debugging
            Log::error('Business creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id ?? 'guest',
                'request' => $request->all(),
            ]);
            Clockwork::error('Business creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id ?? 'guest',
                'request' => $request->all(),
            ]);

            // Return a general error response to the client
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
