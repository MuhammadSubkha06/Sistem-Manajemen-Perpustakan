<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@perpustakaan40.sch.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // ── Anggota / Siswa ────────────────────────────────────
        $userAnggota = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@perpustakaan40.sch.id',
            'password' => Hash::make('anggota123'),
            'role'     => 'anggota',
        ]);

        Anggota::create([
            'user_id' => $userAnggota->id,
            'nis'     => '12345001',
            'nama'    => 'Budi Santoso',
            'kelas'   => 'XI RPL 1',
            'no_hp'   => '08123456789',
            'alamat'  => 'Jl. Merdeka No. 1, Jakarta',
        ]);

        // ── Kategori ───────────────────────────────────────────
        $kategori = Kategori::insert([
            ['nama_kategori' => 'Pemrograman',    'deskripsi' => 'Buku pemrograman dan coding',   'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Matematika',     'deskripsi' => 'Buku matematika',               'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Sains',          'deskripsi' => 'Buku ilmu pengetahuan alam',    'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Bahasa',         'deskripsi' => 'Buku bahasa Indonesia & Inggris', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Sejarah',        'deskripsi' => 'Buku sejarah Indonesia & dunia', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── Buku ───────────────────────────────────────────────
        $b1 = Buku::create(['judul'=>'Pemrograman Laravel untuk Pemula', 'pengarang'=>'Ahmad Fauzi', 'penerbit'=>'Informatika', 'tahun_terbit'=>2023, 'stok'=>5]);
        $b2 = Buku::create(['judul'=>'Algoritma dan Struktur Data',      'pengarang'=>'Rinaldi Munir', 'penerbit'=>'ITB Press',  'tahun_terbit'=>2021, 'stok'=>3]);
        $b3 = Buku::create(['judul'=>'Matematika Diskrit',               'pengarang'=>'Rinaldi Munir', 'penerbit'=>'ITB Press',  'tahun_terbit'=>2020, 'stok'=>4]);

        // Relasi buku-kategori
        $b1->kategoris()->attach(Kategori::where('nama_kategori','Pemrograman')->first()?->id);
        $b2->kategoris()->attach(Kategori::where('nama_kategori','Pemrograman')->first()?->id);
        $b3->kategoris()->attach(Kategori::where('nama_kategori','Matematika')->first()?->id);
    }
}
