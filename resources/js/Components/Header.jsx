import { Button } from "./ui/button";
import { Menu } from "lucide-react";

export default function Header() {
	return (
		<header className="flex h-14 items-center gap-4 border-b px-6">
			<Button variant="ghost" size="icon">
				<Menu className="h-5 w-5" />
			</Button>

			<h1 className="text-lg font-semibold">Dashboard</h1>

			<div className="flex-1" />

		</header>
	)
}