@extends('layout.app')

    @section('content')

    <h1 class="mb-10 text-2xl">Books</h1>

    <form class="mb-8 flex gap-4 items-center" method="GET" action="{{ route('books.index') }}">
    
        <input class="input" type="text" name="title" placeholder="Search by title" value="{{ request('title') }}" />
        <input type="hidden" name="filter" value="{{ request('filter') }}">
        <button type="submit" class="btnSearch">Search</button>
        <a href="{{ route('books.index') }}">Clear</a>

    </form>

    <div class="filter-container mb-4 flex">
        @php
            $filters = [
                '' => 'Latest',
                'popular_last_month' => 'Popular last month',
                'popular_last_6months' => 'Popular last 6 months',
                'highest_rated_last_month' => 'Highest rated last month',
                'highest_rated_last_6months' => 'Highest rated last 6 months',
            ];      
        @endphp

        @foreach ($filters as $key => $label)
                                             {{-- Esta parte no me quedo muy clara, pero bue --}}
            <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"
                {{-- Si la request tiene un valor de filtro igual a key (la key del array de arriba) o si el filter viene nulo y
                    ademas la key viene asi '' se da como verdadera y pasa a filter-item-active --}}
                class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($books->count())
    <nav class="mb-4">
        {{$books->links()}}
    </nav>
    @endif

    <ul>
        @forelse ($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', $book->id) }}" class="book-title">{{$book->title}}</a>
                            <span class="book-author">{{$book->author}}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                {{ number_format($book->reviews_avg_rating, 1) }}
                            </div>
                            <div class="book-review-count">
                                out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>

    @endsection