<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ str_replace(' ', '-', $jadwal->dosen->nama) }}_{{ str_replace(' ', '-', $jadwal->matakuliah->nama) }}_{{ $jadwal->tanggal->format('Y-m-d') }}</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .dokumentasi-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
            }
            .page-break {
                page-break-after: always;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px;
        }

        .info-table td:first-child {
            width: 200px;
            font-weight: normal;
        }

        .stats-container {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background: #f5f5f5;
            border: 1px solid #ddd;
        }

        .stat-box {
            text-align: center;
        }

        .stat-box .number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .stat-box .label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .attendance-table th {
            background-color: #fff;
            color: #000;
            font-weight: bold;
        }

        .attendance-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }

        .status-hadir {
            background-color: #4CAF50;
            color: white;
        }

        .status-izin {
            background-color: #FFC107;
            color: #000;
        }

        .status-sakit {
            background-color: #2196F3;
            color: white;
        }

        .status-alpha {
            background-color: #F44336;
            color: white;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            margin-top: 80px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn:hover {
            background-color: #45a049;
        }

        .dokumentasi-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .dokumentasi-item {
            border: 1px solid #ddd;
            padding: 5px;
            background: #fff;
            break-inside: avoid;
            text-align: center;
        }

        .dokumentasi-item img {
            width: 100%;
            height: auto;
            display: block;
            max-height: 350px;
            object-fit: contain;
            border: 1px solid #eee;
        }

        .dokumentasi-caption {
            text-align: center;
            margin-top: 5px;
            font-size: 10px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">
        🖨️ Print / Save as PDF
    </button>

    <div class="header">
        <h1>LAPORAN PERKULIAHAN DARING</h1>
    </div>

    <div class="info-section">
        <h3 style="margin-bottom: 15px; color: #333;">IDENTITAS</h3>
        <table class="info-table">
            <tr>
                <td>Nama Mata Kuliah</td>
                <td>: {{ $jadwal->matakuliah->nama }}</td>
            </tr>
            <tr>
                <td>SKS/Semester</td>
                <td>: {{ $jadwal->matakuliah->sks ?? '-' }}/{{ $jadwal->matakuliah->semester ?? '-' }}</td>
            </tr>
            <tr>
                <td>Dosen Pengampu</td>
                <td>: {{ $jadwal->dosen->nama }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>: {{ $jadwal->programstudi->nama }}</td>
            </tr>
            <tr>
                <td>Jam (mulai s/d akhir)</td>
                <td>: {{ $jadwal->jam }}</td>
            </tr>
            <tr>
                <td>Hari/Tanggal</td>
                <td>: {{ $jadwal->tanggal->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</td>
            </tr>
        </table>
    </div>

    <h3 style="margin-top: 30px; margin-bottom: 15px; color: #333;">KEHADIRAN MAHASISWA</h3>

    @if($jadwal->absensis->count() > 0)
        <table class="attendance-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama</th>
                    <th style="width: 120px;">NPM</th>
                    <th style="width: 60px; text-align: center;">Hadir</th>
                    <th style="width: 60px; text-align: center;">T.Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwal->absensis->sortBy('mahasiswa.npm') as $index => $absensi)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $absensi->mahasiswa->nama }}</td>
                        <td>{{ $absensi->mahasiswa->npm }}</td>
                        <td style="text-align: center;">
                            @if($absensi->status === 'hadir')
                                ✓
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($absensi->status !== 'hadir')
                                ✓
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; background: #f5f5f5; border: 1px solid #ddd;">
            <p style="font-size: 16px; color: #666;">Belum ada mahasiswa yang melakukan absensi</p>
        </div>
    @endif

    <!-- Catatan -->
    <div style="margin-top: 30px; padding: 15px; background: #f9f9f9; border-left: 4px solid #4CAF50;">
        <h4 style="margin-top: 0;">Catatan:</h4>
        <ol style="margin: 10px 0; padding-left: 20px; line-height: 1.8;">
            <li>Dosen menyediakan link zoom perkuliahan dan mengundang mahasiswa dalam perkuliahan daring</li>
            <li>Perkuliahan direkam (jika bisa) dan tangkap layar untuk dokumentasi</li>
            <li>Perkuliahan dilaksanakan sesuai waktu kuliah luring atau disesuaikan dengan situasi</li>
            <li>Dosen dapat mengisikan absensi pada BAP sesuai jam kuliah masing-masing</li>
            <li>Dosen mengumpulkan pelaporan kuliah daring kepada ka. Prodi</li>
            <li>Link Presentasi mahasiswa (jika ada): -</li>
            <li>Link rekaman (jika ada): -</li>
        </ol>
    </div>

    <div class="footer">
        <div style="text-align: right; margin-top: 40px; margin-right: 50px;">
            <p style="margin: 5px 0;">Lubuk Alung, {{ $jadwal->tanggal->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</p>
            <p style="margin-top: 20px; margin-bottom: 10px;">Dosen Pengampu</p>
            @if($jadwal->dosen->tanda_tangan)
                <div style="margin: 20px 0; text-align: right;">
                    <img src="{{ $jadwal->dosen->tanda_tangan }}"
                         alt="Tanda Tangan"
                         style="max-width: 350px; max-height: 500px; width: auto; height: auto; display: inline-block;">
                </div>
            @endif
            <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; min-width: 250px; text-align: center;">
                <strong>{{ $jadwal->dosen->nama }}</strong><br>
                <span style="font-size: 11px; font-weight: normal;">NIDN. {{ $jadwal->dosen->nidn ?? '0021065303' }}</span>
            </div>
        </div>

        <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 15px;">
            <p>Halaman 1</p>
        </div>
    </div>

    <!-- Dokumentasi Perkuliahan -->
    @if($jadwal->dokumentasi && count($jadwal->dokumentasi) > 0)
        <div class="page-break"></div>

        <div class="header">
            <h1>LAPORAN PERKULIAHAN DARING</h1>
        </div>

        <h3 style="margin-top: 20px; margin-bottom: 20px; color: #333; text-align: center;">
            DOKUMENTASI PERKULIAHAN
        </h3>

        <div class="dokumentasi-grid">
            @foreach($jadwal->dokumentasi as $index => $foto)
                <div class="dokumentasi-item">
                    <img src="{{ asset('storage/' . $foto) }}"
                         alt="Dokumentasi {{ $index + 1 }}"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%23ddd\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-family=\'Arial\' font-size=\'18\'%3EGambar tidak tersedia%3C/text%3E%3C/svg%3E';">
                    <p class="dokumentasi-caption">
                        Foto {{ $index + 1 }}
                    </p>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 15px;">
            <p>Halaman 2</p>
        </div>
    @endif

    <script>
        // Auto print jika ada parameter print
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('auto_print') === '1') {
            window.onload = function() {
                window.print();
            };
        }
    </script>
</body>
</html>
