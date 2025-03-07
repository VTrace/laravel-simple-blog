<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- Alert for Validation Errors --}}
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <strong>Whoops! Something went wrong.</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <section>
                        <form method="POST" action="{{ route('posts.store') }}" class="space-y-6">
                            @csrf

                            {{-- Title Input --}}
                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title') }}" required />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            {{-- Content Input --}}
                            <div>
                                <x-input-label for="content" :value="__('Content')" />
                                <textarea id="content" name="body" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="6" required>{{ old('body') }}</textarea>
                                <x-input-error :messages="$errors->get('body')" class="mt-2" />
                            </div>

                            {{-- Scheduled Publish Date Input --}}
                            <div>
                                <x-input-label for="scheduled_at" :value="__('Publish Date')" />
                                <x-text-input id="scheduled_at" name="scheduled_at" type="date" class="mt-1 block w-full" value="{{ old('scheduled_at') }}" min="{{ now()->toDateString() }}" />
                                <x-input-error :messages="$errors->get('scheduled_at')" class="mt-2" />
                            </div>

                            {{-- Draft Checkbox --}}
                            <div>
                                <label for="is_draft" class="inline-flex items-center">
                                    <input id="is_draft" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_draft" {{ old('is_draft') ? 'checked' : '' }}>
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Save as Draft') }}</span>
                                </label>
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex items-center gap-4">
                                <x-primary-button id="submit-btn" onclick="this.disabled=true; this.form.submit();">
                                    {{ __('Post') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
