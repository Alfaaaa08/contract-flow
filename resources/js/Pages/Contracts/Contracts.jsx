import { useState } from "react";
import TenantLayout from "@/Layouts/TenantLayout";
import ContractsToolbar from "@/Pages/Contracts/ContractsToolbar";
import ContractsTable from "@/Pages/Contracts/ContractsTable";
import ContractFormModal from "./Partials/ContractFormModal";
import ContractDeleteDialog from "./Partials/ContractDeleteDialog";
export default function Contracts({ contracts, filters, clients, types }) {
    console.log(clients, types);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedContract, setSelectedContract] = useState(null);

    const [idsToDelete, setIdsToDelete] = useState([]);

    const isDeleteDialogOpen = idsToDelete.length > 0;

    const handleCreate = () => {
        setSelectedContract(null);
        setIsModalOpen(true);
    };

    const handleEdit = (contract) => {
        setSelectedContract(contract);
        setIsModalOpen(true);
    };

    return (
        <div className=" px-4 sm:px-6 lg:px-8">
            <ContractsToolbar onCreate={handleCreate} filters={filters} />

            <ContractsTable
                onEdit={handleEdit}
                onDelete={(id) => setIdsToDelete([id])}
                onBulkDelete={(ids) => setIdsToDelete(ids)}
                contracts={contracts}
            />

            <ContractFormModal
                dialogOpen={isModalOpen}
                contract={selectedContract}
                onDialogOpenChange={(open) => {
                    setIsModalOpen(open);
                    if (!open) setSelectedContract(null);
                }}
                clients={clients}
                types={types}
            />

            <ContractDeleteDialog
                selectedIds={idsToDelete}
                deleteDialogOpen={isDeleteDialogOpen}
                onDeleteDialogOpenChange={(open) => {
                    if (!open) setIdsToDelete([]);
                }}
                onSuccess={() => {
                    setIdsToDelete([]);
                }}
            />
        </div>
    );
}

Contracts.layout = (page) => (
    <TenantLayout title="Contracts">{page}</TenantLayout>
);
