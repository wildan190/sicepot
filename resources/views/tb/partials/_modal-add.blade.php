            <!-- ================= MODAL: TAMBAH PASIEN (MANUAL / AI) ================= -->
            <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                    <div @click="showAddModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100 overflow-hidden">

                        {{-- Header --}}
                        <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Tambah Data Pasien TBC</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Isi formulir manual atau gunakan bantuan AI</p>
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
                                    ? 'border-b-2 border-indigo-500 text-indigo-700 font-semibold bg-white'
                                    : 'text-slate-500 hover:text-slate-700'"
                                class="flex-1 py-3 text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Formulir Manual
                            </button>
                            <button type="button" @click="addModalTab = 'ai'" :class="addModalTab === 'ai'
                                    ? 'border-b-2 border-indigo-500 text-indigo-700 font-semibold bg-white'
                                    : 'text-slate-500 hover:text-slate-700'"
                                class="flex-1 py-3 text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Bantu AI
                            </button>
                        </div>

                        <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">

                            {{-- ======= TAB: MANUAL FORM ======= --}}
                            <div x-show="addModalTab === 'manual'" class="space-y-4">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                    {{-- Nama Pasien --}}
                                    <div class="sm:col-span-2">
                                        <label class="block text-[11px] font-semibold text-slate-500 mb-1">Nama Lengkap
                                            Pasien <span class="text-rose-500">*</span></label>
                                        <input type="text" x-model="newPatient.nama_lengkap"
                                            placeholder="Nama sesuai KTP"
                                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 focus:bg-white transition-all">
                                    </div>

                                    {{-- NIK --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-500 mb-1">NIK</label>
                                        <input type="text" x-model="newPatient.nik" placeholder="16 digit NIK"
                                            maxlength="16"
                                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 focus:bg-white transition-all">
                                    </div>

                                    {{-- Umur --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-500 mb-1">Umur
                                            (Tahun)</label>
                                        <input type="number" x-model="newPatient.umur" placeholder="Contoh: 32" min="0"
                                            max="120"
                                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 focus:bg-white transition-all">
                                    </div>

                                    {{-- Kategori Usia --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-500 mb-1">Kategori
                                            Usia</label>
                                        <select x-model="newPatient.kategori_usia"
                                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 focus:bg-white transition-all">
                                            <option value="">— Pilih —</option>
                                            <option value="Bayi & Balita">Bayi &amp; Balita (0 – 5 thn)</option>
                                            <option value="Anak/Remaja">Anak / Remaja (6 – 17 thn)</option>
                                            <option value="Dewasa">Dewasa (18 – 60 thn)</option>
                                            <option value="Lansia">Lansia (&gt;60 thn)</option>
                                        </select>
                                    </div>

                                    {{-- Jenis Kelamin --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-500 mb-1">Jenis
                                            Kelamin</label>
                                        <select x-model="newPatient.jenis_kelamin"
                                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 focus:bg-white transition-all">
                                            <option value="">— Pilih —</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>

                                    {{-- Alamat Desa --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-500 mb-1">Alamat Desa /
                                            Kelurahan</label>
                                        <select x-model="newPatient.kelurahan"
                                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 focus:bg-white transition-all">
                                            <option value="">— Pilih Desa —</option>
                                            <option value="Pagedangan">Pagedangan</option>
                                            <option value="Cicalengka">Cicalengka</option>
                                            <option value="Cihuni">Cihuni</option>
                                            <option value="Cijantra">Cijantra</option>
                                            <option value="Medang">Medang</option>
                                            <option value="Lengkong Kulon">Lengkong Kulon</option>
                                            <option value="Situgadung">Situgadung</option>
                                            <option value="Kadusirung">Kadusirung</option>
                                            <option value="Jatake">Jatake</option>
                                            <option value="Malangnengah">Malangnengah</option>
                                            <option value="Karang Tengah">Karang Tengah</option>
                                        </select>
                                    </div>

                                    {{-- No HP --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-500 mb-1">No. HP /
                                            WhatsApp</label>
                                        <input type="tel" x-model="newPatient.no_telepon" placeholder="08xxxxxxxxxx"
                                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 focus:bg-white transition-all">
                                    </div>

                                    {{-- Nama Pelapor --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-500 mb-1">Nama
                                            Pelapor</label>
                                        <input type="text" x-model="newPatient.nama_pelapor"
                                            placeholder="Nama kader / petugas"
                                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 focus:bg-white transition-all">
                                    </div>

                                </div>

                                {{-- Skrining Section --}}
                                <div class="mt-1">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2.5">
                                        Skrining Gejala</p>
                                    <div class="space-y-2">

                                        {{-- Helper macro: yes/no row --}}
                                        <template x-for="item in [
                                            { label: 'Gejala Batuk > 2 Minggu', key: 'batuk_2_minggu', opts: ['Ya','Tidak'] },
                                            { label: 'Berat Badan Turun',        key: 'bb_turun',       opts: ['Ya','Tidak'] },
                                            { label: 'Keringat Malam',           key: 'keringat_malam', opts: ['Ya','Tidak'] },
                                            { label: 'Kontak Erat dengan Penderita TB', key: 'kontak_tb', opts: ['Ya','Tidak'] }
                                        ]" :key="item.key">
                                            <div
                                                class="flex items-center justify-between gap-3 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5">
                                                <span class="text-xs font-medium text-slate-700 flex-1"
                                                    x-text="item.label"></span>
                                                <div class="flex gap-1.5 shrink-0">
                                                    <template x-for="opt in item.opts" :key="opt">
                                                        <button type="button" @click="newPatient[item.key] = opt"
                                                            :class="newPatient[item.key] === opt
                                                                ? (opt === 'Ya' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-rose-500 text-white border-rose-500')
                                                                : 'bg-white text-slate-500 border-slate-300 hover:border-slate-400'"
                                                            class="px-3 py-1 text-[11px] font-bold border rounded-lg transition-all cursor-pointer"
                                                            x-text="opt">
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Sudah Pengobatan (Sudah / Belum) --}}
                                        <div
                                            class="flex items-center justify-between gap-3 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5">
                                            <span class="text-xs font-medium text-slate-700 flex-1">Apakah sudah
                                                melakukan Pengobatan?</span>
                                            <div class="flex gap-1.5 shrink-0">
                                                <button type="button" @click="newPatient.sudah_pengobatan = 'Sudah'"
                                                    :class="newPatient.sudah_pengobatan === 'Sudah' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-500 border-slate-300 hover:border-slate-400'"
                                                    class="px-3 py-1 text-[11px] font-bold border rounded-lg transition-all cursor-pointer">Sudah</button>
                                                <button type="button" @click="newPatient.sudah_pengobatan = 'Belum'"
                                                    :class="newPatient.sudah_pengobatan === 'Belum' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-slate-500 border-slate-300 hover:border-slate-400'"
                                                    class="px-3 py-1 text-[11px] font-bold border rounded-lg transition-all cursor-pointer">Belum</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div x-show="aiAddError"
                                    class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700"
                                    x-text="aiAddError"></div>
                            </div>

                            {{-- ======= TAB: AI PROMPT ======= --}}
                            <div x-show="addModalTab === 'ai'" class="space-y-3">
                                <div
                                    class="p-3.5 bg-indigo-50 border border-indigo-100 rounded-xl text-xs text-indigo-700 leading-relaxed">
                                    Tulis deskripsi pasien seperti berbicara kepada rekan kerja. Contoh:<br>
                                    <span class="mt-1.5 block text-indigo-500 italic">"Pasien bernama Budi Santoso,
                                        laki-laki 35 tahun, NIK 3603xx, warga Kelurahan Pagedangan Kab. Tangerang.
                                        Terdaftar TB-03 dengan hasil TCM positif rifampisin sensitif, mulai pengobatan
                                        Januari 2025, riwayat HIV negatif."</span>
                                </div>

                                {{-- Step indicator --}}
                                <div class="flex items-center gap-2 text-[11px]">
                                    <span
                                        :class="aiAddStep === 'prompt' ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-600'"
                                        class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">1</span>
                                    <span
                                        :class="aiAddStep === 'prompt' ? 'font-semibold text-slate-700' : 'text-slate-400'">Tulis
                                        Deskripsi</span>
                                    <span class="text-slate-300 mx-1">—</span>
                                    <span
                                        :class="aiAddStep === 'preview' ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-600'"
                                        class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">2</span>
                                    <span
                                        :class="aiAddStep === 'preview' ? 'font-semibold text-slate-700' : 'text-slate-400'">Periksa
                                        &amp; Simpan</span>
                                </div>

                                {{-- Step 1: Prompt input --}}
                                <div x-show="aiAddStep === 'prompt'" class="space-y-3">
                                    <div>
                                        <textarea x-model="aiAddPrompt" rows="5"
                                            placeholder="Tulis deskripsi data pasien di sini..."
                                            class="w-full px-3.5 py-3 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none leading-relaxed"></textarea>
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
                                        AI berhasil mengekstrak data. Periksa hasil di bawah dan koreksi jika perlu
                                        sebelum
                                        menyimpan.
                                    </div>
                                    <div
                                        class="max-h-72 overflow-y-auto border border-slate-200 rounded-xl divide-y divide-slate-100">
                                        <template
                                            x-for="[key, val] in Object.entries(newPatient).filter(([k,v]) => v !== null && v !== '' && v !== undefined)"
                                            :key="key">
                                            <div class="flex items-start gap-2 px-3.5 py-2.5 hover:bg-slate-50">
                                                <span
                                                    class="text-[11px] font-semibold text-slate-500 w-44 shrink-0 pt-0.5"
                                                    x-text="key.replace(/_/g,' ')"></span>
                                                <input type="text" :value="val"
                                                    @change="newPatient[key] = $event.target.value"
                                                    class="flex-1 text-xs text-slate-800 bg-transparent border-0 border-b border-slate-200 focus:border-indigo-400 focus:ring-0 px-0 py-0.5">
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

                        </div>

                        {{-- Footer actions --}}
                        <div class="px-6 pb-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div>
                                {{-- AI: back button --}}
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

                                {{-- MANUAL: Save directly --}}
                                <button x-show="addModalTab === 'manual'" @click="saveNewPatient()" type="button"
                                    :disabled="isSubmittingNewPatient || !newPatient.nama_lengkap"
                                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
                                    <template x-if="!isSubmittingNewPatient">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Simpan Data
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

                                {{-- AI Step 1: Parse --}}
                                <button x-show="addModalTab === 'ai' && aiAddStep === 'prompt'" @click="runTbAiParse()"
                                    type="button" :disabled="aiAddLoading || aiAddPrompt.trim().length < 10"
                                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
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

                                {{-- AI Step 2: Save --}}
                                <button x-show="addModalTab === 'ai' && aiAddStep === 'preview'"
                                    @click="saveNewPatient()" type="button" :disabled="isSubmittingNewPatient"
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

