import { BookText, LayoutDashboard, User, Search, Settings, LogOut } from "lucide-react"

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
        url: "#",
        icon: LayoutDashboard,
    },
    {
        title: "Contracts",
        url: "#",
        icon: BookText,
    },
    {
        title: "Clients",
        url: "#",
        icon: User,
    },
    {
        title: "Types",
        url: "#",
        icon: Search,
    },
    {
        title: "Settings",
        url: "#",
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
                                            <a href={item.url}>
                                                <item.icon />
                                                <span>{item.title}</span>
                                            </a>
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