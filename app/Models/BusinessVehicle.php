<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessVehicle extends Model
{
    use HasFactory;
    // Define the table if it's not following Laravel's naming convention (singular, lowercase table names)
    protected $table = 'business_vehicles';

    // Define fillable properties for mass assignment protection
    protected $fillable = [
        'milage',
        'status',
        'other_status',
        'last_inspection',
        'last_service',
    ];

    protected $casts = [
        'last_inspection' => 'datetime',
        'last_service' => 'datetime',
    ];
    // Relationships

    // Each BusinessVehicle belongs to a Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Each BusinessVehicle belongs to a Business
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
