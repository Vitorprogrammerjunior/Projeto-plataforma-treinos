<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    /**
     * Criar um novo treino para o cliente.
     */
    public function store(Request $request, User $client)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'day_of_week' => 'nullable|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['user_id'] = $client->id;
        $validated['order'] = $client->workouts()->count();

        $workout = Workout::create($validated);

        return redirect()
            ->route('admin.clients.workouts', $client)
            ->with('success', 'Treino criado com sucesso!');
    }

    /**
     * Atualizar um treino existente.
     */
    public function update(Request $request, User $client, Workout $workout)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'day_of_week' => 'nullable|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $workout->update($validated);

        return redirect()
            ->route('admin.clients.workouts', $client)
            ->with('success', 'Treino atualizado com sucesso!');
    }

    /**
     * Excluir um treino.
     */
    public function destroy(User $client, Workout $workout)
    {
        $workout->delete();

        return redirect()
            ->route('admin.clients.workouts', $client)
            ->with('success', 'Treino excluído com sucesso!');
    }

    /**
     * Adicionar exercício ao treino.
     */
    public function addExercise(Request $request, User $client, Workout $workout)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sets' => 'nullable|integer|min:1',
            'reps' => 'nullable|string|max:50',
            'rest' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'video_url' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('exercises', 'public');
        }

        $validated['order'] = $workout->exercises()->count();

        $workout->exercises()->create($validated);

        return redirect()
            ->route('admin.clients.workouts', $client)
            ->with('success', 'Exercício adicionado com sucesso!');
    }

    /**
     * Atualizar exercício.
     */
    public function updateExercise(Request $request, User $client, Workout $workout, WorkoutExercise $exercise)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sets' => 'nullable|integer|min:1',
            'reps' => 'nullable|string|max:50',
            'rest' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'video_url' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('exercises', 'public');
        }

        $exercise->update($validated);

        return redirect()
            ->route('admin.clients.workouts', $client)
            ->with('success', 'Exercício atualizado com sucesso!');
    }

    /**
     * Excluir exercício.
     */
    public function destroyExercise(User $client, Workout $workout, WorkoutExercise $exercise)
    {
        $exercise->delete();

        return redirect()
            ->route('admin.clients.workouts', $client)
            ->with('success', 'Exercício excluído com sucesso!');
    }

    /**
     * Reordenar exercícios.
     */
    public function reorderExercises(Request $request, User $client, Workout $workout)
    {
        $validated = $request->validate([
            'exercises' => 'required|array',
            'exercises.*' => 'integer|exists:workout_exercises,id',
        ]);

        foreach ($validated['exercises'] as $order => $id) {
            WorkoutExercise::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}
