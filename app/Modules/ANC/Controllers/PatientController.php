<?php

namespace App\Modules\ANC\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ANC\Models\AncPatient;
use App\Modules\ANC\Requests\StorePatientRequest;
use App\Modules\ANC\Requests\UpdatePatientRequest;

class PatientController extends Controller
{
    /**
     * Get specific patient record (for editing/viewing).
     */
    public function show(AncPatient $patient)
    {
        return response()->json(['success' => true, 'data' => $patient]);
    }

    /**
     * Store new patient record manually.
     */
    public function store(StorePatientRequest $request)
    {
        $payload = $request->validated();
        
        // Auto compute Poedji Rochjati risk assessment
        $assessment = \App\Modules\ANC\Services\RiskAssessmentService::calculate($payload);
        $payload['skor_poedji_rochjati']     = $assessment['skor'];
        $payload['kategori_poedji_rochjati'] = $assessment['kategori'];
        $payload['rekomendasi_faskes']       = $assessment['rekomendasi_faskes'];
        if (empty($payload['status_risti'])) {
            $payload['status_risti'] = $assessment['kategori'] === 'KRR' ? 'Normal' : 'Risiko Tinggi';
        }

        $patient = AncPatient::create($payload);

        return response()->json([
            'success' => true,
            'message' => 'Data ibu hamil berhasil ditambahkan (Skor Poedji Rochjati: ' . $assessment['skor'] . ' - ' . $assessment['kategori'] . ').',
            'data'    => $patient,
            'risk'    => $assessment,
        ], 201);
    }

    /**
     * Update a patient record in real-time.
     */
    public function update(UpdatePatientRequest $request, AncPatient $patient)
    {
        $payload = $request->validated();

        // Auto compute Poedji Rochjati risk assessment on update
        $merged = array_merge($patient->toArray(), $payload);
        $assessment = \App\Modules\ANC\Services\RiskAssessmentService::calculate($merged);
        $payload['skor_poedji_rochjati']     = $assessment['skor'];
        $payload['kategori_poedji_rochjati'] = $assessment['kategori'];
        $payload['rekomendasi_faskes']       = $assessment['rekomendasi_faskes'];

        $patient->update($payload);

        return response()->json([
            'success' => true,
            'message' => 'Data ibu hamil berhasil diperbarui (Skor: ' . $assessment['skor'] . ' - ' . $assessment['kategori'] . ').',
            'data'    => $patient->fresh(),
            'risk'    => $assessment,
        ]);
    }

    /**
     * Delete a patient record.
     */
    public function destroy(AncPatient $patient)
    {
        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data ibu hamil berhasil dihapus.',
        ]);
    }

    /**
     * Record a birth delivery and immediately broadcast birth alert via PieSocket WebSocket.
     */
    public function recordBirth(\Illuminate\Http\Request $request, AncPatient $patient)
    {
        $validated = $request->validate([
            'tanggal_bersalin'      => 'required|date',
            'tempat_bersalin'       => 'nullable|string|max:150',
            'penolong_persalinan'   => 'nullable|string|max:150',
            'kondisi_bayi'          => 'nullable|string|max:100',
            'berat_lahir_bayi'      => 'nullable|numeric|min:0.5|max:10',
            'komplikasi_persalinan' => 'nullable|string|max:255',
            'catatan'               => 'nullable|string',
        ]);

        $validated['status_kehamilan'] = 'Selesai Bersalin';

        $patient->update($validated);

        // Broadcast to PieSocket
        \App\Services\PieSocketService::broadcastBirthAlert($patient->toArray(), $validated);

        return response()->json([
            'success' => true,
            'message' => 'Kelahiran berhasil dicatat dan alert realtime telah disiarkan ke seluruh perangkat!',
            'data'    => $patient->fresh(),
        ]);
    }

    /**
     * Send test birth alert via PieSocket without modifying permanent database.
     */
    public function testBirthAlert(\Illuminate\Http\Request $request)
    {
        $samplePatient = [
            'id'                       => rand(100, 999),
            'nama_lengkap'             => 'Ny. Siti Aminah (Simulasi Uji Coba)',
            'nama_suami'               => 'Tn. Ahmad Fauzi',
            'umur'                     => 28,
            'nik'                      => '360322' . rand(1000000000, 9999999999),
            'no_telepon'               => '08123456789',
            'kelurahan'                => 'Pagedangan',
            'kabupaten'                => 'Kab. Tangerang',
            'status_risti'             => 'Risiko Tinggi',
            'kategori_poedji_rochjati' => 'KRT',
        ];

        $sampleBirthData = [
            'tanggal_bersalin'      => now()->format('Y-m-d'),
            'tempat_bersalin'       => 'Puskesmas PONED Pagedangan',
            'penolong_persalinan'   => 'Bidan Desa & Tim PONED',
            'kondisi_bayi'          => 'Lahir Hidup, Menangis Kuat (Apgar 8/9)',
            'berat_lahir_bayi'      => '3.15',
            'komplikasi_persalinan' => 'Tidak Ada Komplikasi Mayor',
        ];

        $broadcasted = \App\Services\PieSocketService::broadcastBirthAlert($samplePatient, $sampleBirthData);

        return response()->json([
            'success'     => $broadcasted,
            'message'     => $broadcasted 
                ? 'Alert kelahiran berhasil disiarkan melalui PieSocket WebSocket!' 
                : 'Peringatan terkirim, periksa koneksi internet ke PieSocket.',
            'sample_data' => array_merge($samplePatient, $sampleBirthData),
        ]);
    }
}

