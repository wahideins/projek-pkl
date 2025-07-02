<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dokumentasi' }} - Projek PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; }
        /* Styling dasar untuk konten dari file markdown */
        .prose h1 { @apply text-3xl font-bold mb-4 text-gray-800; }
        .prose h2 { @apply text-2xl font-bold mb-3 text-gray-700; }
        .prose p { @apply text-gray-700 leading-relaxed mb-4; }
        .prose a { @apply text-blue-600 hover:underline; }
        .prose code { @apply bg-gray-200 text-red-600 rounded px-1 py-0.5; }
        .prose pre { @apply bg-gray-800 text-white rounded-lg p-4 overflow-x-auto; }
        .prose ul { @apply list-disc list-inside mb-4; }
        .prose ol { @apply list-decimal list-inside mb-4; }
        .prose blockquote { @apply border-l-4 border-gray-300 pl-4 italic text-gray-600 my-4; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm w-full border-b border-gray-200 z-10">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">ProjekPKL</a>
                        <div class="hidden md:flex items-center space-x-2 rounded-lg bg-gray-100 p-1">
                            <a href="{{ route('docs', ['category' => 'epesantren']) }}" class="px-3 py-1 text-sm font-medium rounded-md transition-colors {{ ($currentCategory ?? 'epesantren') == 'epesantren' ? 'bg-white text-gray-800 shadow' : 'text-gray-600 hover:bg-gray-200' }}">Epesantren</a>
                            <a href="{{ route('docs', ['category' => 'adminsekolah']) }}" class="px-3 py-1 text-sm font-medium rounded-md transition-colors {{ ($currentCategory ?? '') == 'adminsekolah' ? 'bg-white text-gray-800 shadow' : 'text-gray-600 hover:bg-gray-200' }}">Admin Sekolah</a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @guest
                            <a href="{{ route('login') }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">Log In</a>
                        @endguest
                        @auth
                            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-900">Log Out</button></form>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar Navigasi -->
            <aside class="w-72 flex-shrink-0 overflow-y-auto bg-white border-r border-gray-200 p-6 hidden md:block">
                <nav>
                    <ul class="w-full">
                        @include('docs._menu_item', ['items' => $navigation])
                    </ul>
                </nav>
            </aside>

            <!-- Konten Dokumentasi (Hanya untuk Tampilan) -->
            <main>
                <div class="prose max-w-none" style="max-width:75vw; margin:10px; min-height:100%;">
                    @include($content)
                </div>
            </main>
        </div>
    </body>
</html>
