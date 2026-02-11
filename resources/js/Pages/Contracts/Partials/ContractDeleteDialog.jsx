import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from "@/components/ui/alert-dialog";

import { router } from "@inertiajs/react";

export default function ContractDeleteDialog({
    selectedIds = [],
    deleteDialogOpen,
    onDeleteDialogOpenChange,
    onSuccess,
}) {
    const isMultiple = selectedIds.length > 1;

    const handleDelete = () => {
        if (selectedIds.length === 0) return;

        router.delete(route("contracts.bulk-destroy"), {
            data: { ids: selectedIds },
            onSuccess: () => {
                onDeleteDialogOpenChange(false);
                if (onSuccess) onSuccess();
            },
        });
    };

    return (
        <AlertDialog
            open={deleteDialogOpen}
            onOpenChange={onDeleteDialogOpenChange}
        >
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently delete 
                        {isMultiple ? ` the ${selectedIds.length} selected contracts.` : " the contract."}
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <AlertDialogAction
                        onClick={handleDelete}
                        className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
                    >
                        Delete
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}
