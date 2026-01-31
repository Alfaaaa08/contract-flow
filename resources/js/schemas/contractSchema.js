import { z } from "zod";

export const contractSchema = z
    .object({
        name: z
            .string()
            .min(1, "Contract name is required")
            .max(255, "Contract name must not exceed 255 characters"),
        client_id: z.int().min(1, "Client is required"),
        contract_type_id: z.int().min(1, "Type is required"),
        start_date: z.string(),
        end_date: z.string().min(1, "End date is required"),
        value: z.number().min(0, "Value is required"),
    })
    .refine(
        (data) => {
            if (!data.start_date || !data.end_date) return true;

            const startDate = new Date(data.start_date);
            const endDate = new Date(data.end_date);

            return endDate > startDate;
        },
        {
            message: "End date must be after start date",
            path: ["end_date"],
        },
    )
    .refine(
        (data) => {
            if (!data.start_date) return true;

            const startDate = new Date(data.start_date);
            const endDate = new Date(data.end_date);

            const diffInMs = endDate - startDate;
            const diffInDays = diffInMs / (1000 * 60 * 60 * 24);

            return diffInDays >= 1;
        },
        {
            message: "End date must be at least 1 day after start date",
            path: ["end_date"],
        },
    );
