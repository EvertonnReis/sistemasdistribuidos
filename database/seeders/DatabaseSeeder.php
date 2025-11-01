<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Create additional users
        $users = User::factory(20)->create();

        // Categories
        $categories = [
            'Web Development',
            'Data Science',
            'Mobile Development',
            'Design & UX',
            'Business & Marketing',
        ];

        $createdCategories = [];
        foreach ($categories as $name) {
            $createdCategories[] = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => fake()->sentence(12),
            ]);
        }

        // Create 20 courses distributed across categories
        $courses = [];
        for ($i = 1; $i <= 20; $i++) {
            $category = $createdCategories[array_rand($createdCategories)];
            $title = fake()->unique()->sentence(rand(3,6));
            $course = Course::create([
                'category_id' => $category->id,
                'title' => $title . ' - ' . $category->name,
                'slug' => Str::slug($title . ' ' . $i),
                'description' => fake()->paragraphs(3, true),
                'duration_hours' => rand(5, 120),
                'price' => number_format(rand(0,1) ? rand(2999,19999)/100 : 0, 2, '.', ''),
                'is_published' => (bool) rand(0,1),
                'published_at' => now()->subDays(rand(0, 120)),
            ]);

            $courses[] = $course;

            // Create lessons for this course
            $lessonCount = rand(5, 12);
            for ($j = 1; $j <= $lessonCount; $j++) {
                Lesson::create([
                    'course_id' => $course->id,
                    'title' => fake()->sentence(rand(3,8)),
                    'slug' => Str::slug(fake()->unique()->words(rand(2,4), true) . ' ' . $course->id . '-' . $j),
                    'content' => fake()->paragraphs(3, true),
                    'video_url' => rand(0,1) ? 'https://www.youtube.com/watch?v=' . Str::random(10) : null,
                    'duration_minutes' => rand(3, 60),
                    'order' => $j,
                    'is_free' => $j === 1,
                ]);
            }
        }

        // Enrollments
        foreach ($users as $user) {
            $enrollCount = rand(1, 5);
            $sample = collect($courses)->random($enrollCount);
            foreach ($sample as $c) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $c->id,
                    'enrolled_at' => now()->subDays(rand(0,90)),
                    'progress' => rand(0,100),
                ]);
            }
        }

        $this->command->info('✓ Database seeded successfully!');
        $this->command->info('✓ Admin user: admin@example.com / password123');
        $this->command->info('✓ Created ' . User::count() . ' users');
        $this->command->info('✓ Created ' . Category::count() . ' categories');
        $this->command->info('✓ Created ' . Course::count() . ' courses');
        $this->command->info('✓ Created ' . Lesson::count() . ' lessons');
        $this->command->info('✓ Created ' . Enrollment::count() . ' enrollments');
    }
}
