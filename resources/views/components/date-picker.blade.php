@props([
    /** Nama field yang dikirim ke server. Kosongkan bila nilai dipakai lewat Alpine parent. */
    'name' => null,
    /** Nilai awal, format Y-m-d. */
    'value' => '',
    /** Properti Alpine di parent untuk two-way binding (mis. 'tgl'). */
    'model' => null,
    /** Batas tanggal minimum/maksimum, format Y-m-d. */
    'min' => '',
    'max' => '',
    /** Tandai field wajib diisi. */
    'required' => false,
])

{{--
    Date Picker dengan grid tanggal besar agar mudah dan cepat diklik.
    Dipakai untuk seluruh input tanggal pada alur kerja sama.
--}}
<div x-data="ksDatePicker(@js($value), @js($min), @js($max))"
     x-modelable="value" @if($model) x-model="{{ $model }}" @endif
     class="relative" @keydown.escape="open = false">

    @if($name)
    <input type="hidden" name="{{ $name }}" :value="value" @if($required) required @endif>
    @endif

    <button type="button" @click="toggle()" :aria-expanded="open" aria-haspopup="dialog"
            class="w-full flex items-center gap-2.5 border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white text-left hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span class="flex-1 truncate" :class="value ? 'text-gray-900 font-medium' : 'text-gray-400'"
              x-text="display || 'Pilih tanggal'"></span>
        <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div x-show="open" x-cloak x-transition.origin.top.left @click.outside="open = false"
         role="dialog" aria-label="Pilih tanggal"
         class="absolute left-0 z-50 mt-2 w-[21rem] bg-white rounded-xl shadow-xl border border-gray-200 p-4">

        <!-- Navigasi Bulan -->
        <div class="flex items-center justify-between mb-3">
            <button type="button" @click="shiftMonth(-1)" aria-label="Bulan sebelumnya"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <p class="text-sm font-bold text-gray-900" x-text="monthLabel" aria-live="polite"></p>
            <button type="button" @click="shiftMonth(1)" aria-label="Bulan berikutnya"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <!-- Nama Hari -->
        <div class="grid grid-cols-7 gap-1 mb-1">
            <template x-for="d in dayNames" :key="d">
                <div class="h-8 flex items-center justify-center text-[11px] font-bold text-gray-400 uppercase" x-text="d"></div>
            </template>
        </div>

        <!-- Grid Tanggal -->
        <div class="grid grid-cols-7 gap-1">
            <template x-for="(cell, i) in cells" :key="i">
                <div>
                    <template x-if="cell">
                        <button type="button" @click="pick(cell.iso)" :disabled="cell.disabled"
                                :aria-pressed="cell.isSelected"
                                class="w-full h-10 rounded-lg text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/40"
                                :class="cell.disabled
                                    ? 'text-gray-300 cursor-not-allowed'
                                    : (cell.isSelected
                                        ? 'bg-indigo-600 text-white shadow-2xs hover:bg-indigo-700'
                                        : (cell.isToday
                                            ? 'text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100'
                                            : 'text-gray-700 hover:bg-gray-100'))"
                                x-text="cell.day"></button>
                    </template>
                    <template x-if="!cell"><div class="h-10"></div></template>
                </div>
            </template>
        </div>

        <!-- Aksi -->
        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
            <button type="button" @click="pickToday()"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors">Hari Ini</button>
            <button type="button" @click="open = false"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-100 transition-colors">Tutup</button>
        </div>
    </div>
</div>
