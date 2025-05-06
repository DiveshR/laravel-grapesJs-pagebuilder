<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Blog Post') }}: {{ $blog->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('blogs.update', $blog) }}">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700">{{ __('Title') }}</label>
                            <input id="title" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="title" value="{{ old('title', $blog->title) }}" required autofocus />
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- We'll add GrapesJS editor for content here later --}}
                        <div class="mt-4">
                            <label for="content" class="block font-medium text-sm text-gray-700">{{ __('Content') }}</label>
                            {{-- This div is where GrapesJS will be initialized --}}
                            <div id="gjs-editor-instance" class="mt-1 p-2 border border-gray-300 rounded-md"></div>
                            {{-- Hidden textarea to store and submit GrapesJS content --}}
                            <textarea id="blog-content-textarea" name="content" class="hidden">{{ old('content', $blog->content) }}</textarea>
                            @error('content')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Update Blog') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 