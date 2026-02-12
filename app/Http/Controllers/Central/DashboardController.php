<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Models\Contract;
use App\Models\Client;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller {
    public function index(): Response {
        $activeContractsCount = Contract::where('status', 2)->count();

        $expiringSoonCount = Contract::where('status', 2)
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->count();

        $totalValue = Contract::where('status', 2)->sum('value');

        $activeClientsCount = Client::whereHas('contracts', function ($query) {
            $query->where('status', 2);
        })->count();

        return Inertia::render('Dashboard/Dashboard', [
            'stats' => [
                'activeContracts' => $activeContractsCount,
                'expiringSoon'    => $expiringSoonCount,
                'totalValue'      => $totalValue,
                'activeClients'   => $activeClientsCount,
            ]
        ]);
    }
}
