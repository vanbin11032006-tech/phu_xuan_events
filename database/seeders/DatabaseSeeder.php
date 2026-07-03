<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Tag;
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
        // 1. Seed Categories and Tags first
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
        ]);

        // 2. Create default accounts for testing (with password = 'password')
        User::factory()->create([
            'name' => 'PXU Admin',
            'email' => 'admin@pxu.edu.vn',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '0987654321',
        ]);

        User::factory()->create([
            'name' => 'PXU Organizer',
            'email' => 'organizer@pxu.edu.vn',
            'password' => bcrypt('password'),
            'role' => 'organizer',
            'phone' => '0912345678',
        ]);

        User::factory()->create([
            'name' => 'PXU Student',
            'email' => 'student@pxu.edu.vn',
            'password' => bcrypt('password'),
            'role' => 'student',
            'phone' => '0905556667',
        ]);

        // 3. Create the remaining users using factories to hit the targets (3 admins, 10 organizers, 50 students total)
        User::factory(2)->admin()->create();
        $organizers = User::factory(9)->organizer()->create();
        $students = User::factory(49)->student()->create();

        // Include the default student/organizer in the queryable arrays
        $allOrganizers = User::where('role', 'organizer')->get();
        $allStudents = User::where('role', 'student')->get();

        // 4. Create 60 events using EventFactory
        // The factory will automatically assign a random category and organizer
        $events = Event::factory(60)->create();

        // 5. Add tags to events and create registrations
        $tags = Tag::all();

        foreach ($events as $event) {
            // Attach 2 to 4 random tags
            $randomTags = $tags->random(rand(2, 4));
            $event->tags()->attach($randomTags);

            // Create some random registrations for each event
            // Only create registrations for published events, and up to the capacity (some can be full, some partially full)
            if ($event->status === 'published') {
                $registrationCount = rand(5, min(25, $event->capacity));
                $registeringStudents = $allStudents->random($registrationCount);

                foreach ($registeringStudents as $student) {
                    Registration::create([
                        'user_id' => $student->id,
                        'event_id' => $event->id,
                        'status' => fake()->randomElement(['pending', 'confirmed', 'confirmed', 'confirmed', 'cancelled']), // mostly confirmed
                        'note' => fake()->optional(0.6)->sentence(),
                        'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
                    ]);
                }
            }
        }
    }
}
