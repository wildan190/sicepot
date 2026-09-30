        <!-- ================= TOAST NOTIFICATION ================= -->
        <div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
            x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-5 right-5 z-[99999] max-w-md w-full pointer-events-auto" style="display: none;">
            <div :class="{
                'bg-slate-900 border-slate-700 text-white': toast.type === 'success',
                'bg-rose-900 border-rose-700 text-white': toast.type === 'error',
                'bg-amber-900 border-amber-700 text-white': toast.type === 'warning'
            }" class="p-4 rounded-2xl shadow-2xl border flex items-start gap-3 backdrop-blur-md">
                <div class="shrink-0 mt-0.5">
                    <template x-if="toast.type === 'success'">
                        <div
                            class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </template>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold tracking-tight" x-text="toast.title"></h4>
                    <p class="text-xs text-slate-300 mt-0.5 leading-relaxed" x-text="toast.message"></p>
                </div>
                <button @click="toast.show = false"
                    class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

