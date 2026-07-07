<?php

namespace Database\Seeders;

use App\Models\Project;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $projects = [
            'Website Development',
            'Mobile App',
            'CRM System',
            'Bug Fixing',
            'Research & Development',
            'Internal Meeting',
        ];

        foreach ($projects as $projectName) {
            Project::create(['name' => $projectName]);
        }
    }
}
