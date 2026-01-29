<?php

namespace Database\Seeders;

use App\Models\Tab;
use Illuminate\Database\Seeder;

/**
 * Seeder para criar abas de exemplo.
 */
class TabSeeder extends Seeder
{
    public function run(): void
    {
        $tabs = [
            [
                'name' => 'Treinos Iniciantes',
                'slug' => 'treinos-iniciantes',
                'description' => 'Treinos para quem está começando a praticar exercícios',
                'icon' => '🌱',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Treinos Intermediários',
                'slug' => 'treinos-intermediarios',
                'description' => 'Treinos para quem já tem uma base de condicionamento',
                'icon' => '💪',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Treinos Avançados',
                'slug' => 'treinos-avancados',
                'description' => 'Treinos intensos para quem busca desafios',
                'icon' => '🔥',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'HIIT',
                'slug' => 'hiit',
                'description' => 'Treinos de alta intensidade com intervalos',
                'icon' => '⚡',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Alongamentos',
                'slug' => 'alongamentos',
                'description' => 'Exercícios de alongamento e flexibilidade',
                'icon' => '🧘',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($tabs as $tab) {
            Tab::updateOrCreate(
                ['slug' => $tab['slug']],
                $tab
            );
        }
    }
}
