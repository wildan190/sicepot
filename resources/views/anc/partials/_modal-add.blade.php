            <!-- ================= MODAL: TAMBAH DATA IBU HAMIL ================= -->
            <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showAddModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split overflow-hidden">

                        {{-- Header --}}
                        <div class="px-6 pt-5 pb-3 border-b border-ant-split flex items-start justify-between gap-3">
                            <div>
                                <h3 class="ant-section-title">Tambah Data Ibu Hamil</h3>
                                <p class="ant-text-secondary mt-0.5">Input data rekam medis ibu hamil secara langsung atau gunakan AI</p>
                            </div>
                            <button @click="showAddModal = false"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Mode Selector Tabs --}}
                        <div class="flex items-center gap-2 border-b border-ant-split px-6 pt-2 bg-[#fafafa]">
                            <button type="button" @click="addMode = 'manual'"
                                :class="addMode === 'manual' ? 'border-primary text-primary font-bold bg-white -mb-px border-b-2' : 'border-transparent text-ant-secondary hover:text-ant-title font-medium'"
                                class="py-2 px-3.5 rounded-t text-xs flex items-center gap-1.5 transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Input Formulir Langsung
                            </button>
                            <button type="button" @click="addMode = 'ai'"
                                :class="addMode === 'ai' ? 'border-primary text-primary font-bold bg-white -mb-px border-b-2' : 'border-transparent text-ant-secondary hover:text-ant-title font-medium'"
                                class="py-2 px-3.5 rounded-t text-xs flex items-center gap-1.5 transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Tulis Deskripsi (AI Assistant)
                            </button>
                        </div>

                        {{-- ================= MODE 1: FORMULIR MANUAL ================= --}}
                        <div x-show="addMode === 'manual'" class="px-6 py-4 max-h-[72vh] overflow-y-auto space-y-3.5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="ant-label">Nama Lengkap Ibu *</label>
                                    <input type="text" x-model="newPatient.nama_lengkap" required placeholder="Nama lengkap ibu hamil"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Nama Suami</label>
                                    <input type="text" x-model="newPatient.nama_suami" placeholder="Nama suami"
                                        class="ant-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="ant-label">NIK (16 Digit)</label>
                                    <input type="text" x-model="newPatient.nik" placeholder="3603xxxxxxxxxxxx"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">No. Telepon / WhatsApp</label>
                                    <input type="text" x-model="newPatient.no_telepon" placeholder="08xxxxxxxxxx"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Tanggal Lahir</label>
                                    <input type="date" x-model="newPatient.tanggal_lahir"
                                        class="ant-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="ant-label">Umur (Tahun)</label>
                                    <input type="number" x-model="newPatient.umur" placeholder="Umur"
                                        @input="autoDetectRiskFactors(newPatient)"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Gravida (G)</label>
                                    <input type="number" min="1" x-model="newPatient.gravida" placeholder="1"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Para (P)</label>
                                    <input type="number" min="0" x-model="newPatient.para" placeholder="0"
                                        @input="autoDetectRiskFactors(newPatient)"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Abortus (A)</label>
                                    <input type="number" min="0" x-model="newPatient.abortus" placeholder="0"
                                        @input="autoDetectRiskFactors(newPatient)"
                                        class="ant-input">
                                </div>
                            </div>

                            {{-- SECTION: HPHT, HPL, USIA KEHAMILAN --}}
                            <div class="p-3 bg-primary-50/40 rounded-ant border border-primary-100 space-y-3">
                                <div class="flex items-center justify-between border-b border-primary-100 pb-1.5">
                                    <span class="font-bold text-primary flex items-center gap-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Penanggalan Obstetri (Kalkulasi Otomatis)
                                    </span>
                                    <span class="text-[10px] text-ant-secondary">Rumus Naegele: tgl +7, bln -3, thn +1</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <label class="ant-label font-bold text-primary">HPHT</label>
                                            <span class="text-[10px] text-ant-secondary">(Haid Terakhir)</span>
                                        </div>
                                        <input type="date" x-model="newPatient.hpht"
                                            @input="onHphtChange(newPatient)"
                                            @change="onHphtChange(newPatient)"
                                            class="ant-input !border-primary-400 !bg-white">
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <label class="ant-label !text-danger font-bold">HPL / TP (Taksiran Persalinan)</label>
                                            <span class="text-[10px] text-danger-600 font-medium">(Otomatis)</span>
                                        </div>
                                        <input type="date" x-model="newPatient.hpl"
                                            class="ant-input !border-danger-400 !bg-danger-50">
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <label class="ant-label font-bold">Usia Kehamilan (Minggu)</label>
                                            <span class="text-[10px] text-primary font-medium" x-show="newPatient.hpht"
                                                x-text="'(Auto HPHT)'"></span>
                                        </div>
                                        <input type="number" min="0" max="45" x-model="newPatient.usia_kehamilan"
                                            placeholder="misal: 24"
                                            class="ant-input">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="ant-label">Kunjungan Ke</label>
                                    <select x-model="newPatient.kunjungan_ke" class="ant-select">
                                        <option value="K1">K1 (Trimester 1)</option>
                                        <option value="K2">K2 (Trimester 1 / 2)</option>
                                        <option value="K3">K3 (Trimester 2)</option>
                                        <option value="K4">K4 (Trimester 3)</option>
                                        <option value="K5">K5 (Trimester 3)</option>
                                        <option value="K6">K6 (Trimester 3)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="ant-label">LiLA (cm)</label>
                                    <input type="number" step="0.1" x-model="newPatient.lila"
                                        @input="autoDetectRiskFactors(newPatient)"
                                        placeholder="misal: 23.5"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Kadar Hb (g/dL)</label>
                                    <input type="text" x-model="newPatient.hb" placeholder="misal: 11.5"
                                        @input="autoDetectRiskFactors(newPatient)"
                                        class="ant-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="ant-label">TD Sistolik (mmHg)</label>
                                    <input type="number" x-model="newPatient.tekanan_darah_sistolik"
                                        @input="autoDetectRiskFactors(newPatient)"
                                        placeholder="misal: 120"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Status RISTI</label>
                                    <select x-model="newPatient.status_risti" class="ant-select">
                                        <option value="Normal">Normal</option>
                                        <option value="Risiko Tinggi">Risiko Tinggi (RISTI)</option>
                                        <option value="Risiko Sangat Tinggi">Risiko Sangat Tinggi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="ant-label">Status Anemia</label>
                                    <select x-model="newPatient.status_anemia" class="ant-select">
                                        <option value="Tidak Anemia">Tidak Anemia</option>
                                        <option value="Anemia Ringan">Anemia Ringan</option>
                                        <option value="Anemia Sedang">Anemia Sedang</option>
                                        <option value="Anemia Berat">Anemia Berat</option>
                                    </select>
                                </div>
                            </div>

                            {{-- FAKTOR RISIKO DENGAN QUICK SELECT CHIPS --}}
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="ant-label mb-0">Faktor Risiko</label>
                                    <span class="text-[10px] text-ant-secondary">Klik chip untuk memilih langsung:</span>
                                </div>
                                <input type="text" x-model="newPatient.faktor_risiko"
                                    placeholder="misal: KEK, Anemia, HDK, BSC"
                                    class="ant-input">
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    <template x-for="chip in ['KEK', 'Anemia', 'HDK', 'BSC', 'Gemelli', 'Primi Muda (< 17 th)', 'Umur Hamil Lanjut (≥ 35 th)', 'TB < 145 cm', 'Asma', 'DM', 'Riwayat Abortus']" :key="chip">
                                        <button type="button" @click="toggleRiskChip(newPatient, chip)"
                                            :class="(newPatient.faktor_risiko && newPatient.faktor_risiko.includes(chip)) ? 'bg-danger-100 text-danger border-danger-300 font-bold' : 'bg-gray-100 text-ant-secondary hover:bg-gray-200 border-gray-200'"
                                            class="px-2 py-0.5 rounded text-[10px] border cursor-pointer transition-colors"
                                            x-text="'+ ' + chip">
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="ant-label">Kabupaten / Kota</label>
                                    <input type="text" x-model="newPatient.kabupaten"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Kelurahan / Desa</label>
                                    <input type="text" x-model="newPatient.kelurahan" placeholder="Kelurahan"
                                        class="ant-input">
                                </div>
                            </div>

                            <div>
                                <label class="ant-label">Alamat Lengkap</label>
                                <textarea rows="2" x-model="newPatient.alamat_lengkap" placeholder="Alamat domisili lengkap..."
                                    class="ant-input !h-auto py-2 resize-none"></textarea>
                            </div>

                            <div x-show="aiAddError" class="ant-alert ant-alert-error" x-text="aiAddError"></div>
                        </div>

                        {{-- ================= MODE 2: DESKRIPSI AI ================= --}}
                        <div x-show="addMode === 'ai'" class="px-6 py-5 space-y-4">
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
                                        3603xx, G2P1A0, HPHT 10 Maret 2025, LiLA 23 cm, Hb 10.2,
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
                                    AI berhasil mengekstrak data. HPL dan Usia Kehamilan dihitung otomatis dari HPHT. Periksa hasil di bawah sebelum menyimpan.
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
                                                @change="newPatient[key] = $event.target.value; if (key === 'hpht') onHphtChange(newPatient);"
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

                        {{-- Footer Actions --}}
                        <div class="px-6 pb-5 pt-3 border-t border-ant-split flex items-center justify-between gap-2">
                            <button x-show="addMode === 'ai' && aiAddStep === 'preview'" @click="aiAddStep = 'prompt'; aiAddError = ''"
                                type="button"
                                class="ant-btn ant-btn-text ant-btn-sm">
                                Kembali ke Deskripsi
                            </button>
                            <div x-show="addMode === 'manual' || (addMode === 'ai' && aiAddStep === 'prompt')"></div>

                            <div class="flex items-center gap-2 ml-auto">
                                <button @click="showAddModal = false" type="button"
                                    class="ant-btn ant-btn-default">
                                    Batal
                                </button>

                                {{-- AI Process Button --}}
                                <button x-show="addMode === 'ai' && aiAddStep === 'prompt'" @click="runAncAiParse()" type="button"
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

                                {{-- Save Button (for Manual Form or AI Preview) --}}
                                <button x-show="addMode === 'manual' || (addMode === 'ai' && aiAddStep === 'preview')"
                                    @click="if (!newPatient.nama_lengkap) { aiAddError = 'Nama lengkap ibu hamil wajib diisi.'; return; } saveNewPatient()"
                                    type="button"
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