<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\DendaController;
use App\Http\Controllers\Admin\StrukController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserBookController;
use App\Http\Controllers\User\UserPeminjamanController;
use App\Http\Controllers\User\UserStrukController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Start page untuk memilih login admin atau user.
Route::get('/', function () {
    return view('welcome');
})->name('start');

// Auth routes (Breeze)
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================================================
// ADMIN ROUTES — hanya role 'admin'
// =========================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Buku
    Route::resource('books', BookController::class);

    // Kategori
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Anggota / Member
    Route::resource('members', MemberController::class);
    Route::get('members/{member}/history', [MemberController::class, 'history'])->name('members.history');

    // Peminjaman
    Route::get('peminjaman/filter', [PeminjamanController::class, 'filter'])->name('peminjaman.filter');
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');

    // Pengembalian
    Route::get('pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('pengembalian/{peminjaman}/proses', [PengembalianController::class, 'proses'])->name('pengembalian.proses');
    Route::post('pengembalian/{peminjaman}/reject', [PengembalianController::class, 'reject'])->name('pengembalian.reject');

    // Denda
    Route::get('denda', [DendaController::class, 'index'])->name('denda.index');
    Route::post('denda/{peminjaman}/bayar', [DendaController::class, 'bayar'])->name('denda.bayar');

    // Struk
    Route::get('struk', [StrukController::class, 'index'])->name('struk.index');
    Route::get('struk/{struk}', [StrukController::class, 'show'])->name('struk.show');
    Route::post('struk/{struk}/approve', [StrukController::class, 'approve'])->name('struk.approve');
});

// =========================================================
// USER / ANGGOTA ROUTES — hanya role 'anggota'
// =========================================================
Route::middleware(['auth', 'role:anggota'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Katalog Buku (read-only)
    Route::get('/books', [UserBookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [UserBookController::class, 'show'])->name('books.show');

    // Peminjaman Anggota
    Route::get('/peminjaman', [UserPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [UserPeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [UserPeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{peminjaman}', [UserPeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('/peminjaman/{peminjaman}/request-return', [UserPeminjamanController::class, 'requestReturn'])->name('peminjaman.request-return');

    // Struk otomatis dari admin
    Route::get('/struk', [UserStrukController::class, 'index'])->name('struk.index');
    Route::get('/struk/{struk}', [UserStrukController::class, 'show'])->name('struk.show');

    // Profil
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::patch('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
});

// Redirect setelah login berdasarkan role (override Breeze default)
Route::middleware('auth')->get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->name('dashboard');
