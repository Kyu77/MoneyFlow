<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavingsGoalController extends Controller
{
    public function index(): View
    {
        $savingsGoals = auth()->user()
            ->savingsGoals()
            ->with('contributions')
            ->latest()
            ->get();

        return view('savings-goals.index', compact('savingsGoals'));
    }

    public function create(): View
    {
        return view('savings-goals.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'numeric', 'gt:0'],
            'target_date' => ['nullable', 'date'],
        ]);

        auth()->user()->savingsGoals()->create([
            'name' => $validated['name'],
            'target_amount' => $validated['target_amount'],
            'target_date' => $validated['target_date'] ?? null,
            'is_completed' => false,
        ]);

        return redirect()
            ->route('savings-goals.index')
            ->with('success', 'Objectif créé avec succès.');
    }

    public function edit(SavingsGoal $savingsGoal): View
    {
        $this->authorizeGoal($savingsGoal);

        return view('savings-goals.edit', compact('savingsGoal'));
    }

    public function update(
        Request $request,
        SavingsGoal $savingsGoal
    ): RedirectResponse {
        $this->authorizeGoal($savingsGoal);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'numeric', 'gt:0'],
            'target_date' => ['nullable', 'date'],
        ]);

        $currentAmount = (float) $savingsGoal
        ->contributions()
        ->sum('amount');

        $isCompleted = $currentAmount >= (float) $validated['target_amount'];

        $savingsGoal->update([
            'name' => $validated['name'],
            'target_amount' => $validated['target_amount'],
            'target_date' => $validated['target_date'] ?? null,
            'is_completed' => $isCompleted,
        ]);

        return redirect()
            ->route('savings-goals.index')
            ->with('success', 'Objectif modifié avec succès.');
    }

    public function destroy(
        SavingsGoal $savingsGoal
    ): RedirectResponse {
        $this->authorizeGoal($savingsGoal);

        $savingsGoal->delete();

        return redirect()
            ->route('savings-goals.index')
            ->with('success', 'Objectif supprimé avec succès.');
    }

    private function authorizeGoal(
        SavingsGoal $savingsGoal
    ): void {
        abort_unless(
            $savingsGoal->user_id === auth()->id(),
            404
        );
    }
}