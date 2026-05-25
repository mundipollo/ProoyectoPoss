<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Configuraciones</h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-[1700px] mx-auto px-3 sm:px-5 lg:px-8">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content space-y-6">
                    <div class="p-6 bg-white rounded-xl border border-gray-200">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-6 bg-white rounded-xl border border-gray-200">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="p-6 bg-white rounded-xl border border-gray-200">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .admin-shell{display:grid;grid-template-columns:230px 1fr;min-height:78vh;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;background:#fff}
        .admin-content{padding:24px;overflow-y:auto;background:#fcfcfd}
        @media(max-width:900px){.admin-shell{grid-template-columns:1fr}.pos-sidebar{border-right:0;border-bottom:1px solid #e5e7eb}}
    </style>
</x-app-layout>
