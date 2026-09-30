            <!-- ================= MODAL: DETAIL / VIEW IBU HAMIL (ANC) ================= -->
            <div x-show="showViewModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showViewModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-4xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100 max-h-[90vh] flex flex-col">
                        <!-- Header Modal -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 shrink-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-pink-100 text-pink-700 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">Detail Rekam Pelayanan Ibu Hamil
                                        (ANC)</h3>
                                    <p class="text-xs text-slate-500">Informasi lengkap data kohort, pemeriksaan klinis,
                                        lab & skrining Poedji Rochjati</p>
                                </div>
                            </div>
                            <button @click="showViewModal = false"
                                class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer">
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
                                class="p-4 rounded-2xl bg-gradient-to-r from-pink-50 via-rose-50 to-amber-50 border border-pink-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-base font-bold text-slate-900"
                                            x-text="viewingPatient?.nama_lengkap || '-'"></span>
                                        <span class="text-xs text-slate-500"
                                            x-text="viewingPatient?.nama_suami ? '(Suami: ' + viewingPatient.nama_suami + ')' : ''"></span>
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-pink-100 text-pink-700 border border-pink-200"
                                            x-text="'Kunjungan ' + (viewingPatient?.kunjungan_ke || '-')"></span>
                                    </div>
                                    <div class="text-slate-500 text-[11px] mt-1"
                                        x-text="'NIK: ' + (viewingPatient?.nik || '-') + ' • Telp/WA: ' + (viewingPatient?.no_telepon || '-') + ' • BPJS: ' + (viewingPatient?.no_bpjs || '-')">
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Poedji Rochjati Badge -->
                                    <span :class="{
                                        'bg-emerald-100 text-emerald-800 border-emerald-300': viewingPatient?.kategori_poedji_rochjati === 'KRR',
                                        'bg-amber-100 text-amber-800 border-amber-300': viewingPatient?.kategori_poedji_rochjati === 'KRT',
                                        'bg-rose-100 text-rose-800 border-rose-300': viewingPatient?.kategori_poedji_rochjati === 'KRST',
                                        'bg-slate-100 text-slate-700 border-slate-200': !viewingPatient?.kategori_poedji_rochjati
                                    }" class="px-3 py-1 rounded-xl font-bold text-xs border shadow-2xs">
                                        <span
                                            x-text="(viewingPatient?.kategori_poedji_rochjati || 'KRR') + ' (Skor: ' + (viewingPatient?.skor_poedji_rochjati ?? 2) + ')'"></span>
                                    </span>

                                    <!-- Status RISTI Badge -->
                                    <span
                                        :class="viewingPatient?.status_risti && viewingPatient.status_risti.toLowerCase().includes('tinggi') ? 'bg-rose-600 text-white' : 'bg-emerald-600 text-white'"
                                        class="px-3 py-1 rounded-xl font-bold text-xs shadow-2xs"
                                        x-text="viewingPatient?.status_risti && viewingPatient.status_risti.toLowerCase().includes('tinggi') ? 'RISTI' : 'Normal'"></span>
                                </div>
                            </div>

                            <!-- Grid Sections -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                <!-- 1. Identitas & Demografi -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4
                                        class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-pink-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Identitas Pasien & Suami
                                    </h4>
                                    <div class="space-y-1.5 text-[11px]">
                                        <div class="flex justify-between"><span class="text-slate-400">Umur:</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.umur ? viewingPatient.umur + ' Tahun' : '-'"></span>
                                        </div>
                                        <div class="flex justify-between"><span class="text-slate-400">Tgl
                                                Lahir:</span><span class="font-semibold text-slate-700"
                                                x-text="formatDate(viewingPatient?.tanggal_lahir)"></span></div>
                                        <div class="flex justify-between"><span
                                                class="text-slate-400">Pekerjaan:</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.pekerjaan || '-'"></span></div>
                                        <div class="flex justify-between"><span
                                                class="text-slate-400">Pendidikan:</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.pendidikan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Golongan
                                                Darah:</span><span class="font-bold text-rose-600"
                                                x-text="viewingPatient?.golongan_darah || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">No. Rekam
                                                Medis:</span><span class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.no_rekam_medis || '-'"></span></div>
                                    </div>
                                </div>

                                <!-- 2. Riwayat Kehamilan (Obstetri) -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4
                                        class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Riwayat Obstetri & Jadwal
                                    </h4>
                                    <div class="space-y-1.5 text-[11px]">
                                        <div class="flex justify-between"><span class="text-slate-400">Gravida / Para /
                                                Abortus:</span><span class="font-bold text-indigo-700"
                                                x-text="'G' + (viewingPatient?.gravida ?? '-') + ' P' + (viewingPatient?.para ?? '-') + ' A' + (viewingPatient?.abortus ?? '-')"></span>
                                        </div>
                                        <div class="flex justify-between"><span class="text-slate-400">Usia
                                                Kehamilan:</span><span class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.usia_kehamilan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">HPHT:</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="formatDate(viewingPatient?.hpht)"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">HPL (Taksiran
                                                Persalinan):</span><span class="font-bold text-rose-700"
                                                x-text="formatDate(viewingPatient?.hpl)"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Tgl Kunjungan
                                                Terakhir:</span><span class="font-semibold text-slate-700"
                                                x-text="formatDate(viewingPatient?.tanggal_kunjungan)"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Status
                                                Kehamilan:</span><span class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.status_kehamilan || 'Aktif'"></span></div>
                                    </div>
                                </div>

                                <!-- 3. Domisili & Fasyankes -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4
                                        class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                        Wilayah & Fasyankes
                                    </h4>
                                    <div class="space-y-1.5 text-[11px]">
                                        <div class="flex justify-between"><span
                                                class="text-slate-400">Kabupaten:</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.kabupaten || '-'"></span></div>
                                        <div class="flex justify-between"><span
                                                class="text-slate-400">Kecamatan:</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.kecamatan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Kelurahan /
                                                Desa:</span><span class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.kelurahan || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Alamat Lengkap:</span><span
                                                class="font-medium text-slate-700 block mt-0.5"
                                                x-text="viewingPatient?.alamat_lengkap || '-'"></span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pemeriksaan Fisik & Laboratorium -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <!-- Pemeriksaan Fisik (10 T) -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4
                                        class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pemeriksaan Fisik & Antropometri
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div><span class="text-slate-400 block">Berat & Tinggi Badan</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="(viewingPatient?.berat_badan ? viewingPatient.berat_badan + ' kg' : '-') + ' / ' + (viewingPatient?.tinggi_badan ? viewingPatient.tinggi_badan + ' cm' : '-')"></span>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 block">Lingkar Lengan Atas (LiLA)</span>
                                            <span class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.lila ? viewingPatient.lila + ' cm' : '-'"></span>
                                            <template
                                                x-if="viewingPatient?.lila && parseFloat(viewingPatient.lila) < 23.5">
                                                <span
                                                    class="px-1.5 py-0.2 bg-amber-100 text-amber-800 border border-amber-300 rounded text-[9px] font-black inline-block mt-0.5">KEK
                                                    (< 23.5 cm)</span>
                                            </template>
                                        </div>
                                        <div><span class="text-slate-400 block">Tekanan Darah</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="(viewingPatient?.tekanan_darah_sistolik && viewingPatient?.tekanan_darah_diastolik) ? viewingPatient.tekanan_darah_sistolik + '/' + viewingPatient.tekanan_darah_diastolik + ' mmHg' : '-'"></span>
                                        </div>
                                        <div><span class="text-slate-400 block">Tinggi Fundus Uteri (TFU)</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.tinggi_fundus_uteri ? viewingPatient.tinggi_fundus_uteri + ' cm' : '-'"></span>
                                        </div>
                                        <div><span class="text-slate-400 block">Presentasi Janin</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.presentasi_janin || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Denyut Jantung Janin (DJJ)</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.denyut_jantung_janin ? viewingPatient.denyut_jantung_janin + ' x/menit' : '-'"></span>
                                        </div>
                                        <div><span class="text-slate-400 block">Status Imunisasi TT</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.status_imunisasi_tt || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Tablet Tambah Darah (Fe)</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.mendapat_fe ? 'Ya (Diberikan)' : 'Belum'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Laboratorium & Skrining Triple Eliminasi -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4
                                        class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                            </path>
                                        </svg>
                                        Pemeriksaan Laboratorium & Triple Eliminasi
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div>
                                            <span class="text-slate-400 block">Hemoglobin (Hb)</span>
                                            <span class="font-bold text-slate-800"
                                                x-text="viewingPatient?.hb ? viewingPatient.hb + ' g/dL' : '-'"></span>
                                            <span class="text-[10px] block"
                                                :class="viewingPatient?.status_anemia && viewingPatient.status_anemia.toLowerCase().includes('anemia') ? 'text-rose-600 font-bold' : 'text-slate-500'"
                                                x-text="viewingPatient?.status_anemia || ''"></span>
                                        </div>
                                        <div><span class="text-slate-400 block">Gula Darah Sewaktu (GDS)</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.gds ? viewingPatient.gds + ' mg/dL' : '-'"></span>
                                        </div>
                                        <div><span class="text-slate-400 block">Protein Urine</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.protein_urine || 'Negatif (-)'"></span></div>
                                        <div><span class="text-slate-400 block">HBsAg (Hepatitis B)</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.hbsag || 'Non-Reaktif'"></span></div>
                                        <div><span class="text-slate-400 block">HIV</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.hiv_status || 'Non-Reaktif'"></span></div>
                                        <div><span class="text-slate-400 block">Sifilis</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.sifilis_status || 'Non-Reaktif'"></span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rujukan & Kesiapan Persalinan (P4K) -->
                            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/80 space-y-2 text-xs">
                                <h4 class="font-bold text-amber-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Perencanaan Persalinan & Pencegahan Komplikasi (P4K) & Rujukan
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-[11px]">
                                    <div><span class="text-amber-800/70 block">Rekomendasi Faskes Rujukan:</span><span
                                            class="font-bold text-slate-800"
                                            x-text="viewingPatient?.rekomendasi_faskes || 'Bidan / BPM / Puskesmas'"></span>
                                    </div>
                                    <div><span class="text-amber-800/70 block">Calon Pendonor Darah:</span><span
                                            class="font-bold text-rose-700"
                                            x-text="viewingPatient?.calon_pendonor || 'Belum Tercatat'"></span></div>
                                    <div><span class="text-amber-800/70 block">Faktor Risiko / Alasan:</span><span
                                            class="font-medium text-rose-700"
                                            x-text="viewingPatient?.faktor_risiko || viewingPatient?.alasan_rujukan || 'Tidak ada komplikasi mayor'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-2">
                                @can('edit-data')
                                <button @click="showViewModal = false; openEditModal(viewingPatient)"
                                    class="px-3.5 py-2 bg-pink-50 hover:bg-pink-100 text-pink-700 rounded-xl text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit Data
                                </button>
                                @endcan
                                <button @click="showViewModal = false; openWhatsAppModal(viewingPatient)"
                                    class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    Kirim WhatsApp
                                </button>
                                <button @click="showViewModal = false; openAiTriage(viewingPatient)"
                                    class="px-3.5 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-xl text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <span
                                        class="text-[10px] font-black px-1 py-0.5 bg-purple-200 text-purple-800 rounded">AI</span>
                                    AI Triage
                                </button>
                            </div>
                            <button @click="showViewModal = false"
                                class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold cursor-pointer transition-colors">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

