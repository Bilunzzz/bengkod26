<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DokterController extends Controller
{
    public function index(): View
    {
        return view('admin.dokter.index', [
            'dokters' => User::query()
                ->with('poli')
                ->where('role', 'dokter')
                ->orderBy('nama')
                ->get(),
            'polis' => Poli::query()->orderBy('nama_poli')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'no_ktp' => ['nullable', 'string', 'max:30', Rule::unique('users', 'no_ktp')],
            'id_poli' => ['required', 'exists:poli,id'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
        ]);

        User::query()->create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'no_ktp' => $validated['no_ktp'] ?? null,
            'id_poli' => $validated['id_poli'],
            'role' => 'dokter',
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Data dokter berhasil ditambahkan.');
    }

    public function update(Request $request, User $dokter): RedirectResponse
    {
        $this->ensureDokter($dokter);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($dokter->id)],
            'alamat' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'no_ktp' => ['nullable', 'string', 'max:30', Rule::unique('users', 'no_ktp')->ignore($dokter->id)],
            'id_poli' => ['required', 'exists:poli,id'],
            'password' => ['nullable', 'string', 'confirmed', 'min:6'],
        ]);

        $payload = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'no_ktp' => $validated['no_ktp'] ?? null,
            'id_poli' => $validated['id_poli'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $dokter->update($payload);

        return back()->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(User $dokter): RedirectResponse
    {
        $this->ensureDokter($dokter);

        $dokter->delete();

        return back()->with('success', 'Data dokter berhasil dihapus.');
    }

    private function ensureDokter(User $user): void
    {
        abort_unless($user->role === 'dokter', 404);
    }
}
