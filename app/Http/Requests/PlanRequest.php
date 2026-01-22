<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validação para criação/edição de planos.
 */
class PlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $planId = $this->route('plan')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('plans', 'slug')->ignore($planId),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do plano é obrigatório.',
            'slug.unique' => 'Este slug já está em uso.',
            'price.required' => 'O preço é obrigatório.',
            'price.min' => 'O preço não pode ser negativo.',
            'duration_days.required' => 'A duração é obrigatória.',
            'duration_days.min' => 'A duração mínima é 1 dia.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->slug && $this->name) {
            $this->merge([
                'slug' => \Str::slug($this->name),
            ]);
        }

        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);

        // Converte features de texto para array
        if ($this->features && is_string($this->features)) {
            $this->merge([
                'features' => array_filter(
                    array_map('trim', explode("\n", $this->features))
                ),
            ]);
        }
    }
}
