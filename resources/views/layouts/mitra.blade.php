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
                <!-- Bell Notification Menu (Alpine.js Dynamic) -->
                <div class="relative" x-data="{ 
                    open: false, 
                    unreadCount: 0, 
                    notifications: [],
                    fetchNotifications() {
                        fetch('{{ route('notifications.latest') }}?role=mitra')
                            .then(res => res.json())
                            .then(data => {
                                this.unreadCount = data.unread_count;
                                this.notifications = data.notifications;
                            })
                            .catch(() => {});
                    },
                    markAllRead() {
                        fetch('{{ route('notifications.mark-read') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ role: 'mitra' })
                        }).then(() => {
                            this.unreadCount = 0;
                            this.notifications.forEach(n => n.is_read = true);
                        });
                    }
                }" x-init="fetchNotifications()">
                    <button type="button" 
                            @click.stop="open = !open; fetchNotifications()" 
                            class="relative p-2.5 text-gray-500 hover:text-primary rounded-xl hover:bg-slate-100/80 transition-all duration-150 focus:outline-none"
                            aria-label="Notifikasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <template x-if="unreadCount > 0">
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-white animate-pulse"></span>
                        </template>
                    </button>

                    <div x-show="open" 
                         x-cloak 
                         @click.away="open = false" 
                         @keydown.escape.window="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="absolute right-0 mt-2.5 w-80 sm:w-88 bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden z-50 text-xs"
                         style="display: none;">
                        
                        <div class="px-4 py-3 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-xs">Pemberitahuan</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100" x-text="unreadCount + ' Baru'"></span>
                            </div>
                            <button type="button" @click="markAllRead()" class="text-[10px] text-primary font-semibold hover:underline">
                                Tandai Dibaca
                            </button>
                        </div>

                        <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                            <template x-for="item in notifications" :key="item.id">
                                <a :href="item.url || '{{ route('mitra.kerjasama.index') }}'" class="px-4 py-3 hover:bg-slate-50/80 flex items-start gap-3 transition-colors group">
                                    <div class="w-2 h-2 rounded-full mt-1.5 shrink-0" :class="item.is_read ? 'bg-slate-300' : 'bg-emerald-500'"></div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-800 group-hover:text-primary transition-colors text-xs" x-text="item.title"></p>
                                        <p class="text-[11px] text-slate-500 leading-snug mt-0.5" x-text="item.message"></p>
                                        <span class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span x-text="item.waktu"></span>
                                        </span>
                                    </div>
                                </a>
                            </template>
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-6 text-center text-slate-400 text-xs">Belum ada notifikasi baru.</div>
                            </template>
                        </div>

                        <div class="px-4 py-2 bg-slate-50/50 border-t border-slate-100 text-center">
                            <a href="{{ route('mitra.kerjasama.index') }}" class="text-[11px] font-semibold text-primary hover:underline transition-colors">
                                Lihat Semua Kegiatan &rarr;
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
