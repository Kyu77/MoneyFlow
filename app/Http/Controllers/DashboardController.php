<?php

namespace App\Http\Controllers;

use App\Services\FinancialForecastService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        FinancialForecastService $forecastService
    ): View {
        $user = auth()->user();

        $accounts = $user->accounts()
            ->orderBy('name')
            ->get();

        $accountForecasts = $accounts->map(function ($account) use ($forecastService) {
            return [
                'account' => $account,
                'forecast' => $forecastService->forecastAccountBalance($account),
            ];
        });

        return view('dashboard', [
            'accounts' => $accounts,
            'accountForecasts' => $accountForecasts,
        ]);
    }
}
