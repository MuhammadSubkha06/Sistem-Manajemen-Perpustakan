# 📊 Panduan Fitur Baru - Dashboard & Laporan

Dokumentasi lengkap untuk fitur-fitur baru yang telah ditambahkan ke Sistem Manajemen Buku Perpustakaan.

---

## 🎯 Fitur Utama

### 1. Dashboard dengan Chart Buku Terpopuler (Warna Kuning)

**Lokasi:** Admin Dashboard → Bagian Bawah

Menampilkan 5 buku dengan peminjaman tertinggi dalam animated bar chart dengan warna kuning sesuai tema.

**Karakteristik:**
- ✅ Warna kuning (#FFC107) sesuai Bootstrap theme
- ✅ Animated progress bar dengan smooth animation
- ✅ Ranking badges (Gold, Silver, Bronze, Star, Bookmark)
- ✅ Responsive design untuk semua device
- ✅ Jika kurang dari 5 buku, menampilkan sesuai rata-rata peminjaman

**Cara Akses:**
1. Login sebagai Admin
2. Dashboard otomatis menampilkan chart
3. Scroll ke bawah untuk melihat "5 Buku Terpopuler"

---

### 2. Halaman Laporan Bulanan (Report)

**Lokasi:** Menu Sidebar → "Laporan Bulanan"

Rekap data peminjaman, pengembalian, dan denda dengan export format PDF/Excel.

**Fitur:**
- 📅 Filter berdasarkan tahun dan bulan
- 📊 Ringkasan bulanan (Total Pinjam, Total Kembali, Total Denda)
- 📋 Detail peminjaman dengan pagination
- 📈 Statistik pelanggaran anggota
- 💾 Export ke PDF dan Excel (CSV)

**Cara Menggunakan:**

```
1. Klik Menu "Laporan Bulanan"
2. Pilih Tahun (required)
3. Pilih Bulan (opsional - kosongkan untuk semua bulan)
4. Sistem otomatis menampilkan:
   - Ringkasan data bulanan
   - Detail peminjaman (20 per halaman)
   - Statistik pelanggaran
5. Klik "Export PDF" atau "Export Excel" untuk download
```

**Struktur Data yang Ditampilkan:**
```
Ringkasan Bulanan:
├── Bulan
├── Total Peminjaman
├── Total Pengembalian
└── Total Denda

Detail Peminjaman:
├── No
├── Nama Anggota
├── Buku
├── Tgl Pinjam
├── Tgl Kembali Rencana
├── Tgl Kembali Aktual
├── Status
└── Denda

Statistik Pelanggaran:
├── Nama Anggota
├── Jumlah Denda
├── Total Denda
└── Pengembalian Terlambat
```

---

### 3. Sistem Manajemen Pelanggaran (Violations)

**Lokasi:** Menu Sidebar → "Pelanggaran"

Monitoring dan manajemen pelanggaran anggota dengan auto-suspend berdasarkan kriteria.

#### 📊 Halaman List Pelanggaran

**Filter Status:**
- 🟡 Semua - Tampilkan semua anggota
- 🟢 Aktif - Hanya anggota yang tidak suspended
- 🔴 Suspended - Hanya anggota yang suspended

**Informasi yang Ditampilkan:**
```
Tabel Pelanggaran:
├── Nama Anggota
├── NIS
├── Denda (jumlah kejadian)
├── Terlambat (jumlah kejadian)
├── Status (Aktif/Suspended/Harus Suspended)
└── Aksi (Detail, Suspend/Unsuspend)
```

**Aksi Tersedia:**
- 👁️ **Detail** - Lihat riwayat pelanggaran lengkap
- 🚫 **Suspend** - Suspend akun anggota (hanya untuk aktif)
- 🔓 **Unsuspend** - Membuka kembali akun suspended

#### 📋 Halaman Detail Pelanggaran

**Informasi Anggota:**
```
├── Nama, NIS, Kelas
├── No HP, Alamat
├── Status (Aktif/Suspended)
├── Tanggal Suspend (jika suspended)
└── Alasan Suspend (jika suspended)
```

**Statistik Pelanggaran:**
```
├── Pelanggaran Denda: X kejadian
└── Pengembalian Terlambat: X kejadian
```

**Riwayat Pelanggaran (Tabel):**
```
├── Tanggal
├── Jenis Pelanggaran (Denda/Terlambat/Kerusakan)
├── Jumlah
├── Nominal
└── Keterangan
```

**Aksi Tambahan:**
- ➕ **Tambah Pelanggaran** - Tambah violation manual (dengan auto-suspend check)

---

## 🔴 Kriteria Auto-Suspend

Anggota akan **otomatis ter-suspend** jika mencapai:

| Kriteria | Threshold | Aksi |
|----------|-----------|------|
| **Denda** | ≥ 3 kali | Auto-suspend dengan alasan "Otomatis di-suspend karena Denda" |
| **Pengembalian Terlambat** | ≥ 3 kali | Auto-suspend dengan alasan "Otomatis di-suspend karena Pengembalian Terlambat" |

**Proses:**
1. Admin menambah violation ke anggota
2. Sistem cek total pelanggaran
3. Jika mencapai threshold → Auto-suspend otomatis
4. Sistem menampilkan notifikasi "Anggota otomatis di-suspend"
5. User akan ter-logout saat membuka aplikasi

---

## 🔐 Sistem Suspend/Unsuspend

### Suspend Manual
```
1. Klik "Detail" pelanggaran anggota
2. Klik button "Suspend" (untuk anggota aktif)
3. Masukkan alasan suspend di modal
4. Klik "Suspend"
5. Anggota akan ter-logout otomatis
```

### Unsuspend
```
1. Klik "Detail" pelanggaran anggota yang suspended
2. Klik button "Unsuspend"
3. Confirm di dialog
4. Akun anggota akan aktif kembali
```

### Efek Suspend
- ❌ User tidak bisa login
- 🚫 Session akan di-terminate otomatis
- 📝 Alasan suspend ditampilkan di homepage

---

## 📊 Jenis-Jenis Violation

| Tipe | Warna Badge | Deskripsi |
|------|-----------|-----------|
| **Denda** | 🔴 Merah | Anggota terkena denda |
| **Pengembalian Terlambat** | 🟡 Kuning | Pengembalian tidak sesuai estimasi |
| **Kerusakan Buku** | 🔵 Biru | Buku dikembalikan dalam kondisi rusak |

---

## 📁 Database Schema

### Migration 1: Add Suspended Fields
```sql
users.suspended_at (timestamp, nullable)
users.suspension_reason (text, nullable)

anggotas.suspended_at (timestamp, nullable)
anggotas.suspension_reason (text, nullable)
```

### Migration 2: Violations Table
```sql
CREATE TABLE violations (
    id BIGINT PRIMARY KEY,
    anggota_id BIGINT FOREIGN KEY,
    type ENUM('denda', 'late_return', 'damage'),
    count INT DEFAULT 1,
    total_amount DECIMAL(10,2),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

---

## 🛠️ API Endpoints

### Report Controller
```
GET  /admin/report                  - Tampilkan laporan
GET  /admin/report/export-pdf       - Export ke PDF
GET  /admin/report/export-excel     - Export ke Excel/CSV

Query Parameters:
- tahun (required) : 2026, 2027, etc.
- bulan (optional) : 1-12
```

### Member Suspension Controller
```
GET  /admin/violations                    - List pelanggaran
GET  /admin/violations/{anggota}          - Detail pelanggaran
POST /admin/violations/{anggota}/suspend  - Suspend anggota
POST /admin/violations/{anggota}/unsuspend - Unsuspend anggota
POST /admin/violations/{anggota}/add-violation - Tambah violation

Request Body untuk Suspend:
{
    "reason": "Alasan suspend..."
}

Request Body untuk Add Violation:
{
    "type": "denda|late_return|damage",
    "count": 1,
    "total_amount": 50000,
    "description": "Keterangan..."
}
```

---

## 📝 Model Methods

### User Model
```php
$user->isSuspended()           // Check apakah suspended
$user->suspend($reason)        // Suspend user
$user->unsuspend()             // Unsuspend user
User::active()                 // Query user aktif
```

### Anggota Model
```php
$anggota->isSuspended()                // Check apakah suspended
$anggota->suspend($reason)             // Suspend anggota
$anggota->unsuspend()                  // Unsuspend anggota
$anggota->getDendaCount()              // Hitung denda
$anggota->getLateReturnCount()         // Hitung terlambat
$anggota->shouldBeSuspended()          // Check auto-suspend
Anggota::active()                      // Query anggota aktif
```

### Violation Model
```php
Violation::denda()              // Filter denda
Violation::lateReturn()         // Filter terlambat
Violation::damage()             // Filter kerusakan
$violation->getTypeLabel()      // Get label type
```

---

## 🚀 Performa & Optimasi

### Indexes
```sql
violations.anggota_id (indexed)
violations.anggota_id + violations.type (composite index)
```

### Caching (Future Implementation)
```
- Cache laporan bulanan selama 1 jam
- Cache buku terpopuler selama 6 jam
- Invalidate cache saat ada peminjaman baru
```

---

## 📋 Checklist Implementasi

- ✅ Chart buku terpopuler dengan warna kuning
- ✅ Laporan bulanan dengan export PDF/Excel
- ✅ Database migration untuk suspended fields
- ✅ Model methods untuk suspend/unsuspend
- ✅ Violation tracking system
- ✅ Auto-suspend based on criteria
- ✅ Middleware check suspended
- ✅ UI untuk manage violations
- ✅ Menu navigation
- ✅ Responsive design

---

## 🔧 Troubleshooting

### Problem: Chart tidak muncul di dashboard
**Solution:** 
- Clear cache: `php artisan cache:clear`
- Check if books memiliki peminjaman: `SELECT COUNT(*) FROM peminjamans`

### Problem: Laporan tidak menampilkan data
**Solution:**
- Check date filters: Pastikan tgl_pinjam dalam range yang dipilih
- Check database: Pastikan ada data di table peminjamans

### Problem: User suspended tapi tetap bisa login
**Solution:**
- Check middleware: Pastikan CheckSuspended di-register di bootstrap/app.php
- Clear sessions: `php artisan session:table`
- Check user.suspended_at: Pastikan not null

---

## 📞 Support

Untuk pertanyaan atau bug report, hubungi tim development.

---

**Last Updated:** 20 Mei 2026
**Version:** 1.0
