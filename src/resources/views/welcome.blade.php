<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Absensi Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold text-lg hover:opacity-80 transition">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Sistem Absensi</span>
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('absensi.riwayat') }}" class="px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="bi bi-clock-history mr-1"></i>Riwayat
                    </a>
                    @if (Route::has('filament.admin.auth.login'))
                    <a href="{{ route('filament.admin.auth.login') }}" class="px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="bi bi-gear-fill mr-1"></i>Admin
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6 max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Absensi Mahasiswa</h1>
            <p class="text-sm text-gray-600">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class="bi bi-check-circle-fill text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mb-4">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Jadwal List -->
        @if($jadwals->count() > 0)
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Pilih Kelas untuk Absensi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($jadwals as $jadwal)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="p-4">
                            <h3 class="font-semibold text-sm text-gray-800 mb-3 line-clamp-2 min-h-[40px]">
                                {{ $jadwal->matakuliah->nama }}
                            </h3>

                            <div class="space-y-2 mb-3">
                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <i class="bi bi-person text-blue-600"></i>
                                    <span class="line-clamp-1">{{ $jadwal->dosen->nama }}</span>
                                </div>

                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <i class="bi bi-calendar3 text-blue-600"></i>
                                    <span>{{ $jadwal->tanggal->format('d M Y') }}</span>
                                </div>

                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <i class="bi bi-clock text-blue-600"></i>
                                    <span>{{ $jadwal->jam }}</span>
                                </div>
                            </div>

                            <button onclick="modal{{ $jadwal->id }}.showModal()" class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <i class="bi bi-check-circle mr-1"></i>Absen Sekarang
                            </button>
                        </div>
                    </div>

                    <!-- Modal Form Absensi -->
                    <dialog id="modal{{ $jadwal->id }}" class="modal">
                        <div class="modal-box max-w-2xl">
                            <form method="dialog">
                                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            </form>

                            <h3 class="font-semibold text-lg mb-4 text-gray-800">Form Absensi</h3>

                            <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                                <input type="hidden" name="lokasi" id="lokasi{{ $jadwal->id }}">

                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                    <div class="flex items-start gap-2">
                                        <i class="bi bi-info-circle text-blue-600"></i>
                                        <div>
                                            <div class="font-semibold text-sm text-gray-800">{{ $jadwal->matakuliah->nama }}</div>
                                            <div class="text-xs text-gray-600 mt-1">{{ $jadwal->tanggal->format('d M Y') }} - {{ $jadwal->jam }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        NPM <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" name="npm" placeholder="Masukkan NPM Anda" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Lengkap <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" name="nama" placeholder="Masukkan Nama Lengkap" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Status <span class="text-red-600">*</span>
                                    </label>
                                    <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="hadir" selected>Hadir</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Foto (Opsional)
                                    </label>
                                    <input type="file" name="foto_absen" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-500 mt-1">Upload foto sebagai bukti kehadiran</p>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Keterangan
                                    </label>
                                    <textarea name="keterangan" placeholder="Keterangan tambahan (opsional)" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                </div>

                                <div class="alert alert-warning mb-4">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span class="text-sm">Lokasi Anda akan otomatis terdeteksi</span>
                                </div>

                                <div class="modal-action">
                                    <button type="button" onclick="modal{{ $jadwal->id }}.close()" class="btn btn-ghost">Batal</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send-fill mr-2"></i>Submit Absensi
                                    </button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                @endforeach
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="bi bi-exclamation-triangle text-4xl text-yellow-600 mb-2"></i>
                <h3 class="font-semibold text-gray-800 mb-1">Tidak ada jadwal yang aktif</h3>
                <p class="text-sm text-gray-600">Silakan hubungi dosen atau admin untuk mengaktifkan jadwal perkuliahan.</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-100 border-t border-gray-200 py-4 mt-10">
        <div class="text-center">
            <p class="text-sm text-gray-600">&copy; {{ date('Y') }} Sistem Absensi Mahasiswa - STKIP YDB Lubuk Alung</p>
        </div>
    </footer>

    <script>
        // Auto-detect location when modal opens
        document.addEventListener('DOMContentLoaded', function() {
            // Get all modal buttons
            const modalButtons = document.querySelectorAll('[onclick^="modal"]');

            modalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Extract modal ID from onclick attribute
                    const onclickAttr = this.getAttribute('onclick');
                    const modalId = onclickAttr.match(/modal(\d+)/)[1];
                    const lokasiInput = document.getElementById('lokasi' + modalId);

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;
                                lokasiInput.value = lat + ',' + lng;
                            },
                            function(error) {
                                console.log('Geolocation error:', error);
                                lokasiInput.value = 'Location not available';
                            }
                        );
                    }
                });
            });
        });
    </script>
</body>
</html>
