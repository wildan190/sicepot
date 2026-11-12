            <!-- ================= MODAL: TAMBAH PASIEN (MANUAL / AI) ================= -->
            <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                    <div @click="showAddModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split overflow-hidden">

                        {{-- Header --}}
                        <div class="px-6 pt-5 pb-4 border-b border-ant-split flex items-center justify-between gap-3">
                            <div>
                                <h3 class="ant-section-title">Tambah Data Pasien TBC</h3>
                                <p class="text-xs text-ant-secondary mt-0.5">Isi formulir manual atau gunakan bantuan AI</p>
                            </div>
                            <button @click="showAddModal = false"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Tab switcher --}}
                        <div class="flex border-b border-ant-split bg-[#fafafa]">
                            <button type="button" @click="addModalTab = 'manual'" :class="addModalTab === 'manual'
                                    ? 'border-b-2 border-primary text-primary font-semibold bg-white'
                                    : 'text-ant-secondary hover:text-ant-text'"
                                class="flex-1 py-3 text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Formulir Manual
                            </button>
                            <button type="button" @click="addModalTab = 'ai'" :class="addModalTab === 'ai'
                                    ? 'border-b-2 border-primary text-primary font-semibold bg-white'
                                    : 'text-ant-secondary hover:text-ant-text'"
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
                                        <label class="ant-label ant-label-required">Nama Lengkap Pasien</label>
                                        <input type="text" x-model="newPatient.nama_lengkap"
                                            placeholder="Nama sesuai KTP"
                                            class="ant-input">
                                    </div>

                                    {{-- NIK --}}
                                    <div>
                                        <label class="ant-label">NIK</label>
                                        <input type="text" x-model="newPatient.nik" placeholder="16 digit NIK"
                                            maxlength="16"
                                            class="ant-input">
                                    </div>

                                    {{-- Umur --}}
                                    <div>
                                        <label class="ant-label">Umur (Tahun)</label>
                                        <input type="number" x-model="newPatient.umur" placeholder="Contoh: 32" min="0"
                                            max="120"
                                            class="ant-input">
                                    </div>

                                    {{-- Kategori Usia --}}
                                    <div>
                                        <label class="ant-label">Kategori Usia</label>
                                        <select x-model="newPatient.kategori_usia"
                                            class="ant-select">
                                            <option value="">— Pilih —</option>
                                            <option value="Bayi & Balita">Bayi &amp; Balita (0 – 5 thn)</option>
                                            <option value="Anak/Remaja">Anak / Remaja (6 – 17 thn)</option>
                                            <option value="Dewasa">Dewasa (18 – 60 thn)</option>
                                            <option value="Lansia">Lansia (&gt;60 thn)</option>
                                        </select>
                                    </div>

                                    {{-- Jenis Kelamin --}}
                                    <div>
                                        <label class="ant-label">Jenis Kelamin</label>
                                        <select x-model="newPatient.jenis_kelamin"
                                            class="ant-select">
                                            <option value="">— Pilih —</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>

                                    {{-- Alamat Desa --}}
                                    <div>
                                        <label class="ant-label">Alamat Desa / Kelurahan</label>
                                        <select x-model="newPatient.kelurahan"
                                            class="ant-select">
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
                                        <label class="ant-label">No. HP / WhatsApp</label>
                                        <input type="tel" x-model="newPatient.no_telepon" placeholder="08xxxxxxxxxx"
                                            class="ant-input">
                                    </div>

                                    {{-- Nama Pelapor --}}
                                    <div>
                                        <label class="ant-label">Nama Pelapor</label>
                                        <input type="text" x-model="newPatient.nama_pelapor"
                                            placeholder="Nama kader / petugas"
                                            class="ant-input">
                                    </div>

                                </div>

                                {{-- Skrining Section --}}
                                <div class="mt-1">
                                    <p class="text-[11px] font-bold text-ant-secondary uppercase tracking-widest mb-2.5">
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
                                                class="flex items-center justify-between gap-3 bg-[#fafafa] border border-ant-split rounded-ant px-3.5 py-2.5">
                                                <span class="text-xs font-medium text-ant-text flex-1"
                                                    x-text="item.label"></span>
                                                <div class="flex gap-1.5 shrink-0">
                                                    <template x-for="opt in item.opts" :key="opt">
                                                        <button type="button" @click="newPatient[item.key] = opt"
                                                            :class="newPatient[item.key] === opt
                                                                ? (opt === 'Ya' ? 'bg-success text-white border-success' : 'bg-danger text-white border-danger')
                                                                : 'bg-white text-ant-secondary border-ant-border hover:border-ant-disabled'"
                                                            class="px-3 py-1 text-[11px] font-bold border rounded-ant transition-all cursor-pointer"
                                                            x-text="opt">
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Sudah Pengobatan (Sudah / Belum) --}}
                                        <div
                                            class="flex items-center justify-between gap-3 bg-[#fafafa] border border-ant-split rounded-ant px-3.5 py-2.5">
                                            <span class="text-xs font-medium text-ant-text flex-1">Apakah sudah
                                                melakukan Pengobatan?</span>
                                            <div class="flex gap-1.5 shrink-0">
                                                <button type="button" @click="newPatient.sudah_pengobatan = 'Sudah'"
                                                    :class="newPatient.sudah_pengobatan === 'Sudah' ? 'bg-success text-white border-success' : 'bg-white text-ant-secondary border-ant-border hover:border-ant-disabled'"
                                                    class="px-3 py-1 text-[11px] font-bold border rounded-ant transition-all cursor-pointer">Sudah</button>
                                                <button type="button" @click="newPatient.sudah_pengobatan = 'Belum'"
                                                    :class="newPatient.sudah_pengobatan === 'Belum' ? 'bg-warning text-white border-warning' : 'bg-white text-ant-secondary border-ant-border hover:border-ant-disabled'"
                                                    class="px-3 py-1 text-[11px] font-bold border rounded-ant transition-all cursor-pointer">Belum</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div x-show="aiAddError"
                                    class="ant-alert ant-alert-error"
                                    x-text="aiAddError"></div>
                            </div>

                            {{-- ======= TAB: AI PROMPT ======= --}}
                            <div x-show="addModalTab === 'ai'" class="space-y-3">
                                <div
                                    class="ant-alert ant-alert-info leading-relaxed">
                                    Tulis deskripsi pasien seperti berbicara kepada rekan kerja. Contoh:<br>
                                    <span class="mt-1.5 block text-primary italic">"Pasien bernama Budi Santoso,
                                        laki-laki 35 tahun, NIK 3603xx, warga Kelurahan Pagedangan Kab. Tangerang.
                                        Terdaftar TB-03 dengan hasil TCM positif rifampisin sensitif, mulai pengobatan
                                        Januari 2025, riwayat HIV negatif."</span>
                                </div>

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
                                        &amp; Simpan</span>
                                </div>

                                {{-- Step 1: Prompt input --}}
                                <div x-show="aiAddStep === 'prompt'" class="space-y-3">
                                    <div>
                                        <textarea x-model="aiAddPrompt" rows="5"
                                            placeholder="Tulis deskripsi data pasien di sini..."
                                            class="ant-input h-auto py-2 resize-none leading-relaxed"></textarea>
                                        <p class="text-[11px] text-ant-secondary mt-1"
                                            x-text="aiAddPrompt.length + ' karakter'"></p>
                                    </div>
                                    <div x-show="aiAddError"
                                        class="ant-alert ant-alert-error"
                                        x-text="aiAddError"></div>
                                </div>

                                {{-- Step 2: Preview parsed fields --}}
                                <div x-show="aiAddStep === 'preview'" class="space-y-3">
                                    <div
                                        class="ant-alert ant-alert-success font-medium">
                                        AI berhasil mengekstrak data. Periksa hasil di bawah dan koreksi jika perlu
                                        sebelum
                                        menyimpan.
                                    </div>
                                    <div
                                        class="max-h-72 overflow-y-auto border border-ant-split rounded-ant divide-y divide-ant-split">
                                        <template
                                            x-for="[key, val] in Object.entries(newPatient).filter(([k,v]) => v !== null && v !== '' && v !== undefined)"
                                            :key="key">
                                            <div class="flex items-start gap-2 px-3.5 py-2.5 hover:bg-[#fafafa]">
                                                <span
                                                    class="text-[11px] font-semibold text-ant-secondary w-44 shrink-0 pt-0.5"
                                                    x-text="key.replace(/_/g,' ')"></span>
                                                <input type="text" :value="val"
                                                    @change="newPatient[key] = $event.target.value"
                                                    class="flex-1 text-xs text-ant-title bg-transparent border-0 border-b border-ant-split focus:border-primary focus:ring-0 px-0 py-0.5">
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

                        </div>

                        {{-- Footer actions --}}
                        <div class="px-6 pb-5 pt-3 border-t border-ant-split flex items-center justify-between gap-2">
                            <div>
                                {{-- AI: back button --}}
                                <button x-show="addModalTab === 'ai' && aiAddStep === 'preview'"
                                    @click="aiAddStep = 'prompt'; aiAddError = ''" type="button"
                                    class="text-xs font-semibold text-ant-secondary hover:text-ant-text cursor-pointer">
                                    Kembali ke Deskripsi
                                </button>
                            </div>

                            <div class="flex items-center gap-2 ml-auto">
                                <button @click="showAddModal = false" type="button"
                                    class="ant-btn ant-btn-default">
                                    Batal
                                </button>

                                {{-- MANUAL: Save directly --}}
                                <button x-show="addModalTab === 'manual'" @click="saveNewPatient()" type="button"
                                    :disabled="isSubmittingNewPatient || !newPatient.nama_lengkap"
                                    class="ant-btn ant-btn-success">
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
                                    class="ant-btn ant-btn-primary">
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
                                    class="ant-btn ant-btn-success">
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
