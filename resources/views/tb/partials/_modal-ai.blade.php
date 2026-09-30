            <!-- ================= MODAL: AI TRIAGE (GOOGLE GEMINI 2.5 FLASH) ================= -->
            <div x-show="showAiModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showAiModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <span class="p-2 rounded-xl bg-purple-100 text-purple-700 font-bold text-xs">AI</span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">AI Triage TBC & Evaluasi Pengobatan
                                    </h3>
                                    <p class="text-xs text-slate-500">Analisis rekam medis TBC berbasis Google Gemini
                                        2.5 Flash</p>
                                </div>
                            </div>
                            <button @click="showAiModal = false"
                                class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4">
                            <div x-show="isLoadingAi"
                                class="py-12 text-center text-xs text-purple-700 flex flex-col items-center justify-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-purple-100 flex items-center justify-center animate-pulse">
                                    <svg class="animate-spin h-5 w-5 text-purple-600" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-bold text-sm">Google Gemini sedang menganalisis rekam medis TBC...
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1">Mengevaluasi kesesuaian regimen OAT,
                                        risiko resistensi obat (RO), kepatuhan, dan jadwal kontrol dahak.</p>
                                </div>
                            </div>

                            <div x-show="!isLoadingAi" class="space-y-3">
                                <div
                                    class="p-3 bg-purple-50/60 border border-purple-100 rounded-2xl text-xs flex items-center justify-between">
                                    <div class="font-bold text-slate-800"
                                        x-text="'Pasien: ' + (targetPatient?.nama_lengkap || '-')"></div>
                                    <div class="text-purple-700 font-semibold"
                                        x-text="'Diagnosis: ' + (targetPatient?.hasil_diagnosis || targetPatient?.hasil_tcm || '-') + ' | ' + (targetPatient?.kelurahan || '-')">
                                    </div>
                                </div>

                                <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl max-h-96 overflow-y-auto text-xs text-slate-700 leading-relaxed font-sans prose prose-sm max-w-none whitespace-pre-wrap"
                                    x-html="formatAiContent(aiAnalysisResult)"></div>
                            </div>

                            <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400">Model: Gemini 2.5 Flash • Sumber: Data Register
                                    SITB</span>
                                <button @click="showAiModal = false"
                                    class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold cursor-pointer">Selesai
                                    Membaca</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

