<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Absensi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <a href="{{ route('absensi.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                    ← Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Absensi</h1>
                <p class="text-gray-600">Cek riwayat kehadiran Anda</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form action="{{ route('absensi.riwayat') }}" method="GET" class="flex gap-4">
                    <input type="text"
                           name="npm"
                           value="{{ request('npm') }}"
                           placeholder="Masukkan NPM Anda"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           required>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Results -->
            @if(isset($mahasiswa))
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $mahasiswa->nama }}</h2>
                    <p class="text-gray-600">NPM: {{ $mahasiswa->npm }}</p>
                </div>

                @if($absensis->count() > 0)
                    <div class="space-y-4">
                        @foreach($absensis as $absensi)
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-lg mb-2">
                                            {{ $absensi->jadwal->matakuliah->nama }}
                                        </h3>
                                        <div class="space-y-1 text-sm text-gray-600">
                                            <p><span class="font-medium">Dosen:</span> {{ $absensi->jadwal->dosen->nama }}</p>
                                            <p><span class="font-medium">Tanggal:</span> {{ $absensi->jadwal->tanggal->format('d F Y') }}</p>
                                            <p><span class="font-medium">Jam:</span> {{ $absensi->jadwal->jam }}</p>
                                            <p><span class="font-medium">Waktu Absen:</span> {{ $absensi->waktu_absen->format('d F Y H:i') }}</p>
                                            @if($absensi->keterangan)
                                                <p><span class="font-medium">Keterangan:</span> {{ $absensi->keterangan }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                            {{ $absensi->status === 'hadir' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $absensi->status === 'izin' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $absensi->status === 'sakit' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $absensi->status === 'alpha' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($absensi->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $absensis->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <p class="text-gray-600">Belum ada riwayat absensi</p>
                    </div>
                @endif
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
