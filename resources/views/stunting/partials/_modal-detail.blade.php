        <!-- ================= MODAL: DETAIL / VIEW BALITA ================= -->
        <div x-show="showViewModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                <div @click="showViewModal = false"
                    class="fixed inset-0 bg-black/45 backdrop-blur-xs transition-opacity"></div>
                <div
                    class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split flex flex-col max-h-[90vh]">
                    <div class="flex items-center justify-between pb-4 border-b border-ant-split shrink-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-ant-lg bg-success-100 text-success-700 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-ant-title">Detail Rekam Balita</h3>
                                <p class="text-xs text-ant-secondary">Informasi lengkap pemantauan pertumbuhan e-PPGBM</p>
                            </div>
                        </div>
                        <button @click="showViewModal = false"
                            class="p-1.5 text-ant-secondary hover:text-ant-text rounded-ant hover:bg-ant-split cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4 overflow-y-auto space-y-4 pr-1 text-xs">
                        <div
                            class="p-4 rounded-ant-lg bg-success-50 border border-success-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-base font-bold text-ant-title"
                                        x-text="viewingPatient?.nama || '-'"></span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border"
                                        :class="viewingPatient?.jenis_kelamin === 'L' ? 'bg-primary-50 text-primary-700 border-primary-100' : 'bg-[#f9f0ff] text-[#531dab] border-[#efdbff]'"
                                        x-text="viewingPatient?.jenis_kelamin === 'L' ? 'Laki-laki' : (viewingPatient?.jenis_kelamin === 'P' ? 'Perempuan' : '-')"></span>
                                </div>
                                <div class="text-ant-secondary text-[11px] mt-0.5"
                                    x-text="'NIK: ' + (viewingPatient?.nik || '-') + ' • Ortu: ' + (viewingPatient?.nama_ortu || '-')">
                                </div>
                            </div>
                            <div>
                                <span
                                    class="px-3 py-1 rounded-ant font-bold text-xs border inline-flex items-center gap-1.5"
                                    :class="getTbuBadgeClass(viewingPatient?.tbu_kategori)">
                                    <span x-text="'Status TB/U: ' + (viewingPatient?.tbu_kategori || '-')"></span>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-3.5 bg-ant-layout rounded-ant-lg border border-ant-split space-y-2.5">
                                <h4
                                    class="font-bold text-ant-title flex items-center gap-2 border-b border-ant-split pb-1.5 text-xs">
                                    Identitas & Wilayah
                                </h4>
                                <div class="grid grid-cols-2 gap-2 text-[11px]">
                                    <div><span class="text-ant-secondary block">Tgl Lahir</span><span
                                            class="font-semibold text-ant-text"
                                            x-text="formatDate(viewingPatient?.tanggal_lahir)"></span></div>
                                    <div><span class="text-ant-secondary block">Usia Ukur</span><span
                                            class="font-semibold text-ant-text"
                                            x-text="(viewingPatient?.usia_saat_ukur || '-') + ' Bulan'"></span></div>
                                    <div><span class="text-ant-secondary block">Desa</span><span
                                            class="font-semibold text-ant-text"
                                            x-text="viewingPatient?.desa || '-'"></span></div>
                                    <div><span class="text-ant-secondary block">Posyandu</span><span
                                            class="font-semibold text-ant-text"
                                            x-text="viewingPatient?.posyandu || '-'"></span></div>
                                    <div class="col-span-2"><span class="text-ant-secondary block">Alamat</span><span
                                            class="font-medium text-ant-text"
                                            x-text="viewingPatient?.alamat || '-'"></span></div>
                                </div>
                            </div>

                            <div class="p-3.5 bg-ant-layout rounded-ant-lg border border-ant-split space-y-2.5">
                                <h4
                                    class="font-bold text-ant-title flex items-center gap-2 border-b border-ant-split pb-1.5 text-xs">
                                    Hasil Pengukuran Antropometri
                                </h4>
                                <div class="grid grid-cols-2 gap-2 text-[11px]">
                                    <div><span class="text-ant-secondary block">Berat Badan</span><span
                                            class="font-bold text-ant-title"
                                            x-text="viewingPatient?.berat ? Number(viewingPatient.berat).toFixed(2) + ' kg' : '-'"></span>
                                    </div>
                                    <div><span class="text-ant-secondary block">Tinggi Badan</span><span
                                            class="font-bold text-ant-title"
                                            x-text="viewingPatient?.tinggi ? Number(viewingPatient.tinggi).toFixed(1) + ' cm' : '-'"></span>
                                    </div>
                                    <div><span class="text-ant-secondary block">LiLA</span><span
                                            class="font-semibold text-ant-text"
                                            x-text="viewingPatient?.lila ? Number(viewingPatient.lila).toFixed(1) + ' cm' : '-'"></span>
                                    </div>
                                    <div><span class="text-ant-secondary block">Tgl Pengukuran</span><span
                                            class="font-semibold text-ant-text"
                                            x-text="formatDate(viewingPatient?.tanggal_pengukuran)"></span></div>
                                    <div><span class="text-ant-secondary block">Kategori BB/U</span><span
                                            class="font-semibold text-ant-text"
                                            x-text="viewingPatient?.bbu_kategori || '-'"></span></div>
                                    <div><span class="text-ant-secondary block">Kategori BB/TB</span><span
                                            class="font-semibold text-ant-text"
                                            x-text="viewingPatient?.bbtb_kategori || '-'"></span></div>
                                </div>
                            </div>

                            <div
                                class="col-span-1 md:col-span-2 p-3.5 bg-ant-layout rounded-ant-lg border border-ant-split space-y-2.5">
                                <h4
                                    class="font-bold text-ant-title flex items-center gap-2 border-b border-ant-split pb-1.5 text-xs">
                                    Skrining Klinis &amp; Layanan Intervensi Spesifik
                                </h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-[11px]">
                                    <div class="p-2 bg-white rounded-ant border border-danger-100">
                                        <span class="text-danger-600 block font-semibold">Test Hemoglobin</span>
                                        <span class="font-bold text-ant-title"
                                            x-text="viewingPatient?.test_hemoglobin || '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white rounded-ant border border-primary-100">
                                        <span class="text-primary-600 block font-semibold">Test Mantoux</span>
                                        <span class="font-bold text-ant-title"
                                            x-text="viewingPatient?.test_mantoux || '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white rounded-ant border border-primary-100">
                                        <span class="text-primary-600 block font-semibold">Konsul Dokter Sp.A</span>
                                        <span class="font-bold text-ant-title"
                                            x-text="viewingPatient?.konsul_spa || '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white rounded-ant border border-warning-100">
                                        <span class="text-warning-600 block font-semibold">Suplementasi Vit A</span>
                                        <span class="font-bold text-ant-title"
                                            x-text="viewingPatient?.jml_vit_a ? viewingPatient.jml_vit_a + ' Kapsul' : '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white rounded-ant border border-[#efdbff]">
                                        <span class="text-[#722ed1] block font-semibold">Kelas Ibu Balita</span>
                                        <span class="font-bold text-ant-title"
                                            x-text="viewingPatient?.kelas_ibu || '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white rounded-ant border border-success-100">
                                        <span class="text-success-600 block font-semibold">Naik Berat Badan</span>
                                        <span class="font-bold text-ant-title"
                                            x-text="viewingPatient?.naik_berat_badan === 'N' ? 'Naik (N)' : (viewingPatient?.naik_berat_badan === 'T' ? 'Turun (T)' : (viewingPatient?.naik_berat_badan || '-'))"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-3 border-t border-ant-split flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-2">
                            <!-- Tombol WhatsApp dari Modal Detail -->
                            <button @click="showViewModal = false; openWhatsAppModal(viewingPatient)" type="button"
                                class="ant-btn ant-btn-default">
                                <svg class="w-3.5 h-3.5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <span>Kirim WhatsApp</span>
                            </button>

                            @can('edit-data')
                            <button @click="showViewModal = false; openEditModal(viewingPatient)"
                                class="ant-btn ant-btn-default">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit Data
                            </button>
                            @endcan
                        </div>
                        <button @click="showViewModal = false"
                            class="ant-btn ant-btn-default">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
