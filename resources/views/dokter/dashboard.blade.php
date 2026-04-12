<x-layouts.app title="Dokter Dashboard">
    <div class="space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800">Halo, Selamat Datang Dokter</h1>
            <p class="mt-1 text-sm text-slate-500">Export data jadwal dan riwayat pasien yang pernah Anda periksa.</p>

            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('dokter.export.jadwal') }}" class="btn btn-primary btn-sm rounded-lg px-4 normal-case">
                    <i class="fas fa-file-excel"></i>
                    Export Jadwal Periksa
                </a>
                <a href="{{ route('dokter.export.riwayat-pasien') }}" class="btn btn-secondary btn-sm rounded-lg px-4 normal-case">
                    <i class="fas fa-file-excel"></i>
                    Export Riwayat Pasien
                </a>
            </div>
        </section>
    </div>
</x-layouts.app>