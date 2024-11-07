@extends('layout')

@section('content')
    <section class="w-9/12 h-screen flex flex-col justify-center items-center">
        <h1 class="font-bold text-3xl text-center">Welcome, {{ session('admin') }}</h1>
        <form action="{{ route('admin.books.search') }}" method="POST"
            class="w-[600px] p-8 flex flex-col justify-center bg-white rounded-md shadow-md mt-8 ">
            @csrf
            <div class="relative mb-2 w-full" data-twe-input-wrapper-init>
                <input type="text"
                    class="bg-white peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                    id="search_book" name="search_book" placeholder="Search for books..." />
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
    </section>
@endsection

@section('script')
    <script>
        document.querySelector('.booksNav').classList.remove('activeNav');
        document.querySelector('.usersNav').classList.remove('activeNav');
        document.querySelector('.homeNav').classList.add('activeNav');
    </script>
@endsection
