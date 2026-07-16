<span class="px-2.5 py-0.5 rounded-full text-xs font-medium
    @switch($status)
        @case('DRAFT') bg-gray-100 text-gray-700 @break
        @case('UPLOAD_DOKUMEN') bg-gray-200 text-gray-800 @break
        @case('DIAJUKAN') bg-blue-100 text-blue-700 @break
        @case('REVIEW_ADMIN') bg-blue-200 text-blue-800 @break
        @case('DITOLAK') bg-red-100 text-red-700 @break
        @case('DISETUJUI') bg-yellow-100 text-yellow-700 @break
        @case('MENUNGGU_PEMBAHASAN') bg-yellow-200 text-yellow-800 @break
        @case('SELESAI_PEMBAHASAN') bg-purple-100 text-purple-700 @break
        @case('PROSES_TTD') bg-purple-200 text-purple-800 @break
        @case('SELESAI') bg-green-100 text-green-700 @break
        @case('EXPIRED') bg-gray-200 text-gray-500 @break
        @default bg-gray-100 text-gray-600
    @endswitch
">
    {{ $label ?? $status }}
</span>
