<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-10 sm:px-6 lg:px-8">
            
            {{-- For Guest Users --}}
            @guest
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p>
                            Please 
                            <a href="{{ route('login') }}" class="text-blue-500 font-semibold hover:underline">Login</a> 
                            or 
                            <a href="{{ route('register') }}" class="text-blue-500 font-semibold hover:underline">Register</a>.
                        </p>
                    </div>
                </div>
            @endguest

            {{-- For Authenticated Users --}}
            @auth
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="space-y-6 p-6">
                        
                        @forelse ($posts as $post)
                            <div class="rounded-md border p-5 shadow">
                                <div class="flex items-center gap-2">
                                    <span class="flex-none rounded {{ $post->status->badgeColor() }}"> {{ ucfirst($post->status->value) }}</span>
                                    <h3 class="text-lg font-semibold">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-500 hover:underline">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="mt-4 flex items-end justify-between text-sm text-gray-600">
                                    <div>
                                        <div>Published: {{ $post->published_at?->translatedFormat('F d, Y') ?? 'Scheduled' }}</div>
                                        <div>Updated: {{ $post->updated_at->translatedFormat('Y-m-d') }}</div>
                                    </div>
                                    <div class="flex gap-3">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-500 hover:underline">View</a> /
                                        <a href="{{ route('posts.edit', $post->slug) }}" class="text-yellow-500 hover:underline">Edit</a> /
                                        <form action="{{ route('posts.destroy', $post->slug) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this post?')" 
                                                class="text-red-500 hover:underline">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 text-center">
                                    No Blog Posts Yet
                                </div>
                            </div>
                        @endforelse

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $posts->links('components.pagination') }}
                        </div>
                    </div>
                </div>
            @endauth

        </div>
    </div>
</x-app-layout>
