<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Category;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $defaultCategories = [
            // Dépenses
            ['name' => 'Courses', 'type' => 'expense', 'icon' => '🛒'],
            ['name' => 'Restaurants & Fast-food', 'type' => 'expense', 'icon' => '🍔'],
            ['name' => 'Logement', 'type' => 'expense', 'icon' => '🏠'],
            ['name' => 'Électricité & Gaz', 'type' => 'expense', 'icon' => '💡'],
            ['name' => 'Eau', 'type' => 'expense', 'icon' => '💧'],
            ['name' => 'Téléphone & Internet', 'type' => 'expense', 'icon' => '📱'],
            ['name' => 'Voiture', 'type' => 'expense', 'icon' => '🚗'],
            ['name' => 'Carburant', 'type' => 'expense', 'icon' => '⛽'],
            ['name' => 'Transports', 'type' => 'expense', 'icon' => '🚌'],
            ['name' => 'Santé', 'type' => 'expense', 'icon' => '🏥'],
            ['name' => 'Pharmacie', 'type' => 'expense', 'icon' => '💊'],
            ['name' => 'Shopping', 'type' => 'expense', 'icon' => '🛍️'],
            ['name' => 'Jeux vidéo', 'type' => 'expense', 'icon' => '🎮'],
            ['name' => 'Cinéma & Divertissement', 'type' => 'expense', 'icon' => '🎬'],
            ['name' => 'Musique', 'type' => 'expense', 'icon' => '🎵'],
            ['name' => 'Sport', 'type' => 'expense', 'icon' => '🏋️'],
            ['name' => 'Voyages', 'type' => 'expense', 'icon' => '✈️'],
            ['name' => 'Hôtel', 'type' => 'expense', 'icon' => '🏨'],
            ['name' => 'Abonnements', 'type' => 'expense', 'icon' => '💳'],
            ['name' => 'Vêtements', 'type' => 'expense', 'icon' => '👕'],
            ['name' => 'Cadeaux', 'type' => 'expense', 'icon' => '🎁'],
            ['name' => 'Animaux', 'type' => 'expense', 'icon' => '🐶'],
            ['name' => 'Éducation', 'type' => 'expense', 'icon' => '🎓'],
            ['name' => 'Épargne', 'type' => 'expense', 'icon' => '💰'],
            ['name' => 'Autres', 'type' => 'expense', 'icon' => '📦'],

            // Revenus
            ['name' => 'Salaire', 'type' => 'income', 'icon' => '💼'],
            ['name' => 'Prime', 'type' => 'income', 'icon' => '💶'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => '🧾'],
            ['name' => 'Loyer reçu', 'type' => 'income', 'icon' => '🏠'],
            ['name' => 'Remboursement', 'type' => 'income', 'icon' => '💸'],
            ['name' => 'Cadeau reçu', 'type' => 'income', 'icon' => '🎁'],
            ['name' => 'Investissements', 'type' => 'income', 'icon' => '📈'],
            ['name' => 'Autres revenus', 'type' => 'income', 'icon' => '💰'],
        ];

        foreach ($defaultCategories as $category) {
            $user->categories()->create([
                ...$category,
                'is_default' => true,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
