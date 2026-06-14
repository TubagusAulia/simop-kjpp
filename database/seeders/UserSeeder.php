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
use App\Models\KoleksiDokumen;
use App\Models\KoleksiFisik;
use App\Models\KoleksiNilai;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        // ── 0. Prep: DB + Storage Cleanup ──
        // Wipe all tables and reset SQLite autoincrement
        DB::connection()->getPdo()->exec('PRAGMA foreign_keys = OFF');
        $tables = [
            'nilai', 'aspek_fisik', 'checklist_fisik', 'dokumen_properti',
            'koleksi_nilai', 'koleksi_fisik', 'koleksi_dokumen',
            'alokasi_proyek', 'properti', 'proyek', 'users',
        ];
        foreach ($tables as $table) {
            DB::connection()->getPdo()->exec("DELETE FROM \"{$table}\"");
        }
        // Reset autoincrement counters
        DB::connection()->getPdo()->exec("DELETE FROM sqlite_sequence WHERE name IN ('" . implode("','", $tables) . "')");
        DB::connection()->getPdo()->exec('PRAGMA foreign_keys = ON');

        Storage::disk('public')->deleteDirectory('kontrak');
        Storage::disk('public')->deleteDirectory('dokumen');
        Storage::disk('public')->deleteDirectory('aspek-fisik');
        Storage::disk('public')->makeDirectory('kontrak');
        Storage::disk('public')->makeDirectory('dokumen');
        Storage::disk('public')->makeDirectory('aspek-fisik');

        // ── 0b. Ensure storage symlink exists ──
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');
        if (!file_exists($linkPath) && !is_link($linkPath)) {
            $created = @symlink($targetPath, $linkPath);
            if (!$created) {
                // Fallback: try exec (Windows may need elevated perms for symlink)
                @exec("mklink /D " . escapeshellarg($linkPath) . " " . escapeshellarg($targetPath) . " 2>&1");
            }
        }

        // ── 1. Create Users ──
        $users = $this->createUsers();

        // ── 2. Create Projects (5 feature-logic test scenarios) ──
        $this->createProjectA($users); // Phase: dokumen — All empty
        $this->createProjectB($users); // Phase: dokumen — Docs almost complete (-1)
        $this->createProjectC($users); // Phase: fisik — Docs verified, partial fisik
        $this->createProjectD($users); // Phase: dinilai — All verified, draft nilai
        $this->createProjectE($users); // Phase: selesai — Everything complete
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
                'password' => Hash::make('password'),
                'role' => $u['role'],
            ]);
        }
        return $created;
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK A — Phase: dokumen, All Empty
    // tanah_kosong — no docs, no checklist, no aspek, no nilai
    // Tests: empty state UI, upload prompt, phase blockers
    // ═══════════════════════════════════════════════════════════════
    private function createProjectA($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Tanah Kosong Subang (Proyek A)',
            'deskripsi' => 'Fase: Verifikasi Dokumen. Semua kosong — tes empty state.',
            'start_date' => now(),
            'due_date' => now()->addMonths(1),
            'status' => 'aktif',
            'current_phase' => 'dokumen',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Tanah Kosong Subang (Proyek A)', 'kontrak'),
        ]);

        $this->getOrCreateProperti($p, 'tanah_kosong');
        $this->assign($p, [$users['karyawan1'], $users['klien1'], $users['mitra1']]);

        // Nothing else — completely empty
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK B — Phase: dokumen, Docs Almost Complete
    // rumah_tinggal — 5 mandatory: 4 verified, 1 menunggu (imb_pbg)
    // 2 optional: 1 verified (stts_pbb), 1 menunggu (denah_arsitektur)
    // No fisik, no nilai
    // Tests: partial verification UI, "almost done" state
    // ═══════════════════════════════════════════════════════════════
    private function createProjectB($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Rumah Tinggal Antasari (Proyek B)',
            'deskripsi' => 'Fase: Verifikasi Dokumen. Dokumen wajib hampir lengkap (-1), 2 dokumen opsional.',
            'start_date' => now()->subDays(10),
            'due_date' => now()->addMonths(1),
            'status' => 'aktif',
            'current_phase' => 'dokumen',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Rumah Tinggal Antasari (Proyek B)', 'kontrak'),
        ]);

        $properti = $this->getOrCreateProperti($p, 'rumah_tinggal');
        $this->assign($p, [$users['karyawan1'], $users['karyawan2'], $users['klien1'], $users['klien2'], $users['mitra1'], $users['mitra2']]);

        // Mandatory: 4 verified
        $this->addDoc($properti, 'sertifikat_utama', 'Sertifikat Hak Milik No. 5678', $users['klien1'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($properti, 'pbb_terakhir', 'PBB Tahun 2025', $users['klien1'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($properti, 'identitas_pemilik', 'KTP Pemilik Aset', $users['klien1'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($properti, 'shm_hgb_skur', 'SHM + Surat Ukur No 567', $users['klien1'], $users['karyawan2'], 'terverifikasi');

        // Mandatory: 1 menunggu (the "-1" not yet verified)
        $this->addDoc($properti, 'imb_pbg', 'IMB No. 2019/045', $users['klien1'], null, 'menunggu');

        // Optional: 1 verified
        $this->addDoc($properti, 'stts_pbb', 'STTS PBB Tahun Terakhir', $users['klien1'], $users['karyawan2'], 'terverifikasi');

        // Optional: 1 menunggu
        $this->addDoc($properti, 'denah_arsitektur', 'Gambar Denah Arsitektur', $users['klien1'], null, 'menunggu');
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK C — Phase: fisik, Docs All Verified, Partial Fisik
    // gudang_penyimpanan — 7 mandatory docs all verified
    // 3 checklist wajib: 2 have aspek (1 verified, 1 menunggu), 1 belum
    // 2 optional aspek: 1 verified, 1 menunggu
    // No nilai
    // Tests: fisik tab with mixed statuses, verification flow
    // ═══════════════════════════════════════════════════════════════
    private function createProjectC($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Gudang Logistik Priok (Proyek C)',
            'deskripsi' => 'Fase: Verifikasi Fisik. Dokumen semua verified, fisik sebagian.',
            'start_date' => now()->subMonths(1),
            'due_date' => now()->addMonths(1),
            'status' => 'aktif',
            'current_phase' => 'fisik',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Gudang Logistik Priok (Proyek C)', 'kontrak'),
        ]);

        $properti = $this->getOrCreateProperti($p, 'gudang_penyimpanan');
        $this->assign($p, [$users['karyawan1'], $users['karyawan3'], $users['klien1'], $users['klien3'], $users['mitra1'], $users['mitra3']]);

        // All 7 mandatory docs verified
        $mandatories = ['sertifikat_utama', 'pbb_terakhir', 'identitas_pemilik', 'shm_hgb_lengkap', 'imb_pbg_gudang', 'denah_loading_dock', 'daftar_aktiva_me_handling'];
        foreach ($mandatories as $m) {
            $this->addDoc($properti, $m, "Dokumen Wajib: $m", $users['klien3'], $users['karyawan3'], 'terverifikasi');
        }

        // 3 checklist fisik wajib
        $cl1 = $this->addChecklist($properti, 'Struktur Rangka Baja', $users['karyawan3']);
        $cl2 = $this->addChecklist($properti, 'Kondisi Lantai Beton', $users['karyawan3']);
        $cl3 = $this->addChecklist($properti, 'Akses Jalan & Loading Dock', $users['karyawan3']);

        // Checklist 1: has aspek, verified
        $this->addAspekFisik($properti, $cl1, 'Struktur Rangka Baja', 'Struktur baja dalam kondisi baik, tidak ada karat.', $users['karyawan3'], $users['karyawan3'], 'terverifikasi', -6.1056, 106.8913);

        // Checklist 2: has aspek, menunggu
        $this->addAspekFisik($properti, $cl2, 'Kondisi Lantai Beton', 'Lantai beton retak di bagian barat.', $users['karyawan3'], $users['karyawan3'], 'menunggu', -6.1057, 106.8914);

        // Checklist 3: belum ada aspek (no AspekFisik created)

        // 2 optional aspek fisik (standalone, no checklist)
        $this->addOptionalAspekFisik($properti, 'Peralatan Handling', 'Forklift dan conveyor dalam kondisi operasional.', $users['karyawan3'], $users['karyawan3'], 'terverifikasi', -6.1058, 106.8915);
        $this->addOptionalAspekFisik($properti, 'Sistem Insulasi & Pendingin', 'Insulasi atap perlu perbaikan.', $users['karyawan3'], $users['karyawan3'], 'menunggu', -6.1059, 106.8916);

        // Koleksi dokumen selesai (phase already advanced to fisik)
        $this->completeKoleksi($properti, 'dokumen', $users['karyawan3']->id);
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK D — Phase: dinilai, All Verified, Draft Nilai (catatan empty)
    // rumah_tinggal — 5 mandatory docs all verified, 2 checklist+aspek verified
    // 1 optional aspek verified, nilai drafted with empty catatan
    // ═══════════════════════════════════════════════════════════════
    private function createProjectD($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Rumah Tinggal Ciganjur (Proyek D)',
            'deskripsi' => 'Fase: Penilaian. Semua verified, nilai draft (catatan kosong).',
            'start_date' => now()->subMonths(3),
            'due_date' => now()->addMonths(1),
            'status' => 'aktif',
            'current_phase' => 'dinilai',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Rumah Tinggal Ciganjur (Proyek D)', 'kontrak'),
        ]);

        $properti = $this->getOrCreateProperti($p, 'rumah_tinggal');
        $this->assign($p, [$users['karyawan1'], $users['karyawan2'], $users['klien1'], $users['klien2'], $users['mitra1']]);

        // All 5 mandatory docs verified
        $this->addDoc($properti, 'sertifikat_utama', 'Sertifikat Hak Milik No. 9901', $users['klien1'], $users['karyawan1'], 'terverifikasi');
        $this->addDoc($properti, 'pbb_terakhir', 'PBB Tahun 2025', $users['klien1'], $users['karyawan1'], 'terverifikasi');
        $this->addDoc($properti, 'identitas_pemilik', 'KTP Pemilik Aset', $users['klien1'], $users['karyawan1'], 'terverifikasi');
        $this->addDoc($properti, 'shm_hgb_skur', 'SHM + Surat Ukur No 990', $users['klien1'], $users['karyawan1'], 'terverifikasi');
        $this->addDoc($properti, 'imb_pbg', 'IMB No. 2020/123', $users['klien1'], $users['karyawan1'], 'terverifikasi');

        // 2 checklist fisik wajib, both verified
        $cl1 = $this->addChecklist($properti, 'Struktur Bangunan Utama', $users['karyawan1']);
        $cl2 = $this->addChecklist($properti, 'Kondisi Fasad & Eksterior', $users['karyawan1']);
        $this->addAspekFisik($properti, $cl1, 'Struktur Bangunan Utama', 'Struktur dalam kondisi baik.', $users['karyawan1'], $users['karyawan1'], 'terverifikasi', -6.3150, 106.8050);
        $this->addAspekFisik($properti, $cl2, 'Kondisi Fasad & Eksterior', 'Fasad cat baik.', $users['karyawan1'], $users['karyawan1'], 'terverifikasi', -6.3151, 106.8051);

        // 1 optional aspek fisik, verified
        $this->addOptionalAspekFisik($properti, 'Perhalaman & Taman', 'Taman depan terawat baik.', $users['karyawan1'], $users['karyawan1'], 'terverifikasi', -6.3152, 106.8052);

        // Nilai: drafted (value filled, catatan still empty)
        $this->addNilai($properti, 3500000000, '', $users['karyawan1']);

        // Koleksi dokumen + fisik selesai (phase already advanced to dinilai)
        $this->completeKoleksi($properti, 'dokumen', $users['karyawan1']->id);
        $this->completeKoleksi($properti, 'fisik', $users['karyawan1']->id);
    }

    // ═══════════════════════════════════════════════════════════════
    // PROYEK E — Phase: selesai, Everything Complete
    // tanah_kosong — all docs, fisik, nilai & catatan filled, project selesai
    // ═══════════════════════════════════════════════════════════════
    private function createProjectE($users)
    {
        $p = Proyek::create([
            'nama_proyek' => 'Penilaian Tanah Kosong Bandung (Proyek E)',
            'deskripsi' => 'Fase: Selesai. Semua lengkap, proyek telah diselesaikan.',
            'start_date' => now()->subMonths(4),
            'due_date' => now()->subWeek(),
            'status' => 'selesai',
            'current_phase' => 'selesai',
            'created_by' => $users['admin']->id,
            'kontrak_file' => $this->genPdf('Kontrak Kerja', 'Penilaian Tanah Kosong Bandung (Proyek E)', 'kontrak'),
        ]);

        $properti = $this->getOrCreateProperti($p, 'tanah_kosong');
        $this->assign($p, [$users['karyawan2'], $users['klien2'], $users['mitra2']]);

        // All 4 mandatory docs verified (3 global + 1 type-specific for tanah_kosong)
        $this->addDoc($properti, 'sertifikat_utama', 'Sertifikat Hak Milik No. 7701', $users['klien2'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($properti, 'pbb_terakhir', 'PBB Tahun 2025', $users['klien2'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($properti, 'identitas_pemilik', 'KTP Pemilik Aset', $users['klien2'], $users['karyawan2'], 'terverifikasi');
        $this->addDoc($properti, 'shm_hgb_skur', 'SHM + Surat Ukur No 770', $users['klien2'], $users['karyawan2'], 'terverifikasi');

        // 2 checklist fisik wajib, both verified
        $cl1 = $this->addChecklist($properti, 'Kondisi Tanah & Topografi', $users['karyawan2']);
        $cl2 = $this->addChecklist($properti, 'Akses Jalan & Batas Tanah', $users['karyawan2']);
        $this->addAspekFisik($properti, $cl1, 'Kondisi Tanah & Topografi', 'Tanah memiliki kontur datar, tidak ada genangan.', $users['karyawan2'], $users['karyawan2'], 'terverifikasi', -6.9175, 107.6191);
        $this->addAspekFisik($properti, $cl2, 'Akses Jalan & Batas Tanah', 'Akses jalan baik, batas tanah jelas.', $users['karyawan2'], $users['karyawan2'], 'terverifikasi', -6.9176, 107.6192);

        // 1 optional aspek fisik, verified
        $this->addOptionalAspekFisik($properti, 'Peta Topografi', 'Topografi tanah datar, cocok untuk bangunan.', $users['karyawan2'], $users['karyawan2'], 'terverifikasi', -6.9177, 107.6193);

        // Nilai + catatan filled
        $this->addNilai($properti, 50000000000, 'Penilaian berdasarkan perbandingan pasar di kawasan Bandung Utara. Tanah kosong siap bangun dengan akses strategis.', $users['karyawan2']);

        // All 3 koleksi selesai (project completed)
        $this->completeKoleksi($properti, 'dokumen', $users['karyawan2']->id);
        $this->completeKoleksi($properti, 'fisik', $users['karyawan2']->id);
        $this->completeKoleksi($properti, 'nilai', $users['karyawan2']->id);

        // Finish request: karyawan requested, admin accepted
        $p->update([
            'finish_requested' => true,
            'finish_requested_by' => $users['karyawan2']->id,
            'finish_requested_at' => now()->subDays(3),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    // HELPER METHODS
    // ═══════════════════════════════════════════════════════════════

    private function getOrCreateProperti($proyek, $tipe)
    {
        $properti = Properti::where('proyek_id', $proyek->id)->first();
        if (!$properti) {
            $properti = $proyek->properti()->create([
                'nama_properti' => $proyek->nama_proyek,
                'tipe_properti' => $tipe,
            ]);
        } else {
            $properti->update(['tipe_properti' => $tipe]);
        }
        // Create koleksi if they don't exist
        if (!$properti->koleksiDokumen) {
            $globalReqs = \App\Services\DocumentRequirementService::getGlobalRequirements();
            $typeReqs = \App\Services\DocumentRequirementService::getTypeRequirements($tipe);
            $wajibKeys = ($typeReqs)
                ? array_merge(array_keys($globalReqs), array_keys($typeReqs['mandatory'] ?? []))
                : array_keys($globalReqs);
            KoleksiDokumen::create(['properti_id' => $properti->id, 'wajib_list' => $wajibKeys]);
        }
        if (!$properti->koleksiFisik) {
            KoleksiFisik::create(['properti_id' => $properti->id]);
        }
        if (!$properti->koleksiNilai) {
            KoleksiNilai::create(['properti_id' => $properti->id]);
        }
        return $properti;
    }

    /**
     * Mark a koleksi as complete (selesai) by a karyawan.
     */
    private function completeKoleksi($properti, string $type, int $userId): void
    {
        $model = match ($type) {
            'dokumen' => KoleksiDokumen::class,
            'fisik' => KoleksiFisik::class,
            'nilai' => KoleksiNilai::class,
            default => null,
        };
        if ($model) {
            $model::where('properti_id', $properti->id)->update([
                'status' => 'selesai',
                'completed_at' => now(),
                'completed_by' => $userId,
            ]);
        }
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

    private function addDoc($properti, $type, $name, $uploader, $verifier = null, $status = 'terverifikasi')
    {
        $path = $this->genPdf($name, $properti->proyek->nama_proyek, 'dokumen');

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

    /**
     * Add an optional (standalone) aspek fisik — not tied to a checklist item.
     */
    private function addOptionalAspekFisik($properti, $nama, $deskripsi, $creator, $verifier, $status, $lat, $lng)
    {
        return AspekFisik::create([
            'properti_id' => $properti->id,
            'checklist_fisik_id' => null,
            'nama_aspek' => $nama,
            'deskripsi' => $deskripsi,
            'foto_paths' => [$this->genPlaceholderFoto()],
            'latitude' => $lat,
            'longitude' => $lng,
            'tipe' => 'opsional',
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
