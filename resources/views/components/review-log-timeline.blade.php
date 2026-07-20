@props(['logs' => []])

@php
    use Illuminate\Support\Collection;
    $logs = $logs instanceof Collection ? $logs : collect($logs);
@endphp

@if($logs->isEmpty())
    <span class="text-gray-400 text-sm">-</span>
@else
    <div class="py-1">
        @foreach($logs as $i => $log)
            @php
                $label = $log->label ?? '';
                $time = $log->created_at ? $log->created_at->format('d M Y, H:i') : '-';
                $catatan = $log->catatan ?? null;
                $last = $i === $logs->count() - 1;

                $badgeClass = match($label) {
                    'Ditolak' => 'bg-red-100 text-red-700',
                    'Disetujui' => 'bg-green-100 text-green-700',
                    'Jadwal' => 'bg-blue-100 text-blue-700',
                    'TTD Selesai' => 'bg-purple-100 text-purple-700',
                    'Finalisasi' => 'bg-violet-100 text-violet-700',
                    default => 'bg-gray-100 text-gray-600',
                };
                $dotColor = match($label) {
                    'Ditolak' => 'bg-red-500',
                    'Disetujui' => 'bg-green-500',
                    'Jadwal' => 'bg-blue-500',
                    'TTD Selesai' => 'bg-purple-500',
                    'Finalisasi' => 'bg-violet-500',
                    default => 'bg-gray-400',
                };
            @endphp
            <div class="flex gap-2.5">
                <div class="flex flex-col items-center shrink-0">
                    <div class="w-2.5 h-2.5 rounded-full {{ $dotColor }} mt-1"></div>
                    @if(!$last)
                        <div class="w-0.5 flex-1 bg-gray-200 my-0.5"></div>
                    @endif
                </div>
                <div class="{{ $last ? '' : 'pb-3' }}">
                    <span class="inline-block px-2 py-0.5 rounded-full font-medium text-[11px] {{ $badgeClass }}">{{ $label }}</span>
                    <span class="text-gray-400 text-[11px] ml-1.5">{{ $time }}</span>
                    @if($catatan)
                        <p class="text-gray-500 text-xs mt-1">{{ $catatan }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
