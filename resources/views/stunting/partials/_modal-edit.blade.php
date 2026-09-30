        <!-- ================= MODAL: EDIT BALITA REALTIME ================= -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                <div @click="showEditModal = false"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <div
                    class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Edit Rekam Data Balita</h3>
                            <p class="text-xs text-slate-500">Perubahan data akan langsung terupdate di tabel dan grafik
                            </p>
                        </div>
                        <button @click="showEditModal = false"
                            class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
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
                                <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap Balita *</label>
                                <input type="text" x-model="editingPatient.nama" required
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">NIK</label>
                                <input type="text" x-model="editingPatient.nik" maxlength="20"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Nama Orang Tua</label>
                                <input type="text" x-model="editingPatient.nama_ortu"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                                <select x-model="editingPatient.jenis_kelamin"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                                <input type="date" x-model="editingPatient.tanggal_lahir"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Usia Saat Ukur (Bulan)</label>
                                <input type="number" x-model="editingPatient.usia_saat_ukur" min="0" max="72"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Desa / Kelurahan</label>
                                <input type="text" x-model="editingPatient.desa"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Posyandu</label>
                                <input type="text" x-model="editingPatient.posyandu"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Puskesmas</label>
                                <input type="text" x-model="editingPatient.puskesmas"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">BB Saat Ini (kg)</label>
                                <input type="number" step="0.01" x-model="editingPatient.berat"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Tinggi Saat Ini (cm)</label>
                                <input type="number" step="0.1" x-model="editingPatient.tinggi"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">LiLA (cm)</label>
                                <input type="number" step="0.1" x-model="editingPatient.lila"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <!-- <div>
                                <label class="block font-semibold text-slate-700 mb-1">Lolos</label>
                                <select x-model="editingPatient.naik_berat_badan"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="">-</option>
                                    <option value="N">Naik (N)</option>
                                    <option value="T">Turun / Tidak (T)</option>
                                    <option value="Y">Pertama Kali (Y)</option>
                                </select>
                            </div> -->
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kategori TB/U</label>
                                <select x-model="editingPatient.tbu_kategori"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="Normal">Normal</option>
                                    <option value="Pendek">Pendek</option>
                                    <option value="Sangat Pendek">Sangat Pendek</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kategori BB/U</label>
                                <select x-model="editingPatient.bbu_kategori"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="Gizi Baik">Gizi Baik / Normal</option>
                                    <option value="Kurang">Gizi Kurang</option>
                                    <option value="Sangat Kurang">Gizi Sangat Kurang</option>
                                    <option value="Risiko Lebih">Risiko Lebih</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kategori BB/TB</label>
                                <select x-model="editingPatient.bbtb_kategori"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="Gizi Baik">Gizi Baik</option>
                                    <option value="Gizi Kurang">Gizi Kurang</option>
                                    <option value="Gizi Buruk">Gizi Buruk</option>
                                    <option value="Berisiko Gizi Lebih">Berisiko Gizi Lebih</option>
                                    <option value="Gizi Lebih">Gizi Lebih</option>
                                    <option value="Obesitas">Obesitas</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button @click="showEditModal = false" type="button"
                                class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                            <button type="submit"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-xs cursor-pointer">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

