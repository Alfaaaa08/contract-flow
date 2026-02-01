import { useState } from "react";
import TenantLayout from "@/Layouts/TenantLayout";
import ContractsToolbar from "@/Pages/Contracts/ContractsToolbar";
import ContractsTable from "@/Pages/Contracts/ContractsTable";
import CreateContractModal from "./Partials/CreateContractModal";

export default function Contracts({ contracts }) {
	const [isModalOpen, setIsModalOpen] = useState(false);
	return (
		<div className=" px-4 sm:px-6 lg:px-8">
			<ContractsToolbar onCreate={() => setIsModalOpen(true)}/>
			<ContractsTable contracts={contracts}/>
			<CreateContractModal dialogOpen={isModalOpen} onDialogOpenChange={setIsModalOpen}/>
		</div>
	);
}

Contracts.layout = page => <TenantLayout title="Contracts">{page}</TenantLayout>;