            <!-- ================= MODAL: DETAIL / VIEW PASIEN TBC ================= -->
            <div x-show="showViewModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showViewModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-3xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100 max-h-[90vh] flex flex-col">
                        <!-- Header Modal -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 shrink-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">Detail Rekam Pasien TBC</h3>
                                    <p class="text-xs text-slate-500">Informasi lengkap data register SITB TB-03 / TB-06
                                    </p>
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
                            <!-- Status Banner -->
                            <div
                                class="p-4 rounded-2xl bg-gradient-to-r from-sky-50 via-blue-50 to-indigo-50 border border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-base font-bold text-slate-900"
                                            x-text="viewingPatient?.nama_lengkap || '-'"></span>
                                        <span
                                            :class="viewingPatient?.report_type === 'tb_03' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-amber-100 text-amber-700 border-amber-200'"
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold border"
                                            x-text="viewingPatient?.report_type === 'tb_03' ? 'Register TB-03 SO' : 'Register TB-06 Terduga'"></span>
                                    </div>
                                    <div class="text-slate-500 text-[11px] mt-0.5"
                                        x-text="'NIK: ' + (viewingPatient?.nik || '-') + ' • No. BPJS: ' + (viewingPatient?.no_bpjs || '-')">
                                    </div>
                                </div>
                                <div>
                                    <span :class="{
                                            'bg-emerald-100 text-emerald-800 border-emerald-300': viewingPatient?.hasil_akhir_pengobatan && (viewingPatient.hasil_akhir_pengobatan.includes('Sembuh') || viewingPatient.hasil_akhir_pengobatan.includes('Lengkap')),
                                            'bg-rose-100 text-rose-800 border-rose-300': viewingPatient?.hasil_akhir_pengobatan && (viewingPatient.hasil_akhir_pengobatan.includes('Putus') || viewingPatient.hasil_akhir_pengobatan.includes('Meninggal') || viewingPatient.hasil_akhir_pengobatan.includes('Gagal')),
                                            'bg-blue-100 text-blue-800 border-blue-300': !viewingPatient?.hasil_akhir_pengobatan
                                        }"
                                        class="px-3 py-1 rounded-xl font-bold text-xs border inline-flex items-center gap-1.5 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                            :class="viewingPatient?.hasil_akhir_pengobatan && viewingPatient.hasil_akhir_pengobatan.includes('Sembuh') ? 'bg-emerald-500' : 'bg-blue-500'"></span>
                                        <span
                                            x-text="viewingPatient?.hasil_akhir_pengobatan || viewingPatient?.status_pengobatan || (viewingPatient?.report_type === 'tb_03' ? 'Dalam Pengobatan' : 'Observasi')"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Grid Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Profil & Demografi -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4
                                        class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Identitas & Demografi
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div><span class="text-slate-400 block">Jenis Kelamin</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.jenis_kelamin === 'L' ? 'Laki-laki' : (viewingPatient?.jenis_kelamin === 'P' ? 'Perempuan' : '-')"></span>
                                        </div>
                                        <div><span class="text-slate-400 block">Umur</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.umur ? viewingPatient.umur + ' Tahun' : '-'"></span>
                                        </div>
                                        <div><span class="text-slate-400 block">Pekerjaan</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.pekerjaan || '-'"></span></div>
                                        <div><span class="text-slate-400 block">No. Rekam Medis</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.no_rekam_medis || '-'"></span></div>
                                    </div>
                                </div>

                                <!-- Registrasi & Fasyankes -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4
                                        class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Registrasi SITB & Fasyankes
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div><span class="text-slate-400 block">No. Reg SITB</span><span
                                                class="font-semibold text-indigo-700"
                                                x-text="viewingPatient?.no_reg_sitb || '-'"></span></div>
                                        <div><span class="text-slate-400 block">No. Reg Terduga</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.no_reg_terduga || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Fasyankes</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.fasyankes_name || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Bulan / Periode</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.bulan || '-'"></span></div>
                                    </div>
                                </div>

                                <!-- Wilayah Pasien -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4
                                        class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Domisili & Wilayah Kerja
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div><span class="text-slate-400 block">Kabupaten / Kota</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.kabupaten || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Kecamatan</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.kecamatan || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Kelurahan / Desa</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.kelurahan || '-'"></span></div>
                                        <div class="col-span-2"><span class="text-slate-400 block">Alamat
                                                Lengkap</span><span class="font-medium text-slate-700"
                                                x-text="viewingPatient?.alamat_lengkap || '-'"></span></div>
                                    </div>
                                </div>

                                <!-- Klinis & Laboratorium -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4
                                        class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                            </path>
                                        </svg>
                                        Hasil Pemeriksaan & Diagnosis
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div><span class="text-slate-400 block">Hasil Diagnosis</span><span
                                                class="font-bold text-slate-800"
                                                x-text="viewingPatient?.hasil_diagnosis || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Tipe Diagnosis</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.tipe_diagnosis || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Hasil TCM</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.hasil_tcm || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Mikroskopis / BTA</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.hasil_mikroskopis || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Lokasi Anatomi</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.lokasi_anatomi || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Riwayat Pengobatan</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.riwayat_pengobatan || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Status HIV</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.status_hiv || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Riwayat DM</span><span
                                                class="font-semibold text-slate-700"
                                                x-text="viewingPatient?.riwayat_dm || '-'"></span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pengobatan Info -->
                            <div
                                class="p-3.5 bg-blue-50/50 rounded-2xl border border-blue-100 flex flex-wrap items-center justify-between gap-3 text-[11px]">
                                <div>
                                    <span class="text-slate-400 block">Tanggal Daftar</span>
                                    <span class="font-bold text-slate-700"
                                        x-text="formatDate(viewingPatient?.tanggal_daftar)"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Mulai Pengobatan OAT</span>
                                    <span class="font-bold text-blue-700"
                                        x-text="formatDate(viewingPatient?.tanggal_mulai_pengobatan)"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Hasil Akhir Pengobatan</span>
                                    <span class="font-bold text-slate-800"
                                        x-text="viewingPatient?.hasil_akhir_pengobatan || 'Masih Terapi Aktif'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-2">
                                @can('edit-data')
                                <button @click="showViewModal = false; openEditModal(viewingPatient)"
                                    class="px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit Data
                                </button>
                                @endcan
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

