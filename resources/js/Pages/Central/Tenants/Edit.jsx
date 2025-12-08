import CentralLayout from '@/Layouts/CentralLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm, usePage } from '@inertiajs/react';

export default function Edit({ tenant }) {
    const { app } = usePage().props;

    const { data, setData, put, processing, errors } = useForm({
        name: tenant.name || '',
        admin_email: tenant.admin_email || '',
        is_active: tenant.is_active ?? true,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('admin.tenants.update', tenant.id));
    };

    return (
        <CentralLayout
            header={
                <div className="flex items-center gap-4">
                    <Link
                        href={route('admin.tenants.show', tenant.id)}
                        className="text-gray-400 hover:text-gray-600"
                    >
                        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Edit Tenant
                    </h2>
                </div>
            }
        >
            <Head title={`Edit ${tenant.name}`} />

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
                                        onChange={(e) => setData('name', e.target.value)}
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
                                            value={tenant.domain}
                                            className="block w-full rounded-r-none bg-gray-50"
                                            disabled={true}
                                        />
                                        <span className="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">
                                            .{app.domain}
                                        </span>
                                    </div>
                                    <p className="mt-1 text-sm text-gray-500">
                                        Domain cannot be changed after creation.
                                    </p>
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
                                    />
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
                                <Link href={route('admin.tenants.show', tenant.id)}>
                                    <SecondaryButton type="button">Cancel</SecondaryButton>
                                </Link>
                                <PrimaryButton disabled={processing}>
                                    {processing ? 'Saving...' : 'Save Changes'}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </CentralLayout>
    );
}
