<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Vehicles\MilageUpdateVehicleRequest;
use App\Http\Requests\Vehicles\RegisterVehicleRequest;
use App\Http\Requests\Vehicles\StatusUpdateVehicleRequest;
use App\Http\Requests\Vehicles\UpdateVehicleRequest;
use App\Models\Business;
use App\Models\Vehicle;
use App\Traits\BusinessHelperTrait;
use Clockwork;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Laravel\Telescope\AuthorizesRequests;
use Log;

class VehicleController extends BaseApiController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use BusinessHelperTrait;
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
                if ($this->isBusiness() === false || $this->isSuperAdmin() === false) {
                    return $this->errorResponse('You do not have permission to perform this action', 403);
                }
                $business = Business::find($business_id);
                if (!$business) {
                    return $this->notFoundResponse('Business not found');
                }
                $isOwner = Business::hasUserIsOwner($business_id, auth()->id());
                if (!$isOwner && $this->isSuperAdmin() === false) {
                    return $this->errorResponse('You do not have permission to perform this action', 403);
                }
                if ($this->isSuperAdmin()) {
                    Clockwork::info('current action act by Super Admin ', ['business_id' => $business_id]);
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
        Log::info('show all Vehicle request received', ['business_id' => $business_id]);
        Clockwork::info('shoaw all Vehicle request received', ['business_id' => $business_id]);
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

    public function updateStatus(StatusUpdateVehicleRequest $request, int $business_id, string $id)
    {
        Log::info('update Vehicle status request received', ['vehicle_id' => $id]);
        Clockwork::info('update Vehicle status request received', ['vehicle_id' => $id]);
        try {
            if ($this->isBusiness() === false || $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            $business = Business::find($business_id);
            if (!$business) {
                return $this->notFoundResponse('Business not found');
            }
            if (!$this->isBusinessOwner($business_id) && $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            if ($this->isSuperAdmin()) {
                Clockwork::info('current action act by Super Admin ', ['business_id' => $business_id]);
            }

            $response = $request->validated();
            $vehicle = Vehicle::find($id);
            if (!$vehicle) {
                return $this->notFoundResponse('Vehicle not found');
            }
            $businessVehicle = $business->vehicles()->where('vehicle_id', $id)->first();
            if (!$businessVehicle) {
                return $this->notFoundResponse('Vehicle not found in this business');
            }
            $businessVehicle->status = $response['status'];
            $businessVehicle->other_status = $response['other_status'];
            $businessVehicle->save();
            return $this->successResponse('Vehicle status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in VehicleController@update_status', ['message' => $e->getMessage()]);
            Clockwork::error('Error in VehicleController@update_status', ['message' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }

    public function updateMileage(MilageUpdateVehicleRequest $request, int $business_id, string $id)
    {
        Log::info('update Vehicle mileage request received', ['vehicle_id' => $id]);
        Clockwork::info('update Vehicle mileage request received', ['vehicle_id' => $id]);
        try {
            if ($this->isBusiness() === false || $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            $business = Business::find($business_id);
            if (!$business) {
                return $this->notFoundResponse('Business not found');
            }
            if (!$this->isBusinessOwner($business_id) && $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            if ($this->isSuperAdmin()) {
                Clockwork::info('current action act by Super Admin ', ['business_id' => $business_id]);
            }

            $response = $request->validated();
            $vehicle = Vehicle::find($id);
            if (!$vehicle) {
                return $this->notFoundResponse('Vehicle not found');
            }
            $businessVehicle = $business->vehicles()->where('vehicle_id', $id)->first();
            if (!$businessVehicle) {
                return $this->notFoundResponse('Vehicle not found in this business');
            }
            $businessVehicle->mileage = $response['mileage'];
            $businessVehicle->save();
            return $this->successResponse('Vehicle mileage updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in VehicleController@update_mileage', ['message' => $e->getMessage()]);
            Clockwork::error('Error in VehicleController@update_mileage', ['message' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, int $business_id, string $id)
    {
        Log::info('update Vehicle request received', ['vehicle_id' => $id]);
        Clockwork::info('update Vehicle request received', ['vehicle_id' => $id]);
        try {
            if ($this->isBusiness() === false || $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            $business = Business::find($business_id);
            if (!$business) {
                return $this->notFoundResponse('Business not found');
            }
            if (!$this->isBusinessOwner($business_id) && $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            if ($this->isSuperAdmin()) {
                Clockwork::info('current action act by Super Admin ', ['business_id' => $business_id]);
            }

            $response = $request->validated();
            $vehicle = Vehicle::find($id);
            if (!$vehicle) {
                return $this->notFoundResponse('Vehicle not found');
            }

            $business = Business::find($business_id);
            if (!$business) {
                return $this->notFoundResponse('Business not found');
            }

            $businessVehicle = $business->vehicles()->where('vehicle_id', $id)->first();
            if (!$businessVehicle) {
                return $this->notFoundResponse('Vehicle not found in this business');
            }

            $vehicle->fill($response);
            $vehicle->save();
            return $this->successResponse('Vehicle updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in VehicleController@update', ['message' => $e->getMessage()]);
            Clockwork::error('Error in VehicleController@update', ['message' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }

    public function removeFromBusiness(int $business_id, string $id)
    {
        Log::info('remove Vehicle request received', ['business_id' => $business_id, 'vehicle_id' => $id]);
        Clockwork::info('remove Vehicle request received', ['business_id' => $business_id, 'vehicle_id' => $id]);
        try {
            if ($this->isBusiness() === false || $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            $business = Business::find($business_id);
            if (!$business) {
                return $this->notFoundResponse('Business not found');
            }
            if (!$this->isBusinessOwner($business_id) && $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            if ($this->isSuperAdmin()) {
                Clockwork::info('current action act by Super Admin ', ['business_id' => $business_id]);
            }
            $vehicle = Vehicle::find($id);
            if (!$vehicle) {
                return $this->notFoundResponse('Vehicle not found');
            }
            $business->removeVehicle($id);
            return $this->successResponse('Vehicle removed from business successfully');
        } catch (\Exception $e) {
            Log::error('Error in VehicleController@removeFromBusiness', ['message' => $e->getMessage()]);
            Clockwork::error('Error in VehicleController@removeFromBusiness', ['message' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }

    public function removeAllVehiclesFromBusiness(int $business_id)
    {
        Log::info('remove all Vehicle request received', ['business_id' => $business_id]);
        Clockwork::info('remove all Vehicle request received', ['business_id' => $business_id]);
        try {
            if ($this->isBusiness() === false && $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            $business = Business::find($business_id);
            if (!$business) {
                return $this->notFoundResponse('Business not found');
            }
            if (!$this->isBusinessOwner($business_id) && $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            if ($this->isSuperAdmin()) {
                Clockwork::info('current action act by Super Admin ', ['business_id' => $business_id]);
            }
            $business->vehicles()->detach();
            return $this->successResponse('All vehicles removed from business successfully');
        } catch (\Exception $e) {
            Log::error('Error in VehicleController@removeAllVehiclesFromBusiness', ['message' => $e->getMessage()]);
            Clockwork::error('Error in VehicleController@removeAllVehiclesFromBusiness', ['message' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $business_id, string $id)
    {
        Log::info('delete Vehicle request received', ['business_id' => $business_id, 'vehicle_id' => $id]);
        Clockwork::info('delete Vehicle request received', ['business_id' => $business_id, 'vehicle_id' => $id]);
        try {
            if ($this->isBusiness() === false && $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            $business = Business::find($business_id);
            if (!$business) {
                return $this->notFoundResponse('Business not found');
            }
            if (!$this->isBusinessOwner($business_id) && $this->isSuperAdmin() === false) {
                return $this->errorResponse('You do not have permission to perform this action', 403);
            }
            if ($this->isSuperAdmin()) {
                Clockwork::info('current action act by Super Admin ', ['business_id' => $business_id]);
            }
            $vehicle = Vehicle::find($id);
            if (!$vehicle) {
                return $this->notFoundResponse('Vehicle not found');
            }
            $business->removeVehicle($id);
            $vehicle->delete();
            return $this->successResponse('Vehicle deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error in VehicleController@destroy', ['message' => $e->getMessage()]);
            Clockwork::error('Error in VehicleController@destroy', ['message' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the business. Please try again later.', 500);
        }
    }
}
