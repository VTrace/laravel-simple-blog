@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex justify-center mt-4 mb-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 mx-1 bg-gray-300 text-gray-600 rounded cursor-not-allowed">Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 mx-1 bg-blue-500 text-white rounded hover:bg-blue-600">Prev</a>
        @endif

        {{-- Pagination Links --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-4 py-2 mx-1 text-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-4 py-2 mx-1 bg-blue-700 text-white rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 mx-1 bg-blue-500 text-white rounded hover:bg-blue-600">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 mx-1 bg-blue-500 text-white rounded hover:bg-blue-600">Next</a>
        @else
            <span class="px-4 py-2 mx-1 bg-gray-300 text-gray-600 rounded cursor-not-allowed">Next</span>
        @endif
    </nav>
@endif
