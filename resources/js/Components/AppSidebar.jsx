import { BookText, LayoutDashboard, User, Search, Settings, LogOut } from "lucide-react"
import { Link } from "@inertiajs/react"
import {
    Sidebar,
    SidebarHeader,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarFooter
} from "@/Components/ui/sidebar"

const items = [
    {
        title: "Dashboard",
        url: "/dashboard",
        icon: LayoutDashboard,
    },
    {
        title: "Contracts",
        url: "/contracts",
        icon: BookText,
    },
    {
        title: "Clients",
        url: "/clients",
        icon: User,
    },
    {
        title: "Types",
        url: "/types",
        icon: Search,
    },
    {
        title: "Settings",
        url: "/settings",
        icon: Settings,
    },
]

export default function AppSidebar() {
    return (
        <div className="flex h-screen">
            <Sidebar className="border-r bg-muted/40">
                <SidebarHeader className="px-4 py-6">
                    <h1 className="text-lg font-semibold tracking-tight">
                        ContractFlow
                    </h1>
                </SidebarHeader>
                <SidebarContent >
                    <SidebarGroup>
                        <SidebarGroupContent>
                            <SidebarMenu>
                                {items.map((item) => (
                                    <SidebarMenuItem key={item.title}>
                                        <SidebarMenuButton asChild>
                                            <Link
                                                href={item.url}
                                                className="text-blue-500 no-blue-link"
                                            >
                                                <item.icon />
                                                <span>{item.title}</span>
                                            </Link>
                                        </SidebarMenuButton>
                                    </SidebarMenuItem>
                                ))}
                            </SidebarMenu>
                        </SidebarGroupContent>
                    </SidebarGroup>
                </SidebarContent>
                <SidebarFooter>
                    <SidebarMenu>
                        <SidebarMenuItem>
                            <SidebarMenuButton>
                                <LogOut />
                                <span>Logout</span>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarFooter>
            </Sidebar>
        </div>
    )
}