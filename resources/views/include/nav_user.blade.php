<!-- Main navigation container -->
<nav
    class="fixed top-0 z-10 flex w-full flex-wrap items-center justify-between bg-zinc-50 py-2 shadow-dark-mild lg:py-4">
    <div class="flex w-full flex-wrap items-center justify-between px-3">
        <!-- Hamburger button for mobile view -->
        <button
            class="block border-0 bg-transparent px-2 text-black/50 hover:no-underline hover:shadow-none focus:no-underline focus:shadow-none focus:outline-none focus:ring-0 lg:hidden"
            type="button" data-twe-collapse-init data-twe-target="#navbarSupportedContent1"
            aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation">
            <!-- Hamburger icon -->
            <span class="[&>svg]:w-7 [&>svg]:stroke-black/50">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>

        <!-- Collapsible navigation container -->
        <div class="!visible hidden flex-grow basis-[100%] items-center lg:!flex lg:basis-auto"
            id="navbarSupportedContent1" data-twe-collapse-item>
            <!-- Logo -->
            <a class="mb-4 me-5 ms-2 mt-3 flex items-center text-neutral-900 hover:text-neutral-900 focus:text-neutral-900 lg:mb-0 lg:mt-0"
                href="#">
                <img src="https://tecdn.b-cdn.net/img/logo/te-transparent-noshadows.webp" style="height: 15px"
                    alt="TE Logo" loading="lazy" />
            </a>
            <!-- Left navigation links -->
            <ul class="list-style-none me-auto flex flex-col ps-0 lg:flex-row" data-twe-navbar-nav-ref>
                <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
                    <!-- Dashboard link -->
                    <a class="homeNav text-black/60 transition duration-200 hover:text-black/80 hover:ease-in-out focus:text-black/80 active:text-black/80 motion-reduce:transition-none lg:px-2"
                        href="{{ route('user.home') }}" data-twe-nav-link-ref>Home</a>
                </li>
                <!-- Team link -->
                <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
                    <a class="booksNav text-black/60 transition duration-200 hover:text-black/80 hover:ease-in-out focus:text-black/80 active:text-black/80 motion-reduce:transition-none lg:px-2"
                        href="{{ route('user.books') }}" data-twe-nav-link-ref>Books</a>
                </li>
                <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
                    <a class="reservesNav text-black/60 transition duration-200 hover:text-black/80 hover:ease-in-out focus:text-black/80 active:text-black/80 motion-reduce:transition-none lg:px-2"
                        href="{{ route('user.reserves') }}" data-twe-nav-link-ref>Reserves</a>
                </li>
                <!-- Projects link -->
                <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
                    <a class="profileNav text-black/60 transition duration-200 hover:text-black/80 hover:ease-in-out focus:text-black/80 active:text-black/80 motion-reduce:transition-none lg:px-2"
                        href="{{ route('user.profile') }}" data-twe-nav-link-ref>Profile</a>
                </li>
            </ul>
            <!-- Left links -->
        </div>

        <!-- Right elements -->
        <div class="relative flex items-center">
                <a class="text-black/60 transition duration-200 hover:text-black/80 hover:ease-in-out focus:text-black/80 active:text-black/80 motion-reduce:transition-none lg:px-2"
                    href="{{ route('user.logout') }}" data-twe-nav-link-ref>Sign Out</a>
        </div>
        <!-- Right elements -->
    </div>
</nav>
