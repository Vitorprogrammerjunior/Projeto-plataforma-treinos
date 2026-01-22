<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Model Video - Representa os vídeos de treino.
 */
class Video extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'video_path',
        'video_url',
        'video_source',
        'duration_seconds',
        'category',
        'order',
        'is_active',
        'is_free',
        'views_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'duration_seconds' => 'integer',
        'views_count' => 'integer',
    ];

    /**
     * Scope para vídeos ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para vídeos gratuitos.
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Scope ordenação padrão.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Retorna duração formatada (mm:ss).
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) {
            return '--:--';
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Retorna URL da thumbnail.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            return Storage::disk('public')->url($this->thumbnail);
        }

        return asset('images/video-placeholder.jpg');
    }

    /**
     * Retorna URL segura do vídeo (para streaming protegido).
     */
    public function getVideoStreamUrlAttribute(): ?string
    {
        if ($this->video_source === 'external') {
            return $this->video_url;
        }

        // Para vídeos locais, retorna rota protegida
        return route('videos.stream', $this->slug);
    }

    /**
     * Incrementa contador de views.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Retorna categorias únicas de todos os vídeos.
     */
    public static function getCategories(): array
    {
        return self::active()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }
}
