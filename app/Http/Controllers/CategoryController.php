<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = auth()->user()
            ->categories()
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:expense,income'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        auth()->user()->categories()->create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(Category $category): View
    {
        $this->authorizeCategory($category);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:expense,income'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie modifiée avec succès.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    private function authorizeCategory(Category $category): void
    {
        abort_unless(
            $category->user_id === auth()->id(),
            404
        );
    }
}