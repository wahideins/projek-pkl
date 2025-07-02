@foreach($items as $item)
<li class="my-1 group"> {{-- Tambahkan 'group' untuk styling hover --}}
    <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 transition-colors
        {{ (isset($editorMode) && isset($selectedNavItemId) && $selectedNavItemId == $item->menu_id) ? 'bg-gray-200 font-semibold' : '' }}">

        @if(isset($editorMode) && $editorMode)
            {{-- Mode Editor: Gunakan data-menu-id untuk AJAX load --}}
            <a href="#"
               data-menu-id="{{ $item->menu_id }}"
               class="menu-item flex items-center flex-1"> {{-- flex-1 agar link mengisi ruang --}}
                <i class="{{ $item->menu_icon }} mr-3"></i>
                <span>{{ $item->menu_nama }}</span>
            </a>
            <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                {{-- Tombol Tambah Anak (hanya jika bukan child dari child) --}}
                @if ($item->menu_child == 0) {{-- Asumsi parent menu saja yang bisa punya tombol add child di UI ini --}}
                    <button data-parent-id="{{ $item->menu_id }}" class="add-child-menu-btn text-green-500 hover:text-green-700 p-1 rounded-full" title="Tambah Sub Menu">
                        <i class="fa fa-plus-circle"></i>
                    </button>
                @endif
                {{-- Tombol Edit --}}
                <button data-menu-id="{{ $item->menu_id }}" class="edit-menu-btn text-blue-500 hover:text-blue-700 p-1 rounded-full" title="Edit Menu">
                    <i class="fa fa-pencil"></i>
                </button>
                {{-- Tombol Hapus --}}
                <button data-menu-id="{{ $item->menu_id }}" data-menu-nama="{{ $item->menu_nama }}" class="delete-menu-btn text-red-500 hover:text-red-700 p-1 rounded-full" title="Hapus Menu">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        @else
            {{-- Mode Navigasi Publik: Gunakan href normal dan highlight berdasarkan URL --}}
            <a href="{{ $item->menu_link !== '#' ? url($item->menu_link) : '#' }}"
               class="flex items-center flex-1
                   {{ request()->is(trim(parse_url($item->menu_link, PHP_URL_PATH) ?? '', '/')) ? 'bg-gray-200 font-semibold' : '' }}">
                <i class="{{ $item->menu_icon }} mr-3"></i>
                <span>{{ $item->menu_nama }}</span>
            </a>
        @endif
    </div>

    @if(isset($item->children) && count($item->children) > 0)
        <ul class="pl-6 mt-1">
            {{-- Rekursif memanggil partial yang sama untuk anak-anak --}}
            @include('docs._menu_item', [
                'items' => $item->children,
                'editorMode' => $editorMode ?? false,
                'selectedNavItemId' => $selectedNavItemId ?? null
            ])
        </ul>
    @endif
</li>
@endforeach
