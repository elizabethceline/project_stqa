@extends('layout')

@section('content')
<section class="w-9/12 h-screen flex flex-col justify-center items-center">
        <h1 class="font-bold text-3xl">Welcome, {{ session('admin') }}</h1>
</section>
@endsection

@section('script')
    <script>
        document.querySelector('.homeNav').classList.add('activeNav');
    </script>
@endsection
