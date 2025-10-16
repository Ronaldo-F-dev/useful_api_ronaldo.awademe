<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/ModuleSeeder.php
public function run()
{
    DB::table('modules')->insert([
        ['name' => 'URL Shortener', 'description' => 'Raccourcir et gérer des liens'],
        ['name' => 'Wallet', 'description' => 'Gestion du solde et transferts'],
        ['name' => 'Marketplace', 'description' => 'Gestion de produits et commandes'],
        ['name' => 'Time Tracker', 'description' => 'Suivi du temps et sessions'],
        ['name' => 'Investment Tracker', 'description' => 'Suivi du portefeuille d’investissement'],
    ]);
}

}
