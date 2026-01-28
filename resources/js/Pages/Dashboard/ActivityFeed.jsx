import { dashboardMock } from "@/Pages/Tenant/Mocks/DashboardMock";

export default function ActivityFeed() {
	return (
		<div className="relative space-y-6">
			<div className="absolute left-2.5 top-2 bottom-2 w-px bg-border" />

			{dashboardMock.activities.map((item, index) => {
				const IconComponent = item.icon;

				return (
					<div key={index} className="relative flex gap-4 pl-8">
						<div className="absolute left-0 top-1 h-5 w-5 rounded-full bg-card border border-border flex items-center justify-center z-10">
							<IconComponent className={`h-3 w-3 ${item.iconColor}`} />
						</div>

						<div className="flex flex-col gap-0.5">
							<p className="text-sm text-foreground">
								<span className="font-semibold text-primary">{item.user}</span>{" "}
								<span className="text-muted-foreground">{item.action}</span>{" "}
								<span className="font-medium">{item.target}</span>
							</p>
							<span className="text-[10px] text-muted-foreground uppercase tracking-widest">
								{item.timestamp}
							</span>
						</div>
					</div>
				);
			})}
		</div>
	);
};