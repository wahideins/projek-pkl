<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Navigasi - Projek PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Navigasi</h1>
            <a href="{{ route('navigasi.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tambah Navigasi Baru</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Nama Menu</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Link</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Ikon</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Parent ID</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Order</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($navigationItems as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $item->menu_id }}</td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $item->menu_nama }}</td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $item->menu_link }}</td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">
                                @if ($item->menu_icon)
                                    <i class="fa {{ $item->menu_icon }} mr-1"></i> {{ $item->menu_icon }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $item->menu_child }}</td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $item->menu_order }}</td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $item->menu_status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->menu_status == 1 ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700 flex space-x-2">
                                <a href="{{ route('navigasi.edit', $item->menu_id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-md text-sm hover:bg-yellow-600 transition-colors">Edit</a>
                                <form action="{{ route('navigasi.destroy', $item->menu_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini? Ini juga akan menghapus sub-menu yang terkait.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        {{-- Tampilkan sub-menu jika ada --}}
                        @foreach ($item->children as $child)
                        <tr class="bg-gray-50 hover:bg-gray-100">
                            <td class="py-2 px-4 border-b"></td>
                            <td class="py-2 px-4 border-b pl-8 text-sm text-gray-700">-- {{ $child->menu_nama }}</td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $child->menu_link }}</td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">
                                @if ($child->menu_icon)
                                    <i class="fa {{ $child->menu_icon }} mr-1"></i> {{ $child->menu_icon }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $child->menu_child }}</td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $child->menu_order }}</td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $child->menu_status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $child->menu_status == 1 ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b text-sm text-gray-700 flex space-x-2">
                                <a href="{{ route('navigasi.edit', $child->menu_id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-md text-sm hover:bg-yellow-600 transition-colors">Edit</a>
                                <form action="{{ route('navigasi.destroy', $child->menu_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 text-center text-gray-500">Belum ada data navigasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
