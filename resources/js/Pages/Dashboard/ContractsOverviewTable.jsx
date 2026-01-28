import { Button } from "@/components/ui/button"
import {
	DropdownMenu,
	DropdownMenuContent,
	DropdownMenuItem,
	DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"

import { Progress } from "@/components/ui/progress"

import { Trash, Pencil } from "lucide-react"

import {
	Table,
	TableBody,
	TableCell,
	TableHead,
	TableHeader,
	TableRow,
} from "@/components/ui/table"

import { MoreHorizontalIcon } from "lucide-react"

import { dashboardMock } from "@/Pages/Tenant/Mocks/DashboardMock"

const statusStyles = {
	Active: "bg-primary text-primary",
	Expiring: "bg-chart-4/1000 text-chart-4",
	Draft: "bg-muted text-muted-foreground",
	Expired: "bg-destructive text-destructive",
};

export default function ContractsOverviewTable() {
	return (
		<Table>
			<TableHeader>
				<TableRow>
					<TableHead className="w-[25%]">Contract Name</TableHead>
					<TableHead className="w-[20%]">Client</TableHead>
					<TableHead className="w-[15%]">Value</TableHead>
					<TableHead className="w-[10%]">Status</TableHead>
					<TableHead className="w-[20%]">Progress</TableHead>
					<TableHead className="text-right w-[10%]">Actions</TableHead>
				</TableRow>
			</TableHeader>
			<TableBody>
				{dashboardMock.overviewTable.map((item, index) => (
					<TableRow key={index}>
						<TableCell>{item.contractName}</TableCell>
						<TableCell>{item.client}</TableCell>
						<TableCell>${item.value}</TableCell>
						<TableCell >
							<div className="flex items-center gap-2">
								<span className={`h-2 w-2 rounded-full bg-current ${statusStyles[item.status]}`} />
								<span className="text-sm font-medium text-slate-300">
									{item.status}
								</span>
							</div>
						</TableCell>
						<TableCell><Progress value={item.progress} /></TableCell>
						<TableCell className="text-right">
							<DropdownMenu>
								<DropdownMenuTrigger asChild>
									<Button variant="ghost" size="icon" className="size-8">
										<MoreHorizontalIcon />
										<span className="sr-only">Open menu</span>
									</Button>
								</DropdownMenuTrigger>
								<DropdownMenuContent align="end">
									<DropdownMenuItem className="focus:bg-muted/20 focus:text-foreground"><Pencil size="icon" className="size-8" />Edit</DropdownMenuItem>
									<DropdownMenuItem className="text-red-500 focus:bg-red-500/20 focus:text-red-500 cursor-pointer">
										<Trash size="icon" className="size-8" />Delete
									</DropdownMenuItem>
								</DropdownMenuContent>
							</DropdownMenu>
						</TableCell>
					</TableRow>
				))}
			</TableBody>
		</Table>
	)
}