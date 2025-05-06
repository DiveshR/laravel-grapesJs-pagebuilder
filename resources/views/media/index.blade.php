<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Media Library') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Uploaded Media</h3>
                    @if ($mediaAssets->isEmpty())
                        <p>No media files found.</p>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach ($mediaAssets as $asset)
                                <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                                    <a href="{{ $asset['src'] }}" target="_blank" title="View original: {{ $asset['file_name'] }}">
                                        <img src="{{ $asset['thumb_src'] ?? $asset['src'] }}" alt="{{ $asset['name'] }}" class="w-full h-32 object-cover">
                                    </a>
                                    <div class="p-2 text-xs">
                                        <p class="font-semibold truncate" title="{{ $asset['name'] }}">{{ $asset['name'] }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $asset['created_at'] }}</p>
                                        {{-- You could add a delete button here if needed --}}
                                        {{-- <form action="{{ route('media.destroy', $asset['id']) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs mt-1">Delete</button>
                                        </form> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 