<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\KnowledgeSharing;
use Carbon\Carbon;

class KnowledgeSharingSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $documents = [
            ['title' => 'Laravel Best Practices Guide', 'type' => 'Documentation'],
            ['title' => 'Vue.js Component Architecture', 'type' => 'Tutorial'],
            ['title' => 'Database Optimization Techniques', 'type' => 'Article'],
            ['title' => 'API Design Principles', 'type' => 'Guide'],
            ['title' => 'JavaScript ES6 Features', 'type' => 'Presentation'],
            ['title' => 'Docker Container Setup', 'type' => 'Tutorial'],
            ['title' => 'Agile Development Process', 'type' => 'Documentation'],
            ['title' => 'Security Best Practices', 'type' => 'Guide'],
            ['title' => 'Performance Testing Strategy', 'type' => 'Article'],
            ['title' => 'Code Review Guidelines', 'type' => 'Documentation']
        ];

        foreach ($employees as $employee) {
            // Create 0-5 knowledge sharing records per employee
            $documentCount = rand(0, 5);
            
            for ($i = 0; $i < $documentCount; $i++) {
                $doc = $documents[array_rand($documents)];
                $hasLink = rand(0, 1);
                
                KnowledgeSharing::create([
                    'employee_id' => $employee->id,
                    'document_title' => $doc['title'],
                    'document_type' => $doc['type'],
                    'link' => $hasLink ? 'https://company.com/docs/' . strtolower(str_replace(' ', '-', $doc['title'])) : null,
                    'date_shared' => Carbon::now()->subYears(rand(0, 2))->subMonths(rand(0, 11))->format('Y-m-d'),
                ]);
            }
        }
    }
}
