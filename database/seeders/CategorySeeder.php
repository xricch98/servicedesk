<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $categories = [
        'IT' => ['Network', 'Hardware', 'Printer', 'Software', 'Account Access', 'Other'],
        'BIOMED' => ['Patient Monitor', 'Ventilator', 'Imaging Equipment', 'Calibration', 'Other'],
        'FAC' => ['Plumbing', 'Air Conditioning', 'Carpentry', 'Doors & Locks', 'Other'],
        'ELEC' => ['Power Outage', 'Lighting', 'Socket / Outlet', 'Generator', 'Other'],
        'HK' => ['Cleaning Request', 'Waste Disposal', 'Linen', 'Other'],
    ];

    foreach ($categories as $code => $names) {
        $department = \App\Models\Department::where('code', $code)->first();

        if (! $department) {
            continue;
        }

        foreach ($names as $name) {
            \App\Models\Category::firstOrCreate([
                'department_id' => $department->id,
                'name' => $name,
            ]);
        }
    }
}
}
