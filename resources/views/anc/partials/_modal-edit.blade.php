            <!-- ================= MODAL: EDIT IBU HAMIL REALTIME ================= -->
            <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showEditModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                        <div class="flex items-center justify-between pb-3 border-b border-ant-split">
                            <div>
                                <h3 class="ant-section-title">Edit Data Ibu Hamil (Real-time)</h3>
                                <p class="ant-text-secondary mt-0.5">Perubahan data akan langsung terupdate di tabel &
                                    indikator dashboard</p>
                            </div>
                            <button @click="showEditModal = false"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="saveEditPatient()" class="mt-4 space-y-3.5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="ant-label">Nama Lengkap *</label>
                                    <input type="text" x-model="editingPatient.nama_lengkap" required
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Nama Suami</label>
                                    <input type="text" x-model="editingPatient.nama_suami" placeholder="Nama suami"
                                        class="ant-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="ant-label">NIK</label>
                                    <input type="text" x-model="editingPatient.nik" placeholder="16 digit NIK"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">No. Telp / WhatsApp</label>
                                    <input type="text" x-model="editingPatient.no_telepon"
                                        placeholder="Nomor telepon/HP"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Tanggal Lahir</label>
                                    <input type="date" x-model="editingPatient.tanggal_lahir"
                                        class="ant-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="ant-label">Umur (Thn)</label>
                                    <input type="number" x-model="editingPatient.umur"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Gravida (G)</label>
                                    <input type="number" x-model="editingPatient.gravida"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Para (P)</label>
                                    <input type="number" x-model="editingPatient.para"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Abortus (A)</label>
                                    <input type="number" x-model="editingPatient.abortus"
                                        class="ant-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label class="ant-label">Usia Kehamilan (minggu)</label>
                                        <span class="text-[10px] text-primary font-medium" x-show="editingPatient.hpht"
                                            x-text="'Dari HPHT: ' + (calculateUsiaKehamilan(editingPatient.hpht, editingPatient.tanggal_kunjungan) ?? '-') + ' minggu'"></span>
                                    </div>
                                    <input type="text" inputmode="numeric" x-model="editingPatient.usia_kehamilan"
                                        placeholder="contoh: 24"
                                        class="ant-input">
                                    <p class="text-[10px] text-ant-secondary mt-1 leading-snug">Angka minggu terisi dari HPHT. Biarkan lalu simpan. Ubah hanya kalau angkanya salah.</p>
                                </div>
                                <div>
                                    <label class="ant-label">Kunjungan Ke</label>
                                    <select x-model="editingPatient.kunjungan_ke"
                                        class="ant-select">
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
                                    <input type="number" step="0.1" x-model="editingPatient.lila"
                                        @input="autoDetectRiskFactors(editingPatient)"
                                        placeholder="misal: 23.5"
                                        class="ant-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="ant-label">Kadar Hb (g/dL)</label>
                                    <input type="text" x-model="editingPatient.hb" placeholder="misal: 11.5"
                                        @input="autoDetectRiskFactors(editingPatient)"
                                        class="ant-input">
                                </div>
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label class="ant-label font-bold text-primary">HPHT</label>
                                        <span class="text-[10px] text-ant-secondary">(Hari Pertama Haid)</span>
                                    </div>
                                    <input type="date" x-model="editingPatient.hpht"
                                        @input="onHphtChange(editingPatient)"
                                        @change="onHphtChange(editingPatient)"
                                        class="ant-input !border-primary-400 !bg-primary-50/20">
                                </div>
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label class="ant-label !text-danger font-bold">HPL / TP (Taksiran Persalinan)</label>
                                        <span class="text-[10px] text-danger-600 font-medium">(tgl +7, bln -3, thn +1)</span>
                                    </div>
                                    <input type="date" x-model="editingPatient.hpl"
                                        class="ant-input !border-danger-400 !bg-danger-50">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="ant-label">Status RISTI</label>
                                    <select x-model="editingPatient.status_risti"
                                        class="ant-select">
                                        <option value="Normal">Normal</option>
                                        <option value="Risiko Tinggi">Risiko Tinggi (RISTI)</option>
                                        <option value="Risiko Sangat Tinggi">Risiko Sangat Tinggi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="ant-label">Status Anemia</label>
                                    <select x-model="editingPatient.status_anemia"
                                        class="ant-select">
                                        <option value="Tidak Anemia">Tidak Anemia</option>
                                        <option value="Anemia Ringan">Anemia Ringan</option>
                                        <option value="Anemia Sedang">Anemia Sedang</option>
                                        <option value="Anemia Berat">Anemia Berat</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="ant-label">TD Sistolik (mmHg)</label>
                                    <input type="number" x-model="editingPatient.tekanan_darah_sistolik"
                                        @input="autoDetectRiskFactors(editingPatient)"
                                        placeholder="misal: 120"
                                        class="ant-input">
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="ant-label mb-0">Faktor Risiko</label>
                                    <span class="text-[10px] text-ant-secondary">Klik chip untuk memilih langsung:</span>
                                </div>
                                <input type="text" x-model="editingPatient.faktor_risiko"
                                    placeholder="misal: KEK, Anemia, HDK, BSC"
                                    class="ant-input">
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    <template x-for="chip in ['KEK', 'Anemia', 'HDK', 'BSC', 'Gemelli', 'Primi Muda (< 17 th)', 'Umur Hamil Lanjut (≥ 35 th)', 'TB < 145 cm', 'Asma', 'DM', 'Riwayat Abortus']" :key="chip">
                                        <button type="button" @click="toggleRiskChip(editingPatient, chip)"
                                            :class="(editingPatient.faktor_risiko && editingPatient.faktor_risiko.includes(chip)) ? 'bg-danger-100 text-danger border-danger-300 font-bold' : 'bg-gray-100 text-ant-secondary hover:bg-gray-200 border-gray-200'"
                                            class="px-2 py-0.5 rounded text-[10px] border cursor-pointer transition-colors"
                                            x-text="'+ ' + chip">
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="ant-label">Kabupaten / Kota</label>
                                    <input type="text" x-model="editingPatient.kabupaten"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Kelurahan / Desa</label>
                                    <input type="text" x-model="editingPatient.kelurahan"
                                        class="ant-input">
                                </div>
                            </div>

                            <div>
                                <label class="ant-label">Calon Pendonor Darah
                                    (P4K)</label>
                                <input type="text" x-model="editingPatient.calon_pendonor"
                                    placeholder="Nama & Gol. Darah calon pendonor"
                                    class="ant-input">
                            </div>

                            <div>
                                <label class="ant-label">Alamat Lengkap</label>
                                <textarea rows="2" x-model="editingPatient.alamat_lengkap"
                                    class="ant-input !h-auto py-2 resize-none"></textarea>
                            </div>

                            <div
                                class="p-3 bg-primary-50 rounded-ant border border-primary-100 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-semibold text-ant-title">Klasifikasi Poedji Rochjati
                                        Otomatis:</span>
                                    <span class="ml-1 text-ant-text"
                                        x-text="(editingPatient.kategori_poedji_rochjati || 'KRR') + ' (Skor: ' + (editingPatient.skor_poedji_rochjati ?? 2) + ')'"></span>
                                </div>
                                <div class="text-[11px] text-primary font-medium"
                                    x-text="editingPatient.rekomendasi_faskes || 'Bidan / BPM / Puskesmas'"></div>
                            </div>

                            <div class="mt-5 pt-3 border-t border-ant-split flex items-center justify-end gap-2">
                                <button @click="showEditModal = false" type="button"
                                    class="ant-btn ant-btn-default">Batal</button>
                                <button type="submit"
                                    class="ant-btn ant-btn-primary">Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
