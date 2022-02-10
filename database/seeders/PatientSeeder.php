<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('patients')->insert([
            [
                'id' => 1,
                'firstname' => 'Ethan',
                'lastname' => 'EL DIB',
                'email' => 'ethan@test.com',
                'address' => '4 rue Danton',
                'zipcode' => '27140',
                'city' => 'Gisors',
                'user_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
