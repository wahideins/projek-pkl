<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Navigasi Baru - Projek PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Navigasi Baru</h1>

        <form action="{{ route('navigasi.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="menu_nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Menu:</label>
                <input type="text" id="menu_nama" name="menu_nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('menu_nama') border-red-500 @enderror" value="{{ old('menu_nama') }}" placeholder="Contoh: Beranda" required>
                @error('menu_nama')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="menu_link" class="block text-gray-700 text-sm font-bold mb-2">Link:</label>
                <input type="text" id="menu_link" name="menu_link" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('menu_link') border-red-500 @enderror" value="{{ old('menu_link') }}" placeholder="Contoh: /home atau /docs/epesantren" required>
                @error('menu_link')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="menu_icon" class="block text-gray-700 text-sm font-bold mb-2">Ikon (Font Awesome Class):</label>
                <input type="text" id="menu_icon" name="menu_icon" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('menu_icon') border-red-500 @enderror" value="{{ old('menu_icon') }}" placeholder="Contoh: fa-home, fa-user, fa-cogs">
                <p class="text-gray-600 text-xs italic mt-1">Kosongkan jika tidak ada ikon. Contoh: `fa-home`</p>
                @error('menu_icon')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="menu_child" class="block text-gray-700 text-sm font-bold mb-2">Parent Menu:</label>
                <select id="menu_child" name="menu_child" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('menu_child') border-red-500 @enderror">
                    <option value="0">Tidak Ada (Menu Utama)</option>
                    @foreach ($parentMenus as $parent)
                        <option value="{{ $parent->menu_id }}" {{ old('menu_child') == $parent->menu_id ? 'selected' : '' }}>{{ $parent->menu_nama }}</option>
                    @endforeach
                </select>
                <p class="text-gray-600 text-xs italic mt-1">Pilih menu utama jika ini adalah sub-menu.</p>
                @error('menu_child')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="menu_order" class="block text-gray-700 text-sm font-bold mb-2">Urutan (Order):</label>
                <input type="number" id="menu_order" name="menu_order" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('menu_order') border-red-500 @enderror" value="{{ old('menu_order') ?? 0 }}" placeholder="Contoh: 1" required>
                <p class="text-gray-600 text-xs italic mt-1">Angka lebih kecil akan muncul lebih dulu.</p>
                @error('menu_order')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <input type="checkbox" id="menu_status" name="menu_status" value="1" class="mr-2 leading-tight" {{ old('menu_status') ? 'checked' : '' }}>
                <label for="menu_status" class="text-sm text-gray-700">Aktifkan Menu (akan terlihat di navigasi publik)</label>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline transition-colors">Simpan Menu</button>
                <a href="{{ route('navigasi.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
