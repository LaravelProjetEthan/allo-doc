<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('appointments')->insert([
            [
                'id' => 1,
                'patient_id' => 1,
                'practitioner_id' => 1,
                'reason' => 'Vaccin COVID',
                'meet_at' => '2022-02-09 10:00:00',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'patient_id' => 1,
                'practitioner_id' => 2,
                'reason' => 'Consultation suivi',
                'meet_at' => '2022-07-23 14:15:00',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
