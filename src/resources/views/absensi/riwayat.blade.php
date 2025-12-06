<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Absensi - Sistem Absensi Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-base-200">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold text-lg hover:opacity-80 transition">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Sistem Absensi</span>
                </a>
                <div class="flex gap-2">
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="bi bi-house-fill mr-1"></i>Beranda
                    </a>
                    <a href="{{ route('absensi.riwayat') }}" class="px-4 py-2 rounded-lg bg-blue-700">
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
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Riwayat Absensi</h1>
            <p class="text-sm text-gray-600">Cek riwayat kehadiran Anda</p>
        </div>

        <!-- Alert Messages -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4 flex items-center gap-2">
                <i class="bi bi-exclamation-triangle text-red-600"></i>
                <span class="text-sm text-red-800">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form Pencarian NPM -->
        <div class="flex justify-center mb-6">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm w-full max-w-2xl">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="bi bi-search text-blue-600"></i>Cari Riwayat Absensi
                    </h2>
                    <form action="{{ route('absensi.riwayat') }}" method="GET">
                        <div class="flex gap-2">
                            <input type="text"
                                   name="npm"
                                   placeholder="Masukkan NPM Anda"
                                   value="{{ request('npm') }}"
                                   class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition-colors duration-200">
                                <i class="bi bi-search mr-1"></i>Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hasil Riwayat -->
        @if(isset($mahasiswa))
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
                <div class="p-4 bg-gradient-to-r from-blue-50 to-blue-100/50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-bold flex items-center gap-2 text-gray-800">
                                <i class="bi bi-person-circle text-blue-600"></i>
                                {{ $mahasiswa->nama }}
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">NPM: {{ $mahasiswa->npm }}</p>
                        </div>
                        <div>
                            <div class="px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-full">
                                {{ $absensis->total() }} Total Absensi
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($absensis->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($absensis as $absensi)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-3 gap-2">
                                    <h3 class="font-semibold text-sm text-gray-800 line-clamp-2 flex-1 min-h-[40px]">
                                        {{ $absensi->jadwal->matakuliah->nama }}
                                    </h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                        @if($absensi->status == 'hadir') bg-green-100 text-green-800
                                        @elseif($absensi->status == 'izin') bg-yellow-100 text-yellow-800
                                        @elseif($absensi->status == 'sakit') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ strtoupper($absensi->status) }}
                                    </span>
                                </div>

                                <div class="space-y-2 text-xs">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="bi bi-person text-blue-600"></i>
                                        <span class="line-clamp-1">{{ $absensi->jadwal->dosen->nama }}</span>
                                    </div>

                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="bi bi-calendar-check text-blue-600"></i>
                                        <span>{{ $absensi->jadwal->tanggal->format('d M Y') }}</span>
                                    </div>

                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="bi bi-clock text-blue-600"></i>
                                        <span>{{ $absensi->jadwal->jam }}</span>
                                    </div>

                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="bi bi-hourglass-split text-green-600"></i>
                                        <span>{{ $absensi->waktu_absen->format('d/m/y H:i') }}</span>
                                    </div>

                                    @if($absensi->keterangan)
                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                            <div class="flex items-start gap-2">
                                                <i class="bi bi-chat-left-text text-gray-500 text-xs"></i>
                                                <span class="text-gray-700 line-clamp-2 flex-1">{{ $absensi->keterangan }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($absensis->hasPages())
                    <div class="flex justify-center mt-6">
                        <div class="inline-flex rounded-lg border border-gray-200 bg-white">
                            @if ($absensis->onFirstPage())
                                <span class="px-4 py-2 text-sm text-gray-400 border-r border-gray-200">«</span>
                            @else
                                <a href="{{ $absensis->appends(['npm' => request('npm')])->previousPageUrl() }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 border-r border-gray-200 transition">«</a>
                            @endif

                            @foreach(range(1, $absensis->lastPage()) as $page)
                                @if($page == $absensis->currentPage())
                                    <span class="px-4 py-2 text-sm bg-blue-600 text-white border-r border-gray-200">{{ $page }}</span>
                                @else
                                    <a href="{{ $absensis->appends(['npm' => request('npm')])->url($page) }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 border-r border-gray-200 transition">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($absensis->hasMorePages())
                                <a href="{{ $absensis->appends(['npm' => request('npm')])->nextPageUrl() }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">»</a>
                            @else
                                <span class="px-4 py-2 text-sm text-gray-400">»</span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                    <i class="bi bi-info-circle text-4xl text-blue-600 mb-2"></i>
                    <p class="text-sm text-gray-700">Belum ada riwayat absensi untuk NPM ini.</p>
                </div>
            @endif
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-100 border-t border-gray-200 py-4 mt-10">
        <div class="text-center">
            <p class="text-sm text-gray-600">&copy; {{ date('Y') }} Sistem Absensi Mahasiswa - STKIP YDB Lubuk Alung</p>
        </div>
    </footer>
</body>
</html>
