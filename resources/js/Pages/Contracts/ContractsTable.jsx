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

import { MoreHorizontalIcon, Trash, Pencil } from "lucide-react";

const statusStyles = {
    Active: "bg-primary/10 text-primary border-primary/20",
    Draft: "bg-muted/10 text-muted-foreground border-border",
    Expiring: "bg-chart text-chart-4 border-chart-4/20",
    Expired: "bg-destructive/10 text-destructive border-destructive/20",
	Terminated: "bg-purple-500/10 text-purple-500 border-purple-500/20"
};

export default function ContractsTable({ contracts }) {
    return (
        <div className="rounded-md border border-border bg-card overflow-hidden">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead className="w-[250px]">
                            Contract Name
                        </TableHead>
                        <TableHead>Client</TableHead>
                        <TableHead>Type</TableHead>
                        <TableHead>Value</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>End Date</TableHead>
                        <TableHead className="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {contracts.map((contract) => (
                        <TableRow key={contract.id}>
                            <TableCell className="font-medium">
                                <div className="flex items-center gap-3">
                                    <DynamicIcon name={contract.type_icon} className={`h-4 w-4 ${statusStyles[contract.status]}`} />
                                    <span>{contract.name}</span>
                                </div>
                            </TableCell>
                            <TableCell>{contract.client}</TableCell>
                            <TableCell>
                                <span className="text-xs text-muted-foreground bg-background px-2 py-1 rounded border border-border">
                                    {contract.type}
                                </span>
                            </TableCell>
                            <TableCell>${contract.value.toLocaleString()}
                            </TableCell>
                            <TableCell>
                                <div
                                    className={`inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold border ${statusStyles[contract.status]}`}
                                >
                                    {contract.status}
                                </div>
                            </TableCell>
                            <TableCell className="text-muted-foreground">
                                {contract.end_date}
                            </TableCell>
                            <TableCell className="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger asChild>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            className="size-8"
                                        >
                                            <MoreHorizontalIcon />
                                            <span className="sr-only">
                                                Open menu
                                            </span>
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem className="focus:bg-muted/20 focus:text-foreground">
                                            <Pencil
                                                size="icon"
                                                className="size-8"
                                            />
                                            Edit
                                        </DropdownMenuItem>
                                        <DropdownMenuItem className="text-red-500 focus:bg-red-500/20 focus:text-red-500 cursor-pointer">
                                            <Trash
                                                size="icon"
                                                className="size-8"
                                            />
                                            Delete
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </div>
    );
}