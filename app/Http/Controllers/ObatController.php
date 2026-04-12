<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObatController extends Controller
{
    private const LOW_STOCK_LIMIT = 5;

    public function index(): View
    {
        return view('admin.obat.index', [
            'obats' => Obat::query()->orderBy('nama_obat')->get(),
            'lowStockLimit' => self::LOW_STOCK_LIMIT,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kemasan' => ['nullable', 'string', 'max:35'],
            'harga' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        Obat::query()->create($validated);

        return back()->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function update(Request $request, Obat $obat): RedirectResponse
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kemasan' => ['nullable', 'string', 'max:35'],
            'harga' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        $obat->update($validated);

        return back()->with('success', 'Data obat berhasil diperbarui.');
    }
}
