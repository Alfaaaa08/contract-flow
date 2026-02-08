import { useState } from "react";
import TenantLayout from "@/Layouts/TenantLayout";
import ContractsToolbar from "@/Pages/Contracts/ContractsToolbar";
import ContractsTable from "@/Pages/Contracts/ContractsTable";
import ContractFormModal from "./Partials/ContractFormModal";
import ContractDeleteDialog from "./Partials/ContractDeleteDialog";
export default function Contracts({ contracts, filters }) {
	const [isModalOpen, setIsModalOpen]               = useState(false);
	const [contractIdToDelete, setContractIdToDelete] = useState(null);

	return (
		<div className=" px-4 sm:px-6 lg:px-8">
			<ContractsToolbar onCreate={() => setIsModalOpen(true)} filters={filters}/>
			<ContractsTable onDelete={(id) => setContractIdToDelete(id)} contracts={contracts}/>
			<ContractFormModal dialogOpen={isModalOpen} onDialogOpenChange={setIsModalOpen}/>
			<ContractDeleteDialog contractId={contractIdToDelete} deleteDialogOpen={!!contractIdToDelete} onDeleteDialogOpenChange={setContractIdToDelete}/>
		</div>
	);
}

Contracts.layout = page => <TenantLayout title="Contracts">{page}</TenantLayout>;