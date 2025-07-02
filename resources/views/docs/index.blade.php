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
            </main>
        </div>
            {{-- Modal for Add/Edit Menu --}}
    <div id="menu-modal" class="modal">
        <div class="modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4" id="modal-title">Tambah Menu Baru</h3>
            <form id="menu-form" method="POST"> {{-- action akan diisi oleh JS --}}
                @csrf
                <input type="hidden" id="form_menu_id" name="menu_id">
                <input type="hidden" id="form_method" name="_method" value="POST"> {{-- Default for POST --}}
                <input type="hidden" id="form_category" name="category" value="{{ $currentCategory ?? 'epesantren' }}">
                <input type="hidden" name="menu_content" value=""> {{-- Tetap ada jika kolom ini di database --}}

                <div class="mb-4">
                    <label for="form_menu_nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Menu:</label>
                    <input type="text" id="form_menu_nama" name="menu_nama"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                                  @error('menu_nama') border-red-500 @enderror"
                           value="{{ old('menu_nama') }}" placeholder="Contoh: Beranda" required>
                    @error('menu_nama')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="form_menu_link" class="block text-gray-700 text-sm font-bold mb-2">Link:</label>
                    <input type="text" id="form_menu_link" name="menu_link"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                                  @error('menu_link') border-red-500 @enderror"
                           value="{{ old('menu_link') }}" placeholder="Contoh: /home atau /docs/epesantren" required>
                    <p class="text-gray-600 text-xs italic mt-1">Contoh: /docs/epesantren/pengenalan</p>
                    @error('menu_link')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="form_menu_icon" class="block text-gray-700 text-sm font-bold mb-2">Ikon (Font Awesome Class):</label>
                    <input type="text" id="form_menu_icon" name="menu_icon"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                                  @error('menu_icon') border-red-500 @enderror"
                           value="{{ old('menu_icon') }}" placeholder="Contoh: fa-home, fa-user, fa-cogs">
                    <p class="text-gray-600 text-xs italic mt-1">Kosongkan jika tidak ada ikon. Contoh: `fa-home`</p>
                    @error('menu_icon')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dropdown Parent Menu yang diisi langsung oleh Blade --}}
                <div class="mb-4">
                    <label for="form_menu_child" class="block text-gray-700 text-sm font-bold mb-2">Parent Menu:</label>
                    <select id="form_menu_child" name="menu_child"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                                   @error('menu_child') border-red-500 @enderror">
                        <option value="0">Tidak Ada (Menu Utama)</option>
                        {{-- Opsi ini sekarang diisi langsung dari $allParentMenus yang di-pass dari controller --}}
                        @if (isset($allParentMenus))
                            @foreach ($allParentMenus as $parent)
                                <option value="{{ $parent->menu_id }}"
                                    {{ old('menu_child') == $parent->menu_id ? 'selected' : '' }}
                                    {{ (isset($editingMenu) && $editingMenu->menu_child == $parent->menu_id) ? 'selected' : '' }}>
                                    {{ $parent->menu_nama }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <p class="text-gray-600 text-xs italic mt-1">Pilih menu utama jika ini adalah sub-menu.</p>
                    @error('menu_child')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="form_menu_order" class="block text-gray-700 text-sm font-bold mb-2">Urutan (Order):</label>
                    <input type="number" id="form_menu_order" name="menu_order"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                                  @error('menu_order') border-red-500 @enderror"
                           value="{{ old('menu_order', (isset($editingMenu) ? $editingMenu->menu_order : 0)) }}" placeholder="Contoh: 1" required>
                    <p class="text-gray-600 text-xs italic mt-1">Angka lebih kecil akan muncul lebih dulu.</p>
                    @error('menu_order')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <input type="checkbox" id="form_menu_status" name="menu_status" value="1"
                           class="mr-2 leading-tight" {{ (old('menu_status', (isset($editingMenu) ? $editingMenu->menu_status : false))) ? 'checked' : '' }}>
                    <label for="form_menu_status" class="text-sm text-gray-700">Aktifkan Menu (akan terlihat di navigasi publik)</label>
                    @error('menu_status')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <button type="button" id="cancel-menu-form-btn" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">Batal</button>
                    <button type="submit" id="submit-menu-form-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Message Box for Notifications --}}
    @if (session('success') || session('error'))
        <div id="notification-box" class="notification-message show {{ session('success') ? 'success' : 'error' }}">
            {{ session('success') ?? session('error') }}
        </div>
    @endif

    <script>
        /**
         * Get the current category from the hidden input.
         * @returns {string} The current category.
         */
        function getCurrentCategory() {
            return document.getElementById('form_category')?.value || 'epesantren';
        }

        /**
         * Shows a notification message.
         * @param {string} message - The message to display.
         * @param {string} type - The type of notification ('success' or 'error').
         */
        function showNotification(message, type = 'success') {
            const notificationBox = document.getElementById('notification-box');
            if (!notificationBox) return; // Guard clause

            notificationBox.textContent = message;
            notificationBox.className = `notification-message show ${type}`;

            setTimeout(() => {
                notificationBox.classList.remove('show');
            }, 3000);
        }

        /**
         * Fungsi untuk menampilkan modal Add/Edit.
         * @param {string} mode - 'create' atau 'edit'.
         * @param {object|null} menuData - Objek data menu untuk mode 'edit'.
         * @param {number} parentId - ID parent untuk mode 'create' (default 0).
         */
        function openMenuModal(mode, menuData = null, parentId = 0) {
            const menuModal = document.getElementById('menu-modal');
            const menuForm = document.getElementById('menu-form');
            const modalTitle = document.getElementById('modal-title');

            // Ambil semua elemen form
            const formMenuId = document.getElementById('form_menu_id');
            const formMethod = document.getElementById('form_method');
            const formMenuNama = document.getElementById('form_menu_nama');
            const formMenuLink = document.getElementById('form_menu_link');
            const formMenuIcon = document.getElementById('form_menu_icon');
            const formMenuChild = document.getElementById('form_menu_child'); // Elemen select
            const formMenuOrder = document.getElementById('form_menu_order');
            const formMenuStatus = document.getElementById('form_menu_status');
            const formCategory = document.getElementById('form_category');

            // Reset form fields
            menuForm.reset();
            // Sembunyikan semua pesan error validasi Blade (dari submission sebelumnya)
            document.querySelectorAll('.text-red-500.text-xs.italic.mt-1').forEach(el => el.classList.add('hidden'));

            if (mode === 'create') {
                modalTitle.textContent = 'Tambah Menu Baru';
                menuForm.action = "{{ url('navigasi') }}"; // URL eksplisit untuk store
                formMethod.value = 'POST';
                formMenuId.value = '';
                formMenuOrder.value = 0;
                formMenuStatus.checked = false;
                formMenuChild.value = parentId; // Set parentId jika membuat child
                formCategory.value = getCurrentCategory(); // Pastikan kategori terisi untuk menu baru

                // Clear old input values for create mode
                formMenuNama.value = '';
                formMenuLink.value = '';
                formMenuIcon.value = '';
            } else if (mode === 'edit' && menuData) {
                modalTitle.textContent = `Edit Menu: ${menuData.menu_nama}`;
                menuForm.action = `{{ url('navigasi') }}/${menuData.menu_id}`; // URL eksplisit untuk update
                formMethod.value = 'PUT'; // Set ke PUT untuk penanganan _method Laravel
                formMenuId.value = menuData.menu_id;
                formMenuNama.value = menuData.menu_nama;
                formMenuLink.value = menuData.menu_link;
                formMenuIcon.value = menuData.menu_icon;
                formMenuOrder.value = menuData.menu_order;
                formMenuStatus.checked = menuData.menu_status == 1;
                formCategory.value = menuData.category; // Set kategori dari data menu

                // Pilih parent yang benar di dropdown
                formMenuChild.value = menuData.menu_child;
            }
            menuModal.classList.add('show');
        }

        function closeMenuModal() {
            document.getElementById('menu-modal').classList.remove('show');
        }

        // Event listener untuk tombol "Tambah Menu Utama Baru"
        document.getElementById('add-parent-menu-btn').addEventListener('click', () => openMenuModal('create', null, 0));

        // Event listener untuk tombol "Batal" di modal
        document.getElementById('cancel-menu-form-btn').addEventListener('click', closeMenuModal);

        document.addEventListener('DOMContentLoaded', () => {
            // Tampilkan notifikasi dari session Laravel jika ada
            const notificationBox = document.getElementById('notification-box');
            if (notificationBox && notificationBox.classList.contains('show')) {
                setTimeout(() => {
                    notificationBox.classList.remove('show');
                }, 3000);
            }

            // Event listeners untuk tombol Edit/Delete/Add Child pada sidebar
            document.querySelectorAll('.edit-menu-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation(); // Mencegah klik pada link parent
                    const menuId = event.currentTarget.dataset.menuId;

                    // Mengambil data menu dari backend untuk mengisi modal edit
                    // Menggunakan URL eksplisit 'navigasi/{id}/get-data'
                    fetch(`{{ url('navigasi') }}/${menuId}/get-data`)
                        .then(response => {
                            if (!response.ok) {
                                console.error('Gagal mengambil data menu:', response.statusText);
                                showNotification('Gagal memuat data menu untuk diedit. Status: ' + response.status, 'error');
                                throw new Error('Network response was not ok.');
                            }
                            return response.json();
                        })
                        .then(menuData => {
                            // Panggil openMenuModal dengan data yang diterima
                            openMenuModal('edit', menuData);
                        })
                        .catch(error => {
                            console.error('Ada masalah dengan operasi fetch:', error);
                            showNotification(`Gagal memuat data menu: ${error.message}`, 'error');
                        });
                });
            });

            document.querySelectorAll('.delete-menu-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    const menuId = event.currentTarget.dataset.menuId;
                    const menuNama = event.currentTarget.dataset.menuNama;

                    if (confirm(`Apakah Anda yakin ingin menghapus menu "${menuNama}"? Ini akan menghapus semua sub-menu-nya juga.`)) {
                        // Untuk operasi DELETE, kita buat form tersembunyi dan submit
                        const deleteForm = document.createElement('form');
                        deleteForm.action = `{{ url('navigasi') }}/${menuId}`; // URL eksplisit untuk delete
                        deleteForm.method = 'POST'; // Laravel menggunakan POST untuk DELETE dengan _method
                        deleteForm.innerHTML = `
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="category" value="${getCurrentCategory()}">
                        `;
                        document.body.appendChild(deleteForm);
                        deleteForm.submit(); // Ini akan menyebabkan halaman di-refresh total
                    }
                });
            });

            document.querySelectorAll('.add-child-menu-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation(); // Mencegah klik pada link parent
                    const parentId = event.currentTarget.dataset.parentId;
                    openMenuModal('create', null, parentId);
                });
            });

            // Event listener untuk klik pada item menu di sidebar (untuk navigasi ke konten)
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', (event) => {
                    event.preventDefault();
                    const menuLink = event.currentTarget.getAttribute('href'); // Ambil href sebenarnya
                    // Ini akan mengarahkan browser ke rute yang memuat konten, menyebabkan refresh halaman
                    window.location.href = menuLink; // Navigasi langsung menggunakan link
                });
            });

            // Handle displaying modal if there were validation errors or an edit operation was attempted
            @if ($errors->any() || session('editingMenuData'))
                @php
                    $menuDataToFillModal = null;
                    if (session('editingMenuData')) {
                        $menuDataToFillModal = session('editingMenuData');
                    } else if (old('menu_id')) {
                        $menuDataToFillModal = (object)[
                            'menu_id' => old('menu_id'),
                            'menu_nama' => old('menu_nama'),
                            'menu_link' => old('menu_link'),
                            'menu_icon' => old('menu_icon'),
                            'menu_child' => old('menu_child'),
                            'menu_order' => old('menu_order'),
                            'menu_status' => old('menu_status', 0),
                            'category' => old('category'),
                        ];
                    }
                @endphp
                // Panggil openMenuModal dengan data yang sudah disiapkan
                openMenuModal('{{ session('editingMenuData') ? 'edit' : 'create' }}', {!! json_encode($menuDataToFillModal) !!});
            @endif
        });
    </script>
    </body>
</html>
