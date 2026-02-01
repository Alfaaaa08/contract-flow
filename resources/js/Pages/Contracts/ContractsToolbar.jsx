import { useState, useEffect, useRef } from "react";
import { router } from "@inertiajs/react";
import { Search, Plus, Loader2, X } from "lucide-react";
import { useDebounce } from "@/hooks/useDebounce";
import { useInertiaProcessing } from "@/hooks/useInertiaProcessing";

export default function ContractsToolbar({ onCreate, filters }) {
    const [search, setSearch] = useState(filters?.search || "");
    const processing = useInertiaProcessing();
    const debouncedSearch = useDebounce(search, 200);

    const isFirstRender = useRef(true);

    useEffect(() => {
        setSearch(filters?.search || "");
    }, [filters?.search]);

    useEffect(() => {
        if (isFirstRender.current) {
            isFirstRender.current = false;
            return;
        }

        if (debouncedSearch === filters?.search) return;

        router.get(
            route("contracts.index"),
            { search: debouncedSearch },
            {
                preserveState: true,
                replace: true,
                preserveScroll: true,
            },
        );
    }, [debouncedSearch]);

    return (
        <div className="flex items-center justify-between w-full gap-4 mb-6">
            <div className="flex items-center flex-1 gap-3">
                <div className="relative flex-1">
                    <div className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                        {processing ? (
                            <Loader2 className="h-4 w-4 animate-spin text-primary" />
                        ) : (
                            <Search className="h-4 w-4" />
                        )}
                    </div>
                    <input
                        type="text"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        className="block w-full bg-card border border-border rounded-md pl-10 pr-10 py-2 text-sm focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-muted-foreground"
                        placeholder="Search contracts..."
                    />
                    {search && (
                        <button
                            onClick={() => setSearch("")}
                            className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors"
                        >
                            <X className="h-4 w-4" />
                        </button>
                    )}
                </div>
                <select
                    id="status"
                    className="bg-card border border-border rounded-md px-3 py-2 text-sm outline-none cursor-pointer hover:bg-muted/50 transition-colors min-w-[140px] h-[38px]"
                >
                    <option>All Statuses</option>
                    <option value="1">Draft</option>
                    <option value="2">Active</option>
                    <option value="5">Expiring</option>
                    <option value="3">Expired</option>
                    <option value="4">Terminated</option>
                </select>
            </div>

            <button
                onClick={onCreate}
                className="bg-primary text-primary-foreground px-4 py-2 rounded-md text-sm font-semibold hover:opacity-90 transition-all flex items-center gap-2 whitespace-nowrap h-[38px]"
            >
                <Plus className="h-4 w-4" />
                Create Contract
            </button>
        </div>
    );
}
