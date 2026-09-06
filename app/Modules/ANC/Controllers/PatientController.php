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
}
