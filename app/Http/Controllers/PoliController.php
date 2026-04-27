<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PoliController extends Controller
{
    public function index(): View
    {
        return view('admin.poli.index', [
            'polis' => Poli::query()->orderBy('nama_poli')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_poli' => ['required', 'string', 'max:25'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Poli::query()->create($validated);

        return back()->with('success', 'Data poli berhasil ditambahkan.');
    }

    public function update(Request $request, Poli $poli): RedirectResponse
    {
        $validated = $request->validate([
            'nama_poli' => ['required', 'string', 'max:25'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $poli->update($validated);

        return back()->with('success', 'Data poli berhasil diperbarui.');
    }

    public function destroy(Poli $poli): RedirectResponse
    {
        $poli->delete();

        return back()->with('success', 'Data poli berhasil dihapus.');
    }
}
