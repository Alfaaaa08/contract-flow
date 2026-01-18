import { SidebarProvider } from "@/Components/ui/sidebar";
import AppSidebar from "@/Components/AppSidebar"
import Header from "@/Components/Header"

export default function TenantLayout({ children }) {
    return (
        <SidebarProvider>
            <div className="flex h-screen w-full">
                <AppSidebar />

                <div className="flex flex-1 flex-col">
                    <Header />

                    <main className="flex-1 p-6">
                        {children}
                    </main>
                </div>
            </div>
        </SidebarProvider>
    )
}