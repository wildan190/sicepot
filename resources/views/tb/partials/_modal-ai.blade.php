            <!-- ================= MODAL: AI TRIAGE (GOOGLE GEMINI 2.5 FLASH) ================= -->
            <div x-show="showAiModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showAiModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                        <div class="flex items-center justify-between pb-3 border-b border-ant-split">
                            <div class="flex items-center gap-2.5">
                                <span class="p-2 rounded-ant bg-[#f9f0ff] text-[#531dab] font-bold text-xs">AI</span>
                                <div>
                                    <h3 class="ant-section-title">AI Triage TBC & Evaluasi Pengobatan
                                    </h3>
                                    <p class="text-xs text-ant-secondary">Analisis rekam medis TBC berbasis Google Gemini
                                        2.5 Flash</p>
                                </div>
                            </div>
                            <button @click="showAiModal = false"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4">
                            <div x-show="isLoadingAi"
                                class="py-12 text-center text-xs text-[#531dab] flex flex-col items-center justify-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-ant bg-[#f9f0ff] flex items-center justify-center animate-pulse">
                                    <svg class="animate-spin h-5 w-5 text-[#531dab]" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-bold text-sm">Google Gemini sedang menganalisis rekam medis TBC...
                                    </div>
                                    <p class="text-[11px] text-ant-secondary mt-1">Mengevaluasi kesesuaian regimen OAT,
                                        risiko resistensi obat (RO), kepatuhan, dan jadwal kontrol dahak.</p>
                                </div>
                            </div>

                            <div x-show="!isLoadingAi" class="space-y-3">
                                <div
                                    class="p-3 bg-[#f9f0ff]/60 border border-[#efdbff] rounded-ant-lg text-xs flex items-center justify-between">
                                    <div class="font-bold text-ant-title"
                                        x-text="'Pasien: ' + (targetPatient?.nama_lengkap || '-')"></div>
                                    <div class="text-[#531dab] font-semibold"
                                        x-text="'Diagnosis: ' + (targetPatient?.hasil_diagnosis || targetPatient?.hasil_tcm || '-') + ' | ' + (targetPatient?.kelurahan || '-')">
                                    </div>
                                </div>

                                <div class="p-5 bg-[#fafafa] border border-ant-split rounded-ant-lg max-h-96 overflow-y-auto text-xs text-ant-text leading-relaxed font-sans max-w-none whitespace-pre-wrap"
                                    x-html="formatAiContent(aiAnalysisResult)"></div>
                            </div>

                            <div class="mt-6 pt-3 border-t border-ant-split flex items-center justify-between">
                                <span class="text-[11px] text-ant-secondary">Model: Gemini 2.5 Flash • Sumber: Data Register
                                    SITB</span>
                                <button @click="showAiModal = false"
                                    class="ant-btn ant-btn-default">Selesai
                                    Membaca</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
