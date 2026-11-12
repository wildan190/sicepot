            <!-- ================= MODAL: DETAIL / VIEW IBU HAMIL (ANC) ================= -->
            <div x-show="showViewModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showViewModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-4xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split max-h-[90vh] flex flex-col">
                        <!-- Header Modal -->
                        <div class="flex items-center justify-between pb-4 border-b border-ant-split shrink-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-ant-lg bg-primary-50 text-primary flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="ant-section-title">Detail Rekam Pelayanan Ibu Hamil
                                        (ANC)</h3>
                                    <p class="ant-text-secondary mt-0.5">Informasi lengkap data kohort, pemeriksaan klinis,
                                        lab & skrining Poedji Rochjati</p>
                                </div>
                            </div>
                            <button @click="showViewModal = false"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Content Modal -->
                        <div class="mt-4 overflow-y-auto space-y-4 pr-1 text-xs">
                            <!-- Status & RISTI Banner -->
                            <div
                                class="p-4 rounded-ant-lg bg-primary-50 border border-primary-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-base font-bold text-ant-title"
                                            x-text="viewingPatient?.nama_lengkap || '-'"></span>
                                        <span class="text-xs text-ant-secondary"
                                            x-text="viewingPatient?.nama_suami ? '(Suami: ' + viewingPatient.nama_suami + ')' : ''"></span>
                                        <span
                                            class="ant-tag ant-tag-blue text-[10px] font-bold"
                                            x-text="'Kunjungan ' + (viewingPatient?.kunjungan_ke || '-')"></span>
                                    </div>
                                    <div class="text-ant-secondary text-[11px] mt-1"
                                        x-text="'NIK: ' + (viewingPatient?.nik || '-') + ' • Telp/WA: ' + (viewingPatient?.no_telepon || '-') + ' • BPJS: ' + (viewingPatient?.no_bpjs || '-')">
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Poedji Rochjati Badge -->
                                    <span :class="{
                                        'bg-success-50 text-success-600 border-success-100': viewingPatient?.kategori_poedji_rochjati === 'KRR',
                                        'bg-warning-50 text-warning-600 border-warning-100': viewingPatient?.kategori_poedji_rochjati === 'KRT',
                                        'bg-danger-50 text-danger-600 border-danger-100': viewingPatient?.kategori_poedji_rochjati === 'KRST',
                                        'bg-ant-split text-ant-text border-ant-border': !viewingPatient?.kategori_poedji_rochjati
                                    }" class="px-3 py-1 rounded-ant font-bold text-xs border">
                                        <span
                                            x-text="(viewingPatient?.kategori_poedji_rochjati || 'KRR') + ' (Skor: ' + (viewingPatient?.skor_poedji_rochjati ?? 2) + ')'"></span>
                                    </span>

                                    <!-- Status RISTI Badge -->
                                    <span
                                        :class="viewingPatient?.status_risti && viewingPatient.status_risti.toLowerCase().includes('tinggi') ? 'bg-danger text-white' : 'bg-success text-white'"
                                        class="px-3 py-1 rounded-ant font-bold text-xs"
                                        x-text="viewingPatient?.status_risti && viewingPatient.status_risti.toLowerCase().includes('tinggi') ? 'RISTI' : 'Normal'"></span>
                                </div>
                            </div>

                            <!-- Grid Sections -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                <!-- 1. Identitas & Demografi -->
                                <div class="p-3.5 bg-[#fafafa] rounded-ant-lg border border-ant-border space-y-2.5">
                                    <h4
                                        class="font-bold text-ant-title flex items-center gap-2 border-b border-ant-split pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Identitas Pasien & Suami
                                    </h4>
                                    <div class="space-y-1.5 text-[11px]">
                                        <div class="flex justify-between"><span class="text-ant-secondary">Umur:</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.umur ? viewingPatient.umur + ' Tahun' : '-'"></span>
                                        </div>
                                        <div class="flex justify-between"><span class="text-ant-secondary">Tgl
                                                Lahir:</span><span class="font-semibold text-ant-text"
                                                x-text="formatDate(viewingPatient?.tanggal_lahir)"></span></div>
                                        <div class="flex justify-between"><span
                                                class="text-ant-secondary">Pekerjaan:</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.pekerjaan || '-'"></span></div>
                                        <div class="flex justify-between"><span
                                                class="text-ant-secondary">Pendidikan:</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.pendidikan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-ant-secondary">Golongan
                                                Darah:</span><span class="font-bold text-danger"
                                                x-text="viewingPatient?.golongan_darah || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-ant-secondary">No. Rekam
                                                Medis:</span><span class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.no_rekam_medis || '-'"></span></div>
                                    </div>
                                </div>

                                <!-- 2. Riwayat Kehamilan (Obstetri) -->
                                <div class="p-3.5 bg-[#fafafa] rounded-ant-lg border border-ant-border space-y-2.5">
                                    <h4
                                        class="font-bold text-ant-title flex items-center gap-2 border-b border-ant-split pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Riwayat Obstetri & Jadwal
                                    </h4>
                                    <div class="space-y-1.5 text-[11px]">
                                        <div class="flex justify-between"><span class="text-ant-secondary">Gravida / Para /
                                                Abortus:</span><span class="font-bold text-primary"
                                                x-text="'G' + (viewingPatient?.gravida ?? '-') + ' P' + (viewingPatient?.para ?? '-') + ' A' + (viewingPatient?.abortus ?? '-')"></span>
                                        </div>
                                        <div class="flex justify-between"><span class="text-ant-secondary">Usia
                                                Kehamilan:</span><span class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.usia_kehamilan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-ant-secondary">HPHT:</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="formatDate(viewingPatient?.hpht)"></span></div>
                                        <div class="flex justify-between"><span class="text-ant-secondary">HPL (Taksiran
                                                Persalinan):</span><span class="font-bold text-danger"
                                                x-text="formatDate(viewingPatient?.hpl)"></span></div>
                                        <div class="flex justify-between"><span class="text-ant-secondary">Tgl Kunjungan
                                                Terakhir:</span><span class="font-semibold text-ant-text"
                                                x-text="formatDate(viewingPatient?.tanggal_kunjungan)"></span></div>
                                        <div class="flex justify-between"><span class="text-ant-secondary">Status
                                                Kehamilan:</span><span class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.status_kehamilan || 'Aktif'"></span></div>
                                    </div>
                                </div>

                                <!-- 3. Domisili & Fasyankes -->
                                <div class="p-3.5 bg-[#fafafa] rounded-ant-lg border border-ant-border space-y-2.5">
                                    <h4
                                        class="font-bold text-ant-title flex items-center gap-2 border-b border-ant-split pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-success-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                        Wilayah & Fasyankes
                                    </h4>
                                    <div class="space-y-1.5 text-[11px]">
                                        <div class="flex justify-between"><span
                                                class="text-ant-secondary">Kabupaten:</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.kabupaten || '-'"></span></div>
                                        <div class="flex justify-between"><span
                                                class="text-ant-secondary">Kecamatan:</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.kecamatan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-ant-secondary">Kelurahan /
                                                Desa:</span><span class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.kelurahan || '-'"></span></div>
                                        <div><span class="text-ant-secondary block">Alamat Lengkap:</span><span
                                                class="font-medium text-ant-text block mt-0.5"
                                                x-text="viewingPatient?.alamat_lengkap || '-'"></span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pemeriksaan Fisik & Laboratorium -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <!-- Pemeriksaan Fisik (10 T) -->
                                <div class="p-3.5 bg-[#fafafa] rounded-ant-lg border border-ant-border space-y-2.5">
                                    <h4
                                        class="font-bold text-ant-title flex items-center gap-2 border-b border-ant-split pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-warning-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pemeriksaan Fisik & Antropometri
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div><span class="text-ant-secondary block">Berat & Tinggi Badan</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="(viewingPatient?.berat_badan ? viewingPatient.berat_badan + ' kg' : '-') + ' / ' + (viewingPatient?.tinggi_badan ? viewingPatient.tinggi_badan + ' cm' : '-')"></span>
                                        </div>
                                        <div>
                                            <span class="text-ant-secondary block">Lingkar Lengan Atas (LiLA)</span>
                                            <span class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.lila ? viewingPatient.lila + ' cm' : '-'"></span>
                                            <template
                                                x-if="viewingPatient?.lila && parseFloat(viewingPatient.lila) < 23.5">
                                                <span
                                                    class="ant-tag ant-tag-gold text-[9px] font-black mt-0.5">KEK
                                                    (< 23.5 cm)</span>
                                            </template>
                                        </div>
                                        <div><span class="text-ant-secondary block">Tekanan Darah</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="(viewingPatient?.tekanan_darah_sistolik && viewingPatient?.tekanan_darah_diastolik) ? viewingPatient.tekanan_darah_sistolik + '/' + viewingPatient.tekanan_darah_diastolik + ' mmHg' : '-'"></span>
                                        </div>
                                        <div><span class="text-ant-secondary block">Tinggi Fundus Uteri (TFU)</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.tinggi_fundus_uteri ? viewingPatient.tinggi_fundus_uteri + ' cm' : '-'"></span>
                                        </div>
                                        <div><span class="text-ant-secondary block">Presentasi Janin</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.presentasi_janin || '-'"></span></div>
                                        <div><span class="text-ant-secondary block">Denyut Jantung Janin (DJJ)</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.denyut_jantung_janin ? viewingPatient.denyut_jantung_janin + ' x/menit' : '-'"></span>
                                        </div>
                                        <div><span class="text-ant-secondary block">Status Imunisasi TT</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.status_imunisasi_tt || '-'"></span></div>
                                        <div><span class="text-ant-secondary block">Tablet Tambah Darah (Fe)</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.mendapat_fe ? 'Ya (Diberikan)' : 'Belum'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Laboratorium & Skrining Triple Eliminasi -->
                                <div class="p-3.5 bg-[#fafafa] rounded-ant-lg border border-ant-border space-y-2.5">
                                    <h4
                                        class="font-bold text-ant-title flex items-center gap-2 border-b border-ant-split pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-danger" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                            </path>
                                        </svg>
                                        Pemeriksaan Laboratorium & Triple Eliminasi
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div>
                                            <span class="text-ant-secondary block">Hemoglobin (Hb)</span>
                                            <span class="font-bold text-ant-title"
                                                x-text="viewingPatient?.hb ? viewingPatient.hb + ' g/dL' : '-'"></span>
                                            <span class="text-[10px] block"
                                                :class="viewingPatient?.status_anemia && viewingPatient.status_anemia.toLowerCase().includes('anemia') ? 'text-danger font-bold' : 'text-ant-secondary'"
                                                x-text="viewingPatient?.status_anemia || ''"></span>
                                        </div>
                                        <div><span class="text-ant-secondary block">Gula Darah Sewaktu (GDS)</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.gds ? viewingPatient.gds + ' mg/dL' : '-'"></span>
                                        </div>
                                        <div><span class="text-ant-secondary block">Protein Urine</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.protein_urine || 'Negatif (-)'"></span></div>
                                        <div><span class="text-ant-secondary block">HBsAg (Hepatitis B)</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.hbsag || 'Non-Reaktif'"></span></div>
                                        <div><span class="text-ant-secondary block">HIV</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.hiv_status || 'Non-Reaktif'"></span></div>
                                        <div><span class="text-ant-secondary block">Sifilis</span><span
                                                class="font-semibold text-ant-text"
                                                x-text="viewingPatient?.sifilis_status || 'Non-Reaktif'"></span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rujukan & Kesiapan Persalinan (P4K) -->
                            <div class="p-4 rounded-ant-lg bg-warning-50 border border-warning-100 space-y-2 text-xs">
                                <h4 class="font-bold text-warning-600 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-warning-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Perencanaan Persalinan & Pencegahan Komplikasi (P4K) & Rujukan
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-[11px]">
                                    <div><span class="text-warning-600 block">Rekomendasi Faskes Rujukan:</span><span
                                            class="font-bold text-ant-title"
                                            x-text="viewingPatient?.rekomendasi_faskes || 'Bidan / BPM / Puskesmas'"></span>
                                    </div>
                                    <div><span class="text-warning-600 block">Calon Pendonor Darah:</span><span
                                            class="font-bold text-danger"
                                            x-text="viewingPatient?.calon_pendonor || 'Belum Tercatat'"></span></div>
                                    <div><span class="text-warning-600 block">Faktor Risiko / Alasan:</span><span
                                            class="font-medium text-danger"
                                            x-text="viewingPatient?.faktor_risiko || viewingPatient?.alasan_rujukan || 'Tidak ada komplikasi mayor'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="mt-6 pt-3 border-t border-ant-split flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-2">
                                @can('edit-data')
                                <button @click="showViewModal = false; openEditModal(viewingPatient)"
                                    class="px-3.5 py-2 bg-primary-50 hover:bg-primary-100 text-primary rounded-ant text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit Data
                                </button>
                                @endcan
                                <button @click="showViewModal = false; openWhatsAppModal(viewingPatient)"
                                    class="px-3.5 py-2 bg-success-50 hover:bg-success-100 text-success-600 rounded-ant text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    Kirim WhatsApp
                                </button>
                                <button @click="showViewModal = false; openAiTriage(viewingPatient)"
                                    class="px-3.5 py-2 bg-[#f9f0ff] hover:bg-[#efdbff] text-[#722ed1] rounded-ant text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <span
                                        class="text-[10px] font-black px-1 py-0.5 bg-[#efdbff] text-[#531dab] rounded-ant-sm">AI</span>
                                    AI Triage
                                </button>
                            </div>
                            <button @click="showViewModal = false"
                                class="ant-btn ant-btn-default">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
