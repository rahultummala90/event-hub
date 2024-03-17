<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogPostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $tagCount = Tag::all()->count();

         if ($tagCount == 0) {
            $this->command->info("No tags found, skipping the tag seeder");
            return;
         }

         $min = (int)($this->command->ask("Minimum number tags for a blogpost?", 0));
         // The maximum number of tags allowed is the total 
         $max = min((int)$this->command->ask("Maximum number of tags for a blogpost", $tagCount), $tagCount);

         BlogPost::all()->each(function (BlogPost $blogPost) use ($min, $max) {
            $limit = random_int($min, $max);
            $tags = Tag::inRandomOrder()->take($limit)->get()->pluck("id");
            $blogPost->tags()->sync($tags);
         });

    }
}
