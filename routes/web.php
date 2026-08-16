<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Jabatan\Index as JabatanIndex;
use App\Livewire\Admin\Pegawai\Index as PegawaiIndex;
use App\Livewire\Admin\Unit\Index as UnitIndex;
use App\Livewire\Admin\User\Index as UserIndex;
use App\Livewire\Pegawai\Dashboard as PegawaiDashboard;
use App\Livewire\Pegawai\Tugas\Index as PegawaiTugasIndex;
use App\Livewire\Pegawai\Tugas\Show as PegawaiTugasShow;
use App\Livewire\Pimpinan\Dashboard as PimpinanDashboard;
use App\Livewire\Pimpinan\Pegawai\Index as PimpinanPegawaiIndex;
use App\Livewire\Pimpinan\Tugas\Create as PimpinanTugasCreate;
use App\Livewire\Pimpinan\Tugas\Index as PimpinanTugasIndex;
use App\Livewire\Pimpinan\Tugas\Show as PimpinanTugasShow;
use App\Models\Tugas;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'pimpinan' => redirect()->route('pimpinan.dashboard'),
            'pegawai' => redirect()->route('pegawai.dashboard'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');
});

// Admin Routes
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/units', UnitIndex::class)->name('admin.units.index');
        Route::get('/jabatans', JabatanIndex::class)->name('admin.jabatans.index');
        Route::get('/pegawais', PegawaiIndex::class)->name('admin.pegawais.index');
        Route::get('/users', UserIndex::class)->name('admin.users.index');
    });

// Pimpinan Routes
Route::prefix('pimpinan')
    ->middleware(['auth', 'verified', 'role:pimpinan'])
    ->group(function () {
        Route::get('/dashboard', PimpinanDashboard::class)->name('pimpinan.dashboard');
        Route::get('/pegawais', PimpinanPegawaiIndex::class)->name('pimpinan.pegawais.index');
        Route::get('/tugases', PimpinanTugasIndex::class)->name('pimpinan.tugases.index');
        Route::get('/tugases/create', PimpinanTugasCreate::class)->name('pimpinan.tugases.create');
        Route::get('/tugases/{id_tugas}', PimpinanTugasShow::class)->name('pimpinan.tugases.show');
        Route::get('/laporan/pdf', function () {
            $tugases = Tugas::with('pegawai.unit', 'pegawai.jabatan')
                ->latest()
                ->get();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.pimpinan.laporan.pdf', [
                'tugases' => $tugases,
                'title' => 'Laporan Tugas - LPP RRI Kupang',
            ]);

            return $pdf->download('laporan-tugas-rri.pdf');
        })->name('pimpinan.laporan.pdf');
    });

// Pegawai Routes
Route::prefix('pegawai')
    ->middleware(['auth', 'verified', 'role:pegawai'])
    ->group(function () {
        Route::get('/dashboard', PegawaiDashboard::class)->name('pegawai.dashboard');
        Route::get('/tugases', PegawaiTugasIndex::class)->name('pegawai.tugases.index');
        Route::get('/tugases/{id_tugas}', PegawaiTugasShow::class)->name('pegawai.tugases.show');
    });

require __DIR__.'/settings.php';
