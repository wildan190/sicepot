                <!-- DATA TABLE SECTION -->
                <div class="ant-card overflow-hidden">
                    <div
                        class="p-5 border-b border-ant-split flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="ant-section-title">Daftar Rekam Pasien & Terduga TBC</h3>
                            <p class="text-xs text-ant-secondary">Data dapat diedit secara langsung (real-time) melalui
                                tombol Edit pada setiap baris</p>
                        </div>
                        <span
                            class="text-xs font-medium px-3 py-1.5 bg-[#fafafa] text-ant-text rounded-ant self-start sm:self-auto"
                            x-text="'Menampilkan halaman saat ini (' + (patientsData.data ? patientsData.data.length : 0) + ' dari ' + patientsData.total + ' total data)'"></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="ant-table w-full">
                            <thead
                                class="uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Tipe</th>
                                    <th class="px-4 py-3">Nama Lengkap & NIK</th>
                                    <th class="px-4 py-3">No. SITB / Terduga</th>
                                    <th class="px-4 py-3">L/P & Umur</th>
                                    <th class="px-4 py-3">Wilayah (Kab / Kel)</th>
                                    <th class="px-4 py-3">Hasil TCM / Diagnosis</th>
                                    <th class="px-4 py-3">Status / Hasil Akhir</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="p in (patientsData.data || [])" :key="p.id">
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span :class="{
                                                    'bg-primary-50 text-primary-600 border-primary-100': p.report_type === 'tb_03',
                                                    'bg-success-50 text-success-600 border-success-100': p.report_type === 'skrining' || p.nama_pelapor || p.batuk_2_minggu,
                                                    'bg-warning-50 text-warning-600 border-warning-100': p.report_type !== 'tb_03' && p.report_type !== 'skrining' && !p.nama_pelapor && !p.batuk_2_minggu
                                                }" class="px-2 py-0.5 rounded-ant font-semibold text-[11px] border"
                                                x-text="p.report_type === 'tb_03' ? 'TB-03 SO' : ((p.report_type === 'skrining' || p.nama_pelapor || p.batuk_2_minggu) ? 'Skrining' : 'TB-06')"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-ant-title text-sm"
                                                x-text="p.nama_lengkap || '-'"></div>
                                            <div class="text-ant-secondary text-[11px]" x-text="'NIK: ' + (p.nik || '-')">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-ant-text">
                                            <div
                                                x-text="p.no_reg_sitb ? 'SITB: ' + p.no_reg_sitb : (p.no_reg_terduga ? 'Terduga: ' + p.no_reg_terduga : '-')">
                                            </div>
                                            <div class="text-[11px] text-ant-secondary"
                                                x-text="p.bulan ? 'Bulan: ' + p.bulan : ''"></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-ant-text">
                                            <span class="font-medium"
                                                x-text="(p.jenis_kelamin || '-') + ' / ' + (p.umur !== null ? p.umur + ' th' : '-')"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-ant-text" x-text="p.kelurahan || '-'"></div>
                                            <div class="text-[11px] text-ant-secondary" x-text="p.kabupaten || '-'"></div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-ant-text"
                                                x-text="p.hasil_diagnosis || p.hasil_tcm || '-'"></div>
                                            <div class="text-[11px] text-ant-secondary" x-text="p.tipe_diagnosis || ''">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <template x-if="p.hasil_akhir_pengobatan">
                                                <span :class="{
                                                    'bg-success-50 text-success-600 border-success-100': p.hasil_akhir_pengobatan.includes('Sembuh') || p.hasil_akhir_pengobatan.includes('Lengkap'),
                                                    'bg-danger-50 text-danger-600 border-danger-100': p.hasil_akhir_pengobatan.includes('Putus') || p.hasil_akhir_pengobatan.includes('Meninggal') || p.hasil_akhir_pengobatan.includes('Gagal'),
                                                    'bg-ant-split text-ant-text border-ant-border': !p.hasil_akhir_pengobatan.includes('Sembuh') && !p.hasil_akhir_pengobatan.includes('Putus')
                                                }" class="px-2 py-1 rounded-ant text-[11px] font-semibold border"
                                                    x-text="p.hasil_akhir_pengobatan"></span>
                                            </template>
                                            <template x-if="!p.hasil_akhir_pengobatan">
                                                <span
                                                    class="px-2 py-1 bg-primary-50 text-primary-600 border border-primary-100 rounded-ant text-[11px] font-medium"
                                                    x-text="p.report_type === 'tb_03' ? 'Dalam Pengobatan' : (p.status_pengobatan || 'Observasi')"></span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <!-- View / Detail Pasien Modal -->
                                                <button @click="openViewModal(p)" type="button"
                                                    class="p-1.5 text-primary hover:text-primary-600 hover:bg-primary-50 rounded-ant transition-colors cursor-pointer"
                                                    title="Lihat Rekam Data Lengkap Pasien">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- WhatsApp Direct Reminder -->
                                                <button @click="openWhatsAppModal(p)" type="button"
                                                    class="p-1.5 text-success-600 hover:text-success-700 hover:bg-success-50 rounded-ant transition-colors cursor-pointer"
                                                    title="Kirim Pesan WhatsApp (wa.me)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- Skrining Duplikasi -->
                                                <button @click="openDuplicateModal(p)" type="button"
                                                    class="p-1.5 text-warning-600 hover:text-warning-700 hover:bg-warning-50 rounded-ant transition-colors cursor-pointer"
                                                    title="Skrining Data Ganda / Lintas Faskes">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- Rekam Jejak Pengobatan (Treatment Timeline) -->
                                                <button @click="openTimelineModal(p)" type="button"
                                                    class="p-1.5 text-primary hover:text-primary-600 hover:bg-primary-50 rounded-ant transition-colors cursor-pointer"
                                                    title="Riwayat Perjalanan Pengobatan TBC">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>

                                                <!-- AI Triage (Google Gemini) -->
                                                <button @click="openAiTriage(p)" type="button"
                                                    class="p-1.5 text-[#531dab] hover:text-[#531dab] hover:bg-[#f9f0ff] rounded-ant transition-colors cursor-pointer"
                                                    title="AI Triage & Evaluasi Pengobatan (Google Gemini)">
                                                    <span
                                                        class="text-xs font-black px-1 py-0.5 bg-[#f9f0ff] text-[#531dab] rounded">AI</span>
                                                </button>

                                                @can('edit-data')
                                                <!-- Edit Pasien -->
                                                <button @click="openEditModal(p)" type="button"
                                                    class="p-1.5 text-primary hover:text-primary-600 hover:bg-primary-50 rounded-ant transition-colors cursor-pointer"
                                                    title="Edit Data Realtime">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                @endcan
                                                @can('delete-data')
                                                <button @click="deletePatient(p)" type="button"
                                                    class="p-1.5 text-danger hover:text-danger-600 hover:bg-danger-50 rounded-ant transition-colors cursor-pointer"
                                                    title="Hapus Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="!patientsData.data || patientsData.data.length === 0">
                                    <tr>
                                        <td colspan="8" class="text-center py-8 text-ant-secondary">
                                            Tidak ada data yang cocok dengan filter yang dipilih.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION CONTROLS -->
                    <div
                        class="px-5 py-3.5 bg-[#fafafa] border-t border-ant-split flex items-center justify-between text-xs text-ant-text">
                        <span
                            x-text="'Halaman ' + (patientsData.current_page || 1) + ' dari ' + (patientsData.last_page || 1)"></span>
                        <div class="flex items-center gap-2">
                            <button :disabled="(patientsData.current_page || 1) <= 1"
                                @click="changePage((patientsData.current_page || 1) - 1)"
                                class="ant-btn ant-btn-default">Sebelumnya</button>
                            <button :disabled="(patientsData.current_page || 1) >= (patientsData.last_page || 1)"
                                @click="changePage((patientsData.current_page || 1) + 1)"
                                class="ant-btn ant-btn-default">Berikutnya</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


