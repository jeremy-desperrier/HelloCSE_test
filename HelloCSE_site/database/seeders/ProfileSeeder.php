<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 profil actif minimum 
        Profile::factory()->count(1)->create([
            'statut' => 'actif',
        ]);

        // autres profils totalement aléatoire
        Profile::factory()->count(9)->create();
    }
}
