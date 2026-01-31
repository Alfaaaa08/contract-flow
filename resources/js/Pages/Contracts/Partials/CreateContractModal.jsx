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

import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { router } from "@inertiajs/react";
import { contractSchema } from "@/schemas/contractSchema";

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

    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
        setError,
        reset,
        setValue,
        clearErrors,
    } = useForm({
        resolver: zodResolver(contractSchema),
        mode: "onSubmit",
        reValidateMode: "onChange",
        defaultValues: {
            name: "",
            client_id: 0,
            contract_contract_type_id: 0,
            start_date: "",
            end_date: "",
            value: 0,
        },
    });

    const onSubmit = async (data) => {
        router.post("/contracts", data, {
            onError: (errors) => {
                Object.keys(errors).forEach((key) => {
                    setError(key, {
                        type: "server",
                        message: errors[key],
                    });
                });
            },
            onSuccess: () => {
                reset();
                onDialogOpenChange(false);
            },
        });
    };

    return (
        <Dialog open={dialogOpen} onOpenChange={onDialogOpenChange}>
            <DialogContent className="sm:max-w-[600px] bg-card border-border">
                <DialogHeader>
                    <DialogTitle className="text-xl font-bold text-foreground">
                        Create New Contract
                    </DialogTitle>
                </DialogHeader>
                <form onSubmit={handleSubmit(onSubmit)} type="submit">
                    <div className="grid grid-cols-2 gap-4 py-4">
                        <div className="col-span-2 space-y-2">
                            <Label htmlFor="name">Contract Name</Label>
                            <Input
                                id="name"
                                {...register("name")}
                                placeholder="e.g. 2026 Software License"
                                className={
                                    errors.name
                                        ? "border-red-500"
                                        : "bg-background"
                                }
                            />
                            {errors.name && (
                                <p className="text-sm text-red-500 mt-1">
                                    {errors.name.message}
                                </p>
                            )}
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="client_id">Client</Label>

                            <input type="hidden" {...register("client_id")} />

                            <Popover
                                open={clientPopoverOpen}
                                onOpenChange={setClientPopoverOpen}
                            >
                                <PopoverTrigger asChild>
                                    <Button
                                        type="button" 
                                        variant="outline"
                                        role="combobox"
                                        aria-expanded={clientPopoverOpen}
                                        className={`w-full justify-between bg-background ${
                                            errors.client_id
                                                ? "border-red-500"
                                                : ""
                                        }`}
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
                                                {contractsMock.clients.map(
                                                    (client) => (
                                                        <CommandItem
                                                            key={client.id}
                                                            value={client.name}
                                                            onSelect={() => {
                                                                setSelectedClient(
                                                                    client.name,
                                                                );
                                                                setValue(
                                                                    "client_id",
                                                                    client.id,
                                                                );
                                                                clearErrors(
                                                                    "client_id",
                                                                );
                                                                setClientPopoverOpen(
                                                                    false,
                                                                );
                                                            }}
                                                        >
                                                            {client.name}
                                                        </CommandItem>
                                                    ),
                                                )}
                                            </CommandGroup>
                                        </CommandList>
                                    </Command>
                                </PopoverContent>
                            </Popover>

                            {errors.client_id && (
                                <p className="text-sm text-red-500 mt-1">
                                    {errors.client_id.message}
                                </p>
                            )}
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="contract_type_id">Type</Label>

                            <input type="hidden" {...register("contract_type_id")} />

                            <Popover
                                open={typePopoverOpen}
                                onOpenChange={setTypePopoverOpen}
                            >
                                <PopoverTrigger asChild>
                                    <Button
                                        variant="outline"
                                        role="combobox"
                                        aria-expanded={typePopoverOpen}
                                        className={`w-full justify-between bg-background ${
                                            errors.contract_type_id ? "border-red-500" : ""
                                        }`}
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
                                                {contractsMock.types.map(
                                                    (type) => (
                                                        <CommandItem
                                                            key={type.id}
                                                            value={type.name}
                                                            onSelect={() => {
                                                                setSelectedType(
                                                                    type.name,
                                                                );
                                                                setValue(
                                                                    "contract_type_id",
                                                                    type.id,
                                                                );
                                                                clearErrors(
                                                                    "contract_type_id",
                                                                );
                                                                setTypePopoverOpen(
                                                                    false,
                                                                );
                                                            }}
                                                        >
                                                            {type.name}
                                                        </CommandItem>
                                                    ),
                                                )}
                                            </CommandGroup>
                                        </CommandList>
                                    </Command>
                                </PopoverContent>
                            </Popover>
                            {errors.contract_type_id && (
                                <p className="text-sm text-red-500 mt-1">
                                    {errors.contract_type_id.message}
                                </p>
                            )}
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="start_date">Start Date</Label>
                            <Input
                                {...register("start_date")}
                                id="start_date"
                                type="date"
                                className={
                                    errors.start_date
                                        ? "border-red-500"
                                        : "bg-background"
                                }
                            />
                            {errors.start_date && (
                                <p className="text-sm text-red-500 mt-1">
                                    {errors.start_date.message}
                                </p>
                            )}
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="end_date">End Date</Label>
                            <Input
                                {...register("end_date")}
                                id="end_date"
                                type="date"
                                className={
                                    errors.end_date
                                        ? "border-red-500"
                                        : "bg-background"
                                }
                            />
                            {errors.end_date && (
                                <p className="text-sm text-red-500 mt-1">
                                    {errors.end_date.message}
                                </p>
                            )}
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
                            onClick={() => onDialogOpenChange(false)}
                            className="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            className="bg-primary text-primary-foreground px-4 py-2 rounded-md text-sm font-semibold hover:opacity-90 transition-all"
                        >
                            Save Contract
                        </button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
