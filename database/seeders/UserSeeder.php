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
        $user->supportLanguage()->attach($english->id);

        // Delivery user
        $user = User::factory()->create([
            'first_name' => 'Delivery',
            'last_name' => 'User',
            'email' => 'sivan+delivey@wolberg.pro',
            'phone' => '972501234566',
            'about_me' => 'About me',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole(config('constants.system_roles.delivery'));
        $user->supportLanguage()->attach($english->id);
        $user->supportLanguage()->attach($hebrew->id);
        $userDelivery = UserDelivery::factory()->create([
            'user_id' => $user->id,
        ]);
        $userDelivery->regions()->create([
            'country_code' => 'IL',
            'country_region' => config('constants.country_IL_region.dan'),
        ]);

        $userDelivery->regions()->create([
            'country_code' => 'IL',
            'country_region' => config('constants.country_IL_region.shfela'),
        ]);

        // Business user

        $user = User::factory()->create([
            'first_name' => 'Business',
            'last_name' => 'User',
            'email' => 'sivan+business@wolberg.pro',
            'phone' => '972501234544',
            'phone_verified_at' => now(),
            'about_me' => 'About me',
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole(config('constants.system_roles.business'));
        $user->supportLanguage()->attach($english->id);
        $user->supportLanguage()->attach($hebrew->id);
        $user->business()->create([
            'name' => 'Business Name',
            'description' => 'Business Description',
            'address' => 'Business Address',
            'phone' => '972501234544',
            'email' => 'bz@testmail.org',
            'website' => 'https://www.testmail.org',
            'facebook' => 'https://www.facebook.com/testmail',
            'instagram' => 'https://www.instagram.com/testmail',
            'twitter' => 'https://www.twitter.com/testmail',
            'linkedin' => 'https://www.linkedin.com/testmail',
            'youtube' => 'https://www.youtube.com/testmail',
            'tiktok' => 'https://www.tiktok.com/testmail',
            'country' => 'IL',
            'city' => 'Tel Aviv',
            'zip' => '1234567',
            'business_size' => '1-10',
        ]);

        // Delivery Business user
        $user = User::factory()->create([
            'first_name' => 'Delivery Business',
            'last_name' => 'User',
            'email' => 'sivan+dbusiness@wolberg.pro',
            'phone' => '972501234555',
            'about_me' => 'About me',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole(config('constants.system_roles.delivery_business'));
        $user->supportLanguage()->attach($english->id);
        $user->supportLanguage()->attach($hebrew->id);
        $user->supportLanguage()->attach($spanish->id);

        $userDelivery = UserDelivery::factory()->create([
            'user_id' => $user->id,
        ]);
        $userDelivery->regions()->create([
            'country_code' => 'IL',
            'country_region' => config('constants.country_IL_region.dan'),
        ]);
        $userDelivery->regions()->create([
            'country_code' => 'IL',
            'country_region' => config('constants.country_IL_region.shfela'),
        ]);
    }
}
