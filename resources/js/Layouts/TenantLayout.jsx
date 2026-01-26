import { SidebarProvider } from "@/Components/ui/sidebar";
import AppSidebar from "@/Components/AppSidebar"
import Header from "@/Components/Header"

export default function TenantLayout({ children, title }) {
    return (
        <SidebarProvider>
            <div className="flex h-screen w-full">
                <AppSidebar />

                <div className="flex flex-1 flex-col">
                    <Header title={title} />

                    <main className="flex-1 p-6">
                        {children}
                    </main>
                </div>
            </div>
        </SidebarProvider>
    )
}