            <!-- ================= MODAL: WHATSAPP DIRECT REMINDER (wa.me) ================= -->
            <div x-show="showWhatsAppModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showWhatsAppModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <span class="p-2 rounded-xl bg-emerald-100 text-emerald-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">Kirim Pengingat WhatsApp Langsung
                                    </h3>
                                    <p class="text-xs text-slate-500">Integrasi pesan resmi SICEPOT via WhatsApp (wa.me)
                                    </p>
                                </div>
                            </div>
                            <button @click="showWhatsAppModal = false"
                                class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-4 text-xs">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Pasien TBC Terpilih:</label>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                    <div class="font-bold text-slate-800" x-text="targetPatient?.nama_lengkap || '-'">
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-0.5"
                                        x-text="'Kelurahan: ' + (targetPatient?.kelurahan || '-') + ' | Diagnosis: ' + (targetPatient?.hasil_diagnosis || targetPatient?.hasil_tcm || '-')">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp Pasien / Pengawas
                                    Minum Obat (PMO):</label>
                                <input type="text" x-model="waPhone" placeholder="Contoh: 08123456789 atau 628123456789"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 font-mono">
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Pilih Template Pesan TBC:</label>
                                <select x-model="waTemplateType" @change="prepareWaMessage()"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="oat_daily">Pengingat Minum Obat Harian (Kepatuhan OAT)</option>
                                    <option value="sputum_eval">Jadwal Evaluasi Dahak Akhir Bulan Ke-2/5/6</option>
                                    <option value="dropout_warning">Peringatan Mangkir Berobat / Drop Out TBC (Kritis)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Isi Pesan (Bisa
                                    disesuaikan):</label>
                                <textarea rows="5" x-model="waMessage"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500"></textarea>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <button @click="showWhatsAppModal = false" type="button"
                                    class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                                <button type="button" @click="sendWhatsAppMessage()" :disabled="!waPhone"
                                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-bold shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50">
                                    <span>Buka WhatsApp Web / App ↗</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

