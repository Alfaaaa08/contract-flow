import AdminLayout from '@/Layouts/AdminLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        domain: '',
        admin_email: '',
        is_active: true,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('admin.tenants.store'));
    };

    const handleNameChange = (e) => {
        const name = e.target.value;
        setData((prev) => ({
            ...prev,
            name,
            domain: prev.domain || name.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, ''),
        }));
    };

    return (
        <AdminLayout
            header={
                <div className="flex items-center gap-4">
                    <Link
                        href={route('admin.tenants.index')}
                        className="text-gray-400 hover:text-gray-600"
                    >
                        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Create Tenant
                    </h2>
                </div>
            }
        >
            <Head title="Create Tenant" />

            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow sm:rounded-lg">
                        <form onSubmit={handleSubmit} className="p-6">
                            <div className="space-y-6">
                                <div>
                                    <InputLabel htmlFor="name" value="Tenant Name" />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        name="name"
                                        value={data.name}
                                        className="mt-1 block w-full"
                                        onChange={handleNameChange}
                                        placeholder="Acme Corporation"
                                        isFocused={true}
                                    />
                                    <InputError message={errors.name} className="mt-2" />
                                </div>

                                <div>
                                    <InputLabel htmlFor="domain" value="Subdomain" />
                                    <div className="mt-1 flex rounded-md shadow-sm">
                                        <TextInput
                                            id="domain"
                                            type="text"
                                            name="domain"
                                            value={data.domain}
                                            className="block w-full rounded-r-none"
                                            onChange={(e) => setData('domain', e.target.value.toLowerCase().replace(/[^a-z0-9-]/g, ''))}
                                            placeholder="acme"
                                        />
                                        <span className="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">
                                            .localhost
                                        </span>
                                    </div>
                                    <p className="mt-1 text-sm text-gray-500">
                                        Only lowercase letters, numbers, and hyphens allowed.
                                    </p>
                                    <InputError message={errors.domain} className="mt-2" />
                                </div>

                                <div>
                                    <InputLabel htmlFor="admin_email" value="Admin Email" />
                                    <TextInput
                                        id="admin_email"
                                        type="email"
                                        name="admin_email"
                                        value={data.admin_email}
                                        className="mt-1 block w-full"
                                        onChange={(e) => setData('admin_email', e.target.value)}
                                        placeholder="admin@acme.com"
                                    />
                                    <p className="mt-1 text-sm text-gray-500">
                                        The initial admin user will be created with this email.
                                    </p>
                                    <InputError message={errors.admin_email} className="mt-2" />
                                </div>

                                <div className="flex items-center">
                                    <input
                                        id="is_active"
                                        type="checkbox"
                                        name="is_active"
                                        checked={data.is_active}
                                        onChange={(e) => setData('is_active', e.target.checked)}
                                        className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label htmlFor="is_active" className="ml-2 block text-sm text-gray-700">
                                        Active
                                    </label>
                                </div>
                            </div>

                            <div className="mt-6 flex items-center justify-end gap-4">
                                <Link href={route('admin.tenants.index')}>
                                    <SecondaryButton type="button">Cancel</SecondaryButton>
                                </Link>
                                <PrimaryButton disabled={processing}>
                                    {processing ? 'Creating...' : 'Create Tenant'}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
