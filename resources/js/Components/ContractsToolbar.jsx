import { Search, Plus } from "lucide-react";

export default function ContractsToolbar() {
	return (
		<div className="flex items-center justify-between w-full gap-4 mb-8">
			<div className="flex items-center flex-1 gap-3">

				<div className="relative flex-1">
					<div className="absolute inset-y-0 left-0 flex items-center pl-10 pointer-events-none">
						<Search className="h-4 w-4 text-muted-foreground" />
					</div>
					<input
						type="text"
						className="indent-8 block w-full bg-card border border-border rounded-md pl-10 pr-4 py-2 text-sm focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-muted-foreground"
						placeholder="  Search contracts..."
					/>
				</div>
				<select className="bg-card border border-border rounded-md px-3 py-2 text-sm outline-none cursor-pointer hover:bg-muted/50 transition-colors min-w-[140px] h-[38px]">
					<option>All Statuses</option>
					<option>Active</option>
					<option>Expiring</option>
					<option>Draft</option>
				</select>
			</div>

			<button className="bg-primary text-primary-foreground px-4 py-2 rounded-md text-sm font-semibold hover:opacity-90 transition-all flex items-center gap-2 whitespace-nowrap h-[38px]">
				<Plus className="h-4 w-4" />
				Create Contract
			</button>
		</div>
	);
}