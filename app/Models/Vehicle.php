<?php

namespace App\Models;

use Business;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    // Define the table if it's not following Laravel's naming convention (singular, lowercase table names)
    protected $table = 'vehicles';

    // Define fillable properties for mass assignment protection
    protected $fillable = [
        'vehicle_type',
        'other_vehicle_type',
        'required_driver_license',
        'license_plate',
        'is_manual',
        'is_electric',
        'max_km_per_run',
        'max_weight',
        'has_cooling',
        'last_inspection',
        'last_service',
    ];

    // Define the relationship: Each vehicle belongs to one business

    public function businesses()
    {
        return $this->belongsToMany(Business::class, 'business_vehicles')
            ->withPivot(['description', 'milage', 'status', 'other_status'])
            ->withTimestamps();
    }

    // Model event to ensure the vehicle is not assigned to more than one business
    protected static function booted()
    {
        static::saving(function ($vehicle) {
            if ($vehicle->business_id) {
                // Check if the vehicle is already assigned to a business
                $existingVehicle = Vehicle::where('id', $vehicle->id)
                    ->whereNotNull('business_id')
                    ->first();
                if ($existingVehicle && $existingVehicle->business_id !== $vehicle->business_id) {
                    // If the vehicle is already assigned, throw an exception
                    throw new \Exception("This vehicle is already assigned to another business.");
                }
            }
        });
    }
}
