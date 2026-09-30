                <!-- DATA TABLE SECTION -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
                    <div
                        class="p-5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Daftar Rekam Pemeriksaan Ibu Hamil (ANC)</h3>
                            <p class="text-xs text-slate-500">Data dapat di-edit langsung secara realtime melalui tombol
                                aksi di setiap baris</p>
                        </div>
                        <span
                            class="text-xs font-medium px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg self-start sm:self-auto"
                            x-text="'Menampilkan (' + (patientsData.data ? patientsData.data.length : 0) + ' dari ' + patientsData.total + ' total rekam data)'"></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead
                                class="bg-slate-50/80 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200/80">
                                <tr>
                                    <th class="px-4 py-3">Nama & NIK</th>
                                    <th class="px-4 py-3">Nama Suami & Telp</th>
                                    <th class="px-4 py-3">Umur & G-P-A</th>
                                    <th class="px-4 py-3">Usia Hamil & Kunjungan</th>
                                    <th class="px-4 py-3">HPL / TP</th>
                                    <th class="px-4 py-3">Wilayah (Kab / Kel)</th>
                                    <th class="px-4 py-3">LiLA & Hb</th>
                                    <th class="px-4 py-3">Status RISTI & Risiko</th>
                                    <th class="px-4 py-3">Poedji Rochjati</th>
                                    <th class="px-4 py-3">Rekomendasi Faskes</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="p in (patientsData.data || [])" :key="p.id">
                                    <tr class="hover:bg-pink-50/20 transition-colors">
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-800 text-sm"
                                                x-text="p.nama_lengkap || '-'"></div>
                                            <div class="text-slate-400 text-[11px]" x-text="'NIK: ' + (p.nik || '-')">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            <div class="font-medium text-slate-700"
                                                x-text="p.nama_suami ? 'Tn. ' + p.nama_suami : '-'"></div>
                                            <template x-if="p.no_telepon">
                                                <div
                                                    class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1 mt-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                        </path>
                                                    </svg>
                                                    <span x-text="p.no_telepon"></span>
                                                </div>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                                            <span class="font-medium"
                                                x-text="(p.umur !== null ? p.umur + ' th' : '-')"></span>
                                            <template x-if="p.tanggal_lahir">
                                                <div class="text-[10px] text-slate-400"
                                                    x-text="p.tanggal_lahir ? p.tanggal_lahir.substring(0, 10) : ''">
                                                </div>
                                            </template>
                                            <div class="text-[11px] text-pink-600 font-semibold"
                                                x-text="'G' + (p.gravida || 1) + 'P' + (p.para || 0) + 'A' + (p.abortus || 0)">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-700"
                                                x-text="p.usia_kehamilan ? p.usia_kehamilan + ' mgg' : (p.kunjungan_ke || '-')">
                                            </div>
                                            <div class="text-[11px] text-pink-600 font-medium"
                                                x-text="p.kunjungan_ke || ''"></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-slate-800"
                                                x-text="p.hpl ? p.hpl.substring(0, 10) : '-'"></div>
                                            <template x-if="isDueWithin14Days(p.hpl)">
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 animate-pulse border border-rose-300 mt-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                                    H-14!
                                                </span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-slate-700" x-text="p.kelurahan || '-'"></div>
                                            <div class="text-[11px] text-slate-400" x-text="p.kabupaten || '-'"></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-semibold text-slate-700"
                                                    x-text="p.lila ? p.lila + ' cm' : (p.hb ? p.hb + ' g/dL' : '-')"></span>
                                                <template x-if="p.lila && parseFloat(p.lila) < 23.5">
                                                    <span
                                                        class="px-1.5 py-0.2 bg-amber-100 text-amber-800 border border-amber-300 rounded text-[9px] font-black">KEK</span>
                                                </template>
                                            </div>
                                            <template x-if="p.status_anemia && p.status_anemia !== '-'">
                                                <span :class="{
                                                    'bg-rose-50 text-rose-700 border-rose-200': p.status_anemia.toLowerCase().includes('anemia'),
                                                    'bg-emerald-50 text-emerald-700 border-emerald-200': !p.status_anemia.toLowerCase().includes('anemia')
                                                }" class="px-1.5 py-0.5 rounded text-[10px] font-semibold border inline-block mt-0.5"
                                                    x-text="p.status_anemia"></span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <template
                                                    x-if="p.status_risti && p.status_risti.toLowerCase().includes('tinggi')">
                                                    <span
                                                        class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-md text-[10px] font-bold inline-block">
                                                        RISTI
                                                    </span>
                                                </template>
                                                <template
                                                    x-if="!p.status_risti || !p.status_risti.toLowerCase().includes('tinggi')">
                                                    <span
                                                        class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-semibold inline-block">
                                                        Normal
                                                    </span>
                                                </template>
                                            </div>
                                            <template x-if="p.faktor_risiko">
                                                <div class="text-[10px] text-rose-600 font-semibold mt-0.5 max-w-[140px] truncate"
                                                    :title="p.faktor_risiko" x-text="p.faktor_risiko"></div>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                <span :class="{
                                                    'bg-emerald-100 text-emerald-800 border-emerald-300': p.kategori_poedji_rochjati === 'KRR',
                                                    'bg-amber-100 text-amber-800 border-amber-300': p.kategori_poedji_rochjati === 'KRT',
                                                    'bg-rose-100 text-rose-800 border-rose-300': p.kategori_poedji_rochjati === 'KRST',
                                                    'bg-slate-100 text-slate-600 border-slate-200': !p.kategori_poedji_rochjati
                                                }" class="px-2 py-0.5 rounded-md font-bold text-[11px] border"
                                                    x-text="p.kategori_poedji_rochjati || 'KRR'"></span>
                                                <span class="text-slate-500 text-[11px] font-semibold"
                                                    x-text="'(' + (p.skor_poedji_rochjati ?? 2) + ')'"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-slate-700 font-medium text-[11px]"
                                                x-text="p.rekomendasi_faskes || 'Bidan / BPM / Puskesmas'"></span>
                                            <template x-if="p.calon_pendonor">
                                                <div class="text-[10px] text-rose-600 font-semibold mt-0.5"
                                                    x-text="'Donor: ' + p.calon_pendonor"></div>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <!-- View / Detail Pasien Modal -->
                                                <button @click="openViewModal(p)" type="button"
                                                    class="p-1.5 text-pink-600 hover:text-pink-800 hover:bg-pink-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Lihat Rekam Lengkap Ibu Hamil">
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

                                                <!-- WhatsApp Direct Quick Reminder -->
                                                <button @click="openWhatsAppModal(p)" type="button"
                                                    class="p-1.5 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
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
                                                    class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Skrining Data Ganda / Lintas Wilayah">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- Rekam Jejak / Cohort Timeline -->
                                                <button @click="openTimelineModal(p)" type="button"
                                                    class="p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Rekam Jejak Pasien (Cohort Timeline)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>

                                                <!-- AI Triage (Gemini) -->
                                                <button @click="openAiTriage(p)" type="button"
                                                    class="p-1.5 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-colors cursor-pointer"
                                                    title="AI Triage & Deteksi Anomali (Google Gemini)">
                                                    <span
                                                        class="text-xs font-black px-1 py-0.5 bg-purple-100 text-purple-700 rounded">AI</span>
                                                </button>

                                                @can('edit-data')
                                                <!-- Edit Pasien -->
                                                <button @click="openEditModal(p)" type="button"
                                                    class="p-1.5 text-pink-600 hover:text-pink-900 hover:bg-pink-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Edit Data Realtime">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- Catat Kelahiran & Siarkan Alert Realtime -->
                                                <button @click="openRecordBirthModal(p)" type="button"
                                                    class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Catat Kelahiran & Kirim Alert Realtime">
                                                    <span class="text-sm leading-none">Birth Report</span>
                                                </button>
                                                @endcan

                                                @can('delete-data')
                                                <!-- Hapus Pasien -->
                                                <button @click="deletePatient(p)" type="button"
                                                    class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
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
                                        <td colspan="11" class="text-center py-8 text-slate-400">
                                            Tidak ada data ibu hamil yang cocok dengan filter yang dipilih. Silakan klik
                                            tombol "Import Excel / CSV" atau "Tambah Data".
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION CONTROLS -->
                    <div
                        class="px-5 py-3.5 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-600">
                        <span
                            x-text="'Halaman ' + (patientsData.current_page || 1) + ' dari ' + (patientsData.last_page || 1)"></span>
                        <div class="flex items-center gap-2">
                            <button :disabled="(patientsData.current_page || 1) <= 1"
                                @click="changePage((patientsData.current_page || 1) - 1)"
                                class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer">Sebelumnya</button>
                            <button :disabled="(patientsData.current_page || 1) >= (patientsData.last_page || 1)"
                                @click="changePage((patientsData.current_page || 1) + 1)"
                                class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer">Berikutnya</button>
                        </div>
                    </div>
                </div>
            </div>

