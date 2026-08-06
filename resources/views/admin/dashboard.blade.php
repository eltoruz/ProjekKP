@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Executive Analytics')

@section('page-content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-2xs border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</span>
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Kerja Sama</p>
    </div>

    <div class="bg-white rounded-xl shadow-2xs border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Perlu Review</span>
            <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['perlu_review'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Menunggu Tindakan</p>
    </div>

    <div class="bg-white rounded-xl shadow-2xs border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembahasan</span>
            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['dalam_pembahasan'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-2xs border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Proses</span>
            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['dalam_proses'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-2xs border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Selesai</span>
            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['selesai'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-2xs border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Berakhir</span>
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['berakhir'] }}</p>
    </div>
</div>

@if(count($earlyWarningList) > 0)
<div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl shadow-md p-6 mb-6 text-white relative overflow-hidden">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/20 pb-4 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-xs flex items-center justify-center text-white shrink-0">
                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold">Early Warning System — Masa Berlaku MoU Mendekati Kadaluarsa</h3>
                <p class="text-xs text-white/80 mt-0.5">Daftar kerja sama yang akan berakhir dalam jangka waktu H-90, H-60, dan H-30 hari ke depan.</p>
            </div>
        </div>
        <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold shrink-0 self-start md:self-auto">
            {{ count($earlyWarningList) }} Kerja Sama Perlu Perhatian
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($earlyWarningList as $item)
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-3.5 border border-white/20 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-2 mb-1.5">
                    <span class="font-bold text-sm truncate text-white">{{ $item['kerjasama']->nama_kl }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shadow-2xs 
                        {{ $item['level'] === 'danger' ? 'bg-red-500 text-white' : ($item['level'] === 'warning' ? 'bg-amber-300 text-amber-950' : 'bg-blue-200 text-blue-900') }}">
                        {{ $item['badge_text'] }}
                    </span>
                </div>
                <p class="text-xs text-white/90 line-clamp-2">{{ $item['kerjasama']->tentang ?? '-' }}</p>
            </div>
            <div class="mt-3 pt-2 border-t border-white/15 flex items-center justify-between text-[11px] text-white/80">
                <span>Berakhir: <strong>{{ $item['tanggal_selesai'] }}</strong></span>
                <a href="{{ route('admin.kerjasama.review', $item['kerjasama']->kerjasama_id) }}" class="underline font-semibold hover:text-white">Lihat Review &rarr;</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-2xs border border-gray-200 p-6 lg:col-span-1 flex flex-col justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-800">Distribusi Status Kerja Sama</h3>
            <p class="text-xs text-gray-500 mt-0.5">Proporsi status dokumen MoU di sistem.</p>
        </div>
        <div class="my-4 relative flex items-center justify-center min-h-[220px]">
            <canvas id="statusDistributionChart"></canvas>
        </div>
        <div class="text-xs text-gray-400 text-center">
            Pusdatin Kemendikdasmen © {{ date('Y') }}
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-2xs border border-gray-200 p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-800">Pengajuan Terbaru</h3>
                <p class="text-xs text-gray-500 mt-0.5">Daftar pengajuan kerja sama yang baru diajukan atau diperbarui.</p>
            </div>
            <a href="{{ route('admin.kerjasama.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-y border-slate-100 text-gray-500 font-semibold uppercase">
                    <tr>
                        <th class="px-3.5 py-2.5">No</th>
                        <th class="px-3.5 py-2.5">Mitra / Instansi</th>
                        <th class="px-3.5 py-2.5">Jenis</th>
                        <th class="px-3.5 py-2.5">Status</th>
                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentSubmissions as $ks)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-3.5 py-3 text-gray-400 font-medium">{{ $loop->iteration }}</td>
                        <td class="px-3.5 py-3 font-semibold text-slate-800">{{ $ks->nama_kl ?? 'N/A' }}</td>
                        <td class="px-3.5 py-3 text-gray-600">{{ $ks->jenis?->nama_jenis ?? '-' }}</td>
                        <td class="px-3.5 py-3">
                            @php
                                $lastReject = collect($ks->review_log)->filter(fn($l) => ($l['label'] ?? '') === 'Ditolak')->last();
                                $isRejected = $lastReject && !$ks->ks_status_dok;
                                $sColors = [1 => 'bg-blue-100 text-blue-700', 2 => 'bg-yellow-100 text-yellow-700', 3 => 'bg-orange-100 text-orange-700', 4 => 'bg-purple-100 text-purple-700', 5 => 'bg-green-100 text-green-700', 6 => 'bg-gray-100 text-gray-600'];
                                $sLabels = [1 => 'Menunggu Review', 2 => 'Menunggu Jadwal', 3 => 'Pembahasan', 4 => 'Penandatanganan', 5 => 'Selesai', 6 => 'Berakhir'];
                                $colorClass = $isRejected ? 'bg-red-100 text-red-700' : ($sColors[$ks->ks_status_dok] ?? 'bg-gray-100 text-gray-600');
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $colorClass }}">
                                {{ $isRejected ? 'Ditolak' : ($sLabels[$ks->ks_status_dok] ?? $ks->status_label) }}
                            </span>
                        </td>
                        <td class="px-3.5 py-3 text-right">
                            <a href="{{ route('admin.kerjasama.review', $ks->kerjasama_id) }}" class="inline-flex items-center gap-1 text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 px-2.5 py-1 rounded-lg hover:bg-indigo-100 transition-colors">
                                Review &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada pengajuan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('statusDistributionChart').getContext('2d');
        const statusData = @json($statusDistribution);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        '#3b82f6',
                        '#f59e0b',
                        '#8b5cf6',
                        '#ec4899',
                        '#10b981',
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Poppins', size: 11 },
                            boxWidth: 12,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection
