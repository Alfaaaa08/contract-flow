import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/react";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createRoot } from "react-dom/client";
import { Toaster } from "sonner";
const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob("./Pages/**/*.jsx"),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <>
                <App {...props} />

                <Toaster
                    theme="dark"
                    closeButton
                    richColors={false}
                    toastOptions={{
                        className: "group toast",
                        style: {
                            width: "500px",
                            padding: "20px",
                            fontSize: "1rem",
                            background: "hsl(var(--card))",
                            color: "hsl(var(--foreground))",
                            border: "1px solid hsl(var(--border))",
                        },
                        classNames: {
                            success:
                                "!bg-primary/10 !text-primary !border-primary/30",
                            error: "!bg-destructive/10 !text-destructive !border-destructive/30",
                            info: "!bg-chart-4/10 !text-chart-4 !border-chart-4/30",
                            warning:
                                "!bg-chart-1/10 !text-chart-1 !border-chart-1/30",
                            description: "text-muted-foreground",
                            actionButton: "bg-primary text-primary-foreground",
                            cancelButton: "bg-muted text-muted-foreground",
                        },
                    }}
                />
            </>,
        );
    },
    progress: {
        color: "#4B5563",
    },
});
