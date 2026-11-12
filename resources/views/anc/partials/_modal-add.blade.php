            <!-- ================= MODAL: TAMBAH IBU HAMIL VIA AI PROMPT ================= -->
            <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showAddModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split overflow-hidden">

                        {{-- Header --}}
                        <div class="px-6 pt-6 pb-4 border-b border-ant-split flex items-start justify-between gap-3">
                            <div>
                                <h3 class="ant-section-title">Tambah Data Ibu Hamil</h3>
                                <p class="ant-text-secondary mt-0.5">Deskripsikan data ibu hamil secara bebas, AI
                                    akan mengisi form secara otomatis</p>
                            </div>
                            <button @click="showAddModal = false"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-5 space-y-4">

                            {{-- Step indicator --}}
                            <div class="flex items-center gap-2 text-[11px]">
                                <span
                                    :class="aiAddStep === 'prompt' ? 'bg-primary text-white' : 'bg-primary-50 text-primary'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">1</span>
                                <span
                                    :class="aiAddStep === 'prompt' ? 'font-semibold text-ant-title' : 'text-ant-secondary'">Tulis
                                    Deskripsi</span>
                                <span class="text-ant-disabled mx-1">—</span>
                                <span
                                    :class="aiAddStep === 'preview' ? 'bg-primary text-white' : 'bg-primary-50 text-primary'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">2</span>
                                <span
                                    :class="aiAddStep === 'preview' ? 'font-semibold text-ant-title' : 'text-ant-secondary'">Periksa
                                    & Simpan</span>
                            </div>

                            {{-- Step 1: Prompt --}}
                            <div x-show="aiAddStep === 'prompt'" class="space-y-3">
                                <div
                                    class="p-3.5 bg-primary-50 border border-primary-100 rounded-ant text-xs text-ant-text leading-relaxed">
                                    Tulis deskripsi ibu hamil seperti berbicara kepada rekan bidan. Contoh:<br>
                                    <span class="mt-1.5 block text-primary-600 italic">"Ny. Siti Rahayu, 28 tahun, NIK
                                        3603xx, G2P1A0, HPHT 10 Maret 2025, HPL 15 Desember 2025, LiLA 23 cm, Hb 10.2,
                                        warga Pagedangan Kab. Tangerang, kunjungan K2, faktor risiko KEK dan tensi
                                        140/90."</span>
                                </div>
                                <div>
                                    <textarea x-model="aiAddPrompt" rows="5"
                                        placeholder="Tulis deskripsi data ibu hamil di sini..."
                                        class="ant-input !h-auto py-2.5 resize-none leading-relaxed"></textarea>
                                    <p class="text-[11px] text-ant-secondary mt-1"
                                        x-text="aiAddPrompt.length + ' karakter'"></p>
                                </div>
                                <div x-show="aiAddError"
                                    class="ant-alert ant-alert-error"
                                    x-text="aiAddError"></div>
                            </div>

                            {{-- Step 2: Preview --}}
                            <div x-show="aiAddStep === 'preview'" class="space-y-3">
                                <div
                                    class="ant-alert ant-alert-success font-medium">
                                    AI berhasil mengekstrak data. Periksa hasil di bawah dan koreksi jika perlu sebelum
                                    menyimpan.
                                </div>
                                <div
                                    class="max-h-72 overflow-y-auto border border-ant-border rounded-ant-lg divide-y divide-ant-split">
                                    <template
                                        x-for="[key, val] in Object.entries(newPatient).filter(([k,v]) => v !== null && v !== '' && v !== undefined)"
                                        :key="key">
                                        <div class="flex items-start gap-2 px-3.5 py-2.5 hover:bg-[#fafafa]">
                                            <span class="text-[11px] font-semibold text-ant-secondary w-44 shrink-0 pt-0.5"
                                                x-text="key.replace(/_/g,' ')"></span>
                                            <input type="text" :value="val"
                                                @change="newPatient[key] = $event.target.value"
                                                class="flex-1 text-xs text-ant-title bg-transparent border-0 border-b border-ant-border focus:border-primary focus:ring-0 px-0 py-0.5">
                                        </div>
                                    </template>
                                </div>
                                <p class="text-[11px] text-ant-secondary">Klik nilai untuk mengedit langsung sebelum
                                    disimpan.</p>
                                <div x-show="aiAddError"
                                    class="ant-alert ant-alert-error"
                                    x-text="aiAddError"></div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 pb-6 pt-3 border-t border-ant-split flex items-center justify-between gap-2">
                            <button x-show="aiAddStep === 'preview'" @click="aiAddStep = 'prompt'; aiAddError = ''"
                                type="button"
                                class="ant-btn ant-btn-text ant-btn-sm">
                                Kembali ke Deskripsi
                            </button>
                            <div x-show="aiAddStep === 'prompt'"></div>

                            <div class="flex items-center gap-2 ml-auto">
                                <button @click="showAddModal = false" type="button"
                                    class="ant-btn ant-btn-default">
                                    Batal
                                </button>
                                <button x-show="aiAddStep === 'prompt'" @click="runAncAiParse()" type="button"
                                    :disabled="aiAddLoading || aiAddPrompt.trim().length < 10"
                                    class="ant-btn ant-btn-primary flex items-center gap-2">
                                    <template x-if="!aiAddLoading">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Proses dengan AI
                                        </span>
                                    </template>
                                    <template x-if="aiAddLoading">
                                        <span class="flex items-center gap-2">
                                            <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v8H4z" />
                                            </svg>
                                            Memproses...
                                        </span>
                                    </template>
                                </button>
                                <button x-show="aiAddStep === 'preview'" @click="saveNewPatient()" type="button"
                                    :disabled="isSubmittingNewPatient"
                                    class="ant-btn ant-btn-success flex items-center gap-2">
                                    <template x-if="!isSubmittingNewPatient">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Simpan ke Database
                                        </span>
                                    </template>
                                    <template x-if="isSubmittingNewPatient">
                                        <span class="flex items-center gap-2">
                                            <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v8H4z" />
                                            </svg>
                                            Menyimpan...
                                        </span>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
