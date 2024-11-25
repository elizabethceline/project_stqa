@extends('layout')

@section('content')
    <section class="w-9/12 h-screen flex flex-col justify-center items-center">
        <h1 class="font-bold text-5xl mb-8">SIGN UP</h1>
        <form action="{{ route('user.signup.validate') }}" method="POST"
            class="w-full sm:w-[600px] p-8 flex flex-col justify-center items-center bg-white rounded-md shadow-md">
            @csrf
            <div class="relative mb-5 w-full" data-twe-input-wrapper-init>
                <input type="text"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                    id="name" name="name" placeholder="Name" />
                <label for="name"
                    class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Name
                </label>
            </div>
            <div class="grid sm:grid-cols-2 sm:gap-4 w-full">
                <div class="relative mb-5 w-full" data-twe-input-wrapper-init>
                    <input type="email"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="email" name="email" placeholder="E-mail" />
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
                    id="bio" name="bio" rows="3" placeholder="Your message"></textarea>
                <label for="bio"
                    class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Bio</label>
            </div>
            <button type="submit"
                class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                Sign Up </button>
            <p class="text-sm mt-5 text-center">Already have an account? <a href="{{ route('user.login') }}"
                    class="text-primary underline">Log in</a> now!</p>
        </form>
    </section>
@endsection

@section('script')
@endsection
