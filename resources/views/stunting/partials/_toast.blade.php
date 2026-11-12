        <!-- ================= TOAST NOTIFICATION ================= -->
        <div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
            x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-5 right-5 z-[99999] max-w-md w-full pointer-events-auto" style="display: none;">
            <div :class="{
                'bg-success-50 border-success-100 text-ant-title': toast.type === 'success',
                'bg-danger-50 border-danger-100 text-ant-title': toast.type === 'error',
                'bg-warning-50 border-warning-100 text-ant-title': toast.type === 'warning'
            }" class="p-4 rounded-ant-lg shadow-ant-dropdown border flex items-start gap-3">
                <div class="shrink-0 mt-0.5">
                    <template x-if="toast.type === 'success'">
                        <div
                            class="w-8 h-8 rounded-ant bg-success-100 text-success-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <div class="w-8 h-8 rounded-ant bg-danger-100 text-danger-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </template>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold tracking-tight" x-text="toast.title"></h4>
                    <p class="text-xs text-ant-secondary mt-0.5 leading-relaxed" x-text="toast.message"></p>
                </div>
                <button @click="toast.show = false"
                    class="text-ant-secondary hover:text-ant-title p-1 rounded-ant hover:bg-ant-split transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
