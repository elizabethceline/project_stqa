@extends('layout')

@section('head')
@endsection

@section('content')
    <section class="w-9/12 min-h-screen flex flex-col items-center py-24">
        <h1 class="font-bold text-3xl text-center">Users</h1>

        @if ($customers->isEmpty())
            <div class="w-full flex justify-center items-center">
                <p class="text-lg mt-8">No users found.</p>
            </div>
        @else
            <div class="flex flex-col w-full mt-4">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table
                                class="min-w-full border border-neutral-200 text-center text-sm font-light text-surface dark:border-white/10 dark:text-white">
                                <thead
                                    class="border-b border-neutral-200 bg-primary text-white font-medium dark:border-white/10">
                                    <tr>
                                        <th scope="col"
                                            class="border-e border-neutral-200 px-6 py-4 dark:border-white/10">
                                            Name
                                        </th>
                                        <th scope="col"
                                            class="border-e border-neutral-200 px-6 py-4 dark:border-white/10">
                                            E-mail
                                        </th>
                                        <th scope="col"
                                            class="border-e border-neutral-200 px-6 py-4 dark:border-white/10">
                                            Bio
                                        </th>
                                        <th scope="col"
                                            class="border-e border-neutral-200 px-6 py-4 dark:border-white/10">
                                            Books
                                        </th>
                                        <th scope="col"
                                            class="border-e border-neutral-200 px-6 py-4 dark:border-white/10">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr class="border-b border-neutral-200 bg-white dark:border-white/10">
                                            <td
                                                class="whitespace-nowrap border-e border-neutral-200 px-6 py-4 font-medium dark:border-white/10">
                                                {{ $customer->name }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap border-e border-neutral-200 px-6 py-4 font-medium dark:border-white/10">
                                                {{ $customer->email }}
                                            </td>
                                            <td
                                                class=" border-e border-neutral-200 px-6 py-4 font-medium dark:border-white/10">
                                                {{ $customer->bio }}
                                            </td>
                                            <td
                                                class=" border-e border-neutral-200 px-6 py-4 font-medium dark:border-white/10">
                                                @if($customer->books->isEmpty())
                                                    <p class="text-red-500">No books reserved</p>
                                                @else
                                                    <button type="button"
                                                        class="text-xs text-white font-medium inline-block rounded bg-warning px-6 pb-2.5 pt-2.5 uppercase leading-normal text-white shadow-warning-3 transition duration-150 ease-in-out hover:bg-warning-accent-300 hover:shadow-warning-2 focus:bg-warning-accent-300 focus:shadow-warning-2 focus:outline-none focus:ring-0 active:bg-warning-600 active:shadow-warning-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                                        data-twe-ripple-init data-twe-ripple-color="light"
                                                        onclick="showBooks('{{ $customer['name'] }}', {!! e(json_encode($customer['books'])) !!})">
                                                        View
                                                    </button>
                                                @endif
                                            </td>
                                            <td
                                                class=" border-e border-neutral-200 px-6 py-4 font-medium dark:border-white/10">
                                                <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                    action="{{ route('admin.users.delete', ['id' => $customer->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-xs text-white font-medium inline-block rounded bg-danger px-6 pb-2.5 pt-2.5 uppercase leading-normal text-white shadow-danger-3 transition duration-150 ease-in-out hover:bg-danger-accent-300 hover:shadow-danger-2 focus:bg-danger-accent-300 focus:shadow-danger-2 focus:outline-none focus:ring-0 active:bg-danger-600 active:shadow-danger-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                                        data-twe-ripple-init data-twe-ripple-color="light">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </section>
@endsection

@section('script')
    <script>
        document.querySelector('.booksNav').classList.remove('activeNav');
        document.querySelector('.usersNav').classList.add('activeNav');
        document.querySelector('.homeNav').classList.remove('activeNav');
    </script>

    <script>
        function showBooks(customerName, books) {
            if (books.length === 0) {
                Swal.fire({
                    title: `No Books Reserved by ${customerName}`,
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2000,
                });
                return;
            }

            let table = `
            <table class="table-auto border-collapse border border-gray-300 w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-center border border-gray-300 px-4 py-2">Title</th>
                        <th class="text-center border border-gray-300 px-4 py-2">Author</th>
                    </tr>
                </thead>
                <tbody>
        `;
            books.forEach(book => {
                table += `
                <tr>
                    <td class="border border-gray-300 px-4 py-2">${book.name}</td>
                    <td class="border border-gray-300 px-4 py-2">${book.author}</td>
                </tr>
            `;
            });
            table += `
                </tbody>
            </table>
        `;

            Swal.fire({
                title: `${customerName}'s Reserved Books`,
                html: table,
                icon: 'info',
                showCloseButton: true,
                showConfirmButton: false,
            });
        }
    </script>
@endsection
