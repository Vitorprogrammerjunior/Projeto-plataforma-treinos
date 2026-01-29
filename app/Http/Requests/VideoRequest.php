<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validação para criação/edição de vídeos.
 */
class VideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $videoId = $this->route('video')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('videos', 'slug')->ignore($videoId),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'tab_id' => ['nullable', 'exists:tabs,id'],
            'thumbnail' => ['nullable', 'image', 'max:2048'], // Máx 2MB
            'video_file' => ['nullable', 'mimes:mp4,webm,mov', 'max:512000'], // Máx 500MB
            'video_url' => ['nullable', 'url', 'max:500'],
            'video_source' => ['required', Rule::in(['local', 'external'])],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_free' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'slug.unique' => 'Este slug já está em uso.',
            'slug.alpha_dash' => 'O slug deve conter apenas letras, números, traços e underscores.',
            'thumbnail.image' => 'A thumbnail deve ser uma imagem.',
            'thumbnail.max' => 'A thumbnail não pode ter mais de 2MB.',
            'video_file.mimes' => 'O vídeo deve ser MP4, WebM ou MOV.',
            'video_file.max' => 'O vídeo não pode ter mais de 500MB.',
            'video_url.url' => 'Informe uma URL válida.',
        ];
    }

    /**
     * Prepara dados para validação.
     */
    protected function prepareForValidation(): void
    {
        // Gera slug automaticamente se não fornecido
        if (!$this->slug && $this->title) {
            $this->merge([
                'slug' => \Str::slug($this->title),
            ]);
        }

        // Converte checkboxes para boolean
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_free' => $this->boolean('is_free'),
        ]);
    }
}
