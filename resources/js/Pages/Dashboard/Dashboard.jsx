import TenantLayout from "@/Layouts/TenantLayout";

import {
    StatsCards,
    ExpirationSummary,
    ActivityFeed,
} from "@/Components/ui";

import ContractsTable from "@/Pages/Contracts/ContractsTable";

export default function Dashboard({ stats, recentContracts }) {
    return (
        <div className="flex flex-1 flex-col">
            <div className="p-4 md:p-8">
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <StatsCards
                        title="Active Contracts"
                        value={stats.activeContracts}
                        description="Currently in force"
                    />
                    <StatsCards
                        title="Contracts Expiring Soon"
                        value={stats.expiringSoon}
                        description="Requires attention"
                    />
                    <StatsCards
                        title="Total Contract Value (TCV)"
                        value={stats.totalValue}
                        description="Across all active contracts"
                    />
                    <StatsCards
                        title="Clients with Active Contracts"
                        value={stats.activeClients}
                        description="Engaged clients"
                    />
                </div>
            </div>
            <div className="p-4 md:p-8">
                <div className="grid grid-cols-1 gap-6 lg:grid-cols-12">
                    <div className="lg:col-span-8 xl:col-span-9 bg-card border border-border rounded-lg shadow-sm overflow-hidden">
                        <div className="px-6 py-4 border-b border-border flex justify-between items-center">
                            <h2 className="text-base font-semibold">
                                Recent Contracts
                            </h2>
                            <button className="text-sm text-primary hover:opacity-80 transition-opacity no-blue-link">
                                View all
                            </button>
                        </div>

                        <div className="overflow-x-auto">
                            <ContractsTable
                                contracts={recentContracts}
                                bIsDashboard={true}
                            />
                        </div>
                    </div>
                    <div className="flex flex-col gap-6 lg:col-span-4 xl:col-span-3">
                        <div className="bg-card border border-border rounded-lg p-6 shadow-sm">
                            <h3 className="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-4">
                                Expiring Soon
                            </h3>
                            <ExpirationSummary />
                        </div>
                        <div className="bg-card border border-border rounded-lg p-6 shadow-sm">
                            <h3 className="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-4">
                                Recent Activity
                            </h3>
                            <ActivityFeed />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

Dashboard.layout = (page) => (
    <TenantLayout title="Dashboard">{page}</TenantLayout>
);
