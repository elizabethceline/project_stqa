@extends('layout')

@section('content')
    <section class="w-9/12 min-h-screen max-lg:py-20 flex flex-col justify-center items-center">
        <div class="grid lg:grid-cols-2 justify-center items-center gap-12">
            <div
                class="block w-full rounded-lg bg-white p-6 text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white flex flex-col items-center justify-center">
                <img src="https://uxwing.com/wp-content/themes/uxwing/download/peoples-avatars/man-user-circle-icon.png"
                    alt="" class="w-1/6">
                <h5 class="my-2 text-2xl font-medium leading-tight text-center">{{ $customer->name }}</h5>
                <div class="w-full h-[1px] bg-neutral-300"></div>
                <p class="my-4 text-base">
                    {{ $customer->bio }}
                </p>
                <div class="w-full h-[1px] bg-neutral-300"></div>
                <p class="my-4 text-base inline-flex items-center gap-1">
                    Joined: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-activity" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2Z" />
                    </svg> {{ $customer->created_at->diffForHumans() }}
                </p>
            </div>
            <form action="{{ route('user.profile.update') }}" method="POST"
                class="w-full p-8 flex flex-col justify-center bg-white rounded-md shadow-md ">
                @csrf
                @method('PUT')
                <p class="text-2xl text-center font-medium">Manage Account</p>
                <p class="text-base text-center mt-1 mb-4">One account for everything</p>
                <div class="relative mb-5 w-full" data-twe-input-wrapper-init>
                    <input type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="name" name="name" value="{{ $customer->name }}" placeholder="Name" />
                    <label for="name"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Name
                    </label>
                </div>
                <div class="grid sm:grid-cols-2 sm:gap-4">
                    <div class="relative mb-5 w-full" data-twe-input-wrapper-init>
                        <input type="email"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                            id="email" name="email" value="{{ $customer->email }}" placeholder="E-mail" />
                        <label for="email"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">E-mail
                        </label>
                    </div>
                    <div class="relative mb-5 w-full" data-twe-input-wrapper-init>
                        <input type="password"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                            id="password" name="password" placeholder="Password" />
                        <label for="password"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Password
                        </label>
                    </div>
                </div>

                <div class="relative mb-5 w-full" data-twe-input-wrapper-init>
                    <textarea
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="bio" name="bio" rows="3" placeholder="Your message">{{ $customer->bio }}</textarea>
                    <label for="bio"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Bio</label>
                </div>

                <button type="submit"
                    class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                    Save Changes </button>
            </form>
        </div>

    </section>
@endsection

@section('script')
    <script>
        document.querySelector('.booksNav').classList.remove('activeNav');
        document.querySelector('.profileNav').classList.add('activeNav');
        document.querySelector('.homeNav').classList.remove('activeNav');
        document.querySelector('.reservesNav').classList.remove('activeNav');
    </script>
@endsection
