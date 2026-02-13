import { STATUS_STYLES } from "@/Constants/contracts";

export default function ContractStatusBadge(status) {
    return (
        <div
            className={`inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border ${STATUS_STYLES[status]}`}
        >
            {status}
        </div>
    );
}
