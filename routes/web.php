<?php

use App\Http\Controllers\AspekFisikController;
use App\Http\Controllers\ChecklistFisikController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenPropertiController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertiController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\UserController;
use App\Models\Proyek;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // ===== PROYEK (Centralized) =====
    Route::get('/proyek', [ProyekController::class, 'index'])->name('proyek.index');
    Route::get('/proyek/create', [ProyekController::class, 'create'])->name('proyek.create');
    Route::post('/proyek', [ProyekController::class, 'store'])->name('proyek.store');
    Route::get('/proyek/{proyek}', [ProyekController::class, 'show'])->name('proyek.show');
    Route::post('/proyek/{proyek}/request-finish', [ProyekController::class, 'requestFinish'])->name('proyek.request-finish');
    Route::post('/proyek/{proyek}/accept-finish', [ProyekController::class, 'acceptFinish'])->name('proyek.accept-finish');
    Route::post('/proyek/{proyek}/selesai-verifikasi-dokumen', [ProyekController::class, 'selesaiVerifikasiDokumen'])->name('proyek.selesai-verifikasi-dokumen');
    Route::post('/proyek/{proyek}/selesai-verifikasi-fisik', [ProyekController::class, 'selesaiVerifikasiFisik'])->name('proyek.selesai-verifikasi-fisik');
    Route::post('/proyek/{proyek}/selesai-penilaian', [ProyekController::class, 'selesaiPenilaian'])->name('proyek.selesai-penilaian');

    // ===== PROPERTI (Management) =====
    Route::post('/properti/{properti}/update-type', [PropertiController::class, 'updateType'])->name('properti.updateType');
    Route::post('/properti/{properti}/nilai', [NilaiController::class, 'store'])->name('properti.nilai.save');
    Route::delete('/nilai/{nilai}', [NilaiController::class, 'destroy'])->name('properti.nilai.destroy');

    // Dokumen Properti
    Route::post('/properti/{properti}/dokumen', [DokumenPropertiController::class, 'store'])->name('dokumen.store');
    Route::put('/dokumen/{dokumen}', [DokumenPropertiController::class, 'update'])->name('dokumen.update');
    Route::post('/dokumen/{dokumen}/verifikasi', [DokumenPropertiController::class, 'verifikasi'])->name('dokumen.verifikasi');
    Route::delete('/dokumen/{dokumen}', [DokumenPropertiController::class, 'destroy'])->name('dokumen.destroy');

    // Checklist Fisik
    Route::post('/properti/{properti}/checklist-fisik', [ChecklistFisikController::class, 'store'])->name('checklist-fisik.store');
    Route::delete('/checklist-fisik/{checklistFisik}', [ChecklistFisikController::class, 'destroy'])->name('checklist-fisik.destroy');

    // Aspek Fisik Properti
    Route::post('/properti/{properti}/aspek-fisik', [AspekFisikController::class, 'store'])->name('aspek-fisik.store');
    Route::put('/aspek-fisik/{aspekFisik}', [AspekFisikController::class, 'update'])->name('aspek-fisik.update');
    Route::post('/aspek-fisik/{aspekFisik}/verifikasi', [AspekFisikController::class, 'verify'])->name('aspek-fisik.verifikasi');
    Route::delete('/aspek-fisik/{aspekFisik}', [AspekFisikController::class, 'destroy'])->name('aspek-fisik.destroy');

    // Chat (polling-based, no Livewire)
    Route::get('/chats', [MessageController::class, 'index'])->name('chats.index');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/messages/conversation/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::match(['put', 'patch'], '/messages/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/conversation/{user}/read', [MessageController::class, 'markRead'])->name('messages.markRead');

    // Survey / Mitra
    Route::get('/survey/{project_id?}', function ($project_id = null) {
        $project = Proyek::find($project_id);

        return view('dashboards.mitra.index', compact('project'));
    })->name('survey.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Laporan
Route::prefix('laporan')->middleware('auth')->group(function () {
    // Laporan list page (unified view with Proyek/Tahunan toggle)
    Route::get('/project', [PropertiController::class, 'laporanProject'])->name('laporan.project');

    // Laporan detail page for a specific proyek (like proyek/show but with Detail + Laporan tabs)
    Route::get('/proyek/{proyek}', [PropertiController::class, 'laporanProyekShow'])->name('laporan.proyek.show');

    // Download PDF laporan for a specific proyek
    Route::get('/proyek/{proyek}/pdf', [PropertiController::class, 'downloadPdf'])->name('laporan.proyek.pdf');

    // JSON endpoint for single project data (POST to avoid conflict with /project GET)
    Route::post('/project/{id}', [PropertiController::class, 'getProject'])->name('laporan.project.data');
    Route::post('/upload', [PropertiController::class, 'uploadLaporan'])->name('laporan.upload');
    Route::delete('/reset/{id}', [PropertiController::class, 'resetLaporan'])->name('laporan.reset');
    Route::get('/tahunan', [PropertiController::class, 'laporanTahunan'])->name('laporan.tahunan');
    Route::get('/tahunan/{year}', [PropertiController::class, 'getTahunanByYear'])->name('laporan.tahunan.show');
    Route::delete('/project/delete/{id}', [PropertiController::class, 'deleteProject'])->name('laporan.project.delete');
    Route::get('/tahunan/download-pdf/{year}', [PropertiController::class, 'downloadTahunanPdf'])->name('laporan.tahunan.pdf');
    Route::get('/tahunan/download-zip/{year}', [PropertiController::class, 'downloadZipTahunan'])->name('laporan.tahunan.zip');
});

require __DIR__.'/auth.php';
