@foreach($items as $item)
@php
    $hasActualChildren = isset($item->children) && is_array($item->children) && count($item->children) > 0;
    $isActiveParent = in_array($item->menu_id, $selectedParentIds ?? []);
    $isActiveItem = isset($selectedNavItemId) && $selectedNavItemId == $item->menu_id;
    $isExpanded = $isActiveParent || $isActiveItem;
    $linkHref = $hasActualChildren ? '#' : $item->menu_link;
@endphp

<div class="my-1 group">
    {{-- Baris menu utama --}}
    <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-white transition-colors
        {{ $isActiveItem ? 'bg-blue-100 font-semibold' : '' }}
        {{ $hasActualChildren ? 'cursor-pointer menu-parent-toggle' : 'cursor-pointer' }}"
        @if($hasActualChildren) data-menu-id="{{ $item->menu_id }}" @endif
    >
        <a href="{{ $linkHref }}" class="menu-item-link flex items-center flex-1 space-x-3">
            @if($item->menu_icon)
                <i class="{{ $item->menu_icon }} w-4 text-center"></i>
            @else
                <span class="w-4"></span>
            @endif
            <span>{{ $item->menu_nama }}</span>
        </a>

        @if($hasActualChildren)
            <span class="menu-arrow transform transition-transform duration-200 {{ $isExpanded ? 'rotate-90' : '' }}">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </span>
        @endif

        @if(isset($editorMode) && $editorMode)
        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity ml-2">
            <button data-parent-id="{{ $item->menu_id }}" class="add-child-menu-btn text-green-500 hover:text-green-700 p-1" title="Tambah Sub Menu">
                <i class="fa-solid fa-plus-circle"></i>
            </button>
            <button data-menu-id="{{ $item->menu_id }}" class="edit-menu-btn text-blue-500 hover:text-blue-700 p-1" title="Edit Menu">
                <i class="fa-solid fa-pencil"></i>
            </button>
            <button data-menu-id="{{ $item->menu_id }}" data-menu-nama="{{ $item->menu_nama }}" class="delete-menu-btn text-red-500 hover:text-red-700 p-1" title="Hapus Menu">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
        @endif
    </div>

    {{-- Sub-menu (dropdown item) --}}
    @if($hasActualChildren)
    <div id="sub-menu-{{ $item->menu_id }}"
         class="pl-6 mt-1 border-l border-gray-200 overflow-hidden transition-all duration-300 ease-in-out {{ $isExpanded ? 'max-h-screen' : 'max-h-0' }}">
        @include('docs._menu_item', [
            'items' => $item->children,
            'editorMode' => $editorMode ?? false,
            'selectedNavItemId' => $selectedNavItemId ?? null,
            'selectedParentIds' => $selectedParentIds ?? []
        ])
    </div>
    @endif
</div>
@endforeach
