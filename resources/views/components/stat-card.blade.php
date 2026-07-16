<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
    <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 rounded-lg {{ $bgColor ?? 'bg-blue-100' }} flex items-center justify-center">
            {!! $icon ?? '' !!}
        </div>
        <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
    </div>
    <p class="text-3xl font-bold text-gray-900">{{ $value }}</p>
    @if(isset($subtitle))
        <p class="text-xs text-gray-400 mt-1">{{ $subtitle }}</p>
    @endif
</div>
