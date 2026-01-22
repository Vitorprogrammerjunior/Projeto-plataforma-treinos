<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

/**
 * Seeder de planos iniciais.
 */
class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Plano Mensal',
                'slug' => 'mensal',
                'description' => 'Acesso completo por 30 dias a todos os treinos',
                'price' => 49.90,
                'duration_days' => 30,
                'features' => [
                    'Acesso a todos os vídeos',
                    'Novos treinos toda semana',
                    'Suporte via WhatsApp',
                    'Treinos de 15 a 45 minutos',
                ],
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Plano Trimestral',
                'slug' => 'trimestral',
                'description' => 'Acesso completo por 90 dias - Melhor custo-benefício!',
                'price' => 119.90,
                'duration_days' => 90,
                'features' => [
                    'Tudo do plano mensal',
                    'Economia de R$ 30',
                    'Plano alimentar básico',
                    'Acesso prioritário a novos conteúdos',
                ],
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Plano Anual',
                'slug' => 'anual',
                'description' => 'Acesso completo por 1 ano - Transformação garantida!',
                'price' => 397.00,
                'duration_days' => 365,
                'features' => [
                    'Tudo do plano trimestral',
                    'Economia de R$ 200+',
                    'Consultoria mensal individual',
                    'Plano alimentar personalizado',
                    'Acesso vitalício a bônus',
                ],
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
