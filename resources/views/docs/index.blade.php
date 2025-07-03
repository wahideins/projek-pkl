<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>{{ $title ?? 'Dokumentasi' }} - Projek PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('ckeditor/style.css') }}">
	<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/45.2.1/ckeditor5.css" crossorigin>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .prose h1 { @apply text-3xl font-bold mb-4 text-gray-800; }
        .prose h2 { @apply text-2xl font-bold mb-3 text-gray-700; }
        .prose p { @apply text-gray-700 leading-relaxed mb-4; }
        .prose a { @apply text-blue-600 hover:underline; }
        .prose code:not(pre code) { @apply bg-gray-200 text-red-600 rounded px-1 py-0.5 text-sm; }
        .prose pre { @apply bg-gray-800 text-white rounded-lg p-4 overflow-x-auto; }
        .prose ul { @apply list-disc list-inside mb-4; }
        .prose ol { @apply list-decimal list-inside mb-4; }
        .notification-message { position: fixed; top: 1rem; right: 1rem; padding: 0.75rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 5000; opacity: 0; transform: translateY(-20px); transition: all 0.3s ease-out; }
        .notification-message.show { opacity: 1; transform: translateY(0); }
        .notification-message.success { background-color: #d1fae5; color: #065f46; border: 1px solid #34d399; }
        .notification-message.error { background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
        .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center; z-index: 4000; visibility: hidden; opacity: 0; transition: visibility 0s, opacity 0.3s; }
        .modal.show { visibility: visible; opacity: 1; }
        .modal-content { background-color: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1); width: 90%; max-width: 600px; transform: translateY(-50px); transition: transform 0.3s ease-out; }
        .modal.show .modal-content { transform: translateY(0); }
             .buttons{
            min-width: 100%;
            margin: 10px;
            display: flex;
            padding: 10px;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn{
            padding: 12px;
            border-radius: 8px;
        }
        .btn-simpan{
            background-color: #45a65a;
            color: white;
        }

        .btn-batal{
            background-color: #00c0ef;
            color: white;
        }
        .btn-hapus{
            background-color: red;
            color: white;
        }
        .judul-halaman{
            margin: 10px 10px 10px 0;
        }
        .judul-halaman h1{
            font-size: 26px
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen flex-col">
        {{-- Header --}}
        <header class="bg-white shadow-sm w-full border-b border-gray-200 z-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">ProjekPKL</a>
                        <div class="hidden md:flex items-center space-x-2 rounded-lg bg-gray-100 p-1">
                            <a href="{{ route('docs', ['category' => 'epesantren']) }}" class="px-3 py-1 text-sm font-medium rounded-md transition-colors {{ $currentCategory == 'epesantren' ? 'bg-white text-gray-800 shadow' : 'text-gray-600 hover:bg-gray-200' }}">Epesantren</a>
                            <a href="{{ route('docs', ['category' => 'adminsekolah']) }}" class="px-3 py-1 text-sm font-medium rounded-md transition-colors {{ $currentCategory == 'adminsekolah' ? 'bg-white text-gray-800 shadow' : 'text-gray-600 hover:bg-gray-200' }}">Admin Sekolah</a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @guest
                            <a href="{{ route('login') }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">Log In</a>
                        @else
                            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-900">Log Out</button></form>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            {{-- Sidebar --}}
            <aside class="w-72 flex-shrink-0 overflow-y-auto bg-stone border-r border-gray-200 p-6">
                @auth
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Navigasi</h2>
                    <button id="add-parent-menu-btn" class="bg-blue-500 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors" title="Tambah Menu Utama Baru">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                @endauth
                <nav id="sidebar-navigation">
                    @include('docs._menu_item', [
                        'items' => $navigation,
                        'editorMode' => auth()->check(),
                        'selectedNavItemId' => $selectedNavItem->menu_id ?? null
                    ])
                </nav>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto p-8 lg:p-12 relative" style="background-color: white">
                @auth
                @if (isset($selectedNavItem))
                    <div class="absolute top-8 right-8 z-10">
                         {{-- Tombol Edit Konten bisa ditambahkan di sini jika perlu --}}
                    </div>
                @endif
                @endauth
                <div class="judul-halaman">
                    <h1> {!! str_replace('-',' ', ucfirst($currentPage)) !!}</h1>
                </div>
                <div class="prose max-w-none" id="documentation-content" >
                    @include($viewPath)
                </div>


            </main>
        </div>
    </div>

    {{-- Modal for Add/Edit Menu --}}
    @auth
    <div id="menu-modal" class="modal">
        <div class="modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4" id="modal-title">Tambah Menu Baru</h3>
            <form id="menu-form">
                <input type="hidden" id="form_menu_id" name="menu_id">
                <input type="hidden" id="form_method" name="_method" value="POST">
                <input type="hidden" id="form_category" name="category" value="{{ $currentCategory }}">

                {{-- Nama Menu --}}
                <div class="mb-4">
                    <label for="form_menu_nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Menu:</label>
                    <input type="text" id="form_menu_nama" name="menu_nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>

                {{-- Ikon --}}
                <div class="mb-4">
                    <label for="form_menu_icon" class="block text-gray-700 text-sm font-bold mb-2">Ikon (Font Awesome Class):</label>
                    <input type="text" id="form_menu_icon" name="menu_icon" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" placeholder="Contoh: fa-solid fa-house">
                </div>
                
                {{-- Parent Menu --}}
                <div class="mb-4">
                    <label for="form_menu_child" class="block text-gray-700 text-sm font-bold mb-2">Parent Menu:</label>
                    <select id="form_menu_child" name="menu_child" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        <option value="0">Tidak Ada (Menu Utama)</option>
                        @foreach ($allParentMenus as $parent)
                            <option value="{{ $parent->menu_id }}">{{ $parent->menu_nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Urutan --}}
                <div class="mb-4">
                    <label for="form_menu_order" class="block text-gray-700 text-sm font-bold mb-2">Urutan:</label>
                    <input type="number" id="form_menu_order" name="menu_order" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="0" required>
                </div>

                {{-- Status --}}
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="form_menu_status" name="menu_status" value="1" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Aktifkan Menu</span>
                    </label>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" id="cancel-menu-form-btn" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit" id="submit-menu-form-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endauth

    {{-- Container untuk notifikasi --}}
    <div id="notification-container"></div>


<script src="https://cdn.ckeditor.com/ckeditor5/45.2.1/ckeditor5.umd.js" crossorigin></script>
<script src="{{ asset('ckeditor/main.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const currentCategory = document.getElementById('form_category')?.value || 'epesantren';

    //=================================
    // UTILITIES
    //=================================
    const showNotification = (message, type = 'success') => {
        const container = document.getElementById('notification-container');
        const notifId = 'notif-' + Date.now();
        const notifDiv = document.createElement('div');
        notifDiv.id = notifId;
        notifDiv.className = `notification-message ${type}`;
        notifDiv.textContent = message;
        container.appendChild(notifDiv);
        
        // Animate in
        setTimeout(() => notifDiv.classList.add('show'), 10);
        // Animate out
        setTimeout(() => {
            notifDiv.classList.remove('show');
            setTimeout(() => notifDiv.remove(), 500); // Remove from DOM after transition
        }, 3000);
    };

    const fetchAPI = async (url, options = {}) => {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        };
        const mergedOptions = { ...defaultOptions, ...options };
        mergedOptions.headers = { ...defaultOptions.headers, ...options.headers };

        try {
            const response = await fetch(url, mergedOptions);
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Fetch API Error:', error);
            showNotification(error.message, 'error');
            throw error;
        }
    };
    
    //=================================
    // MODAL & FORM LOGIC
    //=================================
    const menuModal = document.getElementById('menu-modal');
    const menuForm = document.getElementById('menu-form');
    const modalTitle = document.getElementById('modal-title');

    const openMenuModal = (mode, menuData = null, parentId = 0) => {
        menuForm.reset();
        document.getElementById('form_menu_id').value = '';
        document.getElementById('form_method').value = mode === 'edit' ? 'PUT' : 'POST';

        if (mode === 'create') {
            modalTitle.textContent = 'Tambah Menu Baru';
            document.getElementById('form_menu_child').value = parentId;
            document.getElementById('form_menu_status').checked = true;
        } else if (mode === 'edit' && menuData) {
            modalTitle.textContent = `Edit Menu: ${menuData.menu_nama}`;
            document.getElementById('form_menu_id').value = menuData.menu_id;
            document.getElementById('form_menu_nama').value = menuData.menu_nama;
            document.getElementById('form_menu_icon').value = menuData.menu_icon;
            document.getElementById('form_menu_child').value = menuData.menu_child;
            document.getElementById('form_menu_order').value = menuData.menu_order;
            document.getElementById('form_menu_status').checked = menuData.menu_status == 1;
        }
        menuModal.classList.add('show');
    };

    const closeMenuModal = () => menuModal.classList.remove('show');

    const refreshSidebar = async () => {
        try {
            const data = await fetchAPI(`/api/navigasi/all/${currentCategory}`);
            const sidebarNav = document.getElementById('sidebar-navigation');
            sidebarNav.innerHTML = data.html;
            attachEventListenersToSidebar(); // Re-attach listeners to new content
        } catch (error) {
            showNotification('Gagal memuat ulang sidebar.', 'error');
        }
    };

    //=================================
    // EVENT LISTENERS
    //=================================
    const attachEventListenersToSidebar = () => {
        // Edit button
        document.querySelectorAll('.edit-menu-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                e.stopPropagation();
                const menuId = e.currentTarget.dataset.menuId;
                try {
                    const menuData = await fetchAPI(`/api/navigasi/${menuId}`);
                    openMenuModal('edit', menuData);
                } catch (error) {
                    showNotification('Gagal memuat data menu.', 'error');
                }
            });
        });

        // Delete button
        document.querySelectorAll('.delete-menu-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                const menuId = e.currentTarget.dataset.menuId;
                const menuNama = e.currentTarget.dataset.menuNama;
                if (confirm(`Yakin ingin menghapus menu "${menuNama}"? Ini akan menghapus semua sub-menunya.`)) {
                    fetchAPI(`/api/navigasi/${menuId}`, { method: 'DELETE' })
                        .then(data => {
                            showNotification(data.success, 'success');
                            refreshSidebar();
                        });
                }
            });
        });

        // Add Child button
        document.querySelectorAll('.add-child-menu-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                const parentId = e.currentTarget.dataset.parentId;
                openMenuModal('create', null, parentId);
            });
        });
        
        // Menu item link
        document.querySelectorAll('.menu-item-link').forEach(link => {
            link.addEventListener('click', function(e) {
                // Allow normal navigation
            });
        });
    };
    
    // Initial listeners for auth-only elements
    if (menuForm) {
        document.getElementById('add-parent-menu-btn').addEventListener('click', () => openMenuModal('create', null, 0));
        document.getElementById('cancel-menu-form-btn').addEventListener('click', closeMenuModal);

        menuForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(menuForm);
            const menuId = formData.get('menu_id');
            const method = document.getElementById('form_method').value;
            
            const url = menuId ? `/api/navigasi/${menuId}` : '/api/navigasi';
            const options = {
                method: method === 'PUT' ? 'POST' : method, // HTML forms don't support PUT, so we use POST and a hidden _method field
                body: JSON.stringify(Object.fromEntries(formData)),
            };
            // For PUT, we add the method override header or include it in the body
            if (method === 'PUT') {
                 options.headers = {'X-HTTP-Method-Override': 'PUT'};
                 let data = Object.fromEntries(formData);
                 data._method = 'PUT';
                 options.body = JSON.stringify(data);
            }

            fetchAPI(url, options)
                .then(data => {
                    showNotification(data.success, 'success');
                    closeMenuModal();
                    refreshSidebar();
                })
                .catch(err => {
                    // Error is already shown by fetchAPI
                });
        });

        attachEventListenersToSidebar();
    }
});
</script>

</body>
</html>