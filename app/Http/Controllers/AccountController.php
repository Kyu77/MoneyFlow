<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $accounts = auth()->user()
            ->accounts()
            ->latest()
            ->get();

        return view('accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        return view('accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:current,savings,joint,other'],
            'balance' => ['required', 'numeric', 'min:0'],
            'is_shared' => ['boolean'],
        ]);

        $validated['is_shared'] = $request->boolean('is_shared');

        auth()->user()->accounts()->create($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Compte créé avec succès.');
    }

    public function show(Account $account): View
    {
        abort_unless($account->user_id === auth()->id(), 404);

        return view('accounts.show', compact('account'));
    }

    public function edit(Account $account): View
    {
        abort_unless($account->user_id === auth()->id(), 404);

        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account): RedirectResponse
    {
        abort_unless($account->user_id === auth()->id(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:current,savings,joint,other'],
            'is_shared' => ['boolean'],
        ]);

        $validated['is_shared'] = $request->boolean('is_shared');

        $account->update($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Compte modifié avec succès.');
    }

    public function destroy(Account $account): RedirectResponse
    {
        abort_unless($account->user_id === auth()->id(), 404);

        $account->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Compte supprimé avec succès.');
    }
}