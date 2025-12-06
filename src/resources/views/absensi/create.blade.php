<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Absensi - {{ $jadwal->matakuliah->nama }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <a href="{{ route('absensi.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                    ← Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Form Absensi</h1>

                <!-- Info Jadwal -->
                <div class="bg-blue-50 rounded-lg p-4 space-y-2 text-sm">
                    <p><span class="font-medium">Mata Kuliah:</span> {{ $jadwal->matakuliah->nama }}</p>
                    <p><span class="font-medium">Dosen:</span> {{ $jadwal->dosen->nama }}</p>
                    <p><span class="font-medium">Jam:</span> {{ $jadwal->jam }}</p>
                    <p><span class="font-medium">Tanggal:</span> {{ $jadwal->tanggal->format('d F Y') }}</p>
                </div>
            </div>

            <!-- Alert -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Absensi -->
            <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
                @csrf
                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                <input type="hidden" name="lokasi" id="lokasi">

                <div class="space-y-6">
                    <!-- NPM -->
                    <div>
                        <label for="npm" class="block text-sm font-medium text-gray-700 mb-2">NPM *</label>
                        <input type="text"
                               id="npm"
                               name="npm"
                               value="{{ old('npm') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: 1234567890"
                               required>
                        @error('npm')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text"
                               id="nama"
                               name="nama"
                               value="{{ old('nama') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Absensi (Optional) -->
                    <div>
                        <label for="foto_absen" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Absensi (Opsional)
                        </label>
                        <input type="file"
                               id="foto_absen"
                               name="foto_absen"
                               accept="image/*"
                               capture="user"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Upload foto selfie untuk verifikasi kehadiran</p>
                        @error('foto_absen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan (Opsional)
                        </label>
                        <textarea id="keterangan"
                                  name="keterangan"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan') }}</textarea>
                    </div>

                    <!-- Location Info -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">
                            <span class="font-medium">📍 Lokasi:</span>
                            <span id="location-status">Mengambil lokasi...</span>
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4">
                        <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition">
                            Submit Absensi
                        </button>
                        <a href="{{ route('absensi.index') }}"
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-center">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Get GPS Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lokasi = position.coords.latitude + ',' + position.coords.longitude;
                    document.getElementById('lokasi').value = lokasi;
                    document.getElementById('location-status').textContent = 'Lokasi berhasil didapatkan ✓';
                },
                function(error) {
                    document.getElementById('location-status').textContent = 'Tidak dapat mengambil lokasi';
                }
            );
        } else {
            document.getElementById('location-status').textContent = 'Browser tidak mendukung GPS';
        }
    </script>
</body>
</html>
