        <!-- ================= MODAL: WHATSAPP DIRECT REMINDER (wa.me) ================= -->
        <div x-show="showWhatsAppModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
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
                                <h3 class="text-base font-bold text-slate-800">Kirim Pengingat WhatsApp (wa.me)</h3>
                                <p class="text-xs text-slate-500" x-text="targetPatient?.nama ? 'Balita: ' + targetPatient.nama : 'Kirim pesan edukasi & pengingat balita'"></p>
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

                    <div class="mt-4 space-y-3.5 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Penerima Pesan (Orang Tua Balita):</label>
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <span x-text="targetPatient?.nama || '-'"></span>
                                    <span class="text-[11px] font-normal text-slate-500" x-text="'(Ortu: ' + (targetPatient?.nama_ortu || '-') + ')'"></span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-1 flex flex-wrap gap-x-3 gap-y-0.5">
                                    <span x-text="'Desa: ' + (targetPatient?.desa || '-')"></span>
                                    <span x-text="'Posyandu: ' + (targetPatient?.posyandu || '-')"></span>
                                    <span x-text="'Status: ' + (targetPatient?.tbu_kategori || '-')"></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp Orang Tua / Wali:</label>
                            <input type="text" x-model="waPhone" placeholder="Contoh: 08123456789 atau 628123456789"
                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 font-mono">
                            <p class="text-[11px] text-slate-400 mt-0.5">Masukkan nomor HP aktif orang tua/kader balita untuk membuka percakapan via WhatsApp.</p>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Pilih Template Pengingat:</label>
                            <select x-model="waTemplateType" @change="prepareWaMessage()"
                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                <option value="posyandu_schedule">Jadwal Penimbangan &amp; Posyandu Rutin</option>
                                <option value="stunting_pmt">Edukasi Terapi Gizi &amp; Kepatuhan Pemberian PMT</option>
                                <option value="weight_faltering">Peringatan Berat Badan Tidak Naik / Turun (T/Faltering)</option>
                                <option value="referral_spa">Rujukan Pemeriksaan Spesialis Anak (Sp.A) / Puskesmas</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Isi Pesan (Bisa diedit langsung):</label>
                            <textarea rows="6" x-model="waMessage"
                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 leading-relaxed font-sans"></textarea>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <button @click="showWhatsAppModal = false" type="button"
                                class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                            <button type="button" @click="sendWhatsAppMessage()" :disabled="!waPhone"
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-bold shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <span>Buka WhatsApp Web / App ↗</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

