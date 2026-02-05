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
        <Sidebar collapsible="icon" className="border-r">
            <SidebarHeader>
                <div className="flex items-center gap-2 px-4 py-6 group-data-[collapsible=icon]:justify-center">
                    <h1 className="text-lg font-semibold tracking-tight truncate group-data-[collapsible=icon]:hidden">
                        ContractFlow
                    </h1>
                </div>
            </SidebarHeader>
            
            <SidebarContent>
                <SidebarGroup>
                    <SidebarGroupContent>
                        <SidebarMenu>
                            {items.map((item) => (
                                <SidebarMenuItem key={item.title}>
                                    <SidebarMenuButton asChild tooltip={item.title}>
                                        <Link href={item.url} className="no-blue-link">
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
                        <SidebarMenuButton tooltip="Logout">
                            <LogOut />
                            <span>Logout</span>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarFooter>
        </Sidebar>
    )
}