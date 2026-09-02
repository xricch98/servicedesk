<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $departments = [
        ['name' => 'Information Technology', 'code' => 'IT'],
        ['name' => 'Biomedical Engineering', 'code' => 'BIOMED'],
        ['name' => 'Facilities & Maintenance', 'code' => 'FAC'],
        ['name' => 'Electrical', 'code' => 'ELEC'],
        ['name' => 'Housekeeping', 'code' => 'HK'],
    ];

    foreach ($departments as $department) {
    \App\Models\Department::firstOrCreate(
        ['code' => $department['code']],
        $department
    );
}
}
}
