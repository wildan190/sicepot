        <!-- ================= MODAL: EDIT BALITA REALTIME ================= -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                <div @click="showEditModal = false"
                    class="fixed inset-0 bg-black/45 backdrop-blur-xs transition-opacity"></div>

                <div
                    class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                    <div class="flex items-center justify-between pb-3 border-b border-ant-split">
                        <div>
                            <h3 class="text-base font-semibold text-ant-title">Edit Rekam Data Balita</h3>
                            <p class="text-xs text-ant-secondary">Perubahan data akan langsung terupdate di tabel dan grafik
                            </p>
                        </div>
                        <button @click="showEditModal = false"
                            class="p-1.5 text-ant-secondary hover:text-ant-text rounded-ant hover:bg-ant-split cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="saveEditPatient()"
                        class="mt-4 space-y-3.5 text-xs max-h-[75vh] overflow-y-auto pr-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="sm:col-span-2">
                                <label class="ant-label">Nama Lengkap Balita <span class="text-danger">*</span></label>
                                <input type="text" x-model="editingPatient.nama" required
                                    class="ant-input">
                            </div>
                            <div>
                                <label class="ant-label">NIK</label>
                                <input type="text" x-model="editingPatient.nik" maxlength="20"
                                    class="ant-input">
                            </div>
                            <div>
                                <label class="ant-label">Nama Orang Tua</label>
                                <input type="text" x-model="editingPatient.nama_ortu"
                                    class="ant-input">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="ant-label">No. Telepon / WhatsApp</label>
                                <input type="tel" x-model="editingPatient.no_telepon" placeholder="08xxxxxxxxxx"
                                    class="ant-input">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="ant-label">Jenis Kelamin</label>
                                <select x-model="editingPatient.jenis_kelamin"
                                    class="ant-select">
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                            </div>
                            <div>
                                <label class="ant-label">Tanggal Lahir</label>
                                <input type="date" x-model="editingPatient.tanggal_lahir"
                                    class="ant-input">
                            </div>
                            <div>
                                <label class="ant-label">Usia Saat Ukur (Bulan)</label>
                                <input type="number" x-model="editingPatient.usia_saat_ukur" min="0" max="72"
                                    class="ant-input">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="ant-label">Desa / Kelurahan</label>
                                <input type="text" x-model="editingPatient.desa"
                                    class="ant-input">
                            </div>
                            <div>
                                <label class="ant-label">Posyandu</label>
                                <input type="text" x-model="editingPatient.posyandu"
                                    class="ant-input">
                            </div>
                            <div>
                                <label class="ant-label">Puskesmas</label>
                                <input type="text" x-model="editingPatient.puskesmas"
                                    class="ant-input">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div>
                                <label class="ant-label">BB Saat Ini (kg)</label>
                                <input type="number" step="0.01" x-model="editingPatient.berat"
                                    class="ant-input">
                            </div>
                            <div>
                                <label class="ant-label">Tinggi Saat Ini (cm)</label>
                                <input type="number" step="0.1" x-model="editingPatient.tinggi"
                                    class="ant-input">
                            </div>
                            <div>
                                <label class="ant-label">LiLA (cm)</label>
                                <input type="number" step="0.1" x-model="editingPatient.lila"
                                    class="ant-input">
                            </div>
                            <!-- <div>
                                <label class="ant-label">Lolos</label>
                                <select x-model="editingPatient.naik_berat_badan"
                                    class="ant-select">
                                    <option value="">-</option>
                                    <option value="N">Naik (N)</option>
                                    <option value="T">Turun / Tidak (T)</option>
                                    <option value="Y">Pertama Kali (Y)</option>
                                </select>
                            </div> -->
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="ant-label">Kategori TB/U</label>
                                <select x-model="editingPatient.tbu_kategori"
                                    class="ant-select">
                                    <option value="Normal">Normal</option>
                                    <option value="Pendek">Pendek</option>
                                    <option value="Sangat Pendek">Sangat Pendek</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>
                            <div>
                                <label class="ant-label">Kategori BB/U</label>
                                <select x-model="editingPatient.bbu_kategori"
                                    class="ant-select">
                                    <option value="Gizi Baik">Gizi Baik / Normal</option>
                                    <option value="Kurang">Gizi Kurang</option>
                                    <option value="Sangat Kurang">Gizi Sangat Kurang</option>
                                    <option value="Risiko Lebih">Risiko Lebih</option>
                                </select>
                            </div>
                            <div>
                                <label class="ant-label">Kategori BB/TB</label>
                                <select x-model="editingPatient.bbtb_kategori"
                                    class="ant-select">
                                    <option value="Gizi Baik">Gizi Baik</option>
                                    <option value="Gizi Kurang">Gizi Kurang</option>
                                    <option value="Gizi Buruk">Gizi Buruk</option>
                                    <option value="Berisiko Gizi Lebih">Berisiko Gizi Lebih</option>
                                    <option value="Gizi Lebih">Gizi Lebih</option>
                                    <option value="Obesitas">Obesitas</option>
                                </select>
                            </div>
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
