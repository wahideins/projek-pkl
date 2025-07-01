@foreach($items as $item)
<li class="my-1">
    <a href="{{ $item->menu_link !== '#' ? url($item->menu_link) : '#' }}" 
       class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100">
        <i class="{{ $item->menu_icon }} mr-3"></i>
        <span>{{ $item->menu_nama }}</span>
    </a>
    @if(isset($item->children) && count($item->children) > 0)
        <ul class="pl-6 mt-1">
            @include('docs._menu_item', ['items' => $item->children])
        </ul>
    @endif
</li>
@endforeach
