<?php

namespace App\Modules\Stunting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stunting\Models\StuntingPatient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Store a new Stunting patient record manually.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'                 => 'nullable|string|max:20',
            'nama'                => 'required|string|max:150',
            'jenis_kelamin'       => 'nullable|in:L,P',
            'tanggal_lahir'       => 'nullable|date',
            'bb_lahir'            => 'nullable|numeric|min:0|max:20',
            'tb_lahir'            => 'nullable|numeric|min:0|max:100',
            'nama_ortu'           => 'nullable|string|max:150',
            'puskesmas'           => 'nullable|string|max:100',
            'desa'                => 'nullable|string|max:100',
            'posyandu'            => 'nullable|string|max:100',
            'rt'                  => 'nullable|string|max:10',
            'rw'                  => 'nullable|string|max:10',
            'alamat'              => 'nullable|string|max:255',
            'usia_saat_ukur'      => 'nullable|integer|min:0|max:72',
            'tanggal_pengukuran'  => 'nullable|date',
            'berat'               => 'nullable|numeric|min:0|max:50',
            'tinggi'              => 'nullable|numeric|min:0|max:150',
            'cara_ukur'           => 'nullable|string|max:10',
            'lila'                => 'nullable|numeric|min:0|max:50',
            'bbu_kategori'        => 'nullable|string|max:50',
            'bbu_zscore'          => 'nullable|numeric',
            'tbu_kategori'        => 'nullable|string|max:50',
            'tbu_zscore'          => 'nullable|numeric',
            'bbtb_kategori'       => 'nullable|string|max:50',
            'bbtb_zscore'         => 'nullable|numeric',
            'naik_berat_badan'    => 'nullable|in:N,T,Y',
        ]);

        $patient = StuntingPatient::create($validated);

        return response()->json([
            'success'  => true,
            'message'  => 'Data balita berhasil ditambahkan.',
            'patient'  => $patient,
        ]);
    }

    /**
     * Update an existing Stunting patient record.
     */
    public function update(Request $request, StuntingPatient $patient)
    {
        $validated = $request->validate([
            'nik'                 => 'nullable|string|max:20',
            'nama'                => 'required|string|max:150',
            'jenis_kelamin'       => 'nullable|in:L,P',
            'tanggal_lahir'       => 'nullable|date',
            'bb_lahir'            => 'nullable|numeric|min:0|max:20',
            'tb_lahir'            => 'nullable|numeric|min:0|max:100',
            'nama_ortu'           => 'nullable|string|max:150',
            'puskesmas'           => 'nullable|string|max:100',
            'desa'                => 'nullable|string|max:100',
            'posyandu'            => 'nullable|string|max:100',
            'rt'                  => 'nullable|string|max:10',
            'rw'                  => 'nullable|string|max:10',
            'alamat'              => 'nullable|string|max:255',
            'usia_saat_ukur'      => 'nullable|integer|min:0|max:72',
            'tanggal_pengukuran'  => 'nullable|date',
            'berat'               => 'nullable|numeric|min:0|max:50',
            'tinggi'              => 'nullable|numeric|min:0|max:150',
            'cara_ukur'           => 'nullable|string|max:10',
            'lila'                => 'nullable|numeric|min:0|max:50',
            'bbu_kategori'        => 'nullable|string|max:50',
            'bbu_zscore'          => 'nullable|numeric',
            'tbu_kategori'        => 'nullable|string|max:50',
            'tbu_zscore'          => 'nullable|numeric',
            'bbtb_kategori'       => 'nullable|string|max:50',
            'bbtb_zscore'         => 'nullable|numeric',
            'naik_berat_badan'    => 'nullable|in:N,T,Y',
        ]);

        $patient->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data balita berhasil diperbarui.',
            'patient' => $patient->fresh(),
        ]);
    }

    /**
     * Delete a single Stunting patient record.
     */
    public function destroy(StuntingPatient $patient)
    {
        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data balita berhasil dihapus.',
        ]);
    }
}
