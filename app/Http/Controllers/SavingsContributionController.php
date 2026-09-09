<?php

namespace App\Http\Controllers;

use App\Models\SavingsContribution;
use App\Models\SavingsGoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavingsContributionController extends Controller
{
    public function create(SavingsGoal $savingsGoal): View
    {
        $this->authorizeGoal($savingsGoal);

        return view(
            'savings-contributions.create',
            compact('savingsGoal')
        );
    }

    public function store(
        Request $request,
        SavingsGoal $savingsGoal
    ): RedirectResponse {
        $this->authorizeGoal($savingsGoal);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'contribution_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $currentAmount = (float) $savingsGoal->contributions()->sum('amount');
        $newAmount = $currentAmount + (float) $validated['amount'];

        SavingsContribution::create([
            'savings_goal_id' => $savingsGoal->id,
            'amount' => $validated['amount'],
            'contribution_date' => $validated['contribution_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($newAmount >= (float) $savingsGoal->target_amount) {
            $savingsGoal->update([
                'is_completed' => true,
            ]);
        }

        return redirect()
            ->route('savings-goals.index')
            ->with('success', 'Contribution ajoutée avec succès.');
    }

    public function destroy(
        SavingsContribution $savingsContribution
    ): RedirectResponse {
        $this->authorizeContribution($savingsContribution);

        $savingsGoal = $savingsContribution->savingsGoal;

        $savingsContribution->delete();

        $currentAmount = (float) $savingsGoal
            ->contributions()
            ->sum('amount');

        if ($currentAmount < (float) $savingsGoal->target_amount) {
            $savingsGoal->update([
                'is_completed' => false,
            ]);
        }

        return redirect()
            ->route('savings-goals.index')
            ->with('success', 'Contribution supprimée avec succès.');
    }

    private function authorizeGoal(SavingsGoal $savingsGoal): void
    {
        abort_unless(
            $savingsGoal->user_id === auth()->id(),
            404
        );
    }

    private function authorizeContribution(
        SavingsContribution $savingsContribution
    ): void {
        abort_unless(
            $savingsContribution
                ->savingsGoal
                ->user_id === auth()->id(),
            404
        );
    }
}