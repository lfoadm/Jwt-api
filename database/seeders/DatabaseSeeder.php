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
        \App\Models\User::factory()->create([
            'first_name' => 'Leandro',
            'last_name' => 'Ferreira Oliveira',
            'email' => 'lfoadm@icloud.com',
        ]);

        \App\Models\User::factory(3)->create();

    }
}
