import {
	Card,
	CardDescription,
	CardFooter,
	CardHeader,
	CardTitle,
} from "@/components/ui/card"

export function StatsCards({ title, value, description }) {
	return (
		<Card className="group relative overflow-hidden w-full bg-card/60 backdrop-blur-sm text-card-foreground border-border/40 shadow-2xl transition-all duration-300 hover:border-primary/50 hover:-translate-y-1">
			<div className="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity" />

			<div className="absolute top-0 left-0 h-[1px] w-full bg-gradient-to-r from-transparent via-primary/50 to-transparent" />

			<CardHeader className="pb-3">
				<CardDescription className="text-[10px] font-bold uppercase tracking-[0.15em] text-muted-foreground transition-colors group-hover:text-primary/80">
					{title}
				</CardDescription>
				<CardTitle className="text-3xl font-bold tracking-tight text-foreground">
					{value}
				</CardTitle>
			</CardHeader>

			<CardFooter className="flex-col items-start gap-3">
				<div className="text-muted-foreground/60 text-xs italic font-medium">
					{description}
				</div>
			</CardFooter>
		</Card>
	);
}