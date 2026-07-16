<div class="w-full py-4">
    <div class="flex items-center justify-between">
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
        @foreach($displaySteps as $index => $step)
            @php
                $stepIdx = array_search($step, $stepKeys);
                $isActive = $stepIdx === $currentIndex;
                $isPassed = $stepIdx < $currentIndex;
                $isRejected = $step === 'DITOLAK' && $stepIdx === $currentIndex;
            @endphp
            <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold
                        {{ $isActive || $isRejected ? 'bg-blue-600 text-white' : ($isPassed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500') }}">
                        @if($isPassed) ✓ @else {{ $stepIdx + 1 }} @endif
                    </div>
                    <span class="text-xs mt-1 text-gray-500 whitespace-nowrap">{{ $steps[$step] }}</span>
                </div>
                @if(!$loop->last)
                    <div class="flex-1 h-0.5 mx-2 {{ $stepIdx < $currentIndex ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
