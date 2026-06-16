<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Tahunan {{ $year }} — KJPP Yanuar Rosye dan Rekan</title>
    <style>
        @page { margin: 15mm 15mm; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; color: #000; line-height: 1.4; }

        /* ── Kop Surat ── */
        .kop-table { width: 100%; border-collapse: collapse; border: none; margin-bottom: 5px; }
        .kop-table td { border: none; padding: 0; vertical-align: middle; text-align: center; }
        .logo-col { width: 20%; }
        .text-col { width: 60%; }
        .logo-img { width: 90%; }
        
        .company-name { font-size: 16pt; font-weight: bold; margin: 0; text-transform: uppercase; }
        .document-title { font-size: 14pt; font-weight: bold; margin: 5px 0 0; }
        
        .double-line { border-top: 3px double #000; margin-top: 5px; margin-bottom: 20px; width: 100%; }

        /* ── Heading ── */
        h1 { font-size: 14pt; font-weight: bold; margin: 30px 0 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        h2 { font-size: 12pt; font-weight: bold; margin: 14px 0 8px; }

        /* ── Info rows ── */
        .info-row { margin: 4px 0; }
        .info-row .label { display: inline-block; width: 160px; }
        .info-row .value { display: inline; }

        /* ── Table ── */
        table.data-table { width: 100%; border-collapse: collapse; margin: 10px 0 15px; }
        table.data-table th { border: 1px solid #000; padding: 6px 10px; font-size: 10pt; font-weight: bold; text-align: left; background: #f5f5f5; }
        table.data-table td { border: 1px solid #000; padding: 5px 10px; font-size: 10pt; }

        /* ── Nilai box ── */
        .nilai-box { border: 1px solid #000; padding: 10px 15px; margin: 10px 0; }
        .nilai-box .label { font-size: 10pt; }
        .nilai-box .value { font-size: 16pt; font-weight: bold; }

        /* ── Page break ── */
        .page-break { page-break-before: always; }
        
        .summary-box { background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <!-- ═══════════════ KOP SURAT ═══════════════ -->
    <table class="kop-table">
        <tr>
            <td rowspan="2" class="logo-col">
                @if(extension_loaded('gd'))
                    <img src="{{ public_path('images/logo_kjpp.png') }}" class="logo-img">
                @else
                    <div style="font-size: 8pt; color: #666; border: 1px solid #ccc; padding: 5px;">LOGO KJPP</div>
                @endif
            </td>
            <td class="text-col">
                <div class="company-name">KJPP Yanuar Rosye dan Rekan</div>
            </td>
            <td rowspan="2" class="logo-col">
                @if(extension_loaded('gd'))
                    <img src="{{ public_path('images/logo_kemenkeu.png') }}" class="logo-img">
                @else
                    <div style="font-size: 8pt; color: #666; border: 1px solid #ccc; padding: 5px;">LOGO KEMENKEU</div>
                @endif
            </td>
        </tr>
        <tr>
            <td class="text-col">
                <div class="document-title">LAPORAN PENILAIAN TAHUNAN {{ $year }}</div>
            </td>
        </tr>
    </table>

    <div class="double-line"></div>

    <div class="summary-box">
        <div class="info-row"><span class="label">Tahun Laporan</span><span class="value">: {{ $year }}</span></div>
        <div class="info-row"><span class="label">Total Proyek</span><span class="value">: {{ $proyeks->count() }}</span></div>
        <div class="info-row"><span class="label">Tanggal Cetak</span><span class="value">: {{ now()->format('d F Y') }}</span></div>
    </div>

    @foreach($proyeks as $index => $proyek)
        @if($index > 0)
            <div class="page-break"></div>
        @endif

        <h1>Proyek: {{ $proyek->nama_proyek }}</h1>

        <h2>Informasi Proyek</h2>
        <div class="info-row"><span class="label">No. Laporan</span><span class="value">: LP-{{ str_pad($proyek->id, 3, '0', STR_PAD_LEFT) }}</span></div>
        <div class="info-row"><span class="label">Nama Proyek</span><span class="value">: {{ $proyek->nama_proyek }}</span></div>
        <div class="info-row"><span class="label">Proyek Selesai</span><span class="value">: {{ $proyek->updated_at->format('d F Y') }}</span></div>
        <div class="info-row"><span class="label">Karyawan</span><span class="value">: {{ $proyek->users->where('role', 'karyawan')->pluck('name')->implode(', ') }}</span></div>
        <div class="info-row"><span class="label">Klien</span><span class="value">: {{ $proyek->users->where('role', 'client')->pluck('name')->implode(', ') }}</span></div>

        <h2>Ringkasan Properti</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tipe Properti</th>
                    <th>Status Dokumen</th>
                    <th>Status Fisik</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $proyek->properti->tipe_properti)) }}</td>
                    <td>{{ $proyek->properti->dokumens->count() }} Dokumen Terunggah</td>
                    <td>{{ $proyek->properti->aspekFisiks->count() }} Aspek Terverifikasi</td>
                </tr>
            </tbody>
        </table>

        <h2>Nilai Properti</h2>
        @if($proyek->properti?->nilai)
        <div class="nilai-box">
            <span class="label">Nilai : </span>
            <span class="value">Rp {{ number_format($proyek->properti->nilai->nilai, 0, ',', '.') }}</span>
        </div>
        @if($proyek->properti->nilai->catatan)
        <div class="info-row"><span class="label">Catatan</span><span class="value">: {{ $proyek->properti->nilai->catatan }}</span></div>
        @endif
        @else
        <p>Nilai belum tersedia.</p>
        @endif

    @endforeach

</body>
</html>
