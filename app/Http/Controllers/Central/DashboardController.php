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
        return Inertia::render('Dashboard/Dashboard', [
            'stats'           => $this->getStatusCardsData(),
            'recentContracts' => $this->getRecentContracts()
        ]);
    }

    private function getStatusCardsData() {
        $activeContractsCount = Contract::where('status', 2)->count();

        $expiringSoonCount = Contract::where('status', 2)
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->count();

        $totalValue = Contract::where('status', 2)->sum('value');

        $activeClientsCount = Client::whereHas('contracts', function ($query) {
            $query->where('status', 2);
        })->count();

        return [
            'activeContracts' => $activeContractsCount,
            'expiringSoon'    => $expiringSoonCount,
            'totalValue'      => $totalValue,
            'activeClients'   => $activeClientsCount,
        ];
    }

    private function getRecentContracts() {
        $recentContracts = Contract::with(['client', 'type'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($contract) => [
                'id'       => $contract->id,
                'name'     => $contract->name,
                'client_id'  => $contract->client->id,
                'client'     => $contract->client->name,
                'value'      => $contract->value ?: 0,
                'status'   => $contract->display_status,
                'progress' => $contract->progress,
                'type_icon'  => $contract->type->icon

            ]);
        return $recentContracts;
    }
}
