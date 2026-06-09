<?php

namespace App\Services;

class DocumentRequirementService
{
    /**
     * Get the global mandatory document requirements.
     */
    public static function getGlobalRequirements()
    {
        return [
            'sertifikat_utama' => 'Sertifikat Kepemilikan Utama',
            'pbb_terakhir' => 'PBB Tahun Terakhir',
            'identitas_pemilik' => 'Identitas Pemilik Aset (KTP/Akta)',
        ];
    }

    /**
     * Get the global optional document requirements.
     */
    public static function getGlobalOptionalRequirements()
    {
        return [
            'stts_pbb' => 'STTS PBB Tahun Terakhir',
            'npwp_pemilik' => 'NPWP Pemilik Aset',
            'foto_awal' => 'Foto-foto Awal Lokasi (Client)',
        ];
    }

    /**
     * Get all available property types.
     */
    public static function getAllTypes()
    {
        return [
            'tanah_kosong' => 'Nomor 1. Tanah Kosong',
            'rumah_tinggal' => 'Nomor 2. Rumah Tinggal (Lengkap IMB)',
            'rumah_tinggal_asumsi' => 'Nomor 2. Rumah Tinggal (Tanpa IMB - Asumsi Khusus)',
            'ruko_rukan' => 'Nomor 3. Ruko / Rukan',
            'apartemen_kondominium' => 'Nomor 4. Apartemen / Kondominium',
            'gedung_kantor' => 'Nomor 5. Gedung Kantor',
            'hotel' => 'Nomor 6. Hotel',
            'pusat_perbelanjaan' => 'Nomor 7. Pusat Perbelanjaan / Mall',
            'rumah_sakit' => 'Nomor 8. Rumah Sakit',
            'pabrik_industri' => 'Nomor 9. Pabrik / Industri',
            'gudang_penyimpanan' => 'Nomor 10. Gudang',
            'bengkel_servis' => 'Nomor 11. Bengkel',
            'sekolah_pendidikan' => 'Nomor 12. Sekolah',
            'rumah_ibadah' => 'Nomor 13. Rumah Ibadah',
            'restoran_cafe' => 'Nomor 14. Restoran / Cafe',
            'showroom' => 'Nomor 15. Showroom',
            'kios_stand' => 'Nomor 16. Kios / Stand',
            'hiburan_rekreasi' => 'Nomor 17. Tempat Hiburan',
            'studio_aula' => 'Nomor 18. Studio / Aula',
            'layanan_kesehatan_lain' => 'Nomor 19. Layanan Kesehatan Lain',
        ];
    }

    /**
     * Get the document requirements for a specific property type.
     */
    public static function getTypeRequirements($type)
    {
        $types = [
            'tanah_kosong' => [
                'name' => 'Tanah Kosong (Residensial/Komersial/Industri/Pertanian)',
                'mandatory' => [
                    'shm_hgb_skur' => 'Sertifikat Hak Milik/HGB + Surat Ukur/GS',
                ],
                'optional' => [
                    'peta_topografi' => 'Peta Topografi/Kontur Tanah',
                    'site_plan' => 'Rencana Induk/Site Plan Kawasan',
                ]
            ],
            'rumah_tinggal' => [
                'name' => 'Rumah Tinggal (Bangunan Hunian Tunggal)',
                'mandatory' => [
                    'shm_hgb_skur' => 'Sertifikat Hak Milik/HGB + Surat Ukur/GS',
                    'imb_pbg' => 'IMB atau Persetujuan Bangunan Gedung (PBG)',
                ],
                'optional' => [
                    'denah_arsitektur' => 'Gambar Denah Arsitektur',
                    'rab_material' => 'Rencana Anggaran Biaya & Spesifikasi Material',
                    'perjanjian_sewa' => 'Perjanjian Sewa (Jika dikontrakkan)',
                ]
            ],
            'rumah_tinggal_asumsi' => [
                'name' => 'Rumah Tinggal (Tanpa IMB - Asumsi Khusus)',
                'mandatory' => [
                    'shm_hgb_skur' => 'Sertifikat Hak Milik/HGB + Surat Ukur/GS',
                    // IMB removed from mandatory
                ],
                'optional' => [
                    'denah_arsitektur' => 'Gambar Denah Arsitektur',
                    'rab_material' => 'Rencana Anggaran Biaya & Spesifikasi Material',
                    'perjanjian_sewa' => 'Perjanjian Sewa (Jika dikontrakkan)',
                ]
            ],
            'ruko_rukan' => [
                'name' => 'Rumah Toko dan Rumah Kantor (Ruko/Rukan)',
                'mandatory' => [
                    'shm_hgb_skur' => 'Sertifikat Hak Milik/HGB + Surat Ukur/GS',
                    'imb_pbg_komersial' => 'IMB/PBG (Penggunaan Hunian & Komersial)',
                ],
                'optional' => [
                    'denah_arsitektur' => 'Gambar Denah Arsitektur (Hunian & Usaha)',
                    'rab' => 'Rencana Anggaran Biaya',
                    'perjanjian_sewa' => 'Perjanjian Sewa-Menyewa',
                ]
            ],
            'apartemen_kondominium' => [
                'name' => 'Apartemen dan Kondominium (Satuan Rumah Susun)',
                'mandatory' => [
                    'shmrs' => 'Sertifikat Hak Milik Atas Satuan Rumah Susun (Detail Unit)',
                    'slf_gedung' => 'Sertifikat Izin Layak Fungsi Gedung',
                ],
                'optional' => [
                    'pertelaan_akta_pemisahan' => 'Dokumen Pertelaan & Akta Pemisahan (NPP)',
                    'konfirmasi_pengelola' => 'Surat Konfirmasi Pengelola (Tunggakan/Dana Cadangan)',
                    'perjanjian_sewa' => 'Perjanjian Sewa',
                ]
            ],
            'gedung_kantor' => [
                'name' => 'Gedung Kantor (Bangunan Perkantoran Bertingkat)',
                'mandatory' => [
                    'hgb_induk' => 'Sertifikat Hak Guna Bangunan Induk',
                    'imb_pbg_lengkap' => 'IMB/PBG (Struktur & Penunjang)',
                    'as_built_drawing' => 'Gambar Denah Arsitektur / As-Built Drawing (Luas Bersih)',
                    'daftar_aktiva_me' => 'Daftar Aktiva Tetap (Mekanikal & Elektrikal)',
                ],
                'optional' => [
                    'laporan_keuangan_3th' => 'Laporan Keuangan Audit (3 Tahun Terakhir)',
                    'perizinan_lingkungan' => 'Dokumen Perizinan Lingkungan',
                    'inspeksi_keselamatan' => 'Laporan Inspeksi Keselamatan Bangunan',
                ]
            ],
            'hotel' => [
                'name' => 'Hotel dan Properti Perhotelan',
                'mandatory' => [
                    'hgb_induk' => 'Sertifikat Hak Guna Bangunan Induk (Detail Kedaluwarsa)',
                    'imb_pbg_hotel' => 'IMB/PBG (Fasilitas Hotel)',
                    'denah_publik_kamar' => 'Gambar Denah Arsitektur (Kamar, Publik, Pendukung)',
                    'daftar_aktiva_hotel' => 'Daftar Aktiva Tetap (Furniture & Dapur)',
                    'laporan_keuangan_3th' => 'Laporan Keuangan Audit (3 Tahun Terakhir)',
                ],
                'optional' => [
                    'lisensi_bintang' => 'Lisensi Hotel / Sertifikat Bintang',
                    'perizinan_lingkungan' => 'Dokumen Perizinan Lingkungan',
                    'analisis_okupansi' => 'Analisis Okupansi dan Tarif Kamar Historis',
                ]
            ],
            'pusat_perbelanjaan' => [
                'name' => 'Pusat Perbelanjaan dan Mall',
                'mandatory' => [
                    'hgb_induk' => 'Sertifikat Hak Guna Bangunan Induk',
                    'imb_pbg_mall' => 'IMB/PBG (Struktur Keseluruhan)',
                    'denah_zona_komersial' => 'Gambar Denah Arsitektur (Luas Lantai Bersih Sewa)',
                    'daftar_aktiva_me_lift' => 'Daftar Aktiva Tetap (ME, Lift, Eskalator)',
                    'laporan_keuangan_3th' => 'Laporan Keuangan Audit (3 Tahun Terakhir)',
                ],
                'optional' => [
                    'daftar_tenant' => 'Daftar Tenant dan Perjanjian Sewa Utama',
                    'analisis_okupansi_sewa' => 'Analisis Okupansi dan Tingkat Sewa',
                    'perizinan_lingkungan' => 'Dokumen Perizinan Lingkungan',
                ]
            ],
            'rumah_sakit' => [
                'name' => 'Rumah Sakit dan Fasilitas Kesehatan',
                'mandatory' => [
                    'hgb_induk' => 'Sertifikat Hak Guna Bangunan Induk',
                    'imb_pbg_medis' => 'IMB/PBG (Fasilitas Medis)',
                    'denah_medis' => 'Gambar Denah Arsitektur (Operasi, Inap, Lab)',
                    'daftar_aktiva_medis_me' => 'Daftar Aktiva Tetap (Alat Medis & ME)',
                    'laporan_keuangan_3th' => 'Laporan Keuangan Audit (3 Tahun Terakhir)',
                ],
                'optional' => [
                    'akreditasi_rs' => 'Sertifikat Akreditasi Rumah Sakit',
                    'perizinan_kesehatan' => 'Dokumen Perizinan Kesehatan',
                    'analisis_okupansi_tarif' => 'Analisis Okupansi dan Tarif Rawat Inap',
                    'limbah_medis' => 'Dokumen Lingkungan (Limbah Medis)',
                ]
            ],
            'pabrik_industri' => [
                'name' => 'Pabrik dan Fasilitas Industri',
                'mandatory' => [
                    'hgb_induk' => 'Sertifikat Hak Guna Bangunan Induk',
                    'imb_pbg_industri' => 'IMB/PBG (Produksi & Penunjang)',
                    'denah_layout_mesin' => 'Gambar Denah & Engineering Drawing (Layout Mesin)',
                    'daftar_aktiva_mesin' => 'Daftar Aktiva Tetap (Mesin & Kondisi)',
                    'amdal_ukl_upl' => 'Dokumen Perizinan Lingkungan (AMDAL/UKL-UPL)',
                    'laporan_keuangan_3th' => 'Laporan Keuangan Audit (3 Tahun Terakhir)',
                ],
                'optional' => [
                    'nib_operasional' => 'Izin Operasional & Nomor Induk Berusaha (NIB)',
                    'sertifikat_iso' => 'Sertifikat ISO / Standar Industri',
                    'maintenance_schedule' => 'Laporan Kondisi & Schedule Pemeliharaan Mesin',
                ]
            ],
            'gudang_penyimpanan' => [
                'name' => 'Gudang dan Fasilitas Penyimpanan (Warehouse)',
                'mandatory' => [
                    'shm_hgb_lengkap' => 'Sertifikat Hak Milik/HGB Lengkap',
                    'imb_pbg_gudang' => 'IMB/PBG (Struktur Gudang)',
                    'denah_loading_dock' => 'Gambar Denah Arsitektur (Layout & Loading Dock)',
                    'daftar_aktiva_me_handling' => 'Daftar Aktiva Tetap (ME & Equipment Handling)',
                ],
                'optional' => [
                    'izin_b3' => 'Dokumen Perizinan Lingkungan (Bahan Berbahaya)',
                    'perjanjian_sewa' => 'Perjanjian Sewa-Menyewa Utama',
                    'kondisi_bangunan_insulasi' => 'Laporan Kondisi Bangunan (Insulasi & Pendingin)',
                ]
            ],
            'bengkel_servis' => [
                'name' => 'Bengkel dan Pusat Servis Otomotif',
                'mandatory' => [
                    'shm_hgb' => 'Sertifikat Hak Milik/HGB',
                    'imb_pbg_servis' => 'IMB/PBG (Peruntukan Servis)',
                    'denah_servis_admin' => 'Gambar Denah Arsitektur (Servis, Part, Admin)',
                    'daftar_aktiva_me_peralatan' => 'Daftar Aktiva Tetap (Peralatan Servis & ME)',
                ],
                'optional' => [
                    'limbah_oli_kimia' => 'Dokumen Perizinan Lingkungan (Limbah Oli/Kimia)',
                    'perjanjian_sewa' => 'Perjanjian Sewa (Jika dikontrakkan)',
                    'laporan_omset' => 'Laporan Omset / Keuangan (Pendekatan Pendapatan)',
                ]
            ],
            'sekolah_pendidikan' => [
                'name' => 'Sekolah dan Institusi Pendidikan',
                'mandatory' => [
                    'shm_hgb_kedaluwarsa' => 'Sertifikat Hak Milik/HGB (Detail Kedaluwarsa)',
                    'imb_pbg_pendidikan' => 'IMB/PBG (Fasilitas Pendidikan)',
                    'denah_lab_kelas' => 'Gambar Denah Arsitektur (Kelas, Lab, Pendukung)',
                    'daftar_aktiva_me_pendidikan' => 'Daftar Aktiva Tetap (Alat Pendidikan & ME)',
                ],
                'optional' => [
                    'izin_operasional' => 'Izin Operasional Institusi Pendidikan',
                    'akreditasi_sekolah' => 'Akreditasi Sekolah atau Program Studi',
                    'laporan_keuangan' => 'Laporan Keuangan (Biaya Pendidikan)',
                ]
            ],
            'rumah_ibadah' => [
                'name' => 'Rumah Ibadah (Masjid/Gereja/Vihara/Pura/Kuil)',
                'mandatory' => [
                    'shm_hgb' => 'Sertifikat Hak Milik/HGB',
                    'imb_pbg' => 'IMB atau Persetujuan Bangunan Gedung (PBG)',
                    'denah_area_ibadah' => 'Gambar Denah Arsitektur (Area Utama & Pendukung)',
                    'identitas_yayasan' => 'Identitas Pengelola Yayasan (Akte Notaris/Surat Daftar)',
                ],
                'optional' => [
                    'izin_pemda_kemenag' => 'Dokumen Izin Pemda / Kementerian Agama',
                    'laporan_keuangan_yayasan' => 'Laporan Keuangan Yayasan',
                ]
            ],
            'restoran_cafe' => [
                'name' => 'Restoran dan Cafe (Fasilitas Kuliner)',
                'mandatory' => [
                    'shm_hgb' => 'Sertifikat Hak Milik/HGB',
                    'imb_pbg_restoran' => 'IMB/PBG (Peruntukan Restoran)',
                    'denah_makan_dapur' => 'Gambar Denah Arsitektur (Makan, Dapur, Gudang)',
                    'daftar_aktiva_dapur_me' => 'Daftar Aktiva Tetap (Alat Dapur & ME)',
                ],
                'optional' => [
                    'izin_usaha_kuliner' => 'Izin Usaha Restoran / Cafe',
                    'kesehatan_lingkungan' => 'Sertifikat Kesehatan Lingkungan',
                    'perjanjian_sewa' => 'Perjanjian Sewa (Jika dikontrakkan)',
                    'laporan_keuangan_historis' => 'Laporan Keuangan atau Omset Historis',
                ]
            ],
            'showroom' => [
                'name' => 'Showroom dan Ruang Pamer Penjualan',
                'mandatory' => [
                    'shm_hgb' => 'Sertifikat Hak Milik/HGB',
                    'imb_pbg_komersial' => 'IMB/PBG (Peruntukan Komersial Pamer)',
                    'denah_pamer_parkir' => 'Gambar Denah Arsitektur (Pamer, Office, Parkir)',
                    'daftar_aktiva_pencahayaan_me' => 'Daftar Aktiva Tetap (Pencahayaan & ME)',
                ],
                'optional' => [
                    'perjanjian_sewa' => 'Perjanjian Sewa dengan Pemilik',
                    'laporan_penjualan_historis' => 'Laporan Penjualan Historis',
                    'perizinan_lingkungan' => 'Dokumen Perizinan Lingkungan',
                ]
            ],
            'kios_stand' => [
                'name' => 'Kios dan Stand (Pusat Perbelanjaan/Bandara)',
                'mandatory' => [
                    'shmrs_kepemilikan' => 'SHMRS atau Dokumen Kepemilikan Unit Kompleks',
                    'surat_pengelola' => 'Surat Kepemilikan Unit dari Pengelola',
                    'denah_layout_unit' => 'Gambar Denah Arsitektur (Layout Unit & Area Bersama)',
                    'status_okupansi' => 'Dokumen Konfirmasi Status Okupansi (Manajemen)',
                ],
                'optional' => [
                    'dokumen_pertelaan' => 'Dokumen Pertelaan (Jika diperlukan)',
                    'perjanjian_sewa_ketiga' => 'Perjanjian Sewa-Menyewa (Pihak Ketiga)',
                    'laporan_pendapatan_sewa' => 'Laporan Pendapatan Sewa',
                ]
            ],
            'hiburan_rekreasi' => [
                'name' => 'Tempat Hiburan dan Rekreasi (Bioskop/Karaoke/Taman)',
                'mandatory' => [
                    'shm_hgb' => 'Sertifikat Hak Milik/HGB',
                    'imb_pbg_hiburan' => 'IMB/PBG (Peruntukan Hiburan)',
                    'denah_publik_teknis' => 'Gambar Denah Arsitektur (Publik, Pertunjukan, Teknis)',
                    'daftar_aktiva_av_me' => 'Daftar Aktiva Tetap (Audio Visual & ME)',
                    'laporan_keuangan_3th' => 'Laporan Keuangan Audit (3 Tahun Terakhir)',
                ],
                'optional' => [
                    'izin_usaha_hiburan' => 'Izin Usaha Tempat Hiburan (Pemda)',
                    'keselamatan_bangunan' => 'Sertifikat Keselamatan Bangunan',
                    'kebisingan_lingkungan' => 'Dokumen Perizinan Lingkungan (Kebisingan)',
                ]
            ],
            'studio_aula' => [
                'name' => 'Studio dan Aula Serbaguna',
                'mandatory' => [
                    'shm_hgb' => 'Sertifikat Hak Milik/HGB',
                    'imb_pbg' => 'IMB atau Persetujuan Bangunan Gedung (PBG)',
                    'denah_studio_aula' => 'Gambar Denah Arsitektur (Studio/Aula & Pendukung)',
                    'daftar_aktiva_studio_me' => 'Daftar Aktiva Tetap (Kamera, Lighting, ME)',
                ],
                'optional' => [
                    'izin_usaha_serbaguna' => 'Izin Usaha Tempat Serbaguna',
                    'laporan_pendapatan_booking' => 'Laporan Pendapatan (Pemesanan Historis)',
                    'perizinan_lingkungan' => 'Dokumen Perizinan Lingkungan',
                ]
            ],
            'layanan_kesehatan_lain' => [
                'name' => 'Layanan Kesehatan Lainnya (Klinik/Apotek/Lab)',
                'mandatory' => [
                    'shm_hgb' => 'Sertifikat Hak Milik/HGB',
                    'imb_pbg_kesehatan' => 'IMB/PBG (Peruntukan Kesehatan)',
                    'denah_praktek_lab' => 'Gambar Denah Arsitektur (Praktek, Lab, Pendukung)',
                    'daftar_aktiva_medis_me' => 'Daftar Aktiva Tetap (Alat Medis & ME)',
                ],
                'optional' => [
                    'izin_klinik_praktek' => 'Sertifikat Izin Klinik atau Praktek (Dinkes)',
                    'laporan_keuangan_omset' => 'Laporan Keuangan atau Omset Historis',
                    'limbah_medis' => 'Dokumen Perizinan Lingkungan (Limbah Medis)',
                ]
            ],
        ];

        return $types[$type] ?? null;
    }

    /**
     * Check if a property can proceed to physical verification.
     */
    public static function canProceed($properti)
    {
        $typeReqs = self::getTypeRequirements($properti->tipe_properti);
        if (!$typeReqs) return false;

        $globalMandatory = array_keys(self::getGlobalRequirements());
        $typeMandatory = array_keys($typeReqs['mandatory']);

        $allMandatory = array_merge($globalMandatory, $typeMandatory);
        
        $verifiedDocs = $properti->dokumens()
            ->where('status', 'terverifikasi')
            ->pluck('tipe_dokumen')
            ->toArray();

        foreach ($allMandatory as $req) {
            if (!in_array($req, $verifiedDocs)) {
                return false;
            }
        }

        return true;
    }
}
