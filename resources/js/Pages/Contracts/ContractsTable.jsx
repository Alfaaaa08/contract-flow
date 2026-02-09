import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

import DynamicIcon from "./DynamicTypeIcon";

import { Button } from "@/components/ui/button";

import { MoreHorizontalIcon, Trash, Pencil, Loader2 } from "lucide-react";

import { useInertiaProcessing } from "@/hooks/useInertiaProcessing";

import { usePage } from "@inertiajs/react";

import { useEffect, useState, useMemo } from "react";

import { getColumns } from "./Partials/Columns";

import {
    useReactTable,
    getCoreRowModel,
    flexRender,
} from "@tanstack/react-table";

const statusStyles = {
    Active: "bg-primary/10 text-primary border-primary/20",
    Draft: "bg-muted/10 text-muted-foreground border-border",
    Expiring: "bg-chart text-chart-4 border-chart-4/20",
    Expired: "bg-destructive/10 text-destructive border-destructive/20",
    Terminated: "bg-purple-500/10 text-purple-500 border-purple-500/20",
};

export default function ContractsTable({ onEdit, onDelete, contracts }) {
    const { flash } = usePage().props;

    const highlightId = flash?.highlightId;

    const processing = useInertiaProcessing();

    const [rowSelection, setRowSelection] = useState({});
    const [shouldHighlight, setShouldHighlight] = useState(false);

    const columns = useMemo(
        () => getColumns(onEdit, onDelete),
        [onEdit, onDelete],
    );

    const table = useReactTable({
        data: contracts,
        columns,
        state: { rowSelection },
        onRowSelectionChange: setRowSelection,
        getCoreRowModel: getCoreRowModel(),
    });

    const selectedRows = table.getSelectedRowModel().rows;

    useEffect(() => {
        if (!highlightId) {
            return;
        }

        const startTimeout = setTimeout(() => {
            setShouldHighlight(true);
        }, 100);

        const endTimeout = setTimeout(() => {
            setShouldHighlight(false);
        }, 1500);

        return () => {
            clearTimeout(startTimeout);
            clearTimeout(endTimeout);
        };
    }, [highlightId]);

    return (
        <div className="rounded-md border border-border bg-card overflow-hidden relative">
            {processing && (
                <div className="absolute inset-0 bg-background/50 backdrop-blur-sm z-10 flex items-center justify-center">
                    <div className="flex items-center gap-2 bg-card px-4 py-2 rounded-lg border border-border shadow-lg">
                        <Loader2 className="h-4 w-4 animate-spin text-primary" />
                        <span className="text-sm text-muted-foreground">
                            Loading contracts...
                        </span>
                    </div>
                </div>
            )}

            <Table>
                <TableHeader>
                    {table.getHeaderGroups().map((headerGroup) => (
                        <TableRow
                            key={headerGroup.id}
                            className="hover:bg-transparent border-b border-border"
                        >
                            {headerGroup.headers.map((header) => (
                                <TableHead
                                    key={header.id}
                                    className="text-muted-foreground font-semibold py-3"
                                >
                                    {flexRender(
                                        header.column.columnDef.header,
                                        header.getContext(),
                                    )}
                                </TableHead>
                            ))}
                        </TableRow>
                    ))}
                </TableHeader>
                <TableBody className={processing ? "opacity-50" : ""}>
                    {table.getRowModel().rows.map((row) => (
                        <TableRow
                            key={row.id}
                            data-state={row.getIsSelected() && "selected"}
                            className={`transition-all duration-300 ${
                                row.original.id === highlightId &&
                                shouldHighlight
                                    ? "bg-primary/20"
                                    : "hover:bg-muted/5"
                            }`}
                        >
                            {row.getVisibleCells().map((cell) => (
                                <TableCell key={cell.id}>
                                    {flexRender(
                                        cell.column.columnDef.cell,
                                        cell.getContext(),
                                    )}
                                </TableCell>
                            ))}
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </div>
    );
}
