@extends('layouts.mitra')

@section('title', 'Integrasi Data Catalog')
@section('page-title', 'Pengajuan Integrasi Data')

@section('page-content')
<div x-data="{
    search: '',
    selected: {{ json_encode($selectedMetadataIds) }}.map(id => id.toLowerCase()),
    activeDb: '{{ array_keys($groupedMetadata->toArray())[0] ?? 'Backbone' }}',
    dbMetadata: {{ json_encode($dbMetadataJson) }},

    toggleColumn(id) {
        id = id.toLowerCase();
        if (this.selected.includes(id)) {
            this.selected = this.selected.filter(i => i !== id);
        } else {
            this.selected.push(id);
        }
    },

    toggleAllTable(ids) {
        const lowerIds = ids.map(id => id.toLowerCase());
        const allSelected = lowerIds.every(id => this.selected.includes(id));
        if (allSelected) {
            this.selected = this.selected.filter(id => !lowerIds.includes(id));
        } else {
            lowerIds.forEach(id => {
                if (!this.selected.includes(id)) this.selected.push(id);
            });
        }
    },

    isTableAllSelected(ids) {
        const lowerIds = ids.map(id => id.toLowerCase());
        return lowerIds.length > 0 && lowerIds.every(id => this.selected.includes(id));
    },

    isTableSomeSelected(ids) {
        const lowerIds = ids.map(id => id.toLowerCase());
        return lowerIds.some(id => this.selected.includes(id)) && !this.isTableAllSelected(ids);
    },

    countSelectedInTable(ids) {
        const lowerIds = ids.map(id => id.toLowerCase());
        return lowerIds.filter(id => this.selected.includes(id)).length;
    },

    tableMatchesSearch(db, tableName) {
        if (!this.search) return true;
        const s = this.search.toLowerCase();
        if (tableName.toLowerCase().includes(s)) return true;
        const cols = this.dbMetadata[db]?.[tableName] || [];
        return cols.some(col => col.name.toLowerCase().includes(s));
    }
}" class="max-w-7xl mx-auto space-y-6">

    <!-- Header & Nav Back -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <a href="{{ route('mitra.kerjasama.show', $kerjasama->kerjasama_id) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary mb-2 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Detail Kerja Sama
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Katalog & Pemilihan Data Integrasi</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih tabel dan kolom data Pusdatin yang Anda butuhkan untuk integrasi sistem.</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 shrink-0 text-right">
            <span class="text-xs text-blue-600 font-semibold block uppercase">Pengaju / Instansi</span>
            <span class="text-sm font-bold text-blue-900">{{ $kerjasama->nama_kl }}</span>
        </div>
    </div>

    <!-- Instruction Info Banner -->
    <div class="bg-blue-50/50 border border-blue-200 rounded-xl p-4 flex items-start space-x-3">
        <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-xs text-blue-800 space-y-1">
            <p class="font-bold">Panduan Pemilihan Data:</p>
            <p>1. Silakan pilih database di bawah ini, lalu buka tabel (accordion) untuk mencentang kolom data yang diperlukan.</p>
            <p>2. Pengajuan data ini akan direview oleh Admin Pusdatin untuk disetujui atau diberi penyamaran/sensor (masking) jika merupakan data sensitif.</p>
        </div>
    </div>

    <!-- Database Tab Switcher -->
    <div class="flex border border-gray-200 bg-white p-1.5 rounded-xl shadow-xs gap-2 overflow-x-auto">
        @foreach($groupedMetadata as $dbName => $tables)
        <button type="button" 
                @click="activeDb = '{{ $dbName }}'"
                :class="activeDb === '{{ $dbName }}' ? 'bg-primary text-white shadow-xs' : 'bg-transparent text-gray-600 hover:bg-gray-50'"
                class="px-5 py-2.5 text-xs font-bold rounded-lg transition-all flex items-center space-x-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
            <span>Database: {{ $dbName }}</span>
            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold"
                  :class="activeDb === '{{ $dbName }}' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'">
                {{ count($tables) }} Tabel
            </span>
        </button>
        @endforeach
    </div>

    <!-- Layout Grid: Main Catalog + Sidebar Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1: Search & Tables Accordion (2 Cols) -->
        <div class="lg:col-span-2 space-y-4">

            <!-- Search Bar -->
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <div class="relative">
                    <input type="text" x-model="search" placeholder="Cari nama tabel atau nama kolom..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary transition">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Tables Accordion List -->
            <form id="integrationForm" method="POST" action="{{ route('mitra.kerjasama.integrasi.store', $kerjasama->kerjasama_id) }}">
                @csrf
                <input type="hidden" name="selected_metadata_json" :value="JSON.stringify(selected)">
                
                @foreach($groupedMetadata as $dbName => $tables)
                <div x-show="activeDb === '{{ $dbName }}'" class="space-y-4">
                    @foreach($tables as $tableName => $metaHeader)
                    @php
                        $columnIds = $metaHeader['column_ids'];
                        $schemaName = $metaHeader['schema_name'];
                        $totalColumns = $metaHeader['total_columns'];
                    @endphp
                    <div x-data="{ open: false }" 
                         x-show="tableMatchesSearch('{{ $dbName }}', '{{ $tableName }}')"
                         class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition">

                        <!-- Table Accordion Header -->
                        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-200 flex items-center justify-between cursor-pointer hover:bg-gray-100/80 transition" @click="open = !open">
                            <div class="flex items-center space-x-3">
                                <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-5 h-5 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-mono font-semibold">{{ $schemaName }}</span>
                                        <h3 class="font-bold text-gray-900 text-base font-mono">{{ $tableName }}</h3>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $totalColumns }} kolom tersedia</p>
                                </div>
                            </div>

                            <!-- Table Controls -->
                            <div class="flex items-center space-x-3" @click.stop>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-700" x-text="countSelectedInTable({{ json_encode($columnIds) }}) + ' / {{ $totalColumns }} dipilih'"></span>
                                <button type="button" @click="toggleAllTable({{ json_encode($columnIds) }})" class="text-xs font-medium text-primary hover:underline bg-white px-3 py-1.5 rounded-lg border border-gray-300 shadow-xs hover:bg-blue-50 transition">
                                    <span x-text="isTableAllSelected({{ json_encode($columnIds) }}) ? 'Batal Semua' : 'Pilih Semua'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Table Columns Body (Lazy Rendered via x-if + x-for) -->
                        <div x-show="open" x-collapse class="divide-y divide-gray-100 bg-white">
                            <template x-if="open">
                                <div>
                                    <template x-for="col in dbMetadata[activeDb]['{{ $tableName }}']" :key="col.id">
                                        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-blue-50/30 transition cursor-pointer" @click="toggleColumn(col.id)">
                                            <div class="flex items-start space-x-3">
                                                <input type="checkbox" 
                                                       :value="col.id" 
                                                       :checked="selected.includes(col.id)" 
                                                       @click.stop="toggleColumn(col.id)"
                                                       class="mt-1 w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                                <div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-semibold text-sm font-mono text-gray-800" x-text="col.name"></span>
                                                        <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-mono" x-text="col.type"></span>
                                                        <template x-if="col.primary_key">
                                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-bold">PK</span>
                                                        </template>
                                                        <template x-if="!col.nullable">
                                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-red-100 text-red-700 font-semibold">NOT NULL</span>
                                                        </template>
                                                    </div>
                                                    <template x-if="col.description">
                                                        <p class="text-xs text-gray-500 mt-1" x-text="col.description"></p>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                    </div>
                    @endforeach
                </div>
                @endforeach
            </form>

        </div>

        <!-- Column 2: Sticky Summary & Submit Action (1 Col) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm sticky top-6 space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Rangkuman Pengajuan</h3>
                    <p class="text-xs text-gray-500">Pastikan seluruh kolom yang Anda centang sudah sesuai dengan kebutuhan integrasi aplikasi Anda.</p>
                </div>

                <!-- Live Metrics -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <div>
                        <span class="text-xs font-medium text-gray-500 block">Total Kolom</span>
                        <span class="text-2xl font-bold text-primary" x-text="selected.length"></span>
                    </div>
                </div>

                <!-- Action Button -->
                <button type="submit" form="integrationForm" class="w-full py-3 px-4 bg-primary hover:bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-sm transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Pilihan Integrasi</span>
                </button>

                <p class="text-[11px] text-gray-400 text-center">Pilihan katalog data ini akan langsung disimpan dan aktif untuk sistem Anda.</p>
            </div>
        </div>

    </div>

</div>
@endsection
