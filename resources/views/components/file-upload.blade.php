<div x-data="{
    files: [],
    dragOver: false,
    handleDrop(e) {
        this.dragOver = false;
        const dropped = e.dataTransfer.files;
        for (let f of dropped) this.addFile(f);
    },
    handleInput(e) {
        for (let f of e.target.files) this.addFile(f);
        e.target.value = '';
    },
    addFile(f) {
        if (!['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'].includes(f.type)) {
            alert('Format tidak didukung. Gunakan PDF, DOCX, atau ZIP.');
            return;
        }
        if (f.size > 20 * 1024 * 1024) {
            alert('Ukuran file maksimal 20MB.');
            return;
        }
        this.files.push({ name: f.name, size: (f.size / 1024 / 1024).toFixed(2), file: f });
    },
    removeFile(index) { this.files.splice(index, 1); }
}">
    <div @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false" @drop.prevent="handleDrop($event)"
        :class="dragOver ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
        class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors">
        <input type="file" id="{{ $inputId ?? 'file-upload' }}" name="{{ $name ?? 'dokumen' }}"
               accept=".pdf,.docx,.zip" class="hidden" @change="handleInput($event)" {{ $multiple ?? false ? 'multiple' : '' }}>
        <label for="{{ $inputId ?? 'file-upload' }}" class="cursor-pointer">
            <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <p class="text-sm text-gray-600">{{ $label ?? 'Drag & drop file di sini, atau klik untuk memilih' }}</p>
            <p class="text-xs text-gray-400 mt-1">PDF, DOCX, ZIP — Maks 20MB</p>
        </label>
    </div>

    <template x-if="files.length > 0">
        <div class="mt-3 space-y-2">
            <template x-for="(f, i) in files" :key="i">
                <div class="flex items-center justify-between bg-gray-50 rounded px-3 py-2 text-sm">
                    <span class="text-gray-700" x-text="f.name"></span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400" x-text="f.size + ' MB'"></span>
                        <button type="button" @click="removeFile(i)" class="text-red-500 hover:text-red-700">&times;</button>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>
