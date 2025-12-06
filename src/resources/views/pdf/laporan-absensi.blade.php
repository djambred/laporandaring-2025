<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ str_replace(' ', '-', $jadwal->dosen->nama) }}_{{ str_replace(' ', '-', $jadwal->matakuliah->nama) }}_{{ $jadwal->tanggal->format('Y-m-d') }}</title>
    <style>
        @page {
        size: A4;
        margin: 18mm; /* Margin aman untuk semua printer */
    }

    @media print {
        html, body {
            width: 210mm;
            height: 297mm;
        }

        .no-print {
            display: none !important;
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

        /* Signature layout improvements */
        .signature-wrapper {
            display: flex;
            justify-content: flex-end; /* taruh di kanan */
            margin-top: 40px;
            margin-right: 50px;
        }

        .signature-box {
            width: 340px; /* lebar tetap supaya rapi */
            text-align: center;
            /* pastikan tidak overflow pada cetak */
            page-break-inside: avoid;
        }

        .signature-label {
            margin: 0 0 10px 0;
            font-weight: normal;
        }

        .signature-img {
            display: block;
            margin: 10px auto;
            max-width: 260px;
            max-height: 140px;
            width: auto;
            height: auto;
        }

        .signature-line {
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 10px;
            display: inline-block;
            min-width: 250px;
            text-align: center;
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

        /* small helper to reserve space if no signature */
        .signature-placeholder {
            display: block;
            height: 120px;
            width: 100%;
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
                @foreach($jadwal->absensis as $index => $absensi)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $absensi->mahasiswa->nama }}</td>
                        <td>{{ $absensi->mahasiswa->npm ?? '-' }}</td>
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

    <!-- Page Break untuk Halaman 2 -->
    <div style="page-break-after: always;"></div>

    <!-- Halaman 2: Dokumentasi Perkuliahan -->
    @if($jadwal->dokumentasi_pre || $jadwal->dokumentasi_whilst_1 || $jadwal->dokumentasi_whilst_2 || $jadwal->dokumentasi_post)
        <!-- Header Halaman 2 -->
        <div class="header">
            <h1>LAPORAN PERKULIAHAN DARING</h1>
        </div>

        <h3 style="margin-top: 20px; margin-bottom: 15px; color: #333; text-align: left;">
            Dokumentasi:
        </h3>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; page-break-inside: avoid;">
            <tr>
                <td style="width: 50%; border: 1px solid #ddd; padding: 8px; vertical-align: top;">
                    <div style="font-weight: bold; margin-bottom: 5px; padding: 3px; background: #f5f5f5; font-size: 11px;">Pre</div>
                    @if($jadwal->dokumentasi_pre)
                        <img src="{{ asset('storage/' . $jadwal->dokumentasi_pre) }}"
                             alt="Dokumentasi Pre"
                             style="width: 100%; max-height: 250px; object-fit: contain; display: block;"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%23ddd\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-family=\'Arial\' font-size=\'18\'%3EGambar tidak tersedia%3C/text%3E%3C/svg%3E';">
                    @else
                        <div style="width: 100%; height: 150px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">Tidak ada gambar</div>
                    @endif
                </td>
                <td style="width: 50%; border: 1px solid #ddd; padding: 8px; vertical-align: top;">
                    <div style="font-weight: bold; margin-bottom: 5px; padding: 3px; background: #f5f5f5; font-size: 11px;">Whilst</div>
                    @if($jadwal->dokumentasi_whilst_1)
                        <img src="{{ asset('storage/' . $jadwal->dokumentasi_whilst_1) }}"
                             alt="Dokumentasi Whilst 1"
                             style="width: 100%; max-height: 250px; object-fit: contain; display: block;"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%23ddd\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-family=\'Arial\' font-size=\'18\'%3EGambar tidak tersedia%3C/text%3E%3C/svg%3E';">
                    @else
                        <div style="width: 100%; height: 150px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">Tidak ada gambar</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 50%; border: 1px solid #ddd; padding: 8px; vertical-align: top;">
                    <div style="font-weight: bold; margin-bottom: 5px; padding: 3px; background: #f5f5f5; font-size: 11px;">Whilst</div>
                    @if($jadwal->dokumentasi_whilst_2)
                        <img src="{{ asset('storage/' . $jadwal->dokumentasi_whilst_2) }}"
                             alt="Dokumentasi Whilst 2"
                             style="width: 100%; max-height: 250px; object-fit: contain; display: block;"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%23ddd\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-family=\'Arial\' font-size=\'18\'%3EGambar tidak tersedia%3C/text%3E%3C/svg%3E';">
                    @else
                        <div style="width: 100%; height: 150px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">Tidak ada gambar</div>
                    @endif
                </td>
                <td style="width: 50%; border: 1px solid #ddd; padding: 8px; vertical-align: top;">
                    <div style="font-weight: bold; margin-bottom: 5px; padding: 3px; background: #f5f5f5; font-size: 11px;">Post</div>
                    @if($jadwal->dokumentasi_post)
                        <img src="{{ asset('storage/' . $jadwal->dokumentasi_post) }}"
                             alt="Dokumentasi Post"
                             style="width: 100%; max-height: 250px; object-fit: contain; display: block;"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%23ddd\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-family=\'Arial\' font-size=\'18\'%3EGambar tidak tersedia%3C/text%3E%3C/svg%3E';">
                    @else
                        <div style="width: 100%; height: 150px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">Tidak ada gambar</div>
                    @endif
                </td>
            </tr>
        </table>
    @endif

    <!-- Page Break untuk Halaman 3 -->
    <div style="page-break-after: always;"></div>

    <!-- Halaman 3: Catatan & Tanda Tangan -->
    <!-- Header Halaman 3 -->
    <div class="header">
        <h1>LAPORAN PERKULIAHAN DARING</h1>
    </div>

    <!-- Catatan -->
    <div style="margin-top: 30px; padding: 15px; background: #f9f9f9; border-left: 4px solid;">
        <h4 style="margin-top: 0;">Catatan:</h4>
        <ol style="margin: 10px 0; padding-left: 20px; line-height: 0;">
            <li>Dosen menyediakan link zoom perkuliahan dan mengundang mahasiswa dalam perkuliahan daring</li>
            <li>Perkuliahan direkam (jika bisa) dan tangkap layar untuk dokumentasi</li>
            <li>Perkuliahan dilaksanakan sesuai waktu kuliah luring atau disesuaikan dengan situasi</li>
            <li>Dosen dapat mengisikan absensi pada BAP sesuai jam kuliah masing-masing</li>
            <li>Dosen mengumpulkan pelaporan kuliah daring kepada ka. Prodi</li>
            <li>Link Presentasi mahasiswa (jika ada): -</li>
            <li>Link rekaman (jika ada): -</li>
        </ol>
    </div>

    <!-- Tanda Tangan -->
    <div class="footer" style="text-align: center; margin-top: 40px;">
        <p style="margin: 5px 0;">
            Lubuk Alung, {{ $jadwal->tanggal->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}
        </p>

        <p class="signature-label" style="margin-top: 20px; margin-bottom: 6px;">Dosen Pengampu</p>

        @if($jadwal->dosen->tanda_tangan)
            <div style="display: flex; justify-content: center; margin: 10px 0;">
                <img src="{{ $jadwal->dosen->tanda_tangan }}"
                     alt="Tanda Tangan"
                     style="max-width: 350px; max-height: 200px; object-fit: contain;">
            </div>
        @else
            <!-- placeholder agar tinggi tetap konsisten -->
            <span style="display: inline-block; height: 200px;" aria-hidden="true"></span>
        @endif

        <div style="margin-top: 10px;">
            <strong style="display:block;">{{ $jadwal->dosen->nama }}</strong>
            <span style="font-size: 11px; font-weight: normal; display:block; margin-top:4px;">
                NIDN. {{ $jadwal->dosen->nidn ?? '0021065303' }}
            </span>
        </div>
    </div>

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
