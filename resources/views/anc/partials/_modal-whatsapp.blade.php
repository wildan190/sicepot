            <!-- ================= MODAL: WHATSAPP DIRECT SENDER ================= -->
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
                                    <h3 class="ant-section-title">Kirim Pengingat WhatsApp (wa.me)</h3>
                                    <p class="ant-text-secondary mt-0.5" x-text="targetPatient?.nama_lengkap"></p>
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

                        <div class="mt-4 space-y-3.5 text-xs">
                            <div>
                                <label class="ant-label">Pilih Target Penerima:</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="waRecipient = 'ibu'; prepareWaMessage()"
                                        :class="waRecipient === 'ibu' ? 'bg-success-50 border-success text-success-600 font-bold ring-2 ring-success-100' : 'bg-[#fafafa] border-ant-border text-ant-text'"
                                        class="p-3 rounded-ant border text-left cursor-pointer transition-all">
                                        <div class="font-bold">Ibu Hamil</div>
                                        <div class="text-[11px] text-ant-secondary mt-0.5"
                                            x-text="targetPatient?.no_telepon || '(Belum ada no)'"></div>
                                    </button>
                                    <button type="button" @click="waRecipient = 'suami'; prepareWaMessage()"
                                        :class="waRecipient === 'suami' ? 'bg-success-50 border-success text-success-600 font-bold ring-2 ring-success-100' : 'bg-[#fafafa] border-ant-border text-ant-text'"
                                        class="p-3 rounded-ant border text-left cursor-pointer transition-all">
                                        <div class="font-bold">Suami SIAGA (P4K)</div>
                                        <div class="text-[11px] text-ant-secondary mt-0.5"
                                            x-text="targetPatient?.nama_suami ? 'Tn. ' + targetPatient.nama_suami : '(Nama suami belum ada)'">
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="ant-label">Nomor WhatsApp Tujuan:</label>
                                <input type="text" x-model="waPhone" placeholder="Contoh: 08123456789 atau 628123456789"
                                    class="ant-input font-mono">
                            </div>

                            <div>
                                <label class="ant-label">Pilih Template Pesan:</label>
                                <select x-model="waTemplateType" @change="prepareWaMessage()"
                                    class="ant-select">
                                    <template x-if="waRecipient === 'ibu'">
                                        <optgroup label="Template Ibu Hamil">
                                            <option value="anc_checkup">Pengingat Jadwal Kontrol ANC</option>
                                            <option value="h1_alert">Peringatan Siaga HPL / Menjelang Persalinan
                                            </option>
                                            <option value="kek_nutrition">Edukasi Gizi & Kepatuhan TTD (Bumil KEK)
                                            </option>
                                        </optgroup>
                                    </template>
                                    <template x-if="waRecipient === 'suami'">
                                        <optgroup label="Template Suami SIAGA">
                                            <option value="suami_p4k">Siaga Persalinan, Transportasi & Donor Darah
                                            </option>
                                        </optgroup>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="ant-label">Isi Pesan (Bisa diedit):</label>
                                <textarea rows="5" x-model="waMessage"
                                    class="ant-input !h-auto py-2 resize-none"></textarea>
                            </div>

                            <div class="mt-4 pt-3 border-t border-ant-split flex items-center justify-between">
                                <button @click="showWhatsAppModal = false" type="button"
                                    class="ant-btn ant-btn-default">Batal</button>
                                <button type="button" @click="sendWhatsAppMessage()" :disabled="!waPhone"
                                    class="ant-btn ant-btn-success flex items-center gap-2">
                                    <span>Buka WhatsApp Web / App ↗</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
