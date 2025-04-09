<?php

namespace Database\Seeders;

use App\Models\TTask;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('asdasdasd'),
        //     'role' => "0"
        // ]);

        $user = User::where("role", "1")->get();

        foreach ($user as $r) {
            for ($i = 0; $i < 20; $i++) {
                TTask::create([
                    "user_id" => $r->id,
                    "keterangan" => "Test Task $i",
                    "created_at" => now(),
                    "deadline" => now()->addDays(3),
                ]);
            }
        }
    }
}
