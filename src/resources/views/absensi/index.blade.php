<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Absensi Perkuliahan</h1>
                <p class="text-gray-600">Pilih jadwal perkuliahan untuk melakukan absensi</p>
            </div>

            <!-- Alert -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Jadwal List -->
            @if($jadwals->count() > 0)
                <div class="grid gap-4">
                    @foreach($jadwals as $jadwal)
                        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        {{ $jadwal->matakuliah->nama }}
                                    </h3>
                                    <div class="space-y-1 text-sm text-gray-600">
                                        <p><span class="font-medium">Program Studi:</span> {{ $jadwal->programstudi->nama }}</p>
                                        <p><span class="font-medium">Dosen:</span> {{ $jadwal->dosen->nama }}</p>
                                        <p><span class="font-medium">Jam:</span> {{ $jadwal->jam }}</p>
                                        <p><span class="font-medium">Tanggal:</span> {{ $jadwal->tanggal->format('d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('absensi.create', $jadwal) }}"
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition">
                                        Absen Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Jadwal Hari Ini</h3>
                    <p class="text-gray-600">Belum ada jadwal perkuliahan untuk hari ini.</p>
                </div>
            @endif

            <!-- Link Riwayat -->
            <div class="mt-6 text-center">
                <a href="{{ route('absensi.riwayat') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Lihat Riwayat Absensi →
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} Sistem Absensi Mahasiswa</p>
            </div>
        </div>
    </div>
</body>
</html>
