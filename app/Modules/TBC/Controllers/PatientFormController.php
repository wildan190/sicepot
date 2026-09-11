<?php

namespace App\Modules\TBC\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TBC\Models\TbPatient;
use Illuminate\Http\Request;

class PatientFormController extends Controller
{
    /**
     * Show public patient screening / reporting form.
     * No authentication required.
     */
    public function show()
    {
        return view('tb.form');
    }

    /**
     * Store a new patient from the public form.
     * No authentication required.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'     => 'required|string|max:255',
            'nik'              => 'nullable|string|max:50',
            'umur'             => 'nullable|integer|min:0|max:120',
            'kategori_usia'    => 'nullable|string|max:50',
            'jenis_kelamin'    => 'required|string|in:L,P',
            'kelurahan'        => 'required|string|max:100',
            'no_telepon'       => 'nullable|string|max:30',
            'nama_pelapor'     => 'nullable|string|max:255',
            'batuk_2_minggu'   => 'required|string|in:Ya,Tidak',
            'bb_turun'         => 'required|string|in:Ya,Tidak',
            'keringat_malam'   => 'required|string|in:Ya,Tidak',
            'kontak_tb'        => 'required|string|in:Ya,Tidak',
            'sudah_pengobatan' => 'required|string|in:Sudah,Belum',
        ], [
            'nama_lengkap.required'     => 'Nama pasien wajib diisi.',
            'jenis_kelamin.required'    => 'Jenis kelamin wajib dipilih.',
            'kelurahan.required'        => 'Alamat desa/kelurahan wajib dipilih.',
            'batuk_2_minggu.required'   => 'Harap jawab pertanyaan gejala batuk.',
            'bb_turun.required'         => 'Harap jawab pertanyaan berat badan.',
            'keringat_malam.required'   => 'Harap jawab pertanyaan keringat malam.',
            'kontak_tb.required'        => 'Harap jawab pertanyaan kontak erat TB.',
            'sudah_pengobatan.required' => 'Harap jawab pertanyaan pengobatan.',
        ]);

        // Auto-fill report_type as skrining / screening (tb_06 or default)
        $validated['report_type'] = 'tb_06';
        $validated['kabupaten']   = 'Tangerang';
        $validated['kecamatan']   = 'Pagedangan';

        TbPatient::create($validated);

        return redirect()->route('tb.form.success');
    }

    /**
     * Show success / thank-you page.
     */
    public function success()
    {
        return view('tb.form-success');
    }
}
