import TenantLayout from "@/Layouts/TenantLayout";

import { dashboardMock } from "./Mocks/DashboardMock";

import { StatsCards } from "@/Components/StatsCards";

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
        </div>
    )
};

Dashboard.layout = page => <TenantLayout>{page}</TenantLayout>