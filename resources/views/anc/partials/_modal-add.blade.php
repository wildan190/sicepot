            <!-- ================= MODAL: TAMBAH IBU HAMIL VIA AI PROMPT ================= -->
            <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showAddModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100 overflow-hidden">

                        {{-- Header --}}
                        <div class="px-6 pt-6 pb-4 border-b border-slate-100 flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Tambah Data Ibu Hamil</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Deskripsikan data ibu hamil secara bebas, AI
                                    akan mengisi form secara otomatis</p>
                            </div>
                            <button @click="showAddModal = false"
                                class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer shrink-0">
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
                                    :class="aiAddStep === 'prompt' ? 'bg-pink-600 text-white' : 'bg-pink-100 text-pink-600'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">1</span>
                                <span
                                    :class="aiAddStep === 'prompt' ? 'font-semibold text-slate-700' : 'text-slate-400'">Tulis
                                    Deskripsi</span>
                                <span class="text-slate-300 mx-1">—</span>
                                <span
                                    :class="aiAddStep === 'preview' ? 'bg-pink-600 text-white' : 'bg-pink-100 text-pink-600'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">2</span>
                                <span
                                    :class="aiAddStep === 'preview' ? 'font-semibold text-slate-700' : 'text-slate-400'">Periksa
                                    & Simpan</span>
                            </div>

                            {{-- Step 1: Prompt --}}
                            <div x-show="aiAddStep === 'prompt'" class="space-y-3">
                                <div
                                    class="p-3.5 bg-pink-50 border border-pink-100 rounded-xl text-xs text-pink-700 leading-relaxed">
                                    Tulis deskripsi ibu hamil seperti berbicara kepada rekan bidan. Contoh:<br>
                                    <span class="mt-1.5 block text-pink-500 italic">"Ny. Siti Rahayu, 28 tahun, NIK
                                        3603xx, G2P1A0, HPHT 10 Maret 2025, HPL 15 Desember 2025, LiLA 23 cm, Hb 10.2,
                                        warga Pagedangan Kab. Tangerang, kunjungan K2, faktor risiko KEK dan tensi
                                        140/90."</span>
                                </div>
                                <div>
                                    <textarea x-model="aiAddPrompt" rows="5"
                                        placeholder="Tulis deskripsi data ibu hamil di sini..."
                                        class="w-full px-3.5 py-3 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 resize-none leading-relaxed"></textarea>
                                    <p class="text-[11px] text-slate-400 mt-1"
                                        x-text="aiAddPrompt.length + ' karakter'"></p>
                                </div>
                                <div x-show="aiAddError"
                                    class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700"
                                    x-text="aiAddError"></div>
                            </div>

                            {{-- Step 2: Preview --}}
                            <div x-show="aiAddStep === 'preview'" class="space-y-3">
                                <div
                                    class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-700 font-medium">
                                    AI berhasil mengekstrak data. Periksa hasil di bawah dan koreksi jika perlu sebelum
                                    menyimpan.
                                </div>
                                <div
                                    class="max-h-72 overflow-y-auto border border-slate-200 rounded-xl divide-y divide-slate-100">
                                    <template
                                        x-for="[key, val] in Object.entries(newPatient).filter(([k,v]) => v !== null && v !== '' && v !== undefined)"
                                        :key="key">
                                        <div class="flex items-start gap-2 px-3.5 py-2.5 hover:bg-slate-50">
                                            <span class="text-[11px] font-semibold text-slate-500 w-44 shrink-0 pt-0.5"
                                                x-text="key.replace(/_/g,' ')"></span>
                                            <input type="text" :value="val"
                                                @change="newPatient[key] = $event.target.value"
                                                class="flex-1 text-xs text-slate-800 bg-transparent border-0 border-b border-slate-200 focus:border-pink-400 focus:ring-0 px-0 py-0.5">
                                        </div>
                                    </template>
                                </div>
                                <p class="text-[11px] text-slate-400">Klik nilai untuk mengedit langsung sebelum
                                    disimpan.</p>
                                <div x-show="aiAddError"
                                    class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700"
                                    x-text="aiAddError"></div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 pb-6 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <button x-show="aiAddStep === 'preview'" @click="aiAddStep = 'prompt'; aiAddError = ''"
                                type="button"
                                class="text-xs font-semibold text-slate-500 hover:text-slate-700 cursor-pointer">
                                Kembali ke Deskripsi
                            </button>
                            <div x-show="aiAddStep === 'prompt'"></div>

                            <div class="flex items-center gap-2 ml-auto">
                                <button @click="showAddModal = false" type="button"
                                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                                    Batal
                                </button>
                                <button x-show="aiAddStep === 'prompt'" @click="runAncAiParse()" type="button"
                                    :disabled="aiAddLoading || aiAddPrompt.trim().length < 10"
                                    class="px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
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
                                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
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

