import { dashboardMock } from "@/Pages/Tenant/Mocks/DashboardMock"

export default function ExpirationSummary() {
	return (
		<div className="space-y-4">
			{dashboardMock.expiringContracts.map((contract, index) => (
				<div key={index}
					className="border-b border-border/100 last:border-10 pb-3 mb-3 flex items-center justify-between p-2 rounded-md hover:bg-muted/20 transition-colors cursor-pointer"
				>
					<div className="flex flex-col w-[75%]">
						<span className="text-sm font-medium text-foreground">
							{contract.contractName}
						</span>
						<span className="text-xs text-muted-foreground">
							{contract.client}
						</span>
					</div>
					<div className={`text-xs font-bold px-2 py-1 rounded bg-background  border-border w-[25%] ${contract.color}`}>
						{contract.daysLeft}d left
					</div>
				</div>
			))}

			<button className="w-full mt-2 py-2 text-xs font-medium text-muted-foreground border border-dashed border-border rounded-md hover:bg-muted/50 transition-all">
				View All Deadlines
			</button>
		</div>
	);
};