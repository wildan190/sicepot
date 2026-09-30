        <!-- ================= MODAL: TAMBAH BALITA (MANUAL / AI) ================= -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                <div @click="showAddModal = false"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <div
                    class="inline-block w-full max-w-2xl text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100 overflow-hidden">
                    {{-- Header --}}
                    <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Tambah Data Balita Stunting</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Isi formulir manual atau gunakan bantuan AI e-PPGBM
                            </p>
                        </div>
                        <button @click="showAddModal = false"
                            class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Tab switcher --}}
                    <div class="flex border-b border-slate-100 bg-slate-50">
                        <button type="button" @click="addModalTab = 'manual'" :class="addModalTab === 'manual'
                                ? 'border-b-2 border-emerald-500 text-emerald-700 font-semibold bg-white'
                                : 'text-slate-500 hover:text-slate-700'"
                            class="flex-1 py-3 text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Formulir Manual
                        </button>
                        <button type="button" @click="addModalTab = 'ai'" :class="addModalTab === 'ai'
                                ? 'border-b-2 border-emerald-500 text-emerald-700 font-semibold bg-white'
                                : 'text-slate-500 hover:text-slate-700'"
                            class="flex-1 py-3 text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Bantu AI (Gemini)
                        </button>
                    </div>

                    <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                        {{-- ======= TAB: MANUAL FORM ======= --}}
                        <div x-show="addModalTab === 'manual'" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Nama Lengkap
                                        Balita <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="newPatient.nama" placeholder="Nama balita"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">NIK
                                        Balita</label>
                                    <input type="text" x-model="newPatient.nik" placeholder="16 digit NIK"
                                        maxlength="20"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Nama Orang
                                        Tua</label>
                                    <input type="text" x-model="newPatient.nama_ortu" placeholder="Nama ayah / ibu"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Jenis
                                        Kelamin</label>
                                    <select x-model="newPatient.jenis_kelamin"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                        <option value="">— Pilih —</option>
                                        <option value="L">Laki-laki (L)</option>
                                        <option value="P">Perempuan (P)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Tanggal
                                        Lahir</label>
                                    <input type="date" x-model="newPatient.tanggal_lahir"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Desa /
                                        Kelurahan</label>
                                    <input type="text" x-model="newPatient.desa" placeholder="Nama desa/kelurahan"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Posyandu</label>
                                    <input type="text" x-model="newPatient.posyandu" placeholder="Nama posyandu"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                            </div>

                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest pt-2">Pengukuran
                                Terkini</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Berat Badan
                                        (kg)</label>
                                    <input type="number" step="0.01" x-model="newPatient.berat"
                                        placeholder="Contoh: 9.5"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Tinggi Badan
                                        (cm)</label>
                                    <input type="number" step="0.1" x-model="newPatient.tinggi"
                                        placeholder="Contoh: 75.2"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">LiLA (cm)</label>
                                    <input type="number" step="0.1" x-model="newPatient.lila" placeholder="Contoh: 12.5"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Kategori
                                        TB/U</label>
                                    <select x-model="newPatient.tbu_kategori"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                        <option value="">— Pilih —</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Pendek">Pendek</option>
                                        <option value="Sangat Pendek">Sangat Pendek</option>
                                        <option value="Tinggi">Tinggi</option>
                                    </select>
                                </div>
                            </div>

                            <div x-show="aiAddError"
                                class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700"
                                x-text="aiAddError"></div>
                        </div>

                        {{-- ======= TAB: AI PROMPT ======= --}}
                        <div x-show="addModalTab === 'ai'" class="space-y-3">
                            <div
                                class="p-3.5 bg-emerald-50 border border-emerald-100 rounded-xl text-xs text-emerald-800 leading-relaxed">
                                Tulis deskripsi data balita secara bebas. Contoh:<br>
                                <span class="mt-1.5 block text-emerald-600 italic">"Balita bernama Muhammad Rizki,
                                    laki-laki lahir 12 Maret 2024, anak dari Bpk Ridwan. Warga Desa Cijantra Posyandu
                                    Melati. Berat badan saat ini 7.2 kg, tinggi 68 cm, LiLA 11.5 cm, status TB/U sangat
                                    pendek, BB/U kurang."</span>
                            </div>

                            {{-- Step indicator --}}
                            <div class="flex items-center gap-2 text-[11px]">
                                <span
                                    :class="aiAddStep === 'prompt' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-600'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">1</span>
                                <span
                                    :class="aiAddStep === 'prompt' ? 'font-semibold text-slate-700' : 'text-slate-400'">Tulis
                                    Deskripsi</span>
                                <span class="text-slate-300 mx-1">—</span>
                                <span
                                    :class="aiAddStep === 'preview' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-600'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">2</span>
                                <span
                                    :class="aiAddStep === 'preview' ? 'font-semibold text-slate-700' : 'text-slate-400'">Periksa
                                    &amp; Simpan</span>
                            </div>

                            {{-- Step 1: Prompt input --}}
                            <div x-show="aiAddStep === 'prompt'" class="space-y-3">
                                <div>
                                    <textarea x-model="aiAddPrompt" rows="5"
                                        placeholder="Tulis deskripsi data balita stunting di sini..."
                                        class="w-full px-3.5 py-3 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 resize-none leading-relaxed"></textarea>
                                    <p class="text-[11px] text-slate-400 mt-1"
                                        x-text="aiAddPrompt.length + ' karakter'"></p>
                                </div>
                                <div x-show="aiAddError"
                                    class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700"
                                    x-text="aiAddError"></div>
                            </div>

                            {{-- Step 2: Preview parsed fields --}}
                            <div x-show="aiAddStep === 'preview'" class="space-y-3">
                                <div
                                    class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-700 font-medium">
                                    AI berhasil mengekstrak data balita. Periksa hasil di bawah sebelum menyimpan.
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
                                                class="flex-1 text-xs text-slate-800 bg-transparent border-0 border-b border-slate-200 focus:border-emerald-500 focus:ring-0 px-0 py-0.5">
                                        </div>
                                    </template>
                                </div>
                                <div x-show="aiAddError"
                                    class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700"
                                    x-text="aiAddError"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer actions --}}
                    <div class="px-6 pb-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div>
                            <button x-show="addModalTab === 'ai' && aiAddStep === 'preview'"
                                @click="aiAddStep = 'prompt'; aiAddError = ''" type="button"
                                class="text-xs font-semibold text-slate-500 hover:text-slate-700 cursor-pointer">
                                Kembali ke Deskripsi
                            </button>
                        </div>
                        <div class="flex items-center gap-2 ml-auto">
                            <button @click="showAddModal = false" type="button"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button x-show="addModalTab === 'manual'" @click="saveNewPatient()" type="button"
                                :disabled="isSubmittingNewPatient || !newPatient.nama"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
                                <span x-show="!isSubmittingNewPatient">Simpan Data</span>
                                <span x-show="isSubmittingNewPatient" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                            <button x-show="addModalTab === 'ai' && aiAddStep === 'prompt'"
                                @click="runStuntingAiParse()" type="button"
                                :disabled="aiAddLoading || aiAddPrompt.trim().length < 10"
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
                                <span x-show="!aiAddLoading">Proses dengan AI</span>
                                <span x-show="aiAddLoading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                            <button x-show="addModalTab === 'ai' && aiAddStep === 'preview'" @click="saveNewPatient()"
                                type="button" :disabled="isSubmittingNewPatient"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
                                <span x-show="!isSubmittingNewPatient">Simpan ke Database</span>
                                <span x-show="isSubmittingNewPatient" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

