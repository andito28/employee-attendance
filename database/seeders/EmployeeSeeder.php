<?php

namespace Database\Seeders;

use App\Models\Employee\User;
use Illuminate\Database\Seeder;
use App\Models\Employee\Sibling;
use App\Models\Employee\Employee;
use App\Models\Employee\Parental;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory()->count(100)->has(User::factory()->count(1))
        ->has(Sibling::factory()->count(2))->has(Parental::factory()->count(1))
        ->create();

    }


    /** --- FUNCTIONS --- */

    private function getData()
    {
        return array(
            []
        );
    }

}
