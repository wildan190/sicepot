            <!-- ================= MODAL: EDIT IBU HAMIL REALTIME ================= -->
            <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showEditModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Edit Data Ibu Hamil (Real-time)</h3>
                                <p class="text-xs text-slate-500">Perubahan data akan langsung terupdate di tabel &
                                    indikator dashboard</p>
                            </div>
                            <button @click="showEditModal = false"
                                class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="saveEditPatient()" class="mt-4 space-y-3.5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" x-model="editingPatient.nama_lengkap" required
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Nama Suami</label>
                                    <input type="text" x-model="editingPatient.nama_suami" placeholder="Nama suami"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">NIK</label>
                                    <input type="text" x-model="editingPatient.nik" placeholder="16 digit NIK"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">No. Telp / WhatsApp</label>
                                    <input type="text" x-model="editingPatient.no_telepon"
                                        placeholder="Nomor telepon/HP"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" x-model="editingPatient.tanggal_lahir"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Umur (Thn)</label>
                                    <input type="number" x-model="editingPatient.umur"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Gravida (G)</label>
                                    <input type="number" x-model="editingPatient.gravida"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Para (P)</label>
                                    <input type="number" x-model="editingPatient.para"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Abortus (A)</label>
                                    <input type="number" x-model="editingPatient.abortus"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Usia Kehamilan
                                        (Minggu)</label>
                                    <input type="text" x-model="editingPatient.usia_kehamilan"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kunjungan Ke</label>
                                    <select x-model="editingPatient.kunjungan_ke"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                        <option value="K1">K1 (Trimester 1)</option>
                                        <option value="K2">K2 (Trimester 1 / 2)</option>
                                        <option value="K3">K3 (Trimester 2)</option>
                                        <option value="K4">K4 (Trimester 3)</option>
                                        <option value="K5">K5 (Trimester 3)</option>
                                        <option value="K6">K6 (Trimester 3)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">LiLA (cm)</label>
                                    <input type="number" step="0.1" x-model="editingPatient.lila"
                                        placeholder="misal: 23.5"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kadar Hb (g/dL)</label>
                                    <input type="text" x-model="editingPatient.hb" placeholder="misal: 11.5"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">HPHT</label>
                                    <input type="date" x-model="editingPatient.hpht"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-rose-700 mb-1">HPL / TP (Taksiran
                                        Persalinan)</label>
                                    <input type="date" x-model="editingPatient.hpl"
                                        class="w-full px-3 py-2 text-xs border border-rose-300 bg-rose-50/20 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Status RISTI</label>
                                    <select x-model="editingPatient.status_risti"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                        <option value="Normal">Normal</option>
                                        <option value="Risiko Tinggi">Risiko Tinggi (RISTI)</option>
                                        <option value="Risiko Sangat Tinggi">Risiko Sangat Tinggi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Status Anemia</label>
                                    <select x-model="editingPatient.status_anemia"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                        <option value="Tidak Anemia">Tidak Anemia</option>
                                        <option value="Anemia Ringan">Anemia Ringan</option>
                                        <option value="Anemia Sedang">Anemia Sedang</option>
                                        <option value="Anemia Berat">Anemia Berat</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Faktor Risiko (jika
                                        ada)</label>
                                    <input type="text" x-model="editingPatient.faktor_risiko"
                                        placeholder="misal: KEK, Anemia, HT"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kabupaten / Kota</label>
                                    <input type="text" x-model="editingPatient.kabupaten"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kelurahan / Desa</label>
                                    <input type="text" x-model="editingPatient.kelurahan"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Calon Pendonor Darah
                                    (P4K)</label>
                                <input type="text" x-model="editingPatient.calon_pendonor"
                                    placeholder="Nama & Gol. Darah calon pendonor"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                                <textarea rows="2" x-model="editingPatient.alamat_lengkap"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500"></textarea>
                            </div>

                            <div
                                class="p-3 bg-pink-50/50 rounded-xl border border-pink-100 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-semibold text-pink-900">Klasifikasi Poedji Rochjati
                                        Otomatis:</span>
                                    <span class="ml-1 text-slate-600"
                                        x-text="(editingPatient.kategori_poedji_rochjati || 'KRR') + ' (Skor: ' + (editingPatient.skor_poedji_rochjati ?? 2) + ')'"></span>
                                </div>
                                <div class="text-[11px] text-pink-700 font-medium"
                                    x-text="editingPatient.rekomendasi_faskes || 'Bidan / BPM / Puskesmas'"></div>
                            </div>

                            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button @click="showEditModal = false" type="button"
                                    class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                                <button type="submit"
                                    class="px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-semibold shadow-xs cursor-pointer">Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

