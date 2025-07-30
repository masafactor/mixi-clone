<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Top Page</title>
    @vite(['resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="relative p-6 text-right">


        @guest
    <a href="{{ route('login') }}" class="text-blue-600">ログイン</a>
    <a href="{{ route('register') }}" class="text-blue-600">登録</a>

    {{-- GitHubログインボタン --}}
    <a href="{{ url('/auth/github') }}"
       class="inline-block mt-4 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 .5C5.7.5.6 5.6.6 12a11.5 11.5 0 008 11c.6.1.8-.2.8-.5v-2c-3.3.7-4-1.6-4-1.6-.6-1.5-1.3-1.9-1.3-1.9-1-.6.1-.6.1-.6 1.1.1 1.7 1.1 1.7 1.1 1 .1 1.7-.8 2-1.2.2-.8.5-1.4 1-1.8-2.6-.3-5.3-1.3-5.3-6A4.7 4.7 0 016.4 6c-.1-.3-.5-1.3.1-2.7 0 0 1-.3 3.2 1a11 11 0 015.6 0c2.2-1.3 3.2-1 3.2-1 .6 1.4.2 2.4.1 2.7A4.7 4.7 0 0119 9.5c0 4.7-2.8 5.6-5.4 5.9.5.4 1 1.2 1 2.4v3.6c0 .3.2.6.8.5a11.5 11.5 0 008-11c0-6.4-5.1-11.5-11.5-11.5z"/>
        </svg>
        GitHubでログイン
    </a>
@endguest
    </div>

    <div class="text-center mt-10">
        <h1 class="text-3xl font-bold">Mixiクローンへようこそ</h1>
        <p class="mt-4">ここにアプリの説明などを入れてください。</p>
        @auth
    <p>ログイン中：{{ Auth::user()->name }}</p>
@endauth
    </div>
</body>
</html>
