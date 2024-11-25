@extends('layout')

@section('content')
    <section class="w-9/12 min-h-screen flex flex-col items-center py-24">
        <h1 class="font-bold text-3xl text-center">My Reserves</h1>

        @if ($books->isEmpty())
            <div class="w-full flex justify-center items-center">
                <p class="text-lg mt-4">No reserves found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 w-full">
                @foreach ($books as $book)
                    <div
                        class="block rounded-lg bg-white p-6 text-surface shadow-secondary-1 w-full flex flex-col justify-between">
                        <div>
                            <h5 class="text-xl font-medium leading-tight">{{ $book->name }}</h5>
                            <p class="mb-4 text-base">
                                {{ $book->author }}
                            </p>
                            <p class="mb-4 text-base">
                                {{ $book->desc }}
                            </p>
                        </div>

                        <div class="flex gap-4 mt-2">
                            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                action="{{ route('user.books.return', ['id' => $book->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-sm text-white font-medium inline-block rounded bg-warning px-6 pb-2.5 pt-2.5 uppercase leading-normal text-white shadow-warning-3 transition duration-150 ease-in-out hover:bg-warning-accent-300 hover:shadow-warning-2 focus:bg-warning-accent-300 focus:shadow-warning-2 focus:outline-none focus:ring-0 active:bg-warning-600 active:shadow-warning-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                    data-twe-ripple-init data-twe-ripple-color="light">
                                    Return
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
        document.querySelector('.booksNav').classList.remove('activeNav');
        document.querySelector('.profileNav').classList.remove('activeNav');
        document.querySelector('.homeNav').classList.remove('activeNav');
        document.querySelector('.reservesNav').classList.add('activeNav');
    </script>
@endsection
