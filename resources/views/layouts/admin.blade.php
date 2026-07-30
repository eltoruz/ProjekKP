@extends('layouts.app')

@section('title', 'Admin — Pusdatin Kemendikdasmen')

@section('content')
<!-- Skip Link untuk Pengguna Keyboard (WCAG 2.4.1) -->
<a href="#main-content" 
   class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 z-50 bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold shadow-lg focus:outline-none focus:ring-4 focus:ring-indigo-300">
   Lompati ke Konten Utama
</a>

<div class="flex min-h-screen bg-slate-50">
    <aside class="w-64 bg-slate-900 flex-shrink-0 sticky top-0 h-screen overflow-y-auto flex flex-col" aria-label="Navigasi Utama Admin">
        <div class="px-5 py-3 border-b border-slate-800">
            <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-2">Panel Admin</p>
            <div class="flex items-center gap-3">
                <img src="https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png" class="h-7" alt="Logo Tutwuri Kemendikdasmen">
                <div>
                    <h2 class="text-sm font-bold text-white leading-tight">Pusdatin</h2>
                    <p class="text-xs text-slate-300">Kemendikdasmen</p>
                </div>
            </div>
        </div>

        <nav class="px-3 py-4 space-y-1 flex-1">
            <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Menu</p>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white font-medium shadow-sm' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <div class="pt-3">
                <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Data Kerja Sama</p>
                <a href="{{ route('admin.kerjasama.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.kerjasama.*') ? 'bg-indigo-600 text-white font-medium shadow-sm' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Daftar Kerja Sama
                </a>
                <a href="{{ route('admin.kerjasama.create') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.kerjasama.create') ? 'bg-indigo-600 text-white font-medium shadow-sm' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Input Data
                </a>
            </div>
        </nav>

        <div class="border-t border-slate-800 px-4 py-3">
            <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-red-900/40 hover:text-red-200 w-full font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
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
                <span class="text-xs tracking-wider font-bold text-white bg-indigo-700 px-3.5 py-1.5 rounded-md shadow-xs">ADMIN</span>
            </div>
        </header>
        <div class="px-6 py-6">
            @yield('page-content')
        </div>
    </main>
</div>
@endsection
