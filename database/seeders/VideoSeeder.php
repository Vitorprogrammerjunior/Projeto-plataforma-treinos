<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

/**
 * Seeder de vídeos de exemplo.
 */
class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            // Vídeos gratuitos (preview)
            [
                'title' => 'Bem-vinda ao Adri Treinos!',
                'slug' => 'bem-vinda',
                'description' => 'Conheça a plataforma e saiba como aproveitar ao máximo seus treinos.',
                'category' => 'Introdução',
                'duration_seconds' => 180,
                'video_url' => 'https://player.vimeo.com/video/example1',
                'video_source' => 'external',
                'is_active' => true,
                'is_free' => true,
                'order' => 0,
            ],
            [
                'title' => 'Aquecimento Completo - 5 minutos',
                'slug' => 'aquecimento-5min',
                'description' => 'Aquecimento rápido e eficiente para fazer antes de qualquer treino.',
                'category' => 'Aquecimento',
                'duration_seconds' => 300,
                'video_url' => 'https://player.vimeo.com/video/example2',
                'video_source' => 'external',
                'is_active' => true,
                'is_free' => true,
                'order' => 1,
            ],
            // Vídeos premium
            [
                'title' => 'Treino HIIT - Queima de Gordura',
                'slug' => 'hiit-queima-gordura',
                'description' => 'Treino intervalado de alta intensidade. 20 minutos de pura queima calórica!',
                'category' => 'HIIT',
                'duration_seconds' => 1200,
                'video_url' => 'https://player.vimeo.com/video/example3',
                'video_source' => 'external',
                'is_active' => true,
                'is_free' => false,
                'order' => 10,
            ],
            [
                'title' => 'Glúteos e Pernas - Intenso',
                'slug' => 'gluteos-pernas-intenso',
                'description' => 'Treino focado em glúteos e pernas. Prepare-se para sentir!',
                'category' => 'Inferior',
                'duration_seconds' => 1800,
                'video_url' => 'https://player.vimeo.com/video/example4',
                'video_source' => 'external',
                'is_active' => true,
                'is_free' => false,
                'order' => 11,
            ],
            [
                'title' => 'Abdômen Definido em 15 min',
                'slug' => 'abdomen-definido',
                'description' => 'Exercícios focados no core para definição abdominal.',
                'category' => 'Core',
                'duration_seconds' => 900,
                'video_url' => 'https://player.vimeo.com/video/example5',
                'video_source' => 'external',
                'is_active' => true,
                'is_free' => false,
                'order' => 12,
            ],
            [
                'title' => 'Braços e Ombros - Tonificação',
                'slug' => 'bracos-ombros',
                'description' => 'Treino completo para membros superiores usando apenas o peso do corpo.',
                'category' => 'Superior',
                'duration_seconds' => 1500,
                'video_url' => 'https://player.vimeo.com/video/example6',
                'video_source' => 'external',
                'is_active' => true,
                'is_free' => false,
                'order' => 13,
            ],
            [
                'title' => 'Full Body - Treino Completo',
                'slug' => 'full-body-completo',
                'description' => 'Treino que trabalha o corpo todo em 30 minutos intensos.',
                'category' => 'Full Body',
                'duration_seconds' => 1800,
                'video_url' => 'https://player.vimeo.com/video/example7',
                'video_source' => 'external',
                'is_active' => true,
                'is_free' => false,
                'order' => 14,
            ],
            [
                'title' => 'Alongamento e Relaxamento',
                'slug' => 'alongamento-relaxamento',
                'description' => 'Sessão de alongamento para recuperação e flexibilidade.',
                'category' => 'Alongamento',
                'duration_seconds' => 600,
                'video_url' => 'https://player.vimeo.com/video/example8',
                'video_source' => 'external',
                'is_active' => true,
                'is_free' => false,
                'order' => 20,
            ],
        ];

        foreach ($videos as $video) {
            Video::updateOrCreate(
                ['slug' => $video['slug']],
                $video
            );
        }
    }
}
