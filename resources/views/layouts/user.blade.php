<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- <link href="../../../node_modules/material-icons/iconfont/ material-icons.css" rel="stylesheet"> -->

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-yellow-500">

    <div class="min-h-screen flex flex-col pt-6 sm:pt-0 overflow-x-hidden">
        <nav class="w-full bg-black">
            <div class="mx-auto flex justify-between items-center py-2">
                <a href="{{ route('posts.index') }}" class="group font-bold text-red-600 text-3xl flex items-center space-x-0 hover:text-green-500 transition">
                    <x-application-logo/>
                    <span class="w-10  text-4xl text-red-600 group-hover:text-green-500 transition ml-16 hover:text-green-500 transition">Rastagram</span>
                </a>

                <div class="flex items-center space-x-4 mr-10">
                    <a href="{{ route('profile.show', auth()->user()) }}" class="font-medium text-yellow-600 hover:text-yellow-500 transition"><x-avatar class="h-12 w-12 inline-block align-bottom" :user="auth()->user()" /><span class="align-[12px] ml-2">{{ Auth::user()->name }}</span></a>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="font-medium text-yellow-600 hover:text-yellow-500 transition ">Déconnexion</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="flex-grow w-full">
            {{ $slot }}
        </main>
    </div>

</body>

</html>