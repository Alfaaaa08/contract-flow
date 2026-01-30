import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from "@/Components/ui/dialog";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import { UploadCloud } from "lucide-react";

import { Button } from "@/Components/ui/button";

import { ChevronsUpDown } from "lucide-react";

import { Check } from "lucide-react";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/components/ui/command";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";

import { useState } from "react";

import { contractsMock } from "@/Pages/Tenant/Mocks/ContractsMock";

export default function CreateContractModal({
    dialogOpen,
    onDialogOpenChange,
}) {
    const [typePopoverOpen, setTypePopoverOpen] = useState(false);
    const [selectedType, setSelectedType] = useState("");
	
    const [clientPopoverOpen, setClientPopoverOpen] = useState(false);
    const [selectedClient, setSelectedClient] = useState("");
	
    return (
        <Dialog open={dialogOpen} onOpenChange={onDialogOpenChange}>
            <DialogContent className="sm:max-w-[600px] bg-card border-border">
                <DialogHeader>
                    <DialogTitle className="text-xl font-bold text-foreground">
                        Create New Contract
                    </DialogTitle>
                </DialogHeader>

                <div className="grid grid-cols-2 gap-4 py-4">
                    <div className="col-span-2 space-y-2">
                        <Label htmlFor="name">Contract Name</Label>
                        <Input
                            id="name"
                            placeholder="e.g. 2026 Software License"
                            className="bg-background"
                        />
                    </div>

                    <div className="space-y-2">
                        <Label>Client</Label>
                        <Popover
                            open={clientPopoverOpen}
                            onOpenChange={setClientPopoverOpen}
                        >
                            <PopoverTrigger asChild>
                                <Button
                                    variant="outline"
                                    role="combobox"
                                    aria-expanded={clientPopoverOpen}
                                    className="w-full justify-between bg-background"
                                >
                                    {selectedClient || "Select a client"}
                                    <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                </Button>
                            </PopoverTrigger>
                            <PopoverContent className="w-[--radix-popover-trigger-width] p-0">
                                <Command>
                                    <CommandInput placeholder="Search client..." />
                                    <CommandList>
                                        <CommandEmpty>
                                            No client found.
                                        </CommandEmpty>
                                        <CommandGroup>
                                            {contractsMock.clients.map((client) => (
                                                <CommandItem
                                                    key={client.id}
                                                    value={client.name}
                                                    onSelect={() => {
                                                        setSelectedClient(
                                                            client.name,
                                                        );
                                                        setClientPopoverOpen(false);
                                                    }}
                                                >
                                                    {client.name}
                                                </CommandItem>
                                            ))}
                                        </CommandGroup>
                                    </CommandList>
                                </Command>
                            </PopoverContent>
                        </Popover>
                    </div>

                    <div className="space-y-2">
                        <Label>Type</Label>
                        <Popover
                            open={typePopoverOpen}
                            onOpenChange={setTypePopoverOpen}
                        >
                            <PopoverTrigger asChild>
                                <Button
                                    variant="outline"
                                    role="combobox"
                                    aria-expanded={typePopoverOpen}
                                    className="w-full justify-between bg-background"
                                >
                                    {selectedType || "Select a type"}
                                    <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                </Button>
                            </PopoverTrigger>
                            <PopoverContent className="w-[--radix-popover-trigger-width] p-0">
                                <Command>
                                    <CommandInput placeholder="Search type..." />
                                    <CommandList>
                                        <CommandEmpty>
                                            No types found.
                                        </CommandEmpty>
                                        <CommandGroup>
                                            {contractsMock.types.map((type) => (
                                                <CommandItem
                                                    key={type.id}
                                                    value={type.name}
                                                    onSelect={() => {
                                                        setSelectedType(
                                                            type.name,
                                                        );
                                                        setTypePopoverOpen(false);
                                                    }}
                                                >
                                                    {type.name}
                                                </CommandItem>
                                            ))}
                                        </CommandGroup>
                                    </CommandList>
                                </Command>
                            </PopoverContent>
                        </Popover>
                    </div>

                    <div className="space-y-2">
                            <Label htmlFor="date">Start Date</Label>
                        <Input
                                id="start_date"
                                type="date"
                            className="bg-background"
                        />
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="date">End Date</Label>
                        <Input
                                id="end_date"
                            type="date"
                            className="bg-background"
                        />
                    </div>

                        <div className="space-y-2">
                            <Label htmlFor="value">Value ($)</Label>
                            <Input
                                id="value"
                                type="number"
                                placeholder="0.00"
                                className="[appearance:textfield] 
                                    [&::-webkit-outer-spin-button]:appearance-none 
                                    [&::-webkit-inner-spin-button]:appearance-none
                                    bg-background"
                            />
                        </div>

                    <div className="col-span-2 pt-2">
                        <Label>Upload Document (PDF)</Label>
                        <div className="mt-2 border-2 border-dashed border-border rounded-lg p-8 flex flex-col items-center justify-center bg-background/50 hover:bg-accent/5 transition-colors cursor-pointer">
                            <UploadCloud className="h-8 w-8 text-muted-foreground mb-2" />
                            <p className="text-sm text-muted-foreground">
                                Click to upload or drag and drop
                            </p>
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <button
                        onClick={() => onODialogOpenChange(false)}
                        className="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground"
                    >
                        Cancel
                    </button>
                    <button className="bg-primary text-primary-foreground px-4 py-2 rounded-md text-sm font-semibold hover:opacity-90 transition-all">
                        Save Contract
                    </button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
