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
    contractId,
    deleteDialogOpen,
    onDeleteDialogOpenChange,
}) {
    const handleDelete = () => {
        if (!contractId) {
            return;
        }

        router.delete(`/contracts/${contractId}`, {
            onError: (errors) => {
                Object.keys(errors).forEach((key) => {
                    setError(key, {
                        type: "server",
                        message: errors[key],
                    });
                });
            },
            onSuccess: () => {
				onDeleteDialogOpenChange(false);
            },
        });

        onDeleteDialogOpenChange(false);
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
                        This action cannot be undone. This will permanently
                        delete the contract.
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
