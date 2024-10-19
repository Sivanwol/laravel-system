<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDelivery;
use App\Models\UserDeliveryRegion;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $english = DB::table('languages')->where('code', 'en')->first();
        $hebrew = DB::table('languages')->where('code', 'he')->first();
        $spanish = DB::table('languages')->where('code', 'es')->first();
        $user = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'sivan@wolberg.pro',
            'phone' => '972501234577',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole(config('constants.system_roles.platform_admin'));
        $user->languages()->attach($english->id);

        // Delivery user
        $user = User::factory()->create([
            'first_name' => 'Delivery',
            'last_name' => 'User',
            'email' => 'sivan+delivey@wolberg.pro',
            'phone' => '972501234566',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole(config('constants.system_roles.delivery'));
        $user->languages()->attach($english->id);
        $user->languages()->attach($hebrew->id);
        $userDeliveries = UserDelivery::factory()->create([
            'user_id' => $user->id,
        ]);
        $userDeliveriesRegion = UserDeliveryRegion::factory()->create([
            'user_delivery_id' => $userDeliveries->id,
            'country_code' => 'IL',
            'country_region' => config('constants.country_IL_region.dan'),
        ]);
        $userDeliveries->regions()->attach($userDeliveriesRegion->id);

        $userDeliveriesRegion = UserDeliveryRegion::factory()->create([
            'user_delivery_id' => $userDeliveries->id,
            'country_code' => 'IL',
            'country_region' => config('constants.country_IL_region.shfela'),
        ]);
        $userDeliveries->regions()->attach($userDeliveriesRegion->id);
        $userDeliveries->save();

        // Business user

        $user = User::factory()->create([
            'first_name' => 'Business',
            'last_name' => 'User',
            'email' => 'sivan+business@wolberg.pro',
            'phone' => '972501234544',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole(config('constants.system_roles.business'));
        $user->languages()->attach($english->id);
        $user->languages()->attach($hebrew->id);

        // Delivery Business user
        $user = User::factory()->create([
            'first_name' => 'Delivery Business',
            'last_name' => 'User',
            'email' => 'sivan+dbusiness@wolberg.pro',
            'phone' => '972501234555',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole(config('constants.system_roles.delivery_business'));
        $user->languages()->attach($english->id);
        $user->languages()->attach($hebrew->id);
        $user->languages()->attach($spanish->id);

        $userDeliveries = UserDelivery::factory()->create([
            'user_id' => $user->id,
        ]);
        $userDeliveriesRegion = UserDeliveryRegion::factory()->create([
            'user_delivery_id' => $userDeliveries->id,
            'country_code' => 'IL',
            'country_region' => config('constants.country_IL_region.north'),
        ]);
        $userDeliveries->regions()->attach($userDeliveriesRegion->id);

        $userDeliveriesRegion = UserDeliveryRegion::factory()->create([
            'user_delivery_id' => $userDeliveries->id,
            'country_code' => 'IL',
            'country_region' => config('constants.country_IL_region.shfela'),
        ]);
        $userDeliveries->regions()->attach($userDeliveriesRegion->id);
        $userDeliveries->save();
    }
}
