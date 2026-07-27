@extends('layouts.mitra')

@section('title', 'Pemilihan Data yang Diperlukan')
@section('page-title', 'Pemilihan Data yang Diperlukan')

@section('page-content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
@endif

@php
    $catalogByDb = isset($tableCatalog) ? $tableCatalog->groupBy('db_name') : collect();
    $initChecked = [];
    if (!empty($selectedColIds)) {
        foreach ($selectedColIds as $id) {
            $initChecked[(string)$id] = true;
        }
    }
    $initReasons = !empty($tableReasons) ? $tableReasons : new stdClass();
@endphp

<div class="space-y-6" x-data="{
    search: '',
    selectedDb: '',
    selectedLetter: '',
    openTables: {},
    loadedCols: {},
    loadingTable: {},
    checkedCols: {{ json_encode($initChecked) }},
    alasan: {{ json_encode($initReasons) }},
    columnsApiUrl: '{{ route('mitra.api.metadata.columns') }}',

    async loadColumns(dbName, tblName) {
        let key = dbName + '.' + tblName;
        if (this.loadedCols[key]) return;
        this.loadingTable = {...this.loadingTable, [key]: true};
        try {
            let res = await fetch(this.columnsApiUrl + '?db_name=' + encodeURIComponent(dbName) + '&tbl_name=' + encodeURIComponent(tblName));
            let cols = await res.json();
            this.loadedCols = {...this.loadedCols, [key]: cols};
        } catch(e) {
            console.error('Failed to load columns for', key, e);
        }
        this.loadingTable = {...this.loadingTable, [key]: false};
    },

    async toggleAccordion(dbName, tblName) {
        let key = dbName + '.' + tblName;
        let isOpen = !!this.openTables[key];
        this.openTables = {...this.openTables, [key]: !isOpen};
        if (!isOpen) {
            await this.loadColumns(dbName, tblName);
        }
    },

    toggleTable(dbName, tblName) {
        let key = dbName + '.' + tblName;
        let cols = this.loadedCols[key] || [];
        let allChecked = cols.length > 0 && cols.every(c => !!this.checkedCols[c.id]);
        let updated = {...this.checkedCols};
        cols.forEach(c => { updated[c.id] = !allChecked; });
        this.checkedCols = updated;
    },

    isTableFullyChecked(dbName, tblName) {
        let cols = this.loadedCols[dbName + '.' + tblName] || [];
        return cols.length > 0 && cols.every(c => !!this.checkedCols[c.id]);
    },

    isTablePartiallyChecked(dbName, tblName) {
        let cols = this.loadedCols[dbName + '.' + tblName] || [];
        let count = cols.filter(c => !!this.checkedCols[c.id]).length;
        return count > 0 && count < cols.length;
    },

    checkedColCountInTable(dbName, tblName) {
        let cols = this.loadedCols[dbName + '.' + tblName] || [];
        return cols.filter(c => !!this.checkedCols[c.id]).length;
    },

    get totalCheckedCols() {
        return Object.values(this.checkedCols).filter(Boolean).length;
    },

    get totalSelectedTables() {
        let count = 0;
        for (let key in this.loadedCols) {
            if (this.loadedCols[key].some(c => !!this.checkedCols[c.id])) count++;
        }
        return count;
    },

    hasMissingReason() {
        if (this.totalCheckedCols === 0) return true;
        for (let key in this.loadedCols) {
            let cols = this.loadedCols[key];
            if (cols.some(c => !!this.checkedCols[c.id])) {
                let tblName = key.split('.').slice(1).join('.');
                if (!this.alasan[tblName] || !this.alasan[tblName].trim()) return true;
            }
        }
        return false;
    },

    expandAll() {
        let updated = {...this.openTables};
        for (let key in updated) updated[key] = true;
        this.openTables = updated;
    },

    collapseAll() {
        let updated = {...this.openTables};
        for (let key in updated) updated[key] = false;
        this.openTables = updated;
    },

    clearAll() {
        this.checkedCols = {};
    },

    matchesFilter(tblName, dbName) {
        if (this.selectedDb && this.selectedDb !== dbName) return false;
        if (this.selectedLetter && !tblName.toUpperCase().startsWith(this.selectedLetter)) return false;
        if (this.search) {
            let s = this.search.toLowerCase();
            if (!tblName.toLowerCase().includes(s) && !dbName.toLowerCase().includes(s)) return false;
        }
        return true;
    },

    dbHasVisibleTable(dbName, tables) {
        return tables.some(t => this.matchesFilter(t, dbName));
    },

    setLetter(letter) {
        this.selectedLetter = this.selectedLetter === letter ? '' : letter;
    },

    validateSubmit(e) {
        let action = e.submitter ? e.submitter.value : 'save';
        if (action === 'submit') {
            if (this.totalCheckedCols === 0) {
                alert('Submit ditolak: Anda wajib memilih minimal 1 kolom data.');
                e.preventDefault();
                return false;
            }
            if (this.hasMissingReason()) {
                alert('Submit ditolak: Setiap tabel yang memiliki kolom terpilih WAJIB disertai Keterangan/Alasan Penggunaan Data.');
                e.preventDefault();
                return false;
            }
            if (!confirm('PERHATIAN: Setelah mengajukan secara final (Submit), data pilihan Anda akan TERKUNCI dan TIDAK DAPAT DIUBAH LAGI.\n\nApakah Anda yakin ingin mengajukan pemilihan data ini?')) {
                e.preventDefault();
                return false;
            }
        }
    }
}">

    <!-- Action Bar & Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('mitra.kerjasama.index') }}" class="hover:text-indigo-600">Kerja Sama</a>
                <span>/</span>
                <a href="{{ route('mitra.kerjasama.show', $kerjasama->kerjasama_id) }}" class="hover:text-indigo-600">{{ Str::limit($kerjasama->nama_kl, 30) }}</a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Pemilihan Data</span>
            </div>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-bold text-gray-900">Pemilihan Data yang Diperlukan</h2>
                @if($kerjasama->status_pemilihan_data === 'submitted')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300">
                        🔒 Final & Diajukan
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-300">
                        📝 Draf Pemilihan
                    </span>
                @endif
            </div>
            <p class="text-xs text-gray-500 mt-0.5">Pilih kolom spesifik pada tiap tabel yang Anda butuhkan beserta alasan penggunaannya</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('mitra.kerjasama.show', $kerjasama->kerjasama_id) }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Detail
            </a>
        </div>
    </div>

    @if($kerjasama->status_pemilihan_data === 'submitted')
    <div class="bg-amber-50 border border-amber-300 p-4 rounded-xl text-amber-900 text-xs flex items-center gap-3 shadow-xs">
        <svg class="w-6 h-6 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        <div>
            <strong class="font-bold text-sm block">Pemilihan Data Telah Diajukan & Terkunci (Read-Only)</strong>
            <span>Pilihan data ini telah dikirim secara final ke Admin Pusdatin dan tidak dapat diubah lagi. Anda dapat melihat kembali item yang telah diajukan di bawah ini.</span>
        </div>
    </div>
    @else
    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl text-amber-950 text-xs flex items-start gap-3 shadow-xs">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div class="space-y-1">
            <strong class="font-bold text-amber-900 text-sm block">⚠️ Perhatian Mengenai Pengajuan Final (Locking)</strong>
            <p class="leading-relaxed">
                Gunakan tombol <strong class="text-indigo-800 bg-indigo-100/80 px-1.5 py-0.5 rounded">💾 Simpan Sebagai Draf</strong> untuk menyimpan pilihan sementara tanpa mengunci form. 
                Jika Anda menekan <strong class="text-white bg-indigo-600 px-1.5 py-0.5 rounded">🚀 Ajukan / Submit Final</strong>, data pilihan beserta alasan akan diajukan secara resmi ke Admin Pusdatin dan <strong class="underline font-semibold text-amber-900">FORM AKAN TERKUNCI PERMANEN (TIDAK DAPAT DIUBAH LAGI)</strong>.
            </p>
        </div>
    </div>
    @endif

    <!-- Container Utama Form Pemilihan Data -->
    <div class="bg-white rounded-xl shadow-sm border border-indigo-200 p-6">
        <form action="{{ route('mitra.kerjasama.pemilihan-data', $kerjasama->kerjasama_id) }}" method="POST" @submit="validateSubmit($event)">
            @csrf
            
            <!-- Toolbar Filter & Pencarian -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-5 space-y-3">
                <!-- Row 1: Database Dropdown + Search Input -->
                <div class="flex flex-col sm:flex-row gap-3">
                    @if(isset($databaseList) && count($databaseList) > 0)
                    <div class="shrink-0">
                        <select x-model="selectedDb" class="w-full sm:w-auto border border-gray-300 rounded-lg px-3.5 py-2 text-xs bg-white focus:ring-2 focus:ring-indigo-500 font-medium shadow-2xs">
                            <option value="">-- Semua Database ({{ count($databaseList) }}) --</option>
                            @foreach($databaseList as $dbItem)
                                <option value="{{ $dbItem }}">{{ $dbItem }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="relative flex-1">
                        <input type="text" x-model="search" placeholder="Cari nama tabel atau database..." 
                               class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-2xs">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Row 2: Filter Abjad A-Z -->
                <div class="pt-2.5 border-t border-slate-200/80">
                    <div class="flex flex-wrap items-center gap-1">
                        <span class="text-[11px] text-gray-500 font-semibold mr-1 shrink-0">Filter Abjad:</span>
                        <button type="button" @click="selectedLetter = ''" 
                                class="px-2.5 py-1 rounded-md text-[11px] font-bold transition-colors border shadow-2xs"
                                :class="!selectedLetter ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300'">
                            Semua
                        </button>
                        @foreach(range('A','Z') as $letter)
                        <button type="button" @click="setLetter('{{ $letter }}')" 
                                class="w-6 h-6 flex items-center justify-center rounded-md text-[11px] font-bold transition-colors border shadow-2xs"
                                :class="selectedLetter === '{{ $letter }}' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-500 border-gray-200 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-300'">
                            {{ $letter }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Row 3: Quick Actions & Counters -->
                <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-slate-200/80 text-xs">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="expandAll()" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-medium hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2-2M5 19l2-2"/></svg>
                            Buka Semua Accordion
                        </button>
                        <span class="text-gray-300">•</span>
                        <button type="button" @click="collapseAll()" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-800 font-medium hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            Tutup Semua
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600">Total Terpilih: <strong class="text-indigo-600" x-text="totalCheckedCols"></strong> kolom (<strong class="text-indigo-600" x-text="totalSelectedTables"></strong> tabel)</span>
                        @if($kerjasama->status_pemilihan_data !== 'submitted')
                        <button type="button" x-show="totalCheckedCols > 0" @click="clearAll()" class="inline-flex items-center gap-0.5 text-red-500 hover:text-red-700 font-medium text-[11px] hover:underline ml-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reset Pilihan
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Accordion Tabel per Database -->
            <div class="space-y-5 max-h-[65vh] overflow-y-auto pr-1">
                @foreach($catalogByDb as $dbName => $tablesInDb)
                @php $tblNamesInDb = $tablesInDb->pluck('tbl_name')->toArray(); @endphp
                <div x-show="(!selectedDb || selectedDb === '{{ $dbName }}') && dbHasVisibleTable('{{ $dbName }}', {{ json_encode($tblNamesInDb) }})" class="space-y-2.5">
                    
                    <!-- Header Group Database -->
                    <div class="flex items-center gap-2 py-2 px-3.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-800 font-mono font-bold text-xs sticky top-0 z-10 shadow-2xs">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s-8-1.79-8-4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                        </svg>
                        <span>DATABASE: {{ $dbName }}</span>
                        <span class="text-[11px] font-normal text-slate-500">({{ count($tablesInDb) }} tabel)</span>
                    </div>

                    @foreach($tablesInDb as $tbl)
                    @php $tblKey = $dbName . '.' . $tbl->tbl_name; @endphp
                    <div x-show="matchesFilter('{{ $tbl->tbl_name }}', '{{ $dbName }}')"
                         class="rounded-xl border transition-all duration-200 overflow-hidden ml-2"
                         :class="checkedColCountInTable('{{ $dbName }}', '{{ $tbl->tbl_name }}') > 0 ? 'bg-indigo-50/20 border-indigo-300 shadow-xs' : 'bg-white border-gray-200 hover:border-indigo-200'">
                        
                        <!-- Table Card Header (Mouseenter pre-fetch for instant opening) -->
                        <div class="p-3.5 bg-gray-50/80 border-b border-gray-200/80 flex items-center justify-between cursor-pointer select-none"
                             @mouseenter="loadColumns('{{ $dbName }}', '{{ $tbl->tbl_name }}')"
                             @click="toggleAccordion('{{ $dbName }}', '{{ $tbl->tbl_name }}')">
                            
                            <div class="flex items-center gap-3">
                                <input type="checkbox" 
                                       :checked="isTableFullyChecked('{{ $dbName }}', '{{ $tbl->tbl_name }}')"
                                       :indeterminate="isTablePartiallyChecked('{{ $dbName }}', '{{ $tbl->tbl_name }}')"
                                       @if($kerjasama->status_pemilihan_data === 'submitted') disabled @endif
                                       @click.stop="loadColumns('{{ $dbName }}', '{{ $tbl->tbl_name }}').then(() => toggleTable('{{ $dbName }}', '{{ $tbl->tbl_name }}'))"
                                       class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">

                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-mono text-sm font-bold text-slate-800">{{ $tbl->tbl_name }}</span>
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-mono text-[11px] font-medium border border-slate-200">{{ $dbName }}.{{ $tbl->schema_name }}</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold"
                                              :class="checkedColCountInTable('{{ $dbName }}', '{{ $tbl->tbl_name }}') > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-200 text-gray-600'"
                                              x-text="checkedColCountInTable('{{ $dbName }}', '{{ $tbl->tbl_name }}') + ' / {{ $tbl->total_cols }} kolom terpilih'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                @if($kerjasama->status_pemilihan_data !== 'submitted')
                                <button type="button" @click.stop="loadColumns('{{ $dbName }}', '{{ $tbl->tbl_name }}').then(() => toggleTable('{{ $dbName }}', '{{ $tbl->tbl_name }}'))" 
                                        class="text-[11px] px-2.5 py-1 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition-colors shadow-2xs">
                                    <span x-text="isTableFullyChecked('{{ $dbName }}', '{{ $tbl->tbl_name }}') ? 'Batalkan Semua' : 'Pilih Semua'"></span>
                                </button>
                                @endif
                                
                                <!-- Loading Spinner -->
                                <svg x-show="loadingTable['{{ $tblKey }}']" class="w-4 h-4 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                
                                <svg x-show="!loadingTable['{{ $tblKey }}']" class="w-4 h-4 text-gray-400 transition-transform duration-200" 
                                     :class="openTables['{{ $tblKey }}'] ? 'rotate-180 text-indigo-600' : ''" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Accordion Body: List Columns (AJAX Loaded) -->
                        <div x-show="openTables['{{ $tblKey }}']" x-collapse class="p-4 bg-white border-t border-gray-100">
                            
                            <!-- Loading Indicator -->
                            <div x-show="loadingTable['{{ $tblKey }}'] && !loadedCols['{{ $tblKey }}']" class="text-center py-6 text-gray-400 text-xs">
                                <svg class="w-6 h-6 mx-auto mb-2 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memuat kolom...
                            </div>

                            <!-- Table Columns Content -->
                            <template x-if="loadedCols['{{ $tblKey }}']">
                                <div>
                                    <div class="mb-4 overflow-x-auto border border-gray-200 rounded-lg shadow-2xs">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase">
                                                    <th class="py-2.5 px-3 w-10 text-center">Pilih</th>
                                                    <th class="py-2.5 px-3">Nama Kolom</th>
                                                    <th class="py-2.5 px-3">Tipe Data</th>
                                                    <th class="py-2.5 px-3">Deskripsi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                <template x-for="col in loadedCols['{{ $tblKey }}']" :key="col.id">
                                                    <tr class="hover:bg-indigo-50/40 transition-colors"
                                                        :class="checkedCols[col.id] ? 'bg-indigo-50/30' : ''">
                                                        <td class="py-2 px-3 text-center">
                                                            <input type="checkbox" 
                                                                   name="selected_data[]" 
                                                                   :value="col.id" 
                                                                   :id="'col_' + col.id"
                                                                   x-model="checkedCols[col.id]"
                                                                   @if($kerjasama->status_pemilihan_data === 'submitted') disabled @endif
                                                                   class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                                                        </td>
                                                        <td class="py-2 px-3">
                                                            <label :for="'col_' + col.id" class="cursor-pointer font-mono font-bold text-slate-800 hover:text-indigo-600" x-text="col.name"></label>
                                                        </td>
                                                        <td class="py-2 px-3 font-mono text-gray-500">
                                                            <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 text-[11px] font-mono" x-text="col.type_name || col.type || '-'"></span>
                                                        </td>
                                                        <td class="py-2 px-3 text-gray-500 text-[11px]" x-text="col.description || '-'"></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Keterangan / Alasan Penggunaan Data (Tanpa Preset Buttons) -->
                                    <div x-show="checkedColCountInTable('{{ $dbName }}', '{{ $tbl->tbl_name }}') > 0" x-transition class="pt-3 border-t border-indigo-100">
                                        <label class="block text-xs font-semibold text-indigo-950 mb-1.5">
                                            Keterangan / Alasan Penggunaan Data untuk Tabel <span class="font-mono text-indigo-600">'{{ $tbl->tbl_name }}'</span> <span class="text-red-500">*</span>
                                        </label>

                                        <textarea :name="'alasan_table[{{ $tbl->tbl_name }}]'" x-model="alasan['{{ $tbl->tbl_name }}']" rows="2" 
                                                  placeholder="Tuliskan keterangan/alasan penggunaan data ini..." 
                                                  @if($kerjasama->status_pemilihan_data === 'submitted') readonly @endif
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-2xs readonly:bg-gray-100 readonly:text-gray-600"></textarea>
                                        
                                        <p x-show="checkedColCountInTable('{{ $dbName }}', '{{ $tbl->tbl_name }}') > 0 && (!alasan['{{ $tbl->tbl_name }}'] || !alasan['{{ $tbl->tbl_name }}'].trim())" 
                                           class="text-[11px] text-red-500 mt-1 font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                            Wajib mengisi alasan untuk tabel ini.
                                        </p>
                                    </div>
                                </div>
                            </template>

                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>

            <!-- Footer Submit Bar -->
            <div class="mt-6 pt-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs" x-text="totalCheckedCols"></span>
                    <span>kolom terpilih dari <strong class="text-gray-800" x-text="totalSelectedTables"></strong> tabel</span>
                </div>

                @if($kerjasama->status_pemilihan_data === 'submitted')
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <span class="px-4 py-2.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-300 flex items-center gap-1.5 shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Form Terkunci (Sudah Diajukan)
                    </span>
                    <a href="{{ route('mitra.kerjasama.show', $kerjasama->kerjasama_id) }}" class="px-5 py-2.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-2xs">
                        Kembali ke Detail
                    </a>
                </div>
                @else
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    <button type="submit" name="action" value="save" 
                            class="w-full sm:w-auto px-6 py-2.5 rounded-lg text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Simpan Sebagai Draf
                    </button>
                    <span class="text-[11px] text-gray-500 italic">
                        *Pengajuan final ke Admin dilakukan melalui tombol di Halaman Detail Kerja Sama.
                    </span>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
