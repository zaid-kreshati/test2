<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Description;

class DescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample descriptions
        Description::create([
            'text' => 'This is the first description.',
            'user_id' => '1',
        ]);

        Description::create([
            'text' => 'This is the second description.',
            'user_id' => '1',

        ]);

        Description::create([
            'text' => 'This is the third description.',
            'user_id' => '2',

        ]);

        Description::create([
            'text' => 'This is the fourth description.',
            'user_id' => '2',

        ]);
    }
}
