<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-10 sm:px-6 lg:px-8">

            {{-- for gueset users --}}
            @guest
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Please <a href="{{ route('login') }}" class="text-blue-500">login</a> or
                    <a href="{{ route('register') }}" class="text-blue-500">register</a>.</p>
                </div>
            </div>
            @endguest

            {{-- for authenticated users --}}
            @auth
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="space-y-6 p-6">
                    @foreach ($posts as $post)
                        <div class="rounded-md border p-5 shadow">
                            <div class="flex items-center gap-2">
                                <span class="flex-none rounded {{ $post->status->badgeColor() }}"> {{ ucfirst($post->status->value) }}</span>
                                <h3><a href="#" class="text-blue-500">{{ $post->title }}</a></h3>
                            </div>
                            <div class="mt-4 flex items-end justify-between">
                                <div>
                                    <div>Published: {{ $post->published_at }}</div>
                                    <div>Updated: {{ $post->updated_at->format('Y-m-d') }}</div>
                                </div>
                                <div>
                                    <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-500">Detail</a> /
                                    <a href="{{ route('posts.edit', $post->slug) }}" class="text-blue-500">Edit</a> /
                                    <form action="{{ route('posts.destroy', $post->slug) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-btn text-red-500">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                    <div>{{ $posts->links('components.pagination') }}</div>
                </div>
            </div>
            @endauth
        </div>
    </div>
</x-app-layout>
