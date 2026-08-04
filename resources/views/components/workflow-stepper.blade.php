<nav aria-label="Progres Alur Nota Kesepakatan" class="w-full py-4">
    @php
        $steps = [
            'DRAFT' => 'Draft',
            'UPLOAD_DOKUMEN' => 'Upload Dokumen',
            'DIAJUKAN' => 'Diajukan',
            'DITOLAK' => 'Ditolak',
            'DISETUJUI' => 'Disetujui',
            'MENUNGGU_PEMBAHASAN' => 'Menunggu Pembahasan',
            'SELESAI_PEMBAHASAN' => 'Selesai Pembahasan',
            'SELESAI' => 'Selesai',
            'EXPIRED' => 'Berakhir',
        ];
        $stepKeys = array_keys($steps);
        $currentIndex = array_search($current, $stepKeys);
        if ($currentIndex === false) $currentIndex = count($stepKeys) - 1;
        $displaySteps = $stepKeys;
    @endphp

    <!-- Card Petunjuk Langkah Selanjutnya (High Impact UX) -->
    <div class="mb-4 p-3.5 bg-indigo-50 border-l-4 border-indigo-600 rounded-r-xl flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <span class="p-2 bg-indigo-600 text-white rounded-lg shrink-0" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <div>
                <p class="text-xs font-bold text-indigo-950">Status Tahapan: <span class="underline decoration-indigo-300">{{ $steps[$current] ?? $current }}</span></p>
                <p class="text-[11px] text-indigo-800 mt-0.5">Pantau status progres di bawah atau gunakan obrolan jika membutuhkan pertolongan.</p>
            </div>
        </div>
    </div>

    <!-- Stepper Semantik dengan ARIA (WCAG 1.4.1 & 1.3.1) -->
    <ol class="flex items-center justify-between">
        @foreach($displaySteps as $index => $step)
            @php
                $stepIdx = array_search($step, $stepKeys);
                $isActive = $stepIdx === $currentIndex;
                $isPassed = $stepIdx < $currentIndex;
                $isRejected = $step === 'DITOLAK' && $stepIdx === $currentIndex;
            @endphp
            <li class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}" 
                @if($isActive) aria-current="step" @endif>
                <div class="flex flex-col items-center">
                    <!-- Lingkaran Indikator dengan Kontras & Ikon WCAG -->
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-all shadow-2xs
                        {{ $isRejected ? 'bg-red-600 text-white ring-4 ring-red-100' : ($isActive ? 'bg-indigo-600 text-white ring-4 ring-indigo-100 scale-110' : ($isPassed ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700')) }}">
                        @if($isPassed)
                            <span aria-label="Selesai">✓</span>
                        @elseif($isRejected)
                            <span aria-label="Ditolak">✕</span>
                        @elseif($isActive)
                            <span aria-label="Aktif">⏳</span>
                        @else
                            <span>{{ $stepIdx + 1 }}</span>
                        @endif
                    </div>
                    <span class="text-[11px] font-semibold mt-1.5 {{ $isActive ? 'text-indigo-950 font-bold' : ($isPassed ? 'text-emerald-900 font-medium' : 'text-slate-600') }} whitespace-nowrap">
                        {{ $steps[$step] }}
                    </span>
                </div>
                @if(!$loop->last)
                    <div class="flex-1 h-1 mx-2 rounded-full {{ $stepIdx < $currentIndex ? 'bg-emerald-500' : 'bg-slate-200' }}" aria-hidden="true"></div>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
