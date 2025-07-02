<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Halaman - Projek PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Styling untuk CKEditor agar terlihat bagus dengan Tailwind */
        .ck-editor__editable_inline {
            min-height: 400px; /* Tinggi minimum untuk editor */
            border: 1px solid #e2e8f0; /* border-gray-200 */
            border-radius: 0.375rem; /* rounded-md */
            padding: 0.75rem 1rem; /* py-3 px-4 */
            background-color: #ffffff; /* bg-white */
        }
        .ck-toolbar {
            border: 1px solid #e2e8f0; /* border-gray-200 */
            border-bottom: none;
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
            background-color: #f7fafc; /* bg-gray-50 */
        }
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
                        <span class="text-xl text-gray-700 font-semibold ml-4">Editor Halaman</span>
                    </div>
                    <div class="flex items-center">
                        <a href="{{ route('navigasi.index') }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 mr-4">Kembali ke Manajemen Navigasi</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar Navigasi -->
            <aside class="w-72 flex-shrink-0 overflow-y-auto bg-white border-r border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pilih Halaman untuk Diedit</h2>
                <nav>
                    <ul class="w-full">
                        {{-- Menggunakan partial _menu_item untuk membangun menu hierarkis --}}
                        @include('docs._menu_item', [
                            'items' => $navigation,
                            'editorMode' => true,
                            'selectedNavItemId' => $selectedNavItem->menu_id ?? null
                        ])
                    </ul>
                </nav>
            </aside>

            <!-- Konten Editor CKEditor -->
            <main class="flex-1 overflow-y-auto p-8 lg:p-12">
                <div class="bg-white p-6 rounded-lg shadow-md h-full flex flex-col">
                    <h2 class="text-xl font-bold text-gray-800 mb-4" id="editor-title">Pilih Menu untuk Diedit Kontennya</h2>
                    <div id="loading-indicator" class="text-center text-gray-500 hidden mb-4">Memuat konten...</div>
                    <div id="editor-container" class="flex-1 flex flex-col">
                        <textarea id="menu_content_editor" name="menu_content" class="w-full flex-1"></textarea>
                        <input type="hidden" id="current_menu_id" value="{{ $selectedNavItem->menu_id ?? '' }}">
                    </div>
                    <div class="mt-6 text-right">
                        <button id="save-content-btn" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 focus:outline-none focus:shadow-outline transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>Simpan Perubahan</button>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Message Box for Notifications --}}
    <div id="notification-box" class="notification-message"></div>

    <script>
        let editor; // Variabel global untuk instance CKEditor

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'success') {
            const notificationBox = document.getElementById('notification-box');
            notificationBox.textContent = message;
            notificationBox.className = `notification-message show ${type}`; // Reset classes and add new ones

            setTimeout(() => {
                notificationBox.classList.remove('show');
            }, 3000); // Hilangkan setelah 3 detik
        }

        // Fungsi untuk memuat konten menu ke editor
        async function loadMenuContent(menuId) {
            console.log('Mencoba memuat konten menu untuk menuId:', menuId); // Log untuk debugging

            const editorTitle = document.getElementById('editor-title');
            const loadingIndicator = document.getElementById('loading-indicator');
            const saveButton = document.getElementById('save-content-btn');
            const currentMenuIdInput = document.getElementById('current_menu_id');

            // Hapus highlight dari semua menu item yang sebelumnya aktif
            document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('bg-gray-200', 'font-semibold'));

            editorTitle.textContent = 'Memuat Konten...';
            loadingIndicator.classList.remove('hidden');
            saveButton.disabled = true;
            currentMenuIdInput.value = ''; // Kosongkan ID saat memuat

            if (editor) {
                editor.setData(''); // Bersihkan editor saat memuat konten baru
            }

            // Periksa apakah menuId valid sebelum melakukan fetch
            if (!menuId || typeof menuId !== 'string' || menuId.trim() === '') {
                console.error('ID menu tidak valid atau kosong:', menuId);
                editorTitle.textContent = 'Pilih Menu untuk Diedit Kontennya';
                showNotification('ID menu tidak valid. Silakan pilih menu yang valid.', 'error');
                loadingIndicator.classList.add('hidden');
                saveButton.disabled = true;
                if (editor) {
                    editor.setData('');
                }
                return; // Hentikan eksekusi jika ID tidak valid
            }

            try {
                const response = await fetch(`/api/navigasi/${menuId}/content`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                editorTitle.textContent = `Editor Halaman: ${data.menu_nama}`;
                if (editor) {
                    editor.setData(data.menu_content);
                }
                currentMenuIdInput.value = data.menu_id;
                saveButton.disabled = false; // Aktifkan tombol simpan setelah konten dimuat

                // Tambahkan highlight ke menu item yang baru dipilih
                const selectedMenuItem = document.querySelector(`.menu-item[data-menu-id="${menuId}"]`);
                if (selectedMenuItem) {
                    selectedMenuItem.classList.add('bg-gray-200', 'font-semibold');
                }

            } catch (error) {
                console.error('Gagal memuat konten menu:', error);
                editorTitle.textContent = 'Gagal Memuat Konten';
                showNotification('Gagal memuat konten menu. Silakan coba lagi.', 'error');
                if (editor) {
                    editor.setData('<p>Gagal memuat konten.</p>');
                }
            } finally {
                loadingIndicator.classList.add('hidden');
            }
        }

        // Fungsi untuk menyimpan konten editor
        async function saveMenuContent() {
            console.log('Mencoba menyimpan konten.'); // Log untuk debugging

            const menuId = document.getElementById('current_menu_id').value;
            const content = editor.getData();
            const saveButton = document.getElementById('save-content-btn');

            // Periksa apakah menuId valid sebelum menyimpan
            if (!menuId || typeof menuId !== 'string' || menuId.trim() === '') {
                showNotification('Tidak ada menu yang dipilih untuk disimpan atau ID menu tidak valid.', 'error');
                return;
            }

            saveButton.disabled = true;
            saveButton.textContent = 'Menyimpan...';

            try {
                const response = await fetch(`/api/navigasi/${menuId}/content`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                    },
                    body: JSON.stringify({ menu_content: content })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }

                showNotification(data.message || 'Konten berhasil disimpan!', 'success');
            } catch (error) {
                console.error('Gagal menyimpan konten:', error);
                showNotification(`Gagal menyimpan konten: ${error.message}`, 'error');
            } finally {
                saveButton.disabled = false;
                saveButton.textContent = 'Simpan Perubahan';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi CKEditor
            ClassicEditor
                .create( document.querySelector( '#menu_content_editor' ), {
                    // Konfigurasi CKEditor
                    // Misalnya: toolbar: { items: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo'] }
                } )
                .then( newEditor => {
                    editor = newEditor;
                    // Muat konten awal jika ada menu yang dipilih dari URL
                    const initialMenuId = document.getElementById('current_menu_id').value;
                    if (initialMenuId) {
                        loadMenuContent(initialMenuId);
                    } else {
                        // Jika tidak ada menu yang dipilih, nonaktifkan tombol simpan
                        document.getElementById('save-content-btn').disabled = true;
                    }
                } )
                .catch( error => {
                    console.error( error );
                } );

            // Event listener untuk klik pada item menu di sidebar
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', (event) => {
                    event.preventDefault(); // Mencegah navigasi default
                    const menuId = event.currentTarget.dataset.menuId;

                    // Highlight akan ditangani di dalam loadMenuContent
                    loadMenuContent(menuId);
                });
            });

            // Event listener untuk tombol simpan
            document.getElementById('save-content-btn').addEventListener('click', saveMenuContent);
        });
    </script>
</body>
</html>
