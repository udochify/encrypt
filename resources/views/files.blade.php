<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row space-x-4 items-center">
            @if(request()->routeIs('files.index'))
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Manage Files') }}
                </h2>
                <a href="{{ route('files.create') }}">
                    <x-custom-button class="bg-gray-800 hover:bg-gray-900 text-gray-50 hover:text-white rounded-full">Upload New File</x-custom-button>
                </a>
            @elseif(request()->routeIs('files.create'))
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Upload New File') }}
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
                    @if(request()->routeIs('files.index'))
                        <x-files.index :files="$files" />
                    @elseif(request()->routeIs('files.create'))
                        <x-files.create />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
