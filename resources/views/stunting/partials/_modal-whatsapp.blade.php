        <!-- ================= MODAL: WHATSAPP DIRECT REMINDER (wa.me) ================= -->
        <div x-show="showWhatsAppModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                <div @click="showWhatsAppModal = false"
                    class="fixed inset-0 bg-black/45 backdrop-blur-xs transition-opacity"></div>
                <div
                    class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                    <div class="flex items-center justify-between pb-3 border-b border-ant-split">
                        <div class="flex items-center gap-2.5">
                            <span class="p-2 rounded-ant bg-success-100 text-success-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-base font-semibold text-ant-title">Kirim Pengingat WhatsApp (wa.me)</h3>
                                <p class="text-xs text-ant-secondary" x-text="targetPatient?.nama ? 'Balita: ' + targetPatient.nama : 'Kirim pesan edukasi & pengingat balita'"></p>
                            </div>
                        </div>
                        <button @click="showWhatsAppModal = false"
                            class="p-1.5 text-ant-secondary hover:text-ant-text rounded-ant hover:bg-ant-split cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4 space-y-3.5 text-xs">
                        <div>
                            <label class="ant-label">Penerima Pesan (Orang Tua Balita):</label>
                            <div class="p-3 bg-ant-layout border border-ant-border rounded-ant">
                                <div class="font-bold text-ant-title flex items-center gap-1.5">
                                    <span x-text="targetPatient?.nama || '-'"></span>
                                    <span class="text-[11px] font-normal text-ant-secondary" x-text="'(Ortu: ' + (targetPatient?.nama_ortu || '-') + ')'"></span>
                                </div>
                                <div class="text-[11px] text-ant-secondary mt-1 flex flex-wrap gap-x-3 gap-y-0.5">
                                    <span x-text="'Desa: ' + (targetPatient?.desa || '-')"></span>
                                    <span x-text="'Posyandu: ' + (targetPatient?.posyandu || '-')"></span>
                                    <span x-text="'Status: ' + (targetPatient?.tbu_kategori || '-')"></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="ant-label">Nomor WhatsApp Orang Tua / Wali:</label>
                            <input type="text" x-model="waPhone" placeholder="Contoh: 08123456789 atau 628123456789"
                                class="ant-input font-mono">
                            <p class="text-[11px] text-ant-secondary mt-0.5">Masukkan nomor HP aktif orang tua/kader balita untuk membuka percakapan via WhatsApp.</p>
                        </div>

                        <div>
                            <label class="ant-label">Pilih Template Pengingat:</label>
                            <select x-model="waTemplateType" @change="prepareWaMessage()"
                                class="ant-select">
                                <option value="posyandu_schedule">Jadwal Penimbangan &amp; Posyandu Rutin</option>
                                <option value="stunting_pmt">Edukasi Terapi Gizi &amp; Kepatuhan Pemberian PMT</option>
                                <option value="weight_faltering">Peringatan Berat Badan Tidak Naik / Turun (T/Faltering)</option>
                                <option value="referral_spa">Rujukan Pemeriksaan Spesialis Anak (Sp.A) / Puskesmas</option>
                            </select>
                        </div>

                        <div>
                            <label class="ant-label">Isi Pesan (Bisa diedit langsung):</label>
                            <textarea rows="6" x-model="waMessage"
                                class="ant-input h-auto leading-relaxed font-sans"></textarea>
                        </div>

                        <div class="mt-4 pt-3 border-t border-ant-split flex items-center justify-between">
                            <button @click="showWhatsAppModal = false" type="button"
                                class="ant-btn ant-btn-default">Batal</button>
                            <button type="button" @click="sendWhatsAppMessage()" :disabled="!waPhone"
                                class="ant-btn ant-btn-success">
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
