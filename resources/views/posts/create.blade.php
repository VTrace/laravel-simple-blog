<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(count($errors) > 0)
                        <div role="alert">
                            <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                                Alert!
                            </div>
                            <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <section>
                        <form method="POST" action="{{ route('posts.store') }}" class="space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" />
                                {{-- <x-input-error :messages="''" class="mt-2" /> --}}
                            </div>

                            <div>
                                <x-input-label for="content" :value="__('Content')" />
                                <textarea id="content" name="body" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="6"></textarea>
                                {{-- <x-input-error :messages="''" class="mt-2" /> --}}
                            </div>

                            <div>
                                <x-input-label for="scheduled_at" :value="__('Publish Date')" />
                                <x-text-input id="scheduled_at" name="scheduled_at" type="date" class="mt-1 block w-full" />
                                {{-- <x-input-error :messages="''" class="mt-2" /> --}}
                            </div>

                            <div>
                                <label for="is_draft" class="inline-flex items-center">
                                    <input id="is_draft" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_draft">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Save as Draft') }}</span>
                                </label>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Post') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
