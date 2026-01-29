<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MealPlan;
use App\Models\Meal;
use App\Models\MealItem;
use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    /**
     * Criar um novo plano alimentar para o cliente.
     */
    public function store(Request $request, User $client)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objective' => 'nullable|string|max:255',
            'daily_calories' => 'nullable|integer|min:0',
            'protein_grams' => 'nullable|integer|min:0',
            'carbs_grams' => 'nullable|integer|min:0',
            'fat_grams' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        // Desativar outros planos do cliente
        $client->mealPlans()->update(['is_active' => false]);

        $validated['user_id'] = $client->id;
        $validated['is_active'] = true;

        $mealPlan = MealPlan::create($validated);

        // Criar refeições padrão
        $defaultMeals = [
            ['name' => 'Café da Manhã', 'time' => '07:00', 'order' => 0],
            ['name' => 'Lanche da Manhã', 'time' => '10:00', 'order' => 1],
            ['name' => 'Almoço', 'time' => '12:30', 'order' => 2],
            ['name' => 'Lanche da Tarde', 'time' => '16:00', 'order' => 3],
            ['name' => 'Jantar', 'time' => '19:30', 'order' => 4],
            ['name' => 'Ceia', 'time' => '21:30', 'order' => 5],
        ];

        foreach ($defaultMeals as $meal) {
            $mealPlan->meals()->create($meal);
        }

        return redirect()
            ->route('admin.clients.meal-plans', $client)
            ->with('success', 'Plano alimentar criado com sucesso!');
    }

    /**
     * Atualizar plano alimentar.
     */
    public function update(Request $request, User $client, MealPlan $mealPlan)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objective' => 'nullable|string|max:255',
            'daily_calories' => 'nullable|integer|min:0',
            'protein_grams' => 'nullable|integer|min:0',
            'carbs_grams' => 'nullable|integer|min:0',
            'fat_grams' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['is_active']) && $validated['is_active']) {
            // Desativar outros planos
            $client->mealPlans()->where('id', '!=', $mealPlan->id)->update(['is_active' => false]);
        }

        $mealPlan->update($validated);

        return redirect()
            ->route('admin.clients.meal-plans', $client)
            ->with('success', 'Plano alimentar atualizado com sucesso!');
    }

    /**
     * Excluir plano alimentar.
     */
    public function destroy(User $client, MealPlan $mealPlan)
    {
        $mealPlan->delete();

        return redirect()
            ->route('admin.clients.meal-plans', $client)
            ->with('success', 'Plano alimentar excluído com sucesso!');
    }

    /**
     * Adicionar item à refeição.
     */
    public function addItem(Request $request, User $client, MealPlan $mealPlan, Meal $meal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:100',
            'calories' => 'nullable|integer|min:0',
            'protein' => 'nullable|integer|min:0',
            'carbs' => 'nullable|integer|min:0',
            'fat' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'alternatives' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('meal-items', 'public');
        }

        $validated['order'] = $meal->items()->count();

        $meal->items()->create($validated);

        return redirect()
            ->route('admin.clients.meal-plans', $client)
            ->with('success', 'Alimento adicionado com sucesso!');
    }

    /**
     * Atualizar item da refeição.
     */
    public function updateItem(Request $request, User $client, MealPlan $mealPlan, Meal $meal, MealItem $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:100',
            'calories' => 'nullable|integer|min:0',
            'protein' => 'nullable|integer|min:0',
            'carbs' => 'nullable|integer|min:0',
            'fat' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'alternatives' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('meal-items', 'public');
        }

        $item->update($validated);

        return redirect()
            ->route('admin.clients.meal-plans', $client)
            ->with('success', 'Alimento atualizado com sucesso!');
    }

    /**
     * Excluir item da refeição.
     */
    public function destroyItem(User $client, MealPlan $mealPlan, Meal $meal, MealItem $item)
    {
        $item->delete();

        return redirect()
            ->route('admin.clients.meal-plans', $client)
            ->with('success', 'Alimento removido com sucesso!');
    }

    /**
     * Atualizar refeição (horário, nome).
     */
    public function updateMeal(Request $request, User $client, MealPlan $mealPlan, Meal $meal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        $meal->update($validated);

        return redirect()
            ->route('admin.clients.meal-plans', $client)
            ->with('success', 'Refeição atualizada com sucesso!');
    }

    /**
     * Adicionar nova refeição ao plano.
     */
    public function addMeal(Request $request, User $client, MealPlan $mealPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        $validated['order'] = $mealPlan->meals()->count();

        $mealPlan->meals()->create($validated);

        return redirect()
            ->route('admin.clients.meal-plans', $client)
            ->with('success', 'Refeição adicionada com sucesso!');
    }

    /**
     * Excluir refeição.
     */
    public function destroyMeal(User $client, MealPlan $mealPlan, Meal $meal)
    {
        $meal->delete();

        return redirect()
            ->route('admin.clients.meal-plans', $client)
            ->with('success', 'Refeição excluída com sucesso!');
    }
}
