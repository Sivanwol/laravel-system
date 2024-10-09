<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\RegisterBusinessRequest;
use App\Http\Requests\Business\UpdateBusinessRequest;
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
    public function index(Request $request)
    {
        Log::info('Business List request received', ['request' => $request->all()]);
        try {
            if (!$request->query('all')) {
                $businesses = Business::active()->get();
                return $this->successResponse($businesses);
            } else {
                if ($request->user()->hasRole('super-admin')) {
                    Clockwork::info('Super admin requested all businesses');
                    $businesses = Business::all();
                    return $this->successResponse($businesses);
                }
            }
            return $this->errorResponse('Unauthorized', 403);
        } catch (Exception $e) {
            // Log the detailed exception for internal debugging
            Log::error('Business List failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id ?? 'guest',
                'request' => $request->all(),
            ]);
            Clockwork::error('Business List failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id ?? 'guest',
                'request' => $request->all(),
            ]);

            // Return a general error response to the client
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }

    public function stats(string $id)
    {
        Log::info('Business Stats request received', ['business_id' => $id]);
        Clockwork::info('Business Stats request received', ['business_id' => $id]);

        try {
            $business = Business::find($id);
            if ($business) {
                $stats = $business->stats();
                return $this->successResponse($stats);
            }
            return $this->errorResponse('Business not found.', 404);
        } catch (Exception $e) {
            Log::error('Business Stats failed: ' . $e->getMessage(), [
                'exception' => $e,
                'business_id' => $id,
            ]);
            Clockwork::error('Business Stats failed: ' . $e->getMessage(), [
                'exception' => $e,
                'business_id' => $id,
            ]);
            return $this->errorResponse('An error occurred while fetching business stats.', 500);
        }

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
    public function show(Request $request, string $id)
    {
        try {
            Log::info('Business details request received', ['business_id' => $id]);
            Clockwork::info('Business details request received', ['business_id' => $id]);
            $business = Business::find($id);
            if ($business) {
                if ($business->active_at) {
                    return $this->successResponse($business);
                } else {
                    if ($request->user()->hasRole('super-admin')) {
                        Clockwork::info('Super admin requested inactive business details', ['business_id' => $id]);
                        return $this->successResponse($business);
                    }
                    return $this->errorResponse('Business is inactive.', 400);
                }
            }
            return $this->errorResponse('Business not found.', 404);
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
     * Update the specified resource in storage.
     */
    public function update(UpdateBusinessRequest $request, string $id)
    {
        try {
            Log::info('Business Update request received', ['request' => $request->all()]);
            $validatedRequest = $request->validated();
            $business = Business::find($id);
            if ($business) {
                if ($business->owner_user_id !== auth()->id()) {
                    if (!auth()->user()->hasRole('super-admin')) {
                        return $this->errorResponse('Unauthorized', 403);
                    }
                }
                unset($validatedRequest['business_id']); // Remove business_id from the request as it is not needed
                $business->update($validatedRequest);
                return $this->successResponse($business, 'Business updated successfully.');
            }
            return $this->errorResponse('Business not found.', 404);
        } catch (Exception $e) {
            // Log the detailed exception for internal debugging
            Log::error('Business Update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id ?? 'guest',
                'request' => $request->all(),
            ]);
            Clockwork::error('Business Update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id ?? 'guest',
                'request' => $request->all(),
            ]);

            // Return a general error response to the client
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        log::info('Deactivating business', ['business_id' => $id]);
        try {
            $business = Business::find($id);
            if ($business) {
                if ($business->owner_user_id !== auth()->id()) {
                    if (!auth()->user()->hasRole('super-admin')) {
                        return $this->errorResponse('Unauthorized', 403);
                    }
                }
                $business->active_at = null;
                $business->save();
                Clockwork::info('Business deactivated successfully', ['business_id' => $id]);
                return $this->successResponse(null, 'Business deactivated successfully.');
            }
            return $this->errorResponse('Business not found.', 404);

        } catch (Exception $e) {
            // Log the detailed exception for internal debugging
            Log::error('Business Delete failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id ?? 'guest',
                'business_id' => $id,
            ]);
            Clockwork::error('Business creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id ?? 'guest',
                'business_id' => $id,
            ]);

            // Return a general error response to the client
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }
}
