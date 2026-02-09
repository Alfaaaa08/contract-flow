import { Checkbox } from "@/Components/ui/checkbox";
import { Button } from "@/Components/ui/button";
import { MoreHorizontal } from "lucide-react";
import DynamicIcon from "../DynamicTypeIcon";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Pencil, Trash } from "lucide-react";

const statusStyles = {
    Active: "bg-primary/10 text-primary border-primary/20",
    Draft: "bg-muted/10 text-muted-foreground border-border",
    Expiring: "bg-chart text-chart-4 border-chart-4/20",
    Expired: "bg-destructive/10 text-destructive border-destructive/20",
    Terminated: "bg-purple-500/10 text-purple-500 border-purple-500/20",
};

/**
 * Returns the column definitions for the TanStack Table.
 * @param {Function} onEdit - Callback when the edit button is clicked
 * @param {Function} onDelete - Callback when the delete button is clicked
 */
export const getColumns = (onEdit, onDelete) => [
    {
        id: "select",
        header: ({ table }) => (
            <div className="px-1">
                <Checkbox
                    checked={table.getIsAllPageRowsSelected()}
                    onCheckedChange={(value) =>
                        table.toggleAllPageRowsSelected(!!value)
                    }
                />
            </div>
        ),
        cell: ({ row }) => (
            <div className="px-1">
                <Checkbox
                    checked={row.getIsSelected()}
                    onCheckedChange={(value) => row.toggleSelected(!!value)}
                />
            </div>
        ),
    },
    {
        accessorKey: "name",
        header: "Contract",
        cell: ({ row }) => {
            const contract = row.original;
            return (
                <div className="flex items-center gap-3 min-w-[200px]">
                    <DynamicIcon
                        name={contract.type_icon}
                        className={`h-4 w-4 ${statusStyles[contract.status]?.split(" ")[1]}`}
                    />
                    <span>{contract.name}</span>
                </div>
            );
        },
    },
    {
        accessorKey: "client",
        header: "Client",
    },
    {
        accessorKey: "type",
        header: "Type",
        cell: ({ row }) => (
            <span className="text-[11px] text-muted-foreground bg-background px-2 py-1 rounded border border-border inline-block">
                {row.getValue("type")}
            </span>
        ),
    },
    {
        accessorKey: "value",
        header: "Value",
        cell: ({ row }) => {
            const val = parseFloat(row.getValue("value"));
            return (
                <span>
                    $
                    {val.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                    })}
                </span>
            );
        },
    },
    {
        accessorKey: "status",
        header: "Status",
        cell: ({ row }) => {
            const status = row.getValue("status");
            return (
                <div
                    className={`inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border ${statusStyles[status]}`}
                >
                    {status}
                </div>
            );
        },
    },
    {
        accessorKey: "end_date",
        header: "End Date",
        cell: ({ row }) => (
            <span className="text-muted-foreground text-sm">
                {row.getValue("end_date")}
            </span>
        ),
    },
    {
        id: "actions",
        header: () => <div className="text-right pr-4"></div>,
        cell: ({ row }) => (
            <div className="text-right">
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="icon" className="h-8 w-8">
                            <MoreHorizontal className="h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        align="end"
                        className="bg-card border-border"
                    >
                        <DropdownMenuItem
                            onClick={() => onEdit(row.original)}
                            className="gap-2 cursor-pointer"
                        >
                            <Pencil className="h-3.5 w-3.5" /> Edit
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            onClick={() => onDelete(row.original.id)}
                            className="gap-2 text-destructive cursor-pointer focus:text-destructive"
                        >
                            <Trash className="h-3.5 w-3.5" /> Delete
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        ),
    },
];
