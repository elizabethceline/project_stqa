@extends('layout')

@section('content')
    <section class="w-9/12 min-h-screen flex flex-col items-center py-24">
        <h1 class="font-bold text-3xl text-center">Books</h1>
        <button type="button" onclick="window.location.href='{{ route('admin.books.add') }}'"
            class="fixed bottom-8 right-8 inline-block rounded-full bg-success px-6 pb-2 pt-2.5 text-base font-bold uppercase leading-normal text-white shadow-success-3 transition duration-150 ease-in-out hover:bg-success-accent-300 hover:shadow-success-2 focus:bg-success-accent-300 focus:shadow-success-2 focus:outline-none focus:ring-0 active:bg-success-600 active:shadow-success-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
            Add New Book
        </button>

        <form action="{{ route('admin.books.search') }}" method="POST" class="w-full my-8">
            @csrf
            <div class="relative mb-2 w-full" data-twe-input-wrapper-init>
                {{-- //if search empty --}}
                <input type="text"
                    class="bg-white peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                    id="search_book" name="search_book" placeholder="Search for books..."
                    value="{{ isset($search) ? $search : '' }}" />
                <label for="search_book"
                    class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">
                    Search for books...
                </label>
            </div>
            <p class="text-sm">Available books will be shown to members.</p>
            <button type="submit"
                class="mt-5 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                Search </button>
        </form>
        @if ($books->isEmpty())
            <div class="w-full flex justify-center items-center">
                <p class="text-lg">No books found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 w-full">
                @foreach ($books as $book)
                    <div
                        class="block rounded-lg bg-white p-6 text-surface shadow-secondary-1 w-full flex flex-col justify-between">
                        <div>
                            <h5 class="text-xl font-medium leading-tight">{{ $book->name }}</h5>
                            <p class="mb-4 text-base">
                                {{ $book->author }} | @if ($book->availability == 1)
                                    <span class="text-green-700">
                                        Available
                                    </span>
                                @else
                                    <span class="text-red-700">
                                        Not Available
                                    </span>
                                @endif
                            </p>
                            <p class="mb-4 text-base">
                                {{ $book->desc }}
                            </p>
                        </div>

                        <div class="flex gap-4 mt-2">
                            <p
                                class="mb-4 text-sm px-2 py-2.5 bg-green-800 text-white font-bold rounded tracking-wider w-fit">
                                Books left: {{ $book->count }}
                            </p>
                            <a href="{{ route('admin.books.edit', ['id' => $book->id]) }}">
                                <button type="button"
                                    class="text-sm text-white font-medium inline-block rounded bg-warning px-6 pb-2.5 pt-2.5 uppercase leading-normal text-white shadow-warning-3 transition duration-150 ease-in-out hover:bg-warning-accent-300 hover:shadow-warning-2 focus:bg-warning-accent-300 focus:shadow-warning-2 focus:outline-none focus:ring-0 active:bg-warning-600 active:shadow-warning-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                    data-twe-ripple-init data-twe-ripple-color="light">
                                    Edit
                                </button>
                            </a>

                            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                action="{{ route('admin.books.delete', ['id' => $book->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-sm text-white font-medium inline-block rounded bg-danger px-6 pb-2.5 pt-2.5 uppercase leading-normal text-white shadow-danger-3 transition duration-150 ease-in-out hover:bg-danger-accent-300 hover:shadow-danger-2 focus:bg-danger-accent-300 focus:shadow-danger-2 focus:outline-none focus:ring-0 active:bg-danger-600 active:shadow-danger-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                    data-twe-ripple-init data-twe-ripple-color="light">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </section>
@endsection

@section('script')
    <script>
        document.querySelector('.booksNav').classList.add('activeNav');
        document.querySelector('.usersNav').classList.remove('activeNav');
        document.querySelector('.homeNav').classList.remove('activeNav');
    </script>
@endsection
