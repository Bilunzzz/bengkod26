<x-layouts.app title="Dashboard Pasien">
    <div class="space-y-6">
        <section
            class="relative overflow-hidden border border-[#6f8dff]/45 bg-gradient-to-r from-[#4f7ce7] via-[#4f6fe4] to-[#4c52dc] p-4 text-white shadow-lg md:px-4 md:py-3.5"
            id="activeQueueBanner"
            @if(!$hasActiveQueue) style="display: none;" @endif
        >
            <span class="pointer-events-none absolute -right-10 -top-10 h-[92px] w-[92px] rounded-full border-[8px] border-[#dbe5ff]/32"></span>
            <span class="pointer-events-none absolute right-20 top-4 h-[24px] w-[24px] rounded-full border-[4px] border-[#d6e2ff]/20"></span>

            <div class="relative z-10 flex min-h-[154px] flex-col justify-between gap-4 md:flex-row md:items-end">
                <div class="max-w-[610px]">
                    <p class="text-[12px] font-bold uppercase tracking-wide text-white/95">ANTRIAN AKTIF ANDA</p>

                    <div class="mt-2.5 space-y-2">
                        <div>
                            <p class="text-[12px] font-medium text-[#c9d8ff]">Poliklinik</p>
                            <p class="text-[22px] font-bold leading-[1.06] md:text-[24px]" id="bannerPoli">{{ $activeQueue['poli'] ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-[12px] font-medium text-[#c9d8ff]">Dokter</p>
                            <p class="text-[21px] font-bold leading-[1.06] md:text-[23px]" id="bannerDokter">{{ $activeQueue['dokter'] ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-[12px] font-medium text-[#c9d8ff]">Jadwal Periksa</p>
                            <p class="text-[20px] font-bold leading-[1.06] md:text-[22px]" id="bannerJadwal">{{ $activeQueue['jadwal'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-2 flex items-stretch gap-3 md:mt-0 md:self-end md:pb-1">
                    <div class="flex h-[112px] w-[106px] flex-col justify-between rounded-[11px] border border-white/15 bg-white/13 px-3 py-3 text-center shadow-[inset_0_1px_0_rgba(255,255,255,0.18)] backdrop-blur-sm">
                        <p class="text-[11px] font-medium text-[#d6e1ff]">Nomor Anda</p>
                        <p class="text-[48px] font-black leading-none" id="bannerYourQueue">{{ $activeQueue['nomor_antrian_pasien'] ?? '-' }}</p>
                    </div>

                    <div class="flex h-[112px] w-[126px] flex-col justify-between rounded-[11px] bg-[#f2f4f8] px-3 py-3 text-center text-[#4b58c9] shadow-lg">
                        <p class="text-[11px] font-bold uppercase tracking-tight text-[#4e5cbc]">Sedang Dilayani</p>
                        <p class="text-[50px] font-black leading-none text-[#5057c7]" id="bannerCurrentQueue">{{ $activeQueue['nomor_dilayani'] ?? '-' }}</p>
                        <p class="mt-0.5 flex items-center justify-center gap-1 text-[11px] font-medium text-[#6472df]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#6c7af3]"></span>
                            Live Update
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" id="noActiveQueueMessage" @if($hasActiveQueue) style="display: none;" @endif>
            <p class="text-sm text-slate-600">
                Anda belum memiliki antrian aktif saat ini. Silakan pilih jadwal poli pada tabel di bawah.
            </p>
        </section>

        @if ($errors->any())
            <section class="alert alert-error rounded-xl text-sm">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </section>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-800">Jadwal Poliklinik</h2>
                <p class="text-sm text-slate-500">Nomor yang sedang dilayani akan diperbarui otomatis.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-500">
                            <th>No</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Hari</th>
                            <th>Jam Periksa</th>
                            <th>Sedang Dilayani</th>
                            <th>Antrian Anda</th>
                            <th>Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalRows as $index => $row)
                            <tr data-jadwal-id="{{ $row['id'] }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['poli'] }}</td>
                                <td>{{ $row['dokter'] }}</td>
                                <td>{{ $row['hari'] }}</td>
                                <td>{{ $row['jam'] }}</td>
                                <td class="current-serving font-semibold text-indigo-700">{{ $row['current_queue'] ?? '-' }}</td>
                                <td class="my-queue font-semibold text-sky-700">{{ $row['your_queue'] ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('pasien.daftar-poli') }}" method="POST" class="flex min-w-[220px] gap-2">
                                        @csrf
                                        <input type="hidden" name="id_jadwal" value="{{ $row['id'] }}">
                                        <input
                                            type="text"
                                            name="keluhan"
                                            placeholder="Keluhan singkat"
                                            class="input input-bordered input-sm w-full"
                                            minlength="5"
                                            required
                                            @if($hasActiveQueue) disabled @endif
                                        >
                                        <button class="btn btn-sm btn-primary" @if($hasActiveQueue) disabled @endif>
                                            Daftar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-slate-500">Belum ada jadwal periksa tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            const snapshotUrl = "{{ route('pasien.dashboard.snapshot') }}";
            const daftarPoliAction = "{{ route('pasien.daftar-poli') }}";
            const banner = document.getElementById('activeQueueBanner');
            const emptyBanner = document.getElementById('noActiveQueueMessage');

            function setText(id, value) {
                const node = document.getElementById(id);
                if (node) {
                    node.textContent = value ?? '-';
                }
            }

            function updateRows(jadwalRows = []) {
                jadwalRows.forEach((row) => {
                    const tr = document.querySelector(`tr[data-jadwal-id="${row.id}"]`);
                    if (!tr) {
                        return;
                    }

                    const servingCell = tr.querySelector('.current-serving');
                    const myQueueCell = tr.querySelector('.my-queue');

                    if (servingCell) {
                        servingCell.textContent = row.current_queue ?? '-';
                    }

                    if (myQueueCell) {
                        myQueueCell.textContent = row.your_queue ?? '-';
                    }
                });
            }

            function toggleRegisterInputs(disabled) {
                document.querySelectorAll(`form[action="${daftarPoliAction}"] input, form[action="${daftarPoliAction}"] button`)
                    .forEach((el) => {
                        if (el.name !== '_token' && el.name !== 'id_jadwal') {
                            el.disabled = disabled;
                        }

                        if (el.tagName === 'BUTTON') {
                            el.disabled = disabled;
                        }
                    });
            }

            async function fetchSnapshot() {
                try {
                    const response = await fetch(snapshotUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        return;
                    }

                    const payload = await response.json();
                    updateRows(payload.jadwal_rows || []);

                    if (payload.has_active_queue && payload.active_queue) {
                        banner.style.display = 'block';
                        emptyBanner.style.display = 'none';
                        setText('bannerPoli', payload.active_queue.poli);
                        setText('bannerDokter', payload.active_queue.dokter);
                        setText('bannerJadwal', payload.active_queue.jadwal);
                        setText('bannerYourQueue', payload.active_queue.nomor_antrian_pasien);
                        setText('bannerCurrentQueue', payload.active_queue.nomor_dilayani);
                    } else {
                        banner.style.display = 'none';
                        emptyBanner.style.display = 'block';
                    }

                    toggleRegisterInputs(payload.has_active_queue === true);
                } catch (error) {
                    console.error('Failed to refresh queue snapshot', error);
                }
            }

            if (window.Echo) {
                window.Echo.channel('queues')
                    .listen('.queue.updated', () => {
                        fetchSnapshot();
                    });
            }

            setInterval(fetchSnapshot, 10000);
        </script>
    @endpush
</x-layouts.app>