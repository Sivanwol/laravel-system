<?
use App\Models\BusinessVehicle;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    // Define the table name if it's not following Laravel's naming convention
    protected $table = 'business';

    // Define fillable properties for mass assignment protection
    protected $fillable = [
        'owner_user_id',
        'name',
        'description',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'whatapp',
        'phone',
        'email',
        'country',
        'city',
        'address',
        'zip',
        'latitude',
        'longitude',
        'timezone',
        'business-size',
    ];

    public function scopeActive($query)
    {
        return $query->whereNotNull('active_at');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'entity_has_vehicles')
            ->withPivot(['description', 'milage', 'status', 'other_status'])
            ->withTimestamps();
    }

    public function addVehicle($vehicle_id, $milage = null, $status = 'active', $other_status = null)
    {
        $this->vehicles()->attach($vehicle_id, [
            'milage' => $milage,
            'status' => $status,
            'other_status' => $other_status,
        ]);
    }

    public function removeVehicle($vehicle_id)
    {
        $this->vehicles()->detach($vehicle_id);
    }

    public function stats()
    {
        $totalVehicles = $this->belongsToMany(Vehicle::class, 'business_vehicles')->count();

        return [
            'total_vehicles' => $totalVehicles,
        ];
    }
}
