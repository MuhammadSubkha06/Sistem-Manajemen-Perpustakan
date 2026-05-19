<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Struk;
use Illuminate\Http\Request;

class StrukController extends Controller
{
    public function index(Request $request)
    {
        $query = Struk::with(['anggota', 'peminjaman.buku', 'approver']);

        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $struks = $query->latest('issued_at')->paginate(10)->withQueryString();
        $pendingCount = Struk::where('is_approved', false)->count();

        return view('admin.struk.index', compact('struks', 'pendingCount'));
    }

    public function show(Struk $struk)
    {
        $struk->load(['anggota', 'peminjaman.buku', 'approver']);

        return view('admin.struk.show', compact('struk'));
    }

    public function approve(Struk $struk)
    {
        if ($struk->is_approved) {
            return back()->with('error', 'Struk ini sudah disetujui sebelumnya.');
        }

        $struk->approve(auth()->user());

        return back()->with('success', 'Struk berhasil disetujui dan sekarang bisa dilihat anggota.');
    }
}
