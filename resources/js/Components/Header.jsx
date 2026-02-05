import { Button } from "./ui/button";
import { Menu } from "lucide-react";
import { SidebarProvider, SidebarTrigger } from "@/components/ui/sidebar"

export default function Header({ title }) {
	return (
		<header className="flex h-14 items-center gap-4 border-b px-6">
			<Button variant="ghost" size="icon">
				<SidebarTrigger />
			</Button>

			<h1 className="text-lg font-semibold">{title}</h1>

			<div className="flex-1" />

		</header>
	)
}