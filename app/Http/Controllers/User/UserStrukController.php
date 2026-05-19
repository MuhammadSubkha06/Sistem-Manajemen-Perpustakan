<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Struk;
use Illuminate\Pagination\LengthAwarePaginator;

class UserStrukController extends Controller
{
    public function index()
    {
        $anggota = auth()->user()->anggota;

        if (!$anggota) {
            return view('user.struk.index', [
                'struks' => new LengthAwarePaginator([], 0, 10),
            ]);
        }

        $struks = $anggota->struks()
            ->approved()
            ->with('peminjaman.buku')
            ->latest('issued_at')
            ->paginate(10);

        return view('user.struk.index', compact('struks'));
    }

    public function show(Struk $struk)
    {
        $anggota = auth()->user()->anggota;

        abort_if(!$anggota || $struk->anggota_id !== $anggota->id || !$struk->is_approved, 403);

        $struk->load(['anggota', 'peminjaman.buku']);

        return view('user.struk.show', compact('struk'));
    }
}
