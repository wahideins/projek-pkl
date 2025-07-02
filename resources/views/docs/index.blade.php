<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dokumentasi' }} - Projek PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- CKEditor 5 CDN Dihapus --}}
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

        /* Styling untuk pesan notifikasi */
        .notification-message {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }
        .notification-message.show {
            opacity: 1;
            transform: translateY(0);
        }
        .notification-message.success {
            background-color: #d1fae5; /* green-100 */
            color: #065f46; /* green-800 */
            border: 1px solid #34d399; /* green-400 */
        }
        .notification-message.error {
            background-color: #fee2e2; /* red-100 */
            color: #991b1b; /* red-800 */
            border: 1px solid #ef4444; /* red-400 */
        }

        /* Modal specific styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.3s;
        }
        .modal.show {
            visibility: visible;
            opacity: 1;
        }
        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            transform: translateY(-50px);
            transition: transform 0.3s ease-out;
        }
        .modal.show .modal-content {
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen flex-col">
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

        <div class="flex flex-1 overflow-hidden">
            <aside class="w-72 flex-shrink-0 overflow-y-auto bg-white border-r border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Navigasi</h2>
                    <button id="add-parent-menu-btn" class="bg-blue-500 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors" title="Tambah Menu Utama Baru">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <nav id="sidebar-navigation">
                    <ul class="w-full">
                        {{-- Menggunakan partial _menu_item untuk membangun menu hierarkis --}}
                        @include('docs._menu_item', [
                            'items' => $navigation,
                            'editorMode' => true, // Aktifkan mode editor
                            'selectedNavItemId' => $selectedNavItem->menu_id ?? null // Kirim ID menu yang sedang dipilih
                        ])
                    </ul>
                </nav>
            </aside>

            <!-- Konten Dokumentasi (Hanya untuk Tampilan) -->
            <main>
                <div class="prose max-w-none" style="max-width:75vw; margin:10px; min-height:100%;">
                    @include($content)
            <main class="flex-1 overflow-y-auto p-8 lg:p-12 relative">
                {{-- Tombol Edit Halaman Konten (jika masih ingin link ke halaman edit) --}}
                @if (isset($selectedNavItem) && $selectedNavItem->menu_id)
                    <div class="absolute top-8 right-8 z-10">
                        <a href="{{ url('docs') }}/{{ $selectedNavItem->menu_link }}?category={{ $currentCategory ?? 'epesantren' }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center space-x-2 transition-colors">
                            <span>Lihat Halaman Ini</span>
                        </a>
                    </div>
                @endif

                <div class="prose max-w-none" id="documentation-content">
                    {{-- Konten dari $content yang sudah di-pass dari controller --}}
                    {!! $content !!}
                </div>

                {{-- CKEditor panel sepenuhnya dihapus --}}
            </main>
        </div>
    </body>
</html>
