<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Books;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User khusus untuk demo
        User::create([
            'name' => 'Faizal Dwi Al Farizi',
            'email' => 'faizaldwialfarizi@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'borrow_quota' => 1,
            'remember_token' => Str::random(10),
        ]);

        // User random
        User::factory()->count(20)->create();

        // Buku random
        Books::factory()->count(20)->create();
    }
}
