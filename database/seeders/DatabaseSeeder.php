<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 1000 users
        User::factory(1000)->create();

        // // Call the seeders
        $this->call(EventSeeder::class);
        $this->call(AttendeeSeeder::class);
        $this->call(BlogPostSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(BlogPostTagTableSeeder::class); 
    }
}
