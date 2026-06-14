<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penilaian Properti — {{ $proyek->nama_proyek }}</title>
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
        h1 { font-size: 14pt; font-weight: bold; margin: 20px 0 10px; }
        h2 { font-size: 12pt; font-weight: bold; margin: 14px 0 8px; }

        /* ── Info rows ── */
        .info-row { margin: 4px 0; }
        .info-row .label { display: inline-block; width: 160px; }
        .info-row .value { display: inline; }

        /* ── Table ── */
        table { width: 100%; border-collapse: collapse; margin: 10px 0 15px; }
        th { border: 1px solid #000; padding: 6px 10px; font-size: 10pt; font-weight: bold; text-align: left; background: #f5f5f5; }
        td { border: 1px solid #000; padding: 5px 10px; font-size: 10pt; }

        /* ── Nilai box ── */
        .nilai-box { border: 1px solid #000; padding: 10px 15px; margin: 10px 0; }
        .nilai-box .label { font-size: 10pt; }
        .nilai-box .value { font-size: 16pt; font-weight: bold; }

        /* ── Page break ── */
        .page-break { page-break-before: always; }
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
                <div class="document-title">Laporan Proyek {{ $proyek->nama_proyek }}</div>
                <div style="font-size: 10pt; font-weight: normal; margin-top: 2px;">
                    No. LP-{{ str_pad($proyek->id, 3, '0', STR_PAD_LEFT) }}
                </div>
            </td>
        </tr>
    </table>

    <div class="double-line"></div>

    <!-- ═══════════════ ISI LAPORAN PROYEK ═══════════════ -->

    <h1>Proyek {{ $proyek->nama_proyek }}</h1>

    <h2>Informasi Proyek</h2>
    <div class="info-row"><span class="label">Nama Proyek</span><span class="value">: {{ $proyek->nama_proyek }}</span></div>
    <div class="info-row"><span class="label">Proyek Mulai</span><span class="value">: {{ $proyek->start_date->format('d F Y') }}</span></div>
    <div class="info-row"><span class="label">Proyek Selesai</span><span class="value">: {{ $proyek->updated_at->format('d F Y') }}</span></div>
    <div class="info-row"><span class="label">Durasi Proyek</span><span class="value">: {{ $proyek->start_date->format('d F Y') }} &mdash; {{ $proyek->updated_at->format('d F Y') }} ({{ $proyek->start_date->diffInMonths($proyek->updated_at) }} bulan)</span></div>
    <div class="info-row"><span class="label">Karyawan</span><span class="value">: {{ $karyawans->pluck('name')->implode(', ') }}</span></div>
    <div class="info-row"><span class="label">Klien</span><span class="value">: {{ $clients->pluck('name')->implode(', ') }}</span></div>
    <div class="info-row"><span class="label">Mitra</span><span class="value">: {{ $mitras->pluck('name')->implode(', ') }}</span></div>

    <h2>Dokumen Properti</h2>
    @if($dokumens->isNotEmpty())
    <table>
        <thead>
            <tr><th style="width:8%">No</th><th>Nama Dokumen</th><th style="width:30%">Tipe</th><th style="width:15%">Status</th></tr>
        </thead>
        <tbody>
            @foreach($dokumens as $i => $doc)
            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td>{{ $doc->nama_dokumen }}</td>
                <td>{{ $typeLabels[$doc->tipe_dokumen] ?? $doc->tipe_dokumen }}</td>
                <td>{{ $doc->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="font-size:10pt;">Tidak ada dokumen.</p>
    @endif

    <h2>Fisik Properti</h2>
    @if($aspeks->isNotEmpty())
    <table>
        <thead>
            <tr><th style="width:8%">No</th><th>Aspek Fisik</th><th style="width:15%">Tipe</th><th style="width:15%">Status</th></tr>
        </thead>
        <tbody>
            @foreach($aspeks as $i => $aspek)
            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td>{{ $aspek->nama_aspek }}</td>
                <td>{{ $aspek->tipe }}</td>
                <td>{{ $aspek->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="font-size:10pt;">Tidak ada aspek fisik.</p>
    @endif

    <h2>Nilai Properti</h2>
    @if($nilai)
    <div class="nilai-box">
        <span class="label">Nilai : </span>
        <span class="value">Rp {{ number_format($nilai->nilai, 0, ',', '.') }}</span>
    </div>
    @if($nilai->catatan)
    <div class="info-row"><span class="label">Catatan</span><span class="value">: {{ $nilai->catatan }}</span></div>
    @endif
    @endif

</body>
</html>
