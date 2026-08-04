@props(['kerjasamaId', 'currentRole' => 'mitra', 'senderName' => 'Pengguna'])

<div x-data="{
        isOpen: false,
        loading: false,
        sending: false,
        messages: [],
        newMessage: '',
        fileInput: null,
        fileName: '',
        unreadCount: 0,
        
        fetchChats() {
            fetch('{{ route('kerjasama.chat.get', $kerjasamaId) }}')
                .then(res => res.json())
                .then(data => {
                    const prevCount = this.messages.length;
                    this.messages = data.chats || [];
                    if (this.messages.length > prevCount && !this.isOpen) {
                        this.unreadCount = this.messages.length - prevCount;
                    }
                    this.scrollToBottom();
                })
                .catch(() => {});
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.unreadCount = 0;
                this.scrollToBottom();
                this.$nextTick(() => {
                    if (this.$refs.chatInput) this.$refs.chatInput.focus();
                });
            }
        },

        handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) {
                this.fileInput = file;
                this.fileName = file.name;
            } else {
                this.fileInput = null;
                this.fileName = '';
            }
        },

        clearFile() {
            this.fileInput = null;
            this.fileName = '';
            if (this.$refs.fileRef) this.$refs.fileRef.value = '';
        },

        send() {
            if (!this.newMessage.trim() && !this.fileInput) return;

            this.sending = true;
            const formData = new FormData();
            formData.append('sender_role', '{{ $currentRole }}');
            formData.append('sender_name', '{{ $senderName }}');
            formData.append('pesan', this.newMessage);
            if (this.fileInput) {
                formData.append('attachment', this.fileInput);
            }

            fetch('{{ route('kerjasama.chat.send', $kerjasamaId) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.sending = false;
                if (data.status === 'success') {
                    this.newMessage = '';
                    this.clearFile();
                    this.fetchChats();
                }
            })
            .catch(() => {
                this.sending = false;
            });
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.chatContainer;
                if (container) container.scrollTop = container.scrollHeight;
            });
        }
     }"
     x-init="fetchChats(); setInterval(() => fetchChats(), 6000)">

    <!-- Floating Chat Trigger Button (Bottom-Right Corner) -->
    <div class="fixed bottom-6 right-6 z-50">
        <button type="button" 
                @click="toggleChat()"
                class="relative w-14 h-14 bg-gradient-to-tr from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-full shadow-2xl flex items-center justify-center cursor-pointer transition-all duration-300 transform hover:scale-105 active:scale-95 border-2 border-white/20 focus:outline-none focus:ring-4 focus:ring-indigo-300"
                aria-label="Buka Diskusi Chat">
            
            <!-- Chat Icon -->
            <svg x-show="!isOpen" class="w-6 h-6 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>

            <!-- Close Icon -->
            <svg x-show="isOpen" x-cloak class="w-6 h-6 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
            </svg>

            <!-- Unread Pulse Badge -->
            <template x-if="unreadCount > 0 && !isOpen">
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 text-white rounded-full ring-4 ring-white text-[10px] font-bold flex items-center justify-center animate-bounce" x-text="unreadCount"></span>
            </template>
        </button>
    </div>

    <!-- Floating Chat Window Card -->
    <div x-show="isOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-250 transform"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         @click.away="isOpen = false"
         @keydown.escape.window="isOpen = false"
         class="fixed bottom-24 right-6 z-50 w-96 sm:w-100 max-w-[calc(100vw-3rem)] bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden flex flex-col"
         style="display: none;">

        <!-- Window Header -->
        <div class="bg-slate-900 text-white px-5 py-4 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 border-2 border-slate-900 rounded-full"></span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white tracking-wide">Diskusi & Klarifikasi Draf</h4>
                    <p class="text-[11px] text-slate-300 flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>{{ $currentRole === 'admin' ? 'Mitra Instansi' : 'Admin Pusdatin' }}</span>
                    </p>
                </div>
            </div>

            <button type="button" 
                    @click="isOpen = false" 
                    class="p-1 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors focus:outline-none"
                    aria-label="Tutup Diskusi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Chat Stream Body -->
        <div x-ref="chatContainer" 
             aria-live="polite" 
             aria-relevant="additions" 
             aria-label="Riwayat Obrolan" 
             class="h-88 max-h-[50vh] overflow-y-auto p-4 space-y-3 bg-slate-50/70 text-xs">
            <template x-for="chat in messages" :key="chat.id">
                <div class="flex flex-col" :class="chat.sender_role === '{{ $currentRole }}' ? 'items-end' : 'items-start'">
                    <div class="flex items-center gap-1.5 mb-1 text-[10px] text-slate-400 px-1">
                        <span class="font-semibold" :class="chat.sender_role === 'admin' ? 'text-indigo-600' : 'text-blue-700'" x-text="chat.sender_name"></span>
                        <span>•</span>
                        <span x-text="chat.created_at"></span>
                    </div>

                    <!-- Message Bubble -->
                    <div class="max-w-[85%] rounded-2xl px-3.5 py-2.5 shadow-2xs leading-relaxed"
                         :class="chat.sender_role === '{{ $currentRole }}' 
                            ? 'bg-indigo-600 text-white rounded-tr-xs' 
                            : 'bg-white text-slate-800 border border-slate-200/90 rounded-tl-xs'">
                        <p class="whitespace-pre-line text-xs" x-text="chat.pesan"></p>

                        <!-- Attachment File Chip -->
                        <template x-if="chat.attachment_url">
                            <div class="mt-2 pt-2 border-t" :class="chat.sender_role === '{{ $currentRole }}' ? 'border-white/20' : 'border-slate-100'">
                                <a :href="chat.attachment_url" target="_blank" 
                                   class="inline-flex items-center gap-1.5 text-[11px] font-medium underline hover:opacity-90">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span x-text="chat.attachment_name"></span>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="messages.length === 0">
                <div class="py-12 text-center text-slate-400">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center mx-auto mb-2 text-indigo-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <p class="font-medium text-xs text-slate-600">Belum ada obrolan</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Kirim pesan di bawah untuk memulai diskusi.</p>
                </div>
            </template>
        </div>

        <!-- Selected File Attachment Chip Banner -->
        <template x-if="fileName">
            <div class="px-4 py-2 bg-indigo-50 border-t border-indigo-100 flex items-center justify-between text-xs text-indigo-900">
                <div class="flex items-center gap-2 truncate">
                    <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <span class="font-medium truncate text-[11px]" x-text="fileName"></span>
                </div>
                <button type="button" @click="clearFile()" class="text-slate-400 hover:text-red-600 font-bold ml-2 text-xs">✕</button>
            </div>
        </template>

        <!-- Chat Input Footer Form -->
        <form @submit.prevent="send()" class="p-3 bg-white border-t border-slate-100 flex items-center gap-2">
            <input type="file" x-ref="fileRef" @change="handleFileSelect($event)" class="hidden" accept=".pdf">

            <button type="button" 
                    @click="$refs.fileRef.click()" 
                    class="p-2 text-slate-500 hover:text-indigo-600 bg-slate-100 hover:bg-indigo-50 rounded-xl transition-colors shrink-0"
                    title="Lampirkan Dokumen">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            </button>

            <input type="text" 
                   x-ref="chatInput" 
                   x-model="newMessage" 
                   placeholder="Tulis pesan..." 
                   aria-label="Tulis Pesan Diskusi"
                   class="flex-1 px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 focus:bg-white transition-colors">

            <button type="submit" 
                    :disabled="sending || (!newMessage.trim() && !fileInput)"
                    class="p-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-40 transition-all shadow-2xs shrink-0 cursor-pointer"
                    title="Kirim Pesan">
                <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </form>
    </div>
</div>
