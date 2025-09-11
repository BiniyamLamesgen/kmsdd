<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,  // Add UserSeeder here
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            SkillSeeder::class,
            EmployeeSkillSeeder::class,
            ExperienceSeeder::class,
            ProjectSeeder::class,
            AchievementSeeder::class,
            CertificationSeeder::class,
            EducationSeeder::class,
            KnowledgeSharingSeeder::class,
            EndorsementSeeder::class,
        ]);
    }
}

