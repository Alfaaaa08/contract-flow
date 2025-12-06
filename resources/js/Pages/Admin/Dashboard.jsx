import AdminLayout from '@/Layouts/AdminLayout';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, Link } from '@inertiajs/react';

function StatCard({ title, value, description, colorClass = 'text-gray-900' }) {
    return (
        <div className="overflow-hidden rounded-lg bg-white shadow">
            <div className="p-5">
                <div className="flex items-center">
                    <div className="flex-1">
                        <p className="text-sm font-medium text-gray-500 truncate">
                            {title}
                        </p>
                        <p className={`mt-1 text-3xl font-semibold ${colorClass}`}>
                            {value}
                        </p>
                        {description && (
                            <p className="mt-1 text-sm text-gray-500">
                                {description}
                            </p>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}

function StatusBadge({ isActive }) {
    return (
        <span
            className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${
                isActive
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800'
            }`}
        >
            {isActive ? 'Active' : 'Inactive'}
        </span>
    );
}

export default function Dashboard({ stats, recentTenants }) {
    return (
        <AdminLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Admin Dashboard
                    </h2>
                    <Link href={route('admin.tenants.create')}>
                        <PrimaryButton>Create Tenant</PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Admin Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {/* Stats Grid */}
                    <div className="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <StatCard
                            title="Total Tenants"
                            value={stats.total}
                            description="All registered tenants"
                        />
                        <StatCard
                            title="Active Tenants"
                            value={stats.active}
                            description="Currently active"
                            colorClass="text-green-600"
                        />
                        <StatCard
                            title="Inactive Tenants"
                            value={stats.inactive}
                            description="Deactivated tenants"
                            colorClass="text-red-600"
                        />
                    </div>

                    {/* Recent Tenants */}
                    <div className="mt-8">
                        <div className="overflow-hidden bg-white shadow sm:rounded-lg">
                            <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <h3 className="text-lg font-medium leading-6 text-gray-900">
                                            Recent Tenants
                                        </h3>
                                        <p className="mt-1 text-sm text-gray-500">
                                            Latest registered tenants
                                        </p>
                                    </div>
                                    <Link
                                        href={route('admin.tenants.index')}
                                        className="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                    >
                                        View all
                                    </Link>
                                </div>
                            </div>
                            <div className="overflow-x-auto">
                                {recentTenants.length > 0 ? (
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Name
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Domain
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Created
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {recentTenants.map((tenant) => (
                                                <tr key={tenant.id} className="hover:bg-gray-50">
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <Link
                                                            href={route('admin.tenants.show', tenant.id)}
                                                            className="text-sm font-medium text-gray-900 hover:text-indigo-600"
                                                        >
                                                            {tenant.name}
                                                        </Link>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {tenant.domain || '-'}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <StatusBadge isActive={tenant.is_active} />
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {tenant.created_at}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                ) : (
                                    <div className="px-6 py-12 text-center">
                                        <p className="text-sm text-gray-500">
                                            No tenants yet.
                                        </p>
                                        <Link
                                            href={route('admin.tenants.create')}
                                            className="mt-2 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                        >
                                            Create your first tenant
                                        </Link>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
