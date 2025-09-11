<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'Angular',
            'Node.js', 'Python', 'Django', 'Flask', 'Java', 'Spring Boot',
            'C#', '.NET', 'Ruby', 'Ruby on Rails', 'Go', 'Rust',
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'AWS', 'Azure',
            'Docker', 'Kubernetes', 'Git', 'DevOps', 'CI/CD', 'Jenkins',
            'HTML', 'CSS', 'SASS', 'Bootstrap', 'Tailwind CSS',
            'UI/UX Design', 'Figma', 'Adobe XD', 'Photoshop', 'Illustrator',
            'Project Management', 'Agile', 'Scrum', 'Kanban',
            'Machine Learning', 'AI', 'Data Science', 'TensorFlow', 'PyTorch',
            'Mobile Development', 'React Native', 'Flutter', 'Swift', 'Kotlin',
        ];

        foreach ($skills as $skill) {
            Skill::create(['skill_name' => $skill]);
        }
    }
}
