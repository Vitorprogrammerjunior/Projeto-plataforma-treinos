<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tab;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller para gerenciamento de abas no painel administrativo.
 * Abas organizam os vídeos em categorias/seções.
 */
class TabController extends Controller
{
    /**
     * Lista todas as abas.
     */
    public function index()
    {
        $tabs = Tab::withCount('videos')
            ->ordered()
            ->get();

        return view('admin.tabs.index', compact('tabs'));
    }

    /**
     * Formulário de criação de aba.
     */
    public function create()
    {
        return view('admin.tabs.create');
    }

    /**
     * Salva nova aba.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Gera slug único
        $validated['slug'] = Str::slug($validated['name']);
        $originalSlug = $validated['slug'];
        $counter = 1;
        
        while (Tab::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter++;
        }

        // Define ordem se não informada
        if (!isset($validated['order'])) {
            $validated['order'] = Tab::max('order') + 1;
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        Tab::create($validated);

        return redirect()
            ->route('admin.tabs.index')
            ->with('success', 'Aba criada com sucesso!');
    }

    /**
     * Formulário de edição de aba.
     */
    public function edit(Tab $tab)
    {
        return view('admin.tabs.edit', compact('tab'));
    }

    /**
     * Atualiza aba existente.
     */
    public function update(Request $request, Tab $tab)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Atualiza slug se nome mudou
        if ($tab->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
            $originalSlug = $validated['slug'];
            $counter = 1;
            
            while (Tab::where('slug', $validated['slug'])->where('id', '!=', $tab->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter++;
            }
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $tab->update($validated);

        return redirect()
            ->route('admin.tabs.index')
            ->with('success', 'Aba atualizada com sucesso!');
    }

    /**
     * Remove uma aba.
     * Vídeos da aba ficam sem aba (tab_id = null).
     */
    public function destroy(Tab $tab)
    {
        // Desvincula vídeos da aba (não os exclui)
        $tab->videos()->update(['tab_id' => null]);
        
        $tab->delete();

        return redirect()
            ->route('admin.tabs.index')
            ->with('success', 'Aba removida com sucesso!');
    }

    /**
     * Reordena abas via AJAX.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:tabs,id',
        ]);

        foreach ($request->order as $position => $tabId) {
            Tab::where('id', $tabId)->update(['order' => $position]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Alterna status ativo/inativo.
     */
    public function toggleActive(Tab $tab)
    {
        $tab->update(['is_active' => !$tab->is_active]);

        $status = $tab->is_active ? 'ativada' : 'desativada';

        return redirect()
            ->route('admin.tabs.index')
            ->with('success', "Aba {$status} com sucesso!");
    }
}
