import {
    Table,
    TableBody,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { Button } from "@/components/ui/button";

import { Trash, Loader2 } from "lucide-react";

import { useInertiaProcessing } from "@/hooks/useInertiaProcessing";

import { usePage } from "@inertiajs/react";

import { useEffect, useState, useMemo } from "react";

import { getColumns } from "./Partials/Columns";

import {
    useReactTable,
    getCoreRowModel,
    flexRender,
} from "@tanstack/react-table";

export default function ContractsTable({ onEdit, onDelete, onBulkDelete, contracts }) {
    const { flash } = usePage().props;

    const highlightId = flash?.highlightId;

    const processing = useInertiaProcessing();

    const [rowSelection, setRowSelection] = useState({});
    const [shouldHighlight, setShouldHighlight] = useState(false);

    const columns = useMemo(
        () => getColumns(onEdit, onDelete),
        [onEdit, onDelete],
    );
    
    useEffect(() => {
        setRowSelection({});
    }, [contracts]);

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
                            className={`
                                transition-all duration-300 border-b border-border/50
                                /* Fix the Selected State brightness */
                                data-[state=selected]:bg-primary/10 
                                data-[state=selected]:hover:bg-primary/20
                                
                                /* Keep your highlight logic for new/edited rows */
                                ${
                                    row.original.id === highlightId &&
                                    shouldHighlight
                                        ? "bg-primary/20 ring-1 ring-inset ring-primary/50"
                                        : "hover:bg-muted/5"
                                }
                            `}
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
                {selectedRows.length > 0 && (
                    <TableFooter className="bg-muted/10 border-t border-border animate-in fade-in slide-in-from-bottom-2">
                        <TableRow className="hover:bg-transparent">
                            <TableCell colSpan={columns.length} className="p-0">
                                <div className="flex items-center justify-between px-6 py-3 h-14 w-full">
                                    <div className="flex items-center gap-3">
                                        <span className="text-sm font-semibold text-foreground">
                                            {selectedRows.length} contracts
                                            selected
                                        </span>
                                        <Button
                                            variant="link"
                                            size="sm"
                                            onClick={() =>
                                                table.resetRowSelection()
                                            }
                                            className="text-xs text-muted-foreground hover:text-foreground p-0 h-auto"
                                        >
                                            Clear selection
                                        </Button>
                                    </div>

                                    <div className="flex items-center gap-2">
                                        <Button
                                            variant="destructive"
                                            onClick={() => onBulkDelete(selectedRows.map(r => r.original.id))}
                                            size="sm"
                                            className="h-9 px-4 gap-2 font-bold shadow-sm"
                                        >
                                            <Trash className="h-4 w-4" />
                                            Delete Selected
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            onClick={() =>
                                                table.resetRowSelection()
                                            }
                                            className="h-9 w-9 border border-border"
                                        ></Button>
                                    </div>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableFooter>
                )}
            </Table>
        </div>
    );
}
