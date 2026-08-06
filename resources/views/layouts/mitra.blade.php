@extends('layouts.app')

@section('title', 'Mitra — Pusdatin Kemendikdasmen')

@section('content')
<a href="#main-content" 
   class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 z-50 bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold shadow-lg focus:outline-none focus:ring-4 focus:ring-indigo-300">
   Lompati ke Konten Utama
</a>

<div class="flex min-h-screen bg-slate-50">
    <aside class="w-64 bg-[#0A0F24] flex-shrink-0 sticky top-0 h-screen overflow-y-auto flex flex-col justify-between border-r border-slate-800/60" aria-label="Navigasi Utama Mitra">
        <div>
            <div class="px-6 py-5 border-b border-slate-800/80 flex items-center gap-3.5">
                <img src="https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png" class="h-8 w-auto drop-shadow-md" alt="Logo Tutwuri Kemendikdasmen">
                <div>
                    <h2 class="text-base font-extrabold text-white leading-tight tracking-tight">Pusdatin</h2>
                    <p class="text-xs text-slate-400 font-medium">Kemendikdasmen</p>
                </div>
            </div>

            <nav class="px-3.5 py-6 space-y-6">
                <div>
                    <p class="px-3 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-2.5">MENU</p>
                    <a href="{{ route('mitra.dashboard') }}"
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('mitra.dashboard') ? 'bg-[#5B46F6] text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div>
                    <p class="px-3 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-2.5">KERJA SAMA</p>
                    <div class="space-y-1">
                        @php
                            $ksGroupActive = request()->routeIs('mitra.kerjasama.*');
                            $ksParentActive = ($ksGroupActive && !request()->routeIs('mitra.kerjasama.create')) || (request()->routeIs('mitra.kerjasama.index') && !request('status'));
                        @endphp
                        <div x-data="{ open: {{ $ksGroupActive ? 'true' : 'false' }} }">
                            <button type="button" @click="open = !open"
                                class="w-full flex items-center gap-3.5 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ $ksParentActive ? 'bg-[#5B46F6] text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="flex-1 text-left">Daftar Kerja Sama</span>
                                <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" x-collapse class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1">
                                <a href="{{ route('mitra.kerjasama.index') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('mitra.kerjasama.index') && !request('status') ? 'bg-[#5B46F6] text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                    <span>Lihat Semua</span>
                                </a>
                                <a href="{{ route('mitra.kerjasama.create') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('mitra.kerjasama.create') ? 'bg-[#5B46F6] text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>Tambah Baru</span>
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('mitra.pelaporan.index') }}"
                            class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('mitra.pelaporan.*') || request()->routeIs('mitra.kerjasama.laporan') ? 'bg-[#5B46F6] text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Pelaporan Berkala</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800/80">
            <a href="/" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold text-slate-300 hover:bg-rose-500/20 hover:text-rose-200 transition-all w-full group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-rose-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <main id="main-content" class="flex-1 min-w-0 bg-slate-50 flex flex-col min-h-screen focus:outline-none" tabindex="-1">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-10 shadow-xs">
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-slate-600 font-medium">Sistem Manajemen Kerja Sama — Pusdatin Kemendikdasmen</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-white bg-blue-700 px-3.5 py-1.5 rounded-full font-bold shadow-xs">MITRA</span>
            </div>
        </header>
        <div class="px-6 py-6">
            @yield('page-content')
        </div>
    </main>
</div>
@endsection
