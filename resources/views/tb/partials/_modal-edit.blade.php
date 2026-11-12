            <!-- ================= MODAL: EDIT PASIEN REALTIME ================= -->
            <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showEditModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                        <div class="flex items-center justify-between pb-3 border-b border-ant-split">
                            <div>
                                <h3 class="ant-section-title">Edit Data Pasien / Terduga (Real-time)
                                </h3>
                                <p class="text-xs text-ant-secondary">Perubahan data akan langsung terupdate di tabel &
                                    grafik</p>
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
                                    <label class="ant-label ant-label-required">Nama Lengkap</label>
                                    <input type="text" x-model="editingPatient.nama_lengkap" required
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">NIK</label>
                                    <input type="text" x-model="editingPatient.nik"
                                        class="ant-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="ant-label">Umur (Tahun)</label>
                                    <input type="number" x-model="editingPatient.umur"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Jenis Kelamin</label>
                                    <select x-model="editingPatient.jenis_kelamin"
                                        class="ant-select">
                                        <option value="L">Laki-laki (L)</option>
                                        <option value="P">Perempuan (P)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="ant-label">Kabupaten / Kota</label>
                                    <input type="text" x-model="editingPatient.kabupaten"
                                        class="ant-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="ant-label">Kecamatan</label>
                                    <input type="text" x-model="editingPatient.kecamatan"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Kelurahan / Desa</label>
                                    <input type="text" x-model="editingPatient.kelurahan"
                                        class="ant-input">
                                </div>
                            </div>

                            <div>
                                <label class="ant-label">Alamat Lengkap</label>
                                <textarea rows="2" x-model="editingPatient.alamat_lengkap"
                                    class="ant-input h-auto py-1.5"></textarea>
                            </div>

                            <div>
                                <label class="ant-label">No. HP / WhatsApp</label>
                                <input type="tel" x-model="editingPatient.no_telepon" placeholder="08xxxxxxxxxx"
                                    class="ant-input">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="ant-label">Hasil Diagnosis</label>
                                    <select x-model="editingPatient.hasil_diagnosis"
                                        class="ant-select">
                                        <option value="Terkonfirmasi TBC">Terkonfirmasi TBC</option>
                                        <option value="TBC SO">TBC SO</option>
                                        <option value="Bukan TBC">Bukan TBC</option>
                                        <option value="Terduga">Terduga</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="ant-label">Hasil Akhir
                                        Pengobatan</label>
                                    <select x-model="editingPatient.hasil_akhir_pengobatan"
                                        class="ant-select">
                                        <option value="">-- Masih Dalam Pengobatan --</option>
                                        <option value="Sembuh">Sembuh</option>
                                        <option value="Pengobatan lengkap">Pengobatan lengkap</option>
                                        <option value="Putus berobat (lost to follow up)">Putus berobat (lost to follow
                                            up)</option>
                                        <option value="Meninggal">Meninggal</option>
                                        <option value="Gagal">Gagal</option>
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
