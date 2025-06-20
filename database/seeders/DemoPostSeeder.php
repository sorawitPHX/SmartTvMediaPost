<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class DemoPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'user_id' => 1,
            'caption' => 'Welcome Slide',
            'filename' => 'demo/image1.jpg',
            'type' => 'image',
            'is_public' => true,
            'duration' => 5,
            'order' => 1
        ]);

        Post::create([
            'user_id' => 1,
            'caption' => 'Hospital Video',
            'filename' => 'demo/video1.mp4',
            'type' => 'video',
            'is_public' => true,
            'duration' => 15,
            'order' => 2
        ]);
    }
}
