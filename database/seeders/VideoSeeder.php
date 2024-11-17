<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Video;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample video entries
        Video::create([
            'url' => 'https://example.com/videos/video1.mp4',
        ]);

        Video::create([
            'url' => 'https://example.com/videos/video2.mp4',
        ]);

        Video::create([
            'url' => 'https://example.com/videos/video3.mp4',
        ]);

        Video::create([
            'url' => 'https://example.com/videos/video4.mp4',
        ]);
    }
}
