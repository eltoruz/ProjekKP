@extends('layouts.app')

@section('title', 'Mitra — Pusdatin Kemendikdasmen')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-navy flex-shrink-0 sticky top-0 h-screen overflow-y-auto flex flex-col">
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <img src="https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png" class="h-8" alt="">
                <div>
                    <h2 class="text-sm font-bold text-white leading-tight">Pusdatin</h2>
                    <p class="text-[10px] text-white/60">Kemendikdasmen</p>
                </div>
            </div>
        </div>

        <nav class="px-3 py-4 space-y-1 flex-1">
            <p class="px-3 text-[10px] font-semibold text-white/40 uppercase tracking-wider mb-2">Menu</p>

            <a href="{{ route('mitra.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('mitra.dashboard') ? 'bg-primary/20 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <div class="pt-3">
                <p class="px-3 text-[10px] font-semibold text-white/40 uppercase tracking-wider mb-2">Kerja Sama</p>
                <a href="{{ route('mitra.kerjasama.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('mitra.kerjasama.index') && !request('status') ? 'bg-primary/20 text-white font-medium' : (request()->routeIs('mitra.kerjasama.show') || request()->routeIs('mitra.kerjasama.edit') || request()->routeIs('mitra.kerjasama.pemilihan-data.*') ? 'bg-primary/20 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Daftar Kerja Sama
                </a>
                <a href="{{ route('mitra.kerjasama.create') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('mitra.kerjasama.create') ? 'bg-primary/20 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Baru
                </a>
                <a href="{{ route('mitra.kerjasama.index', ['status' => 5]) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('mitra.kerjasama.laporan') || request('status') == 5 ? 'bg-primary/20 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Pelaporan Berkala
                </a>
            </div>

        </nav>

        <div class="border-t border-white/10 px-4 py-3">
            <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/70 hover:bg-red-500/20 hover:text-red-300 w-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 min-w-0 bg-gray-50 flex flex-col min-h-screen">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center sticky top-0 z-10 shadow-sm">
            <div>
                <h1 class="text-lg font-semibold text-navy">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-gray-500">Sistem Manajemen Kerja Sama — Pusdatin Kemendikdasmen</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Bell Notification Menu (Alpine.js) -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="relative p-2 text-gray-500 hover:text-primary rounded-full hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-green-500 rounded-full ring-2 ring-white animate-pulse"></span>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50 text-xs">
                        <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                            <span class="font-bold text-gray-900">Notifikasi Kerja Sama</span>
                            <span class="text-[10px] text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded">Terbaru</span>
                        </div>
                        <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto">
                            <a href="{{ route('mitra.kerjasama.index') }}" class="px-4 py-3 hover:bg-slate-50 block transition-colors">
                                <p class="font-bold text-gray-800">Status Persetujuan Data</p>
                                <p class="text-[11px] text-gray-500 mt-0.5">Admin Pusdatin telah memperbarui status persetujuan kolom metadata Anda.</p>
                                <span class="text-[10px] text-gray-400 mt-1 block">Baru saja</span>
                            </a>
                            <a href="{{ route('mitra.kerjasama.index', ['status' => 5]) }}" class="px-4 py-3 hover:bg-slate-50 block transition-colors">
                                <p class="font-bold text-gray-800">Pelaporan Berkala Aktif</p>
                                <p class="text-[11px] text-gray-500 mt-0.5">Menu unggah laporan berkala Semester 1 & 2 kini telah dibuka.</p>
                                <span class="text-[10px] text-gray-400 mt-1 block">Hari ini</span>
                            </a>
                        </div>
                    </div>
                </div>

                <span class="text-xs text-white bg-primary px-3 py-1.5 rounded-full font-medium">Mitra</span>
            </div>
        </header>
        <div class="px-6 py-6">
            @yield('page-content')
        </div>
    </main>
</div>
@endsection
