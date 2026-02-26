<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @isset($title)
            {{ ucfirst($title) }} -
        @endisset
        Arfa - {{ config('app.name') }}
    </title>

    @vite(['resources/css/blog.css', 'resources/js/blog.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .card-hover {
            transition: .2s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>

<body class="bg-gray-100">
    {{-- Flash Messages --}}
    @if (Session::has('message') || Session::has('error'))
        <div class="max-w-7xl mx-auto px-6 mt-6" x-data="{ show: true }" x-show="show">
            <div class="rounded-lg border bg-white shadow p-4 flex items-start gap-3">
                <i class="fas {{ Session::has('message') ? 'fa-check text-green-600' : 'fa-exclamation-triangle text-red-600' }} mt-1"></i>
                <p class="text-gray-700 text-sm flex-1">
                    {{ Session::get('message') ?? Session::get('error') }}
                </p>
                <button @click="show=false">
                    <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- Top Bar --}}
    <nav class="w-full bg-blue-600 border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

            <div class="flex items-center gap-6">
                {{-- Logo: Nama Diubah ke Arfa --}}
                <a href="{{ route('webhome') }}"
                    class="font-semibold text-white text-lg hover:text-slate-100 transition-colors">
                    Arfa Portal
                </a>

                {{-- Links --}}
                <ul class="hidden md:flex items-center gap-4 text-sm font-medium text-slate-100">
                    @foreach ($pages_nav as $page)
                        <li>
                            <a href="{{ route('page.show', $page->slug) }}"
                                class="transition-colors
                                {{ request()->routeIs('page.show') && request('slug') == $page->slug
                                    ? 'text-white font-semibold'
                                    : 'text-slate-100/90 hover:text-white' }}">
                                {{ $page->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="flex items-center gap-4">
                {{-- Social Icons --}}
                <div class="hidden md:flex items-center gap-3">
                    @if ($setting->url_fb)
                        <a href="{{ $setting->url_fb }}" target="_blank" class="text-white/80 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12.07C22 6.48 17.52 2 12 2S2 6.48 2 12.07C2 17.1 5.66 21.24 10.44 22v-6.99H7.9v-2.94h2.54V9.84c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.25c-1.23 0-1.62.77-1.62 1.56v1.88h2.76l-.44 2.94h-2.32V22C18.34 21.24 22 17.1 22 12.07z" /></svg>
                        </a>
                    @endif
                    {{-- Icon medsos lainnya tetap sama --}}
                </div>

                {{-- Auth --}}
                @auth
                    @can('admin-login')
                        <a href="{{ route('admin.index') }}" class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-green-600 transition">Dashboard</a>
                    @endcan
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-600 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-400 transition">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Site Header: Judul Utama Diubah --}}
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-6 py-10 text-center">
            <h1 class="text-4xl font-bold text-gray-800">Arfa Blog & Tech</h1>
            <p class="mt-2 text-gray-500 text-sm italic">
                "Menyajikan informasi dari perspektif Arfa"
            </p>
        </div>
    </header>

    {{-- Topics --}}
    <div class="bg-white border-b py-3">
        <div class="max-w-7xl mx-auto px-6">
            @include('front.partials.category-menu', ['categories' => $categories, 'level' => 0, 'orientation' => 'horizontal'])
        </div>
    </div>

    {{-- MAIN CONTENT + SIDEBAR --}}
    <div class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- MAIN CONTENT --}}
            {{ $slot }}

            {{-- SIDEBAR --}}
            @if (!request()->routeIs('page.show'))
                <aside class="w-full lg:w-1/3 flex flex-col space-y-6 lg:top-24 lg:self-start">
                    {{-- About: Nama Arfa di Sidebar --}}
                    <div class="bg-white rounded-xl border shadow-sm p-6 border-l-4 border-blue-600">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">About Arfa</h3>
                        <p class="text-gray-600 text-sm">Selamat datang di portal saya! Nama saya <strong>Arfa</strong>, dan melalui website ini saya berbagi kabar terbaru dan pemikiran menarik setiap harinya.</p>
                    </div>

                    {{-- Tags --}}
                    <div class="bg-white rounded-xl border shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <a href="{{ route('tag.show', $tag->name) }}" class="px-3 py-1 text-xs border rounded-full bg-gray-50 text-gray-700 hover:bg-gray-100">#{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            @endif
        </div>
    </div>

    {{-- FOOTER: Copyright Diubah --}}
    <footer class="bg-white border-t mt-10">
        <div class="max-w-7xl mx-auto px-6 py-8 text-center text-sm text-gray-600">
            <p class="mb-4">
                @foreach ($pages_footer as $page)
                    <a href="{{ route('page.show', $page->slug) }}" class="px-3 hover:text-gray-900 transition-colors"> {{ $page->name }} </a>
                @endforeach
            </p>
            <p class="text-gray-500 text-xs">
                &copy; {{ date('Y') }} <strong>Arfa</strong> Development. All Rights Reserved.
            </p>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
</body>
</html>