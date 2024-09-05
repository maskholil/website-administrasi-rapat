<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\RapatController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;

// ----------------------------- storage-link ------------------------------//
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully.';
});

// ----------------------------- home ------------------------------//
Route::get('/', function () {
    return view('home');
});

// ----------------------------- laporan ------------------------------//
Route::get('/laporan/cetak-arsip', [LaporanController::class, 'cetakArsip'])->name('laporan.cetakArsip');
Route::get('/laporan/cetak-disposisi', [LaporanController::class, 'cetakDisposisi'])->name('laporan.cetakDisposisi');
Route::get('/laporan/cetak-gabungan', [LaporanController::class, 'cetakGabungan'])->name('laporan.cetakGabungan');
Route::get('/laporan/cetak-rapat', [LaporanController::class, 'cetakRapat'])->name('laporan.cetakRapat');


// ----------------------------- autentikasi ------------------------------//
Route::middleware(['auth'])->group(function () {
    // Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'edit')->name('profile.edit');
        Route::put('profile', 'update')->name('profile.update');
        Route::put('profile/password', 'password')->name('profile.password');
    });

    // ----------------------------- admin ------------------------------//
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::resource('users', UsersController::class);
        Route::resource('kategori', KategoriController::class);

        Route::get('/get-pivot-details/{pivotId}', [SuratMasukController::class, 'getPivotDetails']);

        Route::name('admin.')->group(function () {
            Route::resource('peserta', PesertaController::class);
            Route::resource('agenda', AgendaController::class);
            Route::resource('rapat', RapatController::class);
            Route::resource('surat-masuk', SuratMasukController::class);
            Route::resource('surat-keluar', SuratKeluarController::class);
            Route::resource('disposisi', DisposisiController::class);
        });

        Route::get('/surat-keluar/pdf/{id}', [SuratKeluarController::class, 'lihatIsiSurat'])->name('surat-keluar.pdf');

        Route::post('/disposisi/{id}/disposisi-ulang', [DisposisiController::class, 'disposisiUlang'])->name('disposisi.disposisiUlang');
        Route::get('/rapat/{id}/edit', [RapatController::class, 'edit'])->name('rapat.edit');
        Route::get('/rapat/berita-acara/{id}', [RapatController::class, 'lihatBeritaAcara'])->name('berita-acara.pdf');
        Route::resource('laporan', LaporanController::class);
        Route::get('/rapat/{id}/details', [RapatController::class, 'detailRevisi']);

        // Route::post('/rapat/{id}/tandaTangan', [RapatController::class, 'tandaTangan'])->name('admin.rapat.tandaTangan');
        Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    });

    // ----------------------------- pegawai ------------------------------//
    Route::prefix('pegawai')->middleware('role:pegawai')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'pegawaiDashboard'])->name('pegawai.dashboard');

        Route::name('pegawai.')->group(function () {
            // Route::get('surat-masuk', [SuratMasukController::class, 'index'])->name('pegawai.surat-masuk.index');
            Route::post('/surat-masuk/terima/{suratId}', [SuratMasukController::class, 'terima'])->name('surat-masuk.terima');
            Route::post('/surat-masuk/disposisi/{suratId}', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');
            Route::post('/disposisi/terima/{disposisiId}', [DisposisiController::class, 'terima'])->name('disposisi.terima');
            Route::post('/disposisi/disposisi/{disposisiId}', [DisposisiController::class, 'disposisi'])->name('disposisi.disposisi');

            // Route::resource('users', UsersController::class);
            Route::resource('kategori', KategoriController::class);
            Route::resource('surat-masuk', SuratMasukController::class);
            Route::get('/get-pivot-details/{pivotId}', [SuratMasukController::class, 'getPivotDetails']);

            Route::resource('surat-keluar', SuratKeluarController::class);
            Route::get('/surat-keluar/pdf/{id}', [SuratKeluarController::class, 'lihatIsiSurat'])->name('surat-keluar.pdf');
            Route::resource('disposisi', DisposisiController::class);

            Route::post('/disposisi/{id}/disposisi-ulang', [DisposisiController::class, 'disposisiUlang'])->name('disposisi.disposisiUlang');
            Route::resource('peserta', PesertaController::class);
            Route::resource('agenda', AgendaController::class);
            Route::get('/rapat/{id}/edit', [RapatController::class, 'edit'])->name('rapat.edit');
            Route::resource('rapat', RapatController::class);
            Route::get('/rapat/berita-acara/{id}', [RapatController::class, 'lihatBeritaAcara'])->name('berita-acara.pdf');
            Route::post('/rapat/{id}/tandaTangan', [RapatController::class, 'tandaTangan'])->name('rapat.tandaTangan');
            Route::get('/rapat/{id}/details', [RapatController::class, 'detailRevisi']);

            Route::resource('laporan', LaporanController::class);
        });
    });

    // ----------------------------- dekan ------------------------------//
    Route::prefix('dekan')->middleware('role:dekan')->group(function () {

        Route::post('/surat-keluar/tolak/{id}', [SuratKeluarController::class, 'tolakSurat']);
        Route::post('/surat-keluar/tandaTangan/{id}', [SuratKeluarController::class, 'tandaTangan'])->name('surat-keluar.tandaTangan');
        Route::get('/surat-keluar/pdf/{id}', [SuratKeluarController::class, 'lihatIsiSurat']);

        Route::name('dekan.')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'dekanDashboard'])->name('dashboard');
            Route::get('/get-pivot-details/{pivotId}', [SuratMasukController::class, 'getPivotDetails']);
            Route::get('surat-masuk/{id}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
            Route::get('surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
            Route::post('/surat-masuk/terima/{suratId}', [SuratMasukController::class, 'terima'])->name('surat-masuk.terima');
            Route::post('/surat-masuk/disposisi/{suratId}', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');

            Route::post('/disposisi/terima/{disposisiId}', [DisposisiController::class, 'terima'])->name('disposisi.terima');
            Route::post('/disposisi/disposisi/{disposisiId}', [DisposisiController::class, 'disposisi'])->name('disposisi.disposisi');

            Route::get('disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
            Route::resource('rapat', RapatController::class);
            Route::get('surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
            Route::resource('peserta', PesertaController::class);
            Route::get('/rapat/berita-acara/{id}', [RapatController::class, 'lihatBeritaAcara'])->name('berita-acara.pdf');
            Route::post('/rapat/{id}/tandaTangan', [RapatController::class, 'tandaTangan'])->name('rapat.tandaTangan');
            Route::post('/rapat/revisi/{id}', [RapatController::class, 'revisi']);
            Route::get('/rapat/{id}/details', [RapatController::class, 'detailRevisi']);

            Route::resource('laporan', LaporanController::class);
        });
    });

    // ----------------------------- kaprodi ------------------------------//
    Route::prefix('kaprodi')->middleware('role:kaprodi')->group(function () {

        Route::get('/get-pivot-details/{pivotId}', [SuratMasukController::class, 'getPivotDetails']);
        Route::get('/dashboard', [DashboardController::class, 'kaprodiDashboard'])->name('kaprodi.dashboard');
        Route::post('/surat-masuk/terima/{suratId}', [SuratMasukController::class, 'terima'])->name('surat-masuk.terima');
        Route::post('/surat-masuk/disposisi/{suratId}', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');

        Route::post('/surat-keluar/tolak/{id}', [SuratKeluarController::class, 'tolakSurat']);
        Route::post('/surat-keluar/tandaTangan/{id}', [SuratKeluarController::class, 'tandaTangan'])->name('surat-keluar.tandaTangan');
        Route::get('/surat-keluar/pdf/{id}', [SuratKeluarController::class, 'lihatIsiSurat']);

        Route::name('kaprodi.')->group(function () {
            Route::resource('rapat', RapatController::class);
            Route::resource('peserta', PesertaController::class);
            Route::get('surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
            Route::get('surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');

            Route::post('/disposisi/terima/{disposisiId}', [DisposisiController::class, 'terima'])->name('disposisi.terima');
            Route::post('/disposisi/disposisi/{disposisiId}', [DisposisiController::class, 'disposisi'])->name('disposisi.disposisi');
            Route::post('/rapat/{id}/tandaTangan', [RapatController::class, 'tandaTangan'])->name('rapat.tandaTangan');
            Route::post('/{userRole}/rapat/revisi/{id}', [RapatController::class, 'revisi']);

            Route::get('/rapat/berita-acara/{id}', [RapatController::class, 'lihatBeritaAcara'])->name('berita-acara.pdf');
            Route::get('disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
            Route::get('/rapat/{id}/details', [RapatController::class, 'detailRevisi']);
            Route::resource('laporan', LaporanController::class);
        });
    });

    // ----------------------------- ketua ------------------------------//
    Route::prefix('ketua')->middleware('role:ketua')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'ketuaDashboard'])->name('ketua.dashboard');
        Route::name('ketua.')->group(function () {
            Route::resource('rapat', RapatController::class);
            Route::resource('peserta', PesertaController::class);
            Route::resource('laporan', LaporanController::class);
            Route::get('/rapat/berita-acara/{id}', [RapatController::class, 'lihatBeritaAcara'])->name('berita-acara.pdf');
            Route::post('/rapat/{id}/tandaTangan', [RapatController::class, 'tandaTangan'])->name('rapat.tandaTangan');
            Route::get('/rapat/{id}/details', [RapatController::class, 'detailRevisi']);
            Route::post('/{userRole}/rapat/revisi/{id}', [RapatController::class, 'revisi']);
        });
    });
    // ----------------------------- dosen ------------------------------//
    Route::prefix('dosen')->middleware('role:dosen')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dosenDashboard'])->name('dosen.dashboard');

        Route::name('dosen.')->group(function () {
            Route::resource('rapat', RapatController::class);
            Route::resource('agenda', AgendaController::class);
            Route::resource('peserta', PesertaController::class);
            Route::get('/rapat/berita-acara/{id}', [RapatController::class, 'lihatBeritaAcara'])->name('berita-acara.pdf');
            Route::get('/rapat/{id}/details', [RapatController::class, 'detailRevisi']);

        });
    });
});

// ----------------------------- autentikasi UI ------------------------------//
Auth::routes([
    'register' => false, // Nonaktif registrasi
    'reset' => false,    // Nonaktif reset password
    'verify' => false,   // Nonaktif verifikasi email
    'confirm' => false // Nonaktif konfirmasi password
]);
