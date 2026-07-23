@extends('layouts.admin')

@section('title', 'Review Integrasi Data')
@section('page-title', 'Review Hak Akses Data')

@section('page-content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header & Nav Back -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <a href="{{ route('admin.kerjasama.review', $kerjasama->kerjasama_id) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary mb-2 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Review Kerja Sama
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Review & Pengaturan Sensor Data (Masking)</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar tabel dan kolom data yang diminta oleh mitra. Atur status penyamaran (masking) untuk data sensitif.</p>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-lg px-4 py-3 shrink-0 text-right">
            <span class="text-xs text-purple-600 font-semibold block uppercase">Pemohon Integrasi</span>
            <span class="text-sm font-bold text-purple-900">{{ $kerjasama->nama_kl }}</span>
        </div>
    </div>

    <!-- Alert / Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.kerjasama.integrasi-review.update', $kerjasama->kerjasama_id) }}">
        @csrf

        <div class="space-y-6">
            @forelse($groupedSelections as $tableName => $items)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-mono font-semibold">Tabel</span>
                        <h3 class="font-bold text-gray-900 text-base font-mono">{{ $tableName }}</h3>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                        {{ count($items) }} Kolom Diminta
                    </span>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($items as $item)
                    @php $meta = $item->metadata; @endphp
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50/50 transition">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-sm font-mono text-gray-900">{{ $meta->name }}</span>
                                <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-mono">{{ $meta->type }}</span>
                                @if($meta->primary_key)
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-bold">PK</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 font-mono">Skema: {{ $meta->db_name }}.{{ $meta->schema_name }}</p>
                        </div>

                        <!-- Masking Toggle Switch -->
                        <div class="flex items-center space-x-3 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 shrink-0">
                            <label for="mask_{{ $item->id }}" class="text-xs font-medium text-gray-700 cursor-pointer select-none">
                                Sensor Data (<span class="font-mono text-amber-600">is_masked</span>)
                            </label>
                            <input type="checkbox" 
                                   id="mask_{{ $item->id }}" 
                                   name="masked_metadata[]" 
                                   value="{{ $item->id }}" 
                                   {{ $item->is_masked ? 'checked' : '' }} 
                                   class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="bg-white p-12 text-center rounded-xl border border-gray-200 shadow-sm">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <h3 class="text-base font-semibold text-gray-700">Belum Ada Pengajuan Data Integrasi</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">Mitra belum mencentang atau mengajukan tabel/kolom data dari Katalog Pusdatin.</p>
            </div>
            @endforelse
        </div>

        @if($groupedSelections->count() > 0)
        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-sm transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Pengaturan Sensor Data</span>
            </button>
        </div>
        @endif
    </form>

</div>
@endsection
