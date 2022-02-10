<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => '',
                'email' => 'john.doe@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make("badpassword"),
                'status' => 'active',
                'role' => 'administrator',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => '',
                'email' => 'pierre.louis@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make("badpassword"),
                'status' => 'active',
                'role' => 'practitioner',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => '',
                'email' => 'ethan@test.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make("badpassword"),
                'status' => 'active',
                'role' => 'patient',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => '',
                'email' => 'francoise.legrand@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make("badpassword"),
                'status' => 'active',
                'role' => 'practitioner',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
