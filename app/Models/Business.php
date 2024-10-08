<?
use App\Models\BusinessVehicle;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model {
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

    // Define relationships

    // Each Business belongs to an Owner (User)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'business_vehicles')
                    ->withPivot(['description', 'milage', 'status', 'other_status'])
                    ->withTimestamps();
    }

}