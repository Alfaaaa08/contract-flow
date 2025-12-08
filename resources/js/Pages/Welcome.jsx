import { Head } from '@inertiajs/react';

function FeatureCard({ icon, title, description }) {
    return (
        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
            <div className="flex items-center gap-3 mb-3">
                <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                    {icon}
                </div>
                <h3 className="text-lg font-semibold text-gray-900">{title}</h3>
            </div>
            <p className="text-gray-600 text-sm">{description}</p>
        </div>
    );
}

function TechBadge({ children }) {
    return (
        <span className="inline-flex items-center rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-800">
            {children}
        </span>
    );
}

export default function Welcome({ appUrl, appDomain }) {
    const features = [
        {
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            ),
            title: 'Multi-Tenancy',
            description: 'Isolated databases per tenant with automatic provisioning using Stancl Tenancy.',
        },
        {
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            ),
            title: 'Role-Based Access',
            description: 'Central admin and tenant-level role management with protected routes.',
        },
        {
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            ),
            title: 'Project Management',
            description: 'Full CRUD with status tracking: draft, active, completed, and archived.',
        },
        {
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            ),
            title: 'Team Collaboration',
            description: 'User management with role assignments per tenant and profile settings.',
        },
        {
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            ),
            title: 'Secure Authentication',
            description: 'Login, registration, password reset with email verification support.',
        },
        {
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            ),
            title: 'API-Ready Architecture',
            description: 'Built for REST API integration with proper structure for future expansion.',
        },
    ];

    const techStack = [
        'Laravel 12',
        'React 18',
        'Inertia.js',
        'Tailwind CSS',
        'Stancl Tenancy',
        'MySQL',
    ];

    return (
        <>
            <Head title="Welcome" />

            <div className="min-h-screen bg-gray-50">
                {/* Hero Section */}
                <div className="bg-white">
                    <div className="mx-auto max-w-5xl px-6 py-16 sm:py-24 lg:px-8">
                        <div className="text-center">
                            <h1 className="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                                Multi-Tenant SaaS Starter
                            </h1>
                            <p className="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                                A production-ready Laravel + React multi-tenant boilerplate with
                                isolated databases, role-based access, and modern UI.
                            </p>
                            <div className="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                                <a
                                    href="/admin/login"
                                    className="inline-flex items-center rounded-md bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors"
                                >
                                    Admin Login
                                </a>
                                <a
                                    href={`http://demo.${appDomain}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex items-center rounded-md bg-white px-6 py-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                                >
                                    View Demo Tenant
                                    <svg className="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Features Section */}
                <div className="mx-auto max-w-5xl px-6 py-16 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-2xl font-bold text-gray-900">Features</h2>
                        <p className="mt-2 text-gray-600">Everything you need to build a SaaS application</p>
                    </div>
                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {features.map((feature, index) => (
                            <FeatureCard key={index} {...feature} />
                        ))}
                    </div>
                </div>

                {/* Tech Stack Section */}
                <div className="bg-white">
                    <div className="mx-auto max-w-5xl px-6 py-16 lg:px-8">
                        <div className="text-center mb-8">
                            <h2 className="text-2xl font-bold text-gray-900">Tech Stack</h2>
                            <p className="mt-2 text-gray-600">Built with modern, battle-tested technologies</p>
                        </div>
                        <div className="flex flex-wrap items-center justify-center gap-3">
                            {techStack.map((tech, index) => (
                                <TechBadge key={index}>{tech}</TechBadge>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Demo Credentials Section */}
                <div className="mx-auto max-w-5xl px-6 py-16 lg:px-8">
                    <div className="text-center mb-8">
                        <h2 className="text-2xl font-bold text-gray-900">Demo Credentials</h2>
                        <p className="mt-2 text-gray-600">Use these credentials to explore the application</p>
                    </div>
                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 max-w-3xl mx-auto">
                        {/* Central Admin */}
                        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Central Admin</h3>
                            <div className="space-y-3 text-sm">
                                <div>
                                    <span className="text-gray-500">URL:</span>
                                    <a
                                        href="/admin/login"
                                        className="ml-2 text-indigo-600 hover:text-indigo-500"
                                    >
                                        {appUrl}/admin/login
                                    </a>
                                </div>
                                <div>
                                    <span className="text-gray-500">Email:</span>
                                    <code className="ml-2 rounded bg-gray-100 px-2 py-1 text-gray-800">
                                        admin@example.com
                                    </code>
                                </div>
                                <div>
                                    <span className="text-gray-500">Password:</span>
                                    <code className="ml-2 rounded bg-gray-100 px-2 py-1 text-gray-800">
                                        password
                                    </code>
                                </div>
                            </div>
                        </div>

                        {/* Demo Tenant */}
                        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Demo Tenant</h3>
                            <div className="space-y-3 text-sm">
                                <div>
                                    <span className="text-gray-500">URL:</span>
                                    <a
                                        href={`http://demo.${appDomain}/login`}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="ml-2 text-indigo-600 hover:text-indigo-500"
                                    >
                                        demo.{appDomain}/login
                                    </a>
                                </div>
                                <div>
                                    <span className="text-gray-500">Email:</span>
                                    <code className="ml-2 rounded bg-gray-100 px-2 py-1 text-gray-800">
                                        admin@demo.com
                                    </code>
                                </div>
                                <div>
                                    <span className="text-gray-500">Password:</span>
                                    <code className="ml-2 rounded bg-gray-100 px-2 py-1 text-gray-800">
                                        password
                                    </code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Footer */}
                <footer className="border-t border-gray-200 bg-white">
                    <div className="mx-auto max-w-5xl px-6 py-8 lg:px-8">
                        <div className="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p className="text-sm text-gray-500">
                                Built with Laravel & React
                            </p>
                            <a
                                href="https://github.com/alihamzahq/laravel-multi-tenant-saas-starter"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center text-sm text-gray-500 hover:text-gray-700"
                            >
                                <svg className="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path fillRule="evenodd" clipRule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                </svg>
                                View on GitHub
                            </a>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
