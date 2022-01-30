<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PractitionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('practitioners')->insert([
            [
                'id' => 1,
                'firstname' => 'Arnaud',
                'lastname' => 'Labrocante',
                'email' => 'arnaud.l@gmail.com',
                'address' => '80b Avenue Verge',
                'zipcode' => '75000',
                'city' => 'Paris',
                'phone' => '+33118060001',
                'user_id' => 2,
                'speciality_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'firstname' => 'Marie',
                'lastname' => 'Antoinette',
                'email' => 'nohead@gmail.com',
                'address' => '1 rue des Guillotines',
                'zipcode' => '27000',
                'city' => 'Évreux',
                'phone' => '+33102030405',
                'user_id' => 4,
                'speciality_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
