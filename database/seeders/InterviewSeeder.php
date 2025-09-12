<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Interview;
use App\Models\Question;

class InterviewSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get an admin user to create interviews
        $admin = User::where('email', 'admin@horizon.test')->first();
        
        if (!$admin) {
            $this->command->warn('Admin user not found.');
            return;
        }

        // Create sample interviews
        $interviews = [
            [
                'title' => 'Frontend Developer Interview',
                'description' => 'Technical interview for Frontend Developer position.',
                'questions' => [
                    [
                        'question' => 'Tell us about yourself and your frontend experience.',
                        'time_limit' => 3,
                        'order' => 1
                    ],
                    [
                        'question' => 'Explain React functional vs class components.',
                        'time_limit' => 5,
                        'order' => 2
                    ],
                    [
                        'question' => 'How do you optimize React app performance?',
                        'time_limit' => 5,
                        'order' => 3
                    ]
                ]
            ],
            [
                'title' => 'Backend Developer Interview',
                'description' => 'Technical interview for Backend Developer position.',
                'questions' => [
                    [
                        'question' => 'Introduce yourself and your backend experience.',
                        'time_limit' => 3,
                        'order' => 1
                    ],
                    [
                        'question' => 'Explain the MVC pattern in Laravel.',
                        'time_limit' => 4,
                        'order' => 2
                    ],
                    [
                        'question' => 'Design a database schema for social media.',
                        'time_limit' => 6,
                        'order' => 3
                    ]
                ]
            ]
        ];

        foreach ($interviews as $interviewData) {
            $interview = Interview::create([
                'title' => $interviewData['title'],
                'description' => $interviewData['description'],
                'created_by' => $admin->id,
            ]);

            foreach ($interviewData['questions'] as $questionData) {
                Question::create([
                    'interview_id' => $interview->id,
                    'question' => $questionData['question'],
                    'time_limit' => $questionData['time_limit'],
                    'order' => $questionData['order'],
                ]);
            }

            $this->command->info("Created interview: {$interview->title}");
        }
    }
}
