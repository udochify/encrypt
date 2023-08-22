<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row space-x-4 items-center">
            @if(request()->routeIs('keys.index'))
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Manage Keys') }}
                </h2>
                <a href="{{ route('keys.create') }}">
                    <x-custom-button class="bg-gray-800 hover:bg-gray-900 text-gray-50 hover:text-white rounded-full">Generate Keys</x-custom-button>
                </a>
            @elseif(request()->routeIs('keys.create'))
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Generate Keys') }}
                </h2>
            @endif
        </div>
    </x-slot>

    <x-slot name="notification">
        <x-notification />
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(request()->routeIs('keys.index'))
                        <x-keys.index :keys="$keys" />
                    @elseif(request()->routeIs('keys.create'))
                        <x-keys.create />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
