<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Library Management System</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/css/tw-elements.min.css" />
    <script src="https://cdn.tailwindcss.com/3.3.0"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            overflow-x: hidden;
        }

        .activeNav {
            color: #111e3b !important;
            font-weight: 700;
        }
    </style>
    @yield('head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    @if (session('admin'))
        @include('include.nav_admin')
    @elseif(session('user'))
        @include('include.nav_user')
    @endif

    @if (session()->has('error'))
        <script>
            Swal.fire({
                heightAuto: false,
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: "#3085d6",
            })
        </script>
    @endif

    @if (session()->has('success'))
        <script>
            Swal.fire({
                heightAuto: false,
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: "#3085d6",
            })
        </script>
    @endif

    <section class="bg-neutral-100 flex flex-col justify-center items-center w-screen">
        @yield('content')
    </section>

    @yield('script')
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/js/tw-elements.umd.min.js"></script>
</body>

</html>
