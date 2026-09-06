<?php

namespace App\Modules\TBC\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TBC\Models\TbPatient;
use App\Modules\TBC\Requests\StorePatientRequest;
use App\Modules\TBC\Requests\UpdatePatientRequest;

class PatientController extends Controller
{
    /**
     * Get specific patient record (for editing/viewing).
     */
    public function show(TbPatient $patient)
    {
        return response()->json(['success' => true, 'data' => $patient]);
    }

    /**
     * Store new patient record manually.
     */
    public function store(StorePatientRequest $request)
    {
        $patient = TbPatient::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil ditambahkan.',
            'data'    => $patient,
        ], 201);
    }

    /**
     * Update a patient record in real-time.
     */
    public function update(UpdatePatientRequest $request, TbPatient $patient)
    {
        $patient->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data pasien berhasil diperbarui.',
            'data'    => $patient->fresh(),
        ]);
    }

    /**
     * Delete a patient record.
     */
    public function destroy(TbPatient $patient)
    {
        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pasien berhasil dihapus.',
        ]);
    }
}
