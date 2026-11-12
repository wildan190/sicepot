            <!-- ================= MODAL: WHATSAPP DIRECT REMINDER (wa.me) ================= -->
            <div x-show="showWhatsAppModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showWhatsAppModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                        <div class="flex items-center justify-between pb-3 border-b border-ant-split">
                            <div class="flex items-center gap-2.5">
                                <span class="p-2 rounded-ant bg-success-50 text-success-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="ant-section-title">Kirim Pengingat WhatsApp Langsung
                                    </h3>
                                    <p class="text-xs text-ant-secondary">Integrasi pesan resmi SICEPOT via WhatsApp (wa.me)
                                    </p>
                                </div>
                            </div>
                            <button @click="showWhatsAppModal = false"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-4 text-xs">
                            <div>
                                <label class="ant-label">Pasien TBC Terpilih:</label>
                                <div class="p-3 bg-[#fafafa] border border-ant-split rounded-ant">
                                    <div class="font-bold text-ant-title" x-text="targetPatient?.nama_lengkap || '-'">
                                    </div>
                                    <div class="text-[11px] text-ant-secondary mt-0.5"
                                        x-text="'Kelurahan: ' + (targetPatient?.kelurahan || '-') + ' | Diagnosis: ' + (targetPatient?.hasil_diagnosis || targetPatient?.hasil_tcm || '-')">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="ant-label">Nomor WhatsApp Pasien / Pengawas
                                    Minum Obat (PMO):</label>
                                <input type="text" x-model="waPhone" placeholder="Contoh: 08123456789 atau 628123456789"
                                    class="ant-input font-mono">
                            </div>

                            <div>
                                <label class="ant-label">Pilih Template Pesan TBC:</label>
                                <select x-model="waTemplateType" @change="prepareWaMessage()"
                                    class="ant-select">
                                    <option value="oat_daily">Pengingat Minum Obat Harian (Kepatuhan OAT)</option>
                                    <option value="sputum_eval">Jadwal Evaluasi Dahak Akhir Bulan Ke-2/5/6</option>
                                    <option value="dropout_warning">Peringatan Mangkir Berobat / Drop Out TBC (Kritis)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="ant-label">Isi Pesan (Bisa
                                    disesuaikan):</label>
                                <textarea rows="5" x-model="waMessage"
                                    class="ant-input h-auto py-1.5"></textarea>
                            </div>

                            <div class="mt-4 pt-3 border-t border-ant-split flex items-center justify-between">
                                <button @click="showWhatsAppModal = false" type="button"
                                    class="ant-btn ant-btn-default">Batal</button>
                                <button type="button" @click="sendWhatsAppMessage()" :disabled="!waPhone"
                                    class="ant-btn ant-btn-success">
                                    <span>Buka WhatsApp Web / App ↗</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
