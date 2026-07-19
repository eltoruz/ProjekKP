<span class="px-2.5 py-0.5 rounded-full text-xs font-medium text-white
    @switch((int)$status)
        @case(1) bg-blue-600 @break
        @case(2) bg-yellow-500 @break
        @case(3) bg-orange-500 @break
        @case(4) bg-purple-600 @break
        @case(5) bg-green-600 @break
        @case(6) bg-gray-500 @break
        @default bg-gray-400
    @endswitch
">
    {{ $label ?? $status }}
</span>
