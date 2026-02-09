import { useState } from "react";
import TenantLayout from "@/Layouts/TenantLayout";
import ContractsToolbar from "@/Pages/Contracts/ContractsToolbar";
import ContractsTable from "@/Pages/Contracts/ContractsTable";
import ContractFormModal from "./Partials/ContractFormModal";
import ContractDeleteDialog from "./Partials/ContractDeleteDialog";
export default function Contracts({ contracts, filters }) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [contractIdToDelete, setContractIdToDelete] = useState(null);
    const [selectedContract, setSelectedContract] = useState(null);

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
                onDelete={(id) => setContractIdToDelete(id)}
                contracts={contracts}
            />

            <ContractFormModal
                dialogOpen={isModalOpen}
				contract={selectedContract}
				onDialogOpenChange={(open) => {
                    setIsModalOpen(open);
                    if (!open) setSelectedContract(null);
                }}
            />

            <ContractDeleteDialog
                contractId={contractIdToDelete}
                deleteDialogOpen={!!contractIdToDelete}
                onDeleteDialogOpenChange={setContractIdToDelete}
            />
        </div>
    );
}

Contracts.layout = (page) => (
    <TenantLayout title="Contracts">{page}</TenantLayout>
);
