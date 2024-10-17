<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehicles\RegisterVehicleRequest;
use App\Models\Vehicle;
use BaseApiController;
use Business;
use Clockwork;
use Illuminate\Http\Request;
use Log;

class VehicleController extends BaseApiController
{

    /**
     * Display a listing of the resource.
     */
    public function index(int $business_id, int $vehicle_id)
    {
        Log::info('Vehicle request received', ['business_id' => $business_id, 'vehicle_id' => $vehicle_id]);
        Clockwork::info('Vehicle request received', ['business_id' => $business_id, 'vehicle_id' => $vehicle_id]);
        try {
            if ($business_id) {
                $business = Business::find($business_id);
                if (!$business) {
                    return $this->notFoundResponse('Business not found');
                }
                $vehicle = Vehicle::find($vehicle_id);
                if (!$vehicle) {
                    return $this->notFoundResponse('Vehicle not found');
                }
                $businessVehicles = $business->vehicles()->where('id', $vehicle_id)->first();

                if (!$businessVehicles) {
                    return $this->notFoundResponse('Vehicle not found in this business');
                }
                Clockwork::info('fetched vehicle request received', ['business_id' => $business_id, 'vehicle_id' => $vehicle_id, 'businessVehicles' => $businessVehicles, 'vehicle' => $vehicle]);
                $response = [
                    'vehicle' => $vehicle,
                    'current_meta' => [
                        'mileage' => $businessVehicles->mileage,
                        'status' => $businessVehicles->status,
                        'other_status' => $businessVehicles->other_status,
                    ],
                ];
                return $this->successResponse($response);
            }
            return $this->errorResponse('Invalid business id', 400);
        } catch (\Exception $e) {
            Log::error('Error in VehicleController@index', ['message' => $e->getMessage()]);
            Clockwork::error('Error in VehicleController@index', ['message' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterVehicleRequest $request, int $business_id, string $model_type)
    {
        $modelTypeEnum = ['Business', 'Delivery'];

        if (!in_array($model_type, $modelTypeEnum)) {
            return $this->validationErrorResponse(['error' => 'Invalid model type'], 400);
        }
        try {

            if ($business_id && $model_type === 'Business') {
                $business = Business::find($business_id);
                if (!$business) {
                    return $this->notFoundResponse('Business not found');
                }
                $response = $request->validated();
                $licensePlate = $response['license_plate'];
                $vehicle = Vehicle::where('license_plate', $licensePlate)->first();
                if ($vehicle) {
                    return $this->errorResponse('Vehicle with this license plate already exists', 400);
                }
                Clockwork::info('Vehicle request Saved', ['business_id' => $business_id, 'response' => $response]);
                $vehicle = new Vehicle();
                $vehicle->fill($response);
                $vehicle->save();
                Clockwork::info('Vehicle Attached to business', ['vehicle' => $vehicle, 'business' => $business]);
                $business->addVehicle($vehicle->id, $response['mileage'], $response['status'], $response['other_status']);
            }
            return $this->errorResponse('Invalid business id', 400);
        } catch (\Exception $e) {
            Log::error('Error in VehicleController@store', ['message' => $e->getMessage()]);
            Clockwork::error('Error in VehicleController@store', ['message' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(int $business_id)
    {
        Log::info('Vehicle request received', ['business_id' => $business_id]);
        Clockwork::info('Vehicle request received', ['business_id' => $business_id]);
        try {
            $business = Business::find($business_id);
            if (!$business) {
                return $this->notFoundResponse('Business not found');
            }
            $businessVehicles = $business->vehicles()->distinct('id')->orderBy('created_at', 'desc')->get();
            $responses = [];
            foreach ($businessVehicles as $businessVehicle) {
                $vehicle = Vehicle::find($businessVehicle->id);
                $responses[] = [
                    'vehicle' => $vehicle,
                    'current_meta' => [
                        'mileage' => $businessVehicle->mileage,
                        'status' => $businessVehicle->status,
                        'other_status' => $businessVehicle->other_status,
                    ],
                ];
            }
            Clockwork::info('fetched vehicle request received', ['business_id' => $business_id, 'businessVehicles' => $businessVehicles, 'responses' => $responses]);
            return $this->successResponse($responses);
        } catch (\Exception $e) {
            Log::error('Error in VehicleController@show', ['message' => $e->getMessage()]);
            Clockwork::error('Error in VehicleController@show', ['message' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }

    public function update_status(Request $request, string $id)
    {
        //
    }

    public function update_mileage(Request $request, string $id)
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

    public function removeFromBusiness(int $business_id, string $id)
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
