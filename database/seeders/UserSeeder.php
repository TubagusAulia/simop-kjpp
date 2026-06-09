<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Proyek;
use App\Models\AlokasiProyek;
use App\Models\Properti;
use App\Models\DokumenProperti;
use App\Models\ChecklistFisik;
use App\Models\AspekFisik;
use App\Models\Nilai;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// Manually require FPDF if not auto-loaded
if (!class_exists('FPDF')) {
    $fpdfPath = base_path('vendor/setasign/fpdf/fpdf.php');
    if (file_exists($fpdfPath)) {
        require_once $fpdfPath;
    }
}

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── 0. Prep: Storage Cleanup ──
        Storage::disk('public')->deleteDirectory('kontrak');
        Storage::disk('public')->deleteDirectory('dokumen');
        Storage::disk('public')->deleteDirectory('aspek-fisik');
        Storage::disk('public')->makeDirectory('kontrak');
        Storage::disk('public')->makeDirectory('dokumen');
        Storage::disk('public')->makeDirectory('aspek-fisik');

        // ── 1. Create Users ──
        $users = $this->createUsers();

        // ── 2. Create Projects (5 phases UI testing) ──
        $this->createProjectA($users); // Phase 1 — Dimulai (partial docs)
        $this->createProjectB($users); // Phase 2 — Dokumen verified, partial fisik
        $this->createProjectC($users); // Phase 3 — Fisik verified, draft nilai
        $this->createProjectD($users); // Phase 4 — Dinilai (nilai final)
        $this->createProjectE($users); // Phase 5 — Selesai (complete)
    }

    private function createUsers()
    {
        $data = [
            ['username' => 'admin',  'name' => 'Administrator',       'role' => 'admin'],
            ['username' => 'karyawan1', 'name' => 'Anton (Karyawan 1)', 'role' => 'karyawan'],
            ['username' => 'karyawan2', 'name' => 'Budi (Karyawan 2)',  'role' => 'karyawan'],
            ['username' => 'karyawan3', 'name' => 'Citra (Karyawan 3)', 'role' => 'karyawan'],
            ['username' => 'klien1',    'name' => 'Doni (Klien 1)',     'role' => 'client'],
            ['username' => 'klien2',    'name' => 'Eka (Klien 2)',     'role' => 'client'],
            ['username' => 'klien3',    'name' => 'Fajar (Klien 3)',   'role' => 'client'],
            ['username' => 'mitra1',    'name' => 'Gilang (Mitra 1)',   'role' => 'mitra'],
            ['username' => 'mitra2',    'name' => 'Hadi (Mitra 2)',    'role' => 'mitra'],
            ['username' => 'mitra3',    'name' => 'Indra (Mitra 3)',   'role' => 'mitra'],
        ];

        $created = [];
        foreach ($data as $u) {
            $created[$u['username']] = User::create([
                'name' => $u['name'],
                'username' => $u['username'],
                'email' => "{$u['username']}@kjpp.test",
                'password' => Hash::make('password'),
                'role' => $u['role'],
                'email_verified_at' => now(),
            ]);
        }
        return $created;
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK A — Phase 1: Dimulai
    // tanah_kosong, Jakarta Barat (Kebon Jeruk)
    // Dokumen: 1 verified, 1 menunggu, 2 belum upload
    // Fisik: belum ada checklist
    // ═══════════════════════════════════════════════════════════════
    private function createProjectA($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Tanah Kosong Subang (Proyek A)',
            'deskripsi' => 'Fase 1: Proyek baru. Dokumen sebagian terupload.',
            'start_date' => now(),
            'due_date' => now()->addMonths(1),
            'status' => 'aktif',
            'current_phase' => 'dimulai',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Tanah Kosong Subang (Proyek A)', 'kontrak'),
        ]);

        $properti = $this->getOrCreateProperti($p, 'tanah_kosong');
        $this->assign($p, [$users['karyawan1'], $users['klien1'], $users['mitra1']]);

        // Dokumen verified (mandatory: sertifikat_utama sudah diverifikasi)
        $this->addDoc($p, 'sertifikat_utama', 'Sertifikat Hak Milik No. 1234', $users['klien1'], $users['karyawan1'], 'terverifikasi');

        // Dokumen uploaded but pending verification
        $this->addDoc($p, 'pbb_terakhir', 'PBB Tahun 2025', $users['klien1'], null, 'menunggu');

        // identitas_pemilik & shm_hgb_skur → belum diupload (missing)
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK B — Phase 2: Dokumen Diverifikasi
    // rumah_tinggal, Jakarta Selatan (Ciganjur)
    // Dokumen: semua mandatory verified
    // Fisik: 3 checklist — 1 verified, 1 menunggu, 1 belum ada aspek
    // ═══════════════════════════════════════════════════════════════
    private function createProjectB($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Rumah Tinggal Antasari (Proyek B)',
            'deskripsi' => 'Fase 2: Dokumen diverifikasi. Fisik sebagian terisi.',
            'start_date' => now()->subDays(10),
            'due_date' => now()->addMonths(1),
            'status' => 'aktif',
            'current_phase' => 'dokumen',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Rumah Tinggal Antasari (Proyek B)', 'kontrak'),
        ]);

        $properti = $this->getOrCreateProperti($p, 'rumah_tinggal');
        $this->assign($p, [$users['karyawan1'], $users['karyawan2'], $users['klien1'], $users['klien2'], $users['mitra1'], $users['mitra2']]);

        // Semua mandatory docs verified (3 global + 2 type-specific untuk rumah_tinggal)
        $this->addDoc($p, 'sertifikat_utama', 'Sertifikat Hak Milik No. 5678', $users['klien1'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($p, 'pbb_terakhir', 'PBB Tahun 2025', $users['klien1'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($p, 'identitas_pemilik', 'KTP Pemilik Aset', $users['klien1'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($p, 'shm_hgb_skur', 'SHM + Surat Ukur No 567', $users['klien1'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($p, 'imb_pbg', 'IMB No. 2019/045', $users['klien1'], $users['karyawan2'], 'terverifikasi');

        // 3 checklist fisik wajib
        $cl1 = $this->addChecklist($properti, 'Struktur Bangunan Utama', $users['karyawan1']);
        $cl2 = $this->addChecklist($properti, 'Kondisi Fasad & Eksterior', $users['karyawan1']);
        $cl3 = $this->addChecklist($properti, 'Instalasi Listrik & Plumbing', $users['karyawan1']);

        // Checklist 1: verified
        $this->addAspekFisik($properti, $cl1, 'Struktur Bangunan Utama', 'Struktur dalam kondisi baik, tidak ada retakan.', $users['karyawan1'], $users['karyawan1'], 'terverifikasi', -6.3150, 106.8050);

        // Checklist 2: menunggu verifikasi
        $this->addAspekFisik($properti, $cl2, 'Kondisi Fasad & Eksterior', 'Fasad cat mengelupas di bagian timur.', $users['karyawan1'], $users['karyawan1'], 'menunggu', -6.3151, 106.8051);

        // Checklist 3: belum ada aspek fisik (tidak buat AspekFisik)
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK C — Phase 3: Fisik Diverifikasi
    // gudang_penyimpanan, Jakarta Utara (Priok)
    // Dokumen: semua verified. Fisik: semua verified.
    // Nilai: draft (terisi, belum final)
    // ═══════════════════════════════════════════════════════════════
    private function createProjectC($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Gudang Logistik Priok (Proyek C)',
            'deskripsi' => 'Fase 3: Dokumen & fisik diverifikasi. Draft penilaian.',
            'start_date' => now()->subMonths(1),
            'due_date' => now()->addMonths(1),
            'status' => 'aktif',
            'current_phase' => 'fisik',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Gudang Logistik Priok (Proyek C)', 'kontrak'),
        ]);

        $properti = $this->getOrCreateProperti($p, 'gudang_penyimpanan');
        $this->assign($p, [$users['karyawan1'], $users['karyawan3'], $users['klien1'], $users['klien3'], $users['mitra1'], $users['mitra3']]);

        // Semua mandatory docs verified (4 global + 4 type-specific)
        $mandatories = ['sertifikat_utama', 'pbb_terakhir', 'identitas_pemilik', 'shm_hgb_lengkap', 'imb_pbg_gudang', 'denah_loading_dock', 'daftar_aktiva_me_handling'];
        foreach ($mandatories as $m) {
            $this->addDoc($p, $m, "Dokumen Wajib: $m", $users['klien3'], $users['karyawan3'], 'terverifikasi');
        }

        // 4 checklist fisik, semua verified
        $items = ['Struktur Rangka Baja', 'Kondisi Lantai Beton', 'Sistem Ventilasi', 'Akses Jalan & Loading Dock'];
        foreach ($items as $i => $nama) {
            $cl = $this->addChecklist($properti, $nama, $users['karyawan3']);
            $this->addAspekFisik($properti, $cl, $nama, "Verifikasi: $nama — kondisi baik.", $users['karyawan3'], $users['karyawan3'], 'terverifikasi', -6.1056 + ($i * 0.0001), 106.8913 + ($i * 0.0001));
        }

        // Draft nilai
        $this->addNilai($properti, 42000000000, 'Draft penilaian — perlu review sebelum submit final.', $users['karyawan3']);
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK D — Phase 4: Properti Dinilai
    // pusat_perbelanjaan, Jakarta Selatan (Senayan)
    // Semua verified + nilai final tersimpan
    // ═══════════════════════════════════════════════════════════════
    private function createProjectD($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Mall Senayan City (Proyek D)',
            'deskripsi' => 'Fase 4: Semua verifikasi selesai. Nilai final tersimpan.',
            'start_date' => now()->subMonths(3),
            'due_date' => now()->addMonths(1),
            'status' => 'aktif',
            'current_phase' => 'dinilai',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Mall Senayan City (Proyek D)', 'kontrak'),
        ]);

        $properti = $this->getOrCreateProperti($p, 'pusat_perbelanjaan');
        $this->assign($p, array_values($users));

        // Semua mandatory docs verified (4 global + 4 type-specific)
        $mandatories = ['sertifikat_utama', 'pbb_terakhir', 'identitas_pemilik', 'hgb_induk', 'imb_pbg_mall', 'denah_zona_komersial', 'daftar_aktiva_me_lift', 'laporan_keuangan_3th'];
        foreach ($mandatories as $m) {
            $this->addDoc($p, $m, "Dokumen Final: $m", $users['klien1'], $users['karyawan1'], 'terverifikasi');
        }

        // 5 checklist fisik, semua verified
        $items = ['Struktur Gedung Utama', 'Kondisi Fasad & Kaca', 'Sistem HVAC', 'Area Parkir & Akses', 'Food Court Area'];
        foreach ($items as $i => $nama) {
            $cl = $this->addChecklist($properti, $nama, $users['karyawan1']);
            $this->addAspekFisik($properti, $cl, $nama, "Verifikasi: $nama — ok.", $users['karyawan1'], $users['karyawan1'], 'terverifikasi', -6.2271 + ($i * 0.0001), 106.7976 + ($i * 0.0001));
        }

        // Nilai final
        $this->addNilai($properti, 85000000000, 'Penilaian berdasarkan pendekatan biaya pengganti baru dan perbandingan pasar. Lokasi strategis di kawasan bisnis Senayan dengan okupansi tinggi.', $users['karyawan1']);
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK E — Phase 5: Proyek Selesai
    // apartemen_kondominium, Jakarta Pusat (Sudirman)
    // Semua complete + status selesai
    // ═══════════════════════════════════════════════════════════════
    private function createProjectE($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Apartemen Sudirman Park (Proyek E)',
            'deskripsi' => 'Fase 5: Proyek selesai dan terkunci.',
            'start_date' => now()->subMonths(4),
            'due_date' => now()->subWeek(),
            'status' => 'selesai',
            'current_phase' => 'selesai',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Apartemen Sudirman Park (Proyek E)', 'kontrak'),
        ]);

        $properti = $this->getOrCreateProperti($p, 'apartemen_kondominium');
        $this->assign($p, [$users['karyawan2'], $users['klien2'], $users['mitra2']]);

        // Semua mandatory docs verified (3 global + 2 type-specific untuk apartemen)
        $mandatories = ['sertifikat_utama', 'pbb_terakhir', 'identitas_pemilik', 'shmrs', 'slf_gedung'];
        foreach ($mandatories as $m) {
            $this->addDoc($p, $m, "Dokumen: $m", $users['klien2'], $users['karyawan2'], 'terverifikasi');
        }

        // 5 checklist fisik, semua verified
        $items = ['Struktur Tower & Balcony', 'Kondisi Unit Interior', 'Fasilitas Umum (Pool, Gym)', 'Sistem Keamanan & Akses', 'Parkir Basement'];
        foreach ($items as $i => $nama) {
            $cl = $this->addChecklist($properti, $nama, $users['karyawan2']);
            $this->addAspekFisik($properti, $cl, $nama, "Verifikasi: $nama — baik.", $users['karyawan2'], $users['karyawan2'], 'terverifikasi', -6.2088 + ($i * 0.0001), 106.8456 + ($i * 0.0001));
        }

        // Nilai final
        $this->addNilai($properti, 1200000000, 'Unit tipe 2BR, lantai 25 dengan view city. Penilaian berdasarkan transaksi sejenis di kawasan Sudirman.', $users['karyawan2']);
    }

    // ═══════════════════════════════════════════════════════════════
    // HELPER METHODS
    // ═══════════════════════════════════════════════════════════════

    private function getOrCreateProperti($proyek, $tipe)
    {
        $properti = $proyek->properti()->first();
        if (!$properti) {
            $properti = $proyek->properti()->create(['nama_properti' => $proyek->nama_proyek]);
        }
        // Hanya update tipe jika belum di-set (Proyek A-D existing sudah punya tipe)
        if (!$properti->tipe_properti) {
            $properti->update(['tipe_properti' => $tipe]);
        }
        return $properti;
    }

    private function assign($proyek, $users)
    {
        foreach ($users as $u) {
            if ($u->role === 'admin') continue;
            AlokasiProyek::firstOrCreate([
                'proyek_id' => $proyek->id,
                'user_id' => $u->id,
            ], [
                'allocated_by' => 1,
                'allocated_at' => now(),
            ]);
        }
    }

    private function addDoc($proyek, $type, $name, $uploader, $verifier = null, $status = 'terverifikasi')
    {
        $path = $this->genPdf($name, $proyek->nama_proyek, 'dokumen');
        $properti = $proyek->properti;

        DokumenProperti::create([
            'properti_id' => $properti->id,
            'uploaded_by' => $uploader->id,
            'tipe_dokumen' => $type,
            'nama_dokumen' => $name,
            'file_path' => $path,
            'status' => $status,
            'verified_by' => $verifier?->id,
            'verified_at' => $verifier ? now() : null,
        ]);
    }

    private function addChecklist($properti, $namaItem, $creator)
    {
        return ChecklistFisik::create([
            'properti_id' => $properti->id,
            'nama_item' => $namaItem,
            'tipe' => 'wajib',
            'created_by' => $creator->id,
        ]);
    }

    private function addAspekFisik($properti, $checklist, $nama, $deskripsi, $creator, $verifier, $status, $lat, $lng)
    {
        return AspekFisik::create([
            'properti_id' => $properti->id,
            'checklist_fisik_id' => $checklist->id,
            'nama_aspek' => $nama,
            'deskripsi' => $deskripsi,
            'foto_paths' => [$this->genPlaceholderFoto()],
            'latitude' => $lat,
            'longitude' => $lng,
            'tipe' => 'wajib',
            'status' => $status,
            'created_by' => $creator->id,
            'verified_by' => $verifier->id,
            'verified_at' => $status === 'terverifikasi' ? now() : null,
            'catatan' => $status === 'terverifikasi' ? 'Diverifikasi.' : null,
        ]);
    }

    private function addNilai($properti, $nilaiAmount, $catatan, $creator)
    {
        return Nilai::create([
            'properti_id' => $properti->id,
            'nilai' => $nilaiAmount,
            'catatan' => $catatan,
            'created_by' => $creator->id,
        ]);
    }

    private function genPlaceholderFoto()
    {
        $filename = 'aspek-fisik/placeholder_' . uniqid() . '.jpg';
        // Minimal valid JPEG (1x1 pixel, gray) — base64 encoded
        $minimalJpeg = base64_decode(
            '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQQAQAAAAAAAAAAAAAAAAAAAAD/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8AKwA//9k='
        );
        Storage::disk('public')->put($filename, $minimalJpeg);
        return $filename;
    }

    private function genPdf($type, $projectName, $folder)
    {
        $safeType = preg_replace('/[^a-z0-9_\-]/', '_', strtolower($type));
        $filename = "{$folder}/" . $safeType . "_" . uniqid() . ".pdf";

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(130, 193, 125); // #82C17D
        $pdf->Cell(0, 20, 'DOKUMEN RESMI SIMOP', 0, 1, 'C');
        $pdf->Ln(20);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 10, 'Jenis: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $type, 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Proyek: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $projectName, 0, 1);

        $pdf->Ln(20);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->MultiCell(0, 10, 'Ini adalah dokumen otomatis yang dihasilkan oleh UAT Seeder untuk keperluan pengujian sistem manajemen operasional KJPP.', 0, 'C');

        Storage::disk('public')->put($filename, $pdf->Output('S'));

        return $filename;
    }
}
