<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Certification;
use Carbon\Carbon;

class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $certifications = [
            ['name' => 'AWS Certified Solutions Architect', 'org' => 'Amazon Web Services'],
            ['name' => 'Google Cloud Professional', 'org' => 'Google Cloud'],
            ['name' => 'Microsoft Azure Fundamentals', 'org' => 'Microsoft'],
            ['name' => 'Certified ScrumMaster', 'org' => 'Scrum Alliance'],
            ['name' => 'PMP Certification', 'org' => 'Project Management Institute'],
            ['name' => 'Laravel Certified Developer', 'org' => 'Laravel'],
            ['name' => 'Oracle Database Administrator', 'org' => 'Oracle'],
            ['name' => 'Cisco Certified Network Professional', 'org' => 'Cisco'],
            ['name' => 'CompTIA Security+', 'org' => 'CompTIA'],
            ['name' => 'Kubernetes Administrator', 'org' => 'Linux Foundation']
        ];

        foreach ($employees as $employee) {
            // Create 0-4 certifications per employee
            $certificationCount = rand(0, 4);

            if ($certificationCount === 0) {
                continue;
            }

            $selectedCerts = array_rand($certifications, min($certificationCount, count($certifications)));
            if (!is_array($selectedCerts)) {
                $selectedCerts = [$selectedCerts];
            }

            foreach ($selectedCerts as $certIndex) {
                $cert = $certifications[$certIndex];
                $issueDate = Carbon::now()->subYears(rand(0, 5))->subMonths(rand(0, 11));
                $expiryDate = rand(0, 1) ? $issueDate->copy()->addYears(rand(2, 5)) : null;

                Certification::create([
                    'employee_id' => $employee->id,
                    'certification_name' => $cert['name'],
                    'issuing_organization' => $cert['org'],
                    'issue_date' => $issueDate->format('Y-m-d'),
                    'expiry_date' => $expiryDate?->format('Y-m-d'),
                    'image' => null, // Migration includes image column, set as null or provide a path if available
                ]);
            }
        }
    }
}
