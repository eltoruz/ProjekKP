@extends('layouts.admin')

@section('title', 'Persetujuan Pemilihan Data')
@section('page-title', 'Persetujuan Pemilihan Data Mitra')

@section('page-content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm font-medium flex items-center justify-between">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@php
    $initialApprovals = [];
    $adminDatabaseList = [];
    $groupedData = [];
    $initialOpen = [];

    $hasExistingDecision = $ks->pemilihanData->contains(
        fn($item) => in_array($item->approval_status, ['approved', 'rejected'], true)
    );

    foreach($ks->pemilihanData as $item) {
        $initialApprovals[$item->id] = $item->approval_status ?? 'pending';
        $meta = $item->metadata;
        $dbName = $meta?->db_name ?? 'Database';
        $tblName = $meta?->tbl_name ?? 'Tabel';

        if ($dbName && !in_array($dbName, $adminDatabaseList)) {
            $adminDatabaseList[] = $dbName;
        }

        $groupedData[$dbName][$tblName][] = $item;
    }

    sort($adminDatabaseList);
    ksort($groupedData);

    foreach($groupedData as $dbName => &$tables) {
        ksort($tables);
        foreach($tables as $tblName => $itemsInTbl) {
            $key = $dbName . '::' . $tblName;
            $initialOpen[$key] = false;
        }
    }
@endphp

<div class="space-y-6" x-data="{
    approval: {{ json_encode($initialApprovals) }},
    openTables: {{ json_encode($initialOpen) }},
    search: '',
    selectedDb: '',
    selectedStatus: '',

    toggleTable(key) {
        this.openTables[key] = !this.openTables[key];
    },

    get anyOpen() {
        return Object.values(this.openTables).some(Boolean);
    },

    toggleAll() {
        let shouldOpen = !this.anyOpen;
        for (let k in this.openTables) this.openTables[k] = shouldOpen;
    },

    setTableApproval(ids, status) {
        ids.forEach(id => {
            if (id in this.approval) this.approval[id] = status;
        });
    },

    matchesFilter(itemId, dbName, tblName, colName, alasan) {
        if (this.selectedDb && dbName !== this.selectedDb) return false;
        if (this.selectedStatus && (this.approval[itemId] || 'pending') !== this.selectedStatus) return false;
        
        if (this.search) {
            const q = this.search.toLowerCase();
            if ((!colName || colName.toLowerCase().indexOf(q) === -1) && 
                (!tblName || tblName.toLowerCase().indexOf(q) === -1) && 
                (!dbName || dbName.toLowerCase().indexOf(q) === -1) && 
                (!alasan || alasan.toLowerCase().indexOf(q) === -1)) {
                return false;
            }
        }
        
        return true;
    },

    approveAll() {
        for (let id in this.approval) { this.approval[id] = 'approved'; }
    },
    rejectAll() {
        for (let id in this.approval) { this.approval[id] = 'rejected'; }
    },
    resetPending() {
        for (let id in this.approval) { this.approval[id] = 'pending'; }
    },

    get approvedCount() {
        return Object.values(this.approval).filter(v => v === 'approved').length;
    },
    get rejectedCount() {
        return Object.values(this.approval).filter(v => v === 'rejected').length;
    },
    get pendingCount() {
        return Object.values(this.approval).filter(v => v === 'pending').length;
    }
} shadow-sm">

    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.kerjasama.index') }}" class="hover:text-indigo-600">Daftar Kerja Sama</a>
                <span>/</span>
                <a href="{{ route('admin.kerjasama.review', $ks->kerjasama_id) }}" class="hover:text-indigo-600">{{ Str::limit($ks->nama_kl, 30) }}</a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Persetujuan Data</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Persetujuan Pemilihan Data Mitra</h2>
            <p class="text-xs text-gray-500 mt-0.5">Tentukan persetujuan per item/kolom data yang diajukan oleh Mitra (Default: Belum Disetujui/Pending)</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.kerjasama.review', $ks->kerjasama_id) }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Review
            </a>
        </div>
    </div>

    <form action="{{ route('admin.kerjasama.persetujuan-data', $ks->kerjasama_id) }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-xl border border-indigo-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Pengaturan Metode Pertukaran & Status Implementasi</h3>
                        <p class="text-[11px] text-gray-500">Status Implementasi <strong>'Aktif'</strong> merupakan salah satu syarat utama pembukaan menu pelaporan berkala Mitra.</p>
                    </div>
                </div>

                <div>
                    @if((int)$ks->ks_implementasi === 3)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Status Pengaktifan: AKTIF
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                            Status Pengaktifan: {{ $ks->implementasi?->nama_status ?? 'Belum Aktif' }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Pertukaran Data</label>
                    <select name="ks_metode" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="">-- Pilih Metode --</option>
                        @foreach($metodeList as $mId => $mNama)
                            <option value="{{ $mId }}" {{ (string)$ks->ks_metode === (string)$mId ? 'selected' : '' }}>{{ $mNama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Implementasi (Pengaktifan Layanan)</label>
                    <select name="ks_implementasi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="">-- Pilih Status --</option>
                        @foreach($implementasiList as $iId => $iNama)
                            <option value="{{ $iId }}" {{ (string)$ks->ks_implementasi === (string)$iId ? 'selected' : '' }}>
                                {{ $iNama }} {{ $iId == 3 ? '(Aktifkan Pelaporan)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
            
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                <div class="flex flex-col sm:flex-row gap-3">
                    @if(count($adminDatabaseList) > 0)
                    <div class="shrink-0">
                        <select x-model="selectedDb" class="w-full sm:w-auto border border-gray-300 rounded-lg px-3.5 py-2 text-xs bg-white focus:ring-2 focus:ring-indigo-500 font-medium shadow-2xs">
                            <option value="">-- Semua Database ({{ count($adminDatabaseList) }}) --</option>
                            @foreach($adminDatabaseList as $dbItem)
                                <option value="{{ $dbItem }}">{{ $dbItem }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif


                    <div class="shrink-0">
                        <select x-model="selectedStatus" class="w-full sm:w-auto border border-gray-300 rounded-lg px-3.5 py-2 text-xs bg-white focus:ring-2 focus:ring-indigo-500 font-medium shadow-2xs">
                            <option value="">-- Semua Status Persetujuan --</option>
                            <option value="pending">Status: Pending</option>
                            <option value="approved">Status: Disetujui</option>
                            <option value="rejected">Status: Ditolak</option>
                        </select>
                    </div>

                    <div class="relative flex-1">
                        <input type="text" x-model="search" placeholder="Cari kolom, tabel, database, atau alasan..." 
                               class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-2xs">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-200/80 text-xs">
                    <div class="flex items-center gap-3 flex-wrap">
                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="approveAll()" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-green-700 bg-green-50 border border-green-300 hover:bg-green-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui Semua
                            </button>
                            <button type="button" @click="rejectAll()" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-red-700 bg-red-50 border border-red-300 hover:bg-red-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak Semua
                            </button>
                            <button type="button" @click="resetPending()" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 border border-gray-300 hover:bg-gray-200 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Reset Pending
                            </button>
                        </div>

                        <span class="text-gray-300 hidden sm:inline">•</span>

                        <div class="flex items-center gap-2">
                            <button type="button" @click="toggleAll()" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 font-medium hover:underline font-semibold text-xs">
                                <template x-if="anyOpen">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        Tutup Semua
                                    </span>
                                </template>
                                <template x-if="!anyOpen">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        Buka Semua Accordion
                                    </span>
                                </template>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-[11px] flex-wrap">
                        <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-semibold border border-amber-200">
                            Pending: <strong x-text="pendingCount"></strong>
                        </span>
                        <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-800 font-semibold border border-green-200">
                            Disetujui: <strong x-text="approvedCount"></strong>
                        </span>
                        <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-800 font-semibold border border-red-200">
                            Ditolak: <strong x-text="rejectedCount"></strong>
                        </span>
                    </div>
                </div>
            </div>

            @if(count($groupedData) === 0)
                <div class="p-8 text-center text-gray-500 text-xs">
                    <p class="font-medium text-gray-700 mb-1">Belum ada item data yang diajukan oleh Mitra.</p>
                    <p>Mitra belum memilih data atau belum menekan tombol <strong>Ajukan Pemilihan Data</strong> di halaman detail.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($groupedData as $dbName => $tables)
                    <div x-show="!selectedDb || selectedDb === '{{ $dbName }}'" class="space-y-3">
                        
                        <div class="flex items-center gap-2 py-2 px-3.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-800 font-mono font-bold text-xs sticky top-0 z-10 shadow-2xs">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s-8-1.79-8-4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                            </svg>
                            <span>DATABASE: {{ $dbName }}</span>
                            <span class="text-[11px] font-normal text-slate-500">({{ count($tables) }} tabel terpilih)</span>
                        </div>

                        <div class="space-y-3 pl-1 sm:pl-2">
                            @foreach($tables as $tblName => $itemsInTbl)
                            @php 
                                $accKey = $dbName . '::' . $tblName;
                                $itemIdsInTbl = array_map(fn($it) => $it->id, $itemsInTbl);
                            @endphp
                            <div class="border border-gray-200 rounded-xl overflow-hidden bg-white shadow-2xs transition-all">
                                
                                <div @click="toggleTable('{{ $accKey }}')" 
                                     class="px-4 py-3 bg-white hover:bg-slate-50 cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 select-none">
                                    
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <h4 class="font-mono font-bold text-sm text-gray-900">{{ $tblName }}</h4>
                                        <span class="text-[11px] text-gray-500 font-mono bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                                            {{ $dbName }}
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            {{ count($itemsInTbl) }} kolom terpilih
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0">
                                        <span class="text-xs font-medium text-gray-500 flex items-center gap-1.5">
                                            Detail Kolom
                                            <svg class="w-4 h-4 transition-transform duration-200" :class="openTables['{{ $accKey }}'] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <div x-show="openTables['{{ $accKey }}']">
                                    
                                    <div class="px-4 py-2 bg-slate-50 border-b border-gray-200 flex flex-wrap items-center justify-between gap-2 text-xs">
                                        <span class="text-gray-500 font-medium">Aksi Cepat Tabel ini ({{ count($itemsInTbl) }} Kolom):</span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="setTableApproval({{ json_encode($itemIdsInTbl) }}, 'approved')" 
                                                    class="px-2.5 py-1 rounded bg-green-50 text-green-700 hover:bg-green-100 font-semibold border border-green-200 text-[11px]">
                                                Setujui Semua di Tabel Ini
                                            </button>
                                            <button type="button" @click="setTableApproval({{ json_encode($itemIdsInTbl) }}, 'rejected')" 
                                                    class="px-2.5 py-1 rounded bg-red-50 text-red-700 hover:bg-red-100 font-semibold border border-red-200 text-[11px]">
                                                Tolak Semua di Tabel Ini
                                            </button>
                                            <button type="button" @click="setTableApproval({{ json_encode($itemIdsInTbl) }}, 'pending')" 
                                                    class="px-2.5 py-1 rounded bg-amber-50 text-amber-700 hover:bg-amber-100 font-semibold border border-amber-200 text-[11px]">
                                                Reset Pending Tabel Ini
                                            </button>
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr class="bg-gray-100/80 border-b border-gray-200 text-gray-700 font-bold uppercase tracking-wider">
                                                    <th class="py-2.5 px-4">Nama Kolom</th>
                                                    <th class="py-2.5 px-4 w-1/3">Alasan Penggunaan (Mitra)</th>
                                                    <th class="py-2.5 px-4 text-center min-w-[280px]">Status Persetujuan (Admin)</th>
                                                    <th class="py-2.5 px-4">Catatan Admin</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach($itemsInTbl as $item)
                                                @php $meta = $item->metadata; @endphp
                                                <tr x-show="matchesFilter('{{ $item->id }}', '{{ $meta?->db_name ?? '' }}', '{{ $meta?->tbl_name ?? '' }}', '{{ $meta?->name ?? '' }}', '{{ addslashes($item->alasan ?? '') }}')"
                                                    class="hover:bg-slate-50 transition-colors"
                                                    :class="{
                                                        'bg-green-50/40': approval['{{ $item->id }}'] === 'approved',
                                                        'bg-red-50/40': approval['{{ $item->id }}'] === 'rejected',
                                                        'bg-amber-50/30': approval['{{ $item->id }}'] === 'pending'
                                                    }">
                                                    <td class="py-3 px-4">
                                                        <div class="font-bold text-gray-900 font-mono text-xs">{{ $meta?->name ?? 'Kolom Unknown' }}</div>
                                                        <div class="text-[11px] text-gray-500 font-mono mt-0.5">
                                                            <span class="text-indigo-600 bg-indigo-50 px-1 py-0.5 rounded border border-indigo-100">({{ $meta?->type_name ?? $meta?->type ?? 'text' }})</span>
                                                        </div>
                                                        @if($meta?->description)
                                                            <div class="text-[11px] text-gray-400 italic mt-0.5">{{ Str::limit($meta->description, 60) }}</div>
                                                        @endif
                                                    </td>

                                                    <td class="py-3 px-4 text-gray-700">
                                                        <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-200 text-xs">
                                                            {{ $item->alasan ?? 'Tidak ada alasan' }}
                                                        </div>
                                                    </td>

                                                    <td class="py-3 px-4 text-center">
                                                        <div class="inline-flex items-center p-1 bg-gray-100 rounded-lg border border-gray-200 gap-1">
                                                            <label class="cursor-pointer px-2.5 py-1.5 rounded-md text-[11px] font-bold transition-all flex items-center gap-1"
                                                                   :class="approval['{{ $item->id }}'] === 'pending' ? 'bg-amber-500 text-white shadow-2xs' : 'text-gray-600 hover:text-amber-600'">
                                                                <input type="radio" name="approval_status[{{ $item->id }}]" value="pending" x-model="approval['{{ $item->id }}']" class="sr-only">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                                Pending
                                                            </label>

                                                            <label class="cursor-pointer px-2.5 py-1.5 rounded-md text-[11px] font-bold transition-all flex items-center gap-1"
                                                                   :class="approval['{{ $item->id }}'] === 'approved' ? 'bg-green-600 text-white shadow-2xs' : 'text-gray-600 hover:text-green-600'">
                                                                <input type="radio" name="approval_status[{{ $item->id }}]" value="approved" x-model="approval['{{ $item->id }}']" class="sr-only">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                Setujui
                                                            </label>

                                                            <label class="cursor-pointer px-2.5 py-1.5 rounded-md text-[11px] font-bold transition-all flex items-center gap-1"
                                                                   :class="approval['{{ $item->id }}'] === 'rejected' ? 'bg-red-600 text-white shadow-2xs' : 'text-gray-600 hover:text-red-600'">
                                                                <input type="radio" name="approval_status[{{ $item->id }}]" value="rejected" x-model="approval['{{ $item->id }}']" class="sr-only">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                Tolak
                                                            </label>
                                                        </div>
                                                    </td>

                                                    <td class="py-3 px-4">
                                                        <input type="text" name="catatan_admin[{{ $item->id }}]" 
                                                               value="{{ $item->catatan_admin }}" 
                                                               placeholder="Catatan untuk mitra (opsional)..."
                                                               class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            <div class="pt-4 border-t border-gray-200 flex items-center justify-between">
                <a href="{{ route('admin.kerjasama.review', $ks->kerjasama_id) }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    <span>{{ $hasExistingDecision ? 'Perbarui Keputusan Persetujuan' : 'Simpan Keputusan Persetujuan' }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
