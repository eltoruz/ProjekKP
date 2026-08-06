@props([
    'name' => null,
    'value' => '',
    'model' => null,
    'min' => '',
    'max' => '',
    'required' => false,
])

<div x-data="{
        val: @js($value) || '',
        openPicker() {
            if (this.$refs.dateInput && typeof this.$refs.dateInput.showPicker === 'function') {
                try { this.$refs.dateInput.showPicker(); } catch (e) {}
            }
        }
     }"
     @if($model) x-init="if (!val && typeof {{ $model }} !== 'undefined' && {{ $model }}) { val = {{ $model }}; }" @endif
     x-modelable="val" @if($model) x-model="{{ $model }}" @endif
     class="relative w-full">

    @if($name)
    <input type="hidden" name="{{ $name }}" :value="val">
    @endif

    <div class="relative flex items-center w-full">
        <input type="date"
               x-ref="dateInput"
               x-model="val"
               @if($min) min="{{ $min }}" @endif
               @if($max) max="{{ $max }}" @endif
               @if($required) required @endif
               @click="openPicker()"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white text-gray-900 shadow-2xs hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors font-medium cursor-pointer">
    </div>
</div>
