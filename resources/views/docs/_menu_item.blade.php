@foreach($items as $item)
<div class="my-1 group">
    <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-white transition-colors {{ (isset($selectedNavItemId) && $selectedNavItemId == $item->menu_id) ? 'bg-blue-100 font-semibold' : '' }}">
        <a href="{{ $item->menu_link }}" class="menu-item-link flex items-center flex-1 space-x-3">
            @if($item->menu_icon)<i class="{{ $item->menu_icon }} w-4 text-center"></i>@else<span class="w-4"></span>@endif
            <span>{{ $item->menu_nama }}</span>
        </a>

        @if(isset($editorMode) && $editorMode)
        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
            {{-- Tombol Tambah Anak (hanya untuk level 1) --}}
            @if ($item->menu_child >= 0)
                <button data-parent-id="{{ $item->menu_id }}" class="add-child-menu-btn text-green-500 hover:text-green-700 p-1" title="Tambah Sub Menu">
                    <i class="fa-solid fa-plus-circle"></i>
                </button>
            @endif
            {{-- Tombol Edit --}}
            <button data-menu-id="{{ $item->menu_id }}" class="edit-menu-btn text-blue-500 hover:text-blue-700 p-1" title="Edit Menu">
                <i class="fa-solid fa-pencil"></i>
            </button>
            {{-- Tombol Hapus --}}
            <button data-menu-id="{{ $item->menu_id }}" data-menu-nama="{{ $item->menu_nama }}" class="delete-menu-btn text-red-500 hover:text-red-700 p-1" title="Hapus Menu">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
        @endif
    </div>

    @if(!empty($item->children))
        <div class="pl-6 mt-1 border-l border-gray-200">
            {{-- Panggil partial ini secara rekursif untuk anak-anaknya --}}
            @include('docs._menu_item', [
                'items' => $item->children,
                'editorMode' => $editorMode ?? false,
                'selectedNavItemId' => $selectedNavItemId ?? null
            ])
        </div>
    @endif
</div>
@endforeach