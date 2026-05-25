<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Perfil de empleador
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-semibold mb-2">Panel operativo</p>
                    <p class="text-sm text-gray-600">
                        Aquí puedes gestionar el inventario disponible y navegar por las funcionalidades principales del sistema.
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Accesos rápidos</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm hover:bg-indigo-700 transition">
                            Ver inventario (productos)
                        </a>
                        <a href="{{ route('profile.edit') }}" class="px-4 py-2 rounded-md border border-gray-300 text-sm hover:bg-gray-50 transition">
                            Mi perfil
                        </a>
                        <a href="{{ route('home') }}" class="px-4 py-2 rounded-md border border-gray-300 text-sm hover:bg-gray-50 transition">
                            Ir al sitio público
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
