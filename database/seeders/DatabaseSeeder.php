<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(CategorySeeder::class);  // Seed categories first
        $this->call(RolePermissionsSeeder::class);
        //$this->call(CommentSeeder::class);   // Seed comments (assuming a CommentSeeder exists)
        //$this->call(PostSeeder::class);      // Finally, seed posts, which depend on the above models
        $this->call(DescriptionSeeder::class);      // Finally, seed posts, which depend on the above models
        $this->call(PostSeeder::class);


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
