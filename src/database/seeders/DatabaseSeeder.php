<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Books;

use Illuminate\Database\Seeder;

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
            'name' => 'Demo User',
            'email' => 'demo@ziyad.test',
            'password' => bcrypt('password'),
            'borrow_quota' => 1,
        ]);

        // User random
        User::factory()->count(5)->create();

        // Buku random
        Books::factory()->count(5)->create([
            'stock' => 1, // sengaja 1 untuk test stok habis
        ]);
    }
}
