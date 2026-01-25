import TenantLayout from "@/Layouts/TenantLayout";

import { dashboardMock } from "./Mocks/DashboardMock";

import {
    ContractsOverviewTable,
    StatsCards,
} from "@/Components/ui";

export default function Dashboard() {
    return (
        <div className="flex flex-1 flex-col">
            <div className="p-41 md:p-8">
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <StatsCards
                        title="Active Contracts"
                        value={dashboardMock.cards.activeContracts}
                        description="Currently in force"
                    />
                    <StatsCards
                        title="Contracts Expiring Soon"
                        value={dashboardMock.cards.expiring30Days}
                        description="Requires attention"
                    />
                    <StatsCards
                        title="Total Contract Value (TCV)"
                        value={dashboardMock.cards.totalContractValue}
                        description="Across all active contracts"
                    />
                    <StatsCards
                        title="Clients with Active Contracts"
                        value={dashboardMock.cards.clientsWithActiveContracts}
                        description="Engaged clients"
                    />
                </div>
            </div>
            <div className="p-41 md:p-8">
                <div className="grid grid-cols-1 gap-6 lg:grid-cols-12">
                    <div className="lg:col-span-8 xl:col-span-9 bg-card border border-border rounded-lg shadow-sm overflow-hidden">
                        <div className="px-6 py-4 border-b border-border flex justify-between items-center">
                            <h2 className="text-base font-semibold">Recent Contracts</h2>
                            <button className="text-sm text-primary hover:opacity-80 transition-opacity no-blue-link">
                                View all
                            </button>
                        </div>

                        <div className="overflow-x-auto">
                            <ContractsOverviewTable />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
};

Dashboard.layout = page => <TenantLayout>{page}</TenantLayout>