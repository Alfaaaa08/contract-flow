import TenantLayout from "@/Layouts/TenantLayout";
import ContractsToolbar from "@/Components/ContractsToolbar";

export default function Contracts() {
	return (
		<div className=" px-4 sm:px-6 lg:px-8">
			<ContractsToolbar />
		</div>
	);
}

Contracts.layout = page => <TenantLayout title="Contracts">{page}</TenantLayout>;