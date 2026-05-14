<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Perpustakaan 40</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo40.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            min-height: 100vh;
            background:
                linear-gradient(135deg, rgba(15,23,42,.88), rgba(13,110,253,.70)),
                url('{{ asset('images/logo40.png') }}') center 14% / 160px no-repeat,
                #f4f6f9;
        }
        .start-shell { min-height: 100vh; display: flex; align-items: center; }
        .brand-logo { width: 56px; height: 56px; border-radius: 12px; object-fit: cover; background: #fff; }
        .login-card {
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.96);
            border-radius: 8px;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .login-card:hover { transform: translateY(-2px); box-shadow: 0 16px 40px rgba(15,23,42,.18); }
        .icon-box { width: 46px; height: 46px; border-radius: 8px; display: grid; place-items: center; }
    </style>
</head>
<body>
    <main class="start-shell py-5">
        <div class="container">
            <div class="row align-items-center justify-content-between g-4">
                <div class="col-lg-5 text-white">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo40.png') }}" alt="Logo Perpustakaan 40" class="brand-logo">
                        <div>
                            <div class="fw-bold fs-4">Perpustakaan 40</div>
                            <div class="text-white-50">Sistem Manajemen Buku</div>
                        </div>
                    </div>
                    <h1 class="fw-bold mb-3">Pilih halaman login sesuai akses.</h1>
                    <p class="text-white-50 mb-0">
                        Admin mengelola katalog, anggota, dan approval peminjaman. Anggota masuk menggunakan NIS untuk melihat katalog dan mengajukan peminjaman.
                    </p>
                </div>

                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.login') }}" class="login-card d-block p-4 text-decoration-none text-dark h-100">
                                <div class="icon-box bg-warning-subtle text-warning mb-4">
                                    <i class="fas fa-user-shield fa-lg"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Login Admin</h5>
                                <p class="text-muted small mb-4">Masuk dengan email dan password admin untuk mengelola sistem.</p>
                                <span class="btn btn-warning btn-sm fw-semibold w-100">
                                    Masuk Admin <i class="fas fa-arrow-right ms-1"></i>
                                </span>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ route('user.login') }}" class="login-card d-block p-4 text-decoration-none text-dark h-100">
                                <div class="icon-box bg-primary-subtle text-primary mb-4">
                                    <i class="fas fa-id-card fa-lg"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Login Anggota</h5>
                                <p class="text-muted small mb-4">Masuk dengan NIS dan password untuk membuka portal anggota.</p>
                                <span class="btn btn-primary btn-sm fw-semibold w-100">
                                    Masuk Anggota <i class="fas fa-arrow-right ms-1"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
