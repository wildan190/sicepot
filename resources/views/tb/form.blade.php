<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Skrining TBC – SICEPOT</title>
    <meta name="description" content="Formulir pelaporan dan skrining pasien terduga TBC. Isi data Anda dan tim kesehatan kami akan segera menindaklanjuti.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:   #3b82f6;
            --blue-d: #1d4ed8;
            --indigo: #4f46e5;
            --slate:  #1e293b;
            --muted:  #64748b;
            --light:  #f8fafc;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #1a1a3e 100%);
            min-height: 100vh;
            padding: 2rem 1rem 4rem;
            color: #1e293b;
        }

        /* ── Animated background blobs ── */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            pointer-events: none;
        }
        body::before {
            width: 600px; height: 600px;
            background: #3b82f6;
            top: -200px; right: -150px;
            animation: drift 12s ease-in-out infinite alternate;
        }
        body::after {
            width: 500px; height: 500px;
            background: #8b5cf6;
            bottom: -150px; left: -100px;
            animation: drift 15s ease-in-out infinite alternate-reverse;
        }
        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, 20px) scale(1.05); }
        }

        .page-wrap {
            max-width: 680px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
        }
        .header .badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(59,130,246,.25);
            border: 1px solid rgba(59,130,246,.4);
            color: #93c5fd;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .3rem .8rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }
        .header h1 {
            font-size: clamp(1.5rem, 4vw, 2.25rem);
            font-weight: 900;
            letter-spacing: -.02em;
            line-height: 1.15;
            margin-bottom: .5rem;
        }
        .header h1 span { color: #60a5fa; }
        .header p {
            font-size: .9rem;
            color: rgba(255,255,255,.65);
            line-height: 1.6;
            max-width: 480px;
            margin: 0 auto;
        }

        /* ── Alert messages ── */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 14px;
            font-size: .875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: .75rem;
        }
        .alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
        }
        .alert-icon { flex-shrink: 0; margin-top: 2px; }

        /* ── Card ── */
        .card {
            background: #fff;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 25px 60px rgba(0,0,0,.35), 0 0 0 1px rgba(255,255,255,.06);
        }

        /* ── Section titles ── */
        .section-title {
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #94a3b8;
            margin: 1.75rem 0 .875rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }

        /* ── Form grid ── */
        .form-grid {
            display: grid;
            gap: 1rem;
        }
        .form-grid.cols-2 { grid-template-columns: 1fr 1fr; }
        .form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }

        @media (max-width: 540px) {
            .form-grid.cols-2, .form-grid.cols-3 { grid-template-columns: 1fr; }
        }

        /* ── Field ── */
        .field { display: flex; flex-direction: column; gap: .35rem; }
        .field label {
            font-size: .75rem;
            font-weight: 600;
            color: #475569;
        }
        .field label .req { color: #e11d48; margin-left: 2px; }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            padding: .65rem 1rem;
            font-size: .875rem;
            font-family: inherit;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            color: #1e293b;
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
            appearance: none;
        }
        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59,130,246,.12);
        }
        .field input.is-error,
        .field select.is-error { border-color: #f43f5e; }
        .field .field-error {
            font-size: .7rem;
            color: #f43f5e;
            font-weight: 500;
        }

        /* ── Yes/No toggle group ── */
        .toggle-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .5rem;
        }
        .toggle-group.three-cols { grid-template-columns: 1fr 1fr 1fr; }

        .toggle-group label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .6rem 1rem;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
            background: #f8fafc;
            color: #64748b;
            user-select: none;
        }
        .toggle-group input[type="radio"] { display: none; }
        .toggle-group input[type="radio"]:checked + label {
            border-color: transparent;
            color: #fff;
        }
        /* Ya = green */
        .toggle-group .opt-ya input:checked + label {
            background: #059669;
            box-shadow: 0 2px 8px rgba(5,150,105,.3);
        }
        /* Tidak = rose */
        .toggle-group .opt-tidak input:checked + label {
            background: #e11d48;
            box-shadow: 0 2px 8px rgba(225,29,72,.25);
        }
        /* Sudah = green */
        .toggle-group .opt-sudah input:checked + label {
            background: #059669;
            box-shadow: 0 2px 8px rgba(5,150,105,.3);
        }
        /* Belum = amber */
        .toggle-group .opt-belum input:checked + label {
            background: #d97706;
            box-shadow: 0 2px 8px rgba(217,119,6,.25);
        }

        .toggle-group label:hover {
            border-color: #94a3b8;
            background: #f1f5f9;
        }

        /* ── Skrining card ── */
        .skrining-item {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem 1.25rem;
        }
        .skrining-item .item-label {
            font-size: .8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: .65rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .skrining-item .item-label svg { flex-shrink: 0; }

        /* ── Error on toggle groups ── */
        .skrining-item.is-error { border-color: #f43f5e; background: #fff1f2; }
        .skrining-item.is-error .field-error { margin-top: .4rem; }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            padding: .9rem 1.5rem;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            color: #fff;
            font-family: inherit;
            font-size: .95rem;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            box-shadow: 0 4px 20px rgba(59,130,246,.4);
            transition: opacity .2s, transform .15s;
            letter-spacing: .01em;
            margin-top: 1.5rem;
        }
        .btn-submit:hover { opacity: .92; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        /* ── Privacy note ── */
        .privacy {
            margin-top: 1.25rem;
            text-align: center;
            font-size: .72rem;
            color: #94a3b8;
            line-height: 1.6;
        }
        .privacy svg { vertical-align: middle; }
    </style>
</head>
<body>
<div class="page-wrap">

    <!-- Header -->
    <div class="header">
        <div class="badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            SICEPOT – Sistem Cepat Post dan Tracking
        </div>
        <h1>Formulir Skrining <span>Terduga TBC</span></h1>
        <p>Isi formulir ini dengan jujur dan lengkap. Data Anda dijaga kerahasiaannya dan akan ditindaklanjuti oleh petugas kesehatan setempat.</p>
    </div>

    @if($errors->any())
    <div class="alert alert-error">
        <div class="alert-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div>
            <strong style="display:block;margin-bottom:.25rem">Terdapat kesalahan pada isian:</strong>
            <ul style="list-style:disc;padding-left:1rem;line-height:1.8">
                @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('tb.form.store') }}" novalidate>
        @csrf

        <div class="card">

            <!-- ── DATA DIRI ── -->
            <div class="section-title">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Data Diri Pasien
            </div>

            <div class="form-grid">
                <!-- Nama Pasien -->
                <div class="field" style="grid-column:1/-1">
                    <label for="nama_lengkap">Nama Lengkap Pasien <span class="req">*</span></label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap"
                           value="{{ old('nama_lengkap') }}"
                           placeholder="Nama sesuai KTP"
                           class="{{ $errors->has('nama_lengkap') ? 'is-error' : '' }}">
                    @error('nama_lengkap')
                    <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-grid cols-2" style="margin-top:1rem">
                <!-- NIK -->
                <div class="field">
                    <label for="nik">NIK (Nomor Induk Kependudukan)</label>
                    <input type="text" id="nik" name="nik"
                           value="{{ old('nik') }}"
                           placeholder="16 digit NIK KTP"
                           maxlength="16" inputmode="numeric">
                </div>

                <!-- Umur -->
                <div class="field">
                    <label for="umur">Umur (Tahun)</label>
                    <input type="number" id="umur" name="umur"
                           value="{{ old('umur') }}"
                           placeholder="Contoh: 32"
                           min="0" max="120">
                </div>
            </div>

            <!-- Kategori Usia -->
            <div class="field" style="margin-top:1rem">
                <label for="kategori_usia">Kategori Usia</label>
                <select id="kategori_usia" name="kategori_usia">
                    <option value="">— Pilih Kategori —</option>
                    <option value="Bayi & Balita" {{ old('kategori_usia') == 'Bayi & Balita' ? 'selected' : '' }}>Bayi &amp; Balita (0 bulan – 5 tahun)</option>
                    <option value="Anak/Remaja" {{ old('kategori_usia') == 'Anak/Remaja' ? 'selected' : '' }}>Anak / Remaja (6 – 17 Tahun)</option>
                    <option value="Dewasa" {{ old('kategori_usia') == 'Dewasa' ? 'selected' : '' }}>Dewasa (18 – 60 Tahun)</option>
                    <option value="Lansia" {{ old('kategori_usia') == 'Lansia' ? 'selected' : '' }}>Lansia (&gt;60 Tahun)</option>
                </select>
            </div>

            <!-- Jenis Kelamin -->
            <div class="field" style="margin-top:1rem">
                <label>Jenis Kelamin <span class="req">*</span></label>
                <div class="toggle-group">
                    <div class="opt-ya">
                        <input type="radio" id="jk_l" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}>
                        <label for="jk_l">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="5" r="3"/><path d="M12 8v13m-4-4l4 4 4-4"/></svg>
                            Laki-laki
                        </label>
                    </div>
                    <div class="opt-tidak">
                        <input type="radio" id="jk_p" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                        <label for="jk_p">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="5" r="3"/><path d="M12 8v8m-3 0h6m-3 0v3"/></svg>
                            Perempuan
                        </label>
                    </div>
                </div>
                @error('jenis_kelamin')
                <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- ── KONTAK ── -->
            <div class="section-title" style="margin-top:2rem">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
                Domisili &amp; Kontak
            </div>

            <!-- Alamat Desa -->
            <div class="field">
                <label for="kelurahan">Alamat Desa / Kelurahan <span class="req">*</span></label>
                <select id="kelurahan" name="kelurahan" class="{{ $errors->has('kelurahan') ? 'is-error' : '' }}">
                    <option value="">— Pilih Desa/Kelurahan —</option>
                    @foreach(['Pagedangan','Cicalengka','Cihuni','Cijantra','Medang','Lengkong Kulon','Situgadung','Kadusirung','Jatake','Malangnengah','Karang Tengah'] as $desa)
                    <option value="{{ $desa }}" {{ old('kelurahan') == $desa ? 'selected' : '' }}>{{ $desa }}</option>
                    @endforeach
                </select>
                @error('kelurahan')
                <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-grid cols-2" style="margin-top:1rem">
                <!-- No HP -->
                <div class="field">
                    <label for="no_telepon">No. HP / WhatsApp</label>
                    <input type="tel" id="no_telepon" name="no_telepon"
                           value="{{ old('no_telepon') }}"
                           placeholder="08xxxxxxxxxx" inputmode="tel">
                </div>

                <!-- Nama Pelapor -->
                <div class="field">
                    <label for="nama_pelapor">Nama Pelapor</label>
                    <input type="text" id="nama_pelapor" name="nama_pelapor"
                           value="{{ old('nama_pelapor') }}"
                           placeholder="Nama kader / petugas">
                </div>
            </div>

            <!-- ── SKRINING GEJALA ── -->
            <div class="section-title" style="margin-top:2rem">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Skrining Gejala TBC
            </div>

            <div style="display:grid;gap:.75rem">

                <!-- Batuk > 2 Minggu -->
                <div class="skrining-item {{ $errors->has('batuk_2_minggu') ? 'is-error' : '' }}">
                    <div class="item-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Gejala Batuk lebih dari 2 Minggu <span class="req" style="color:#e11d48">*</span>
                    </div>
                    <div class="toggle-group">
                        <div class="opt-ya">
                            <input type="radio" id="batuk_ya" name="batuk_2_minggu" value="Ya" {{ old('batuk_2_minggu') == 'Ya' ? 'checked' : '' }}>
                            <label for="batuk_ya">✓ Ya</label>
                        </div>
                        <div class="opt-tidak">
                            <input type="radio" id="batuk_tidak" name="batuk_2_minggu" value="Tidak" {{ old('batuk_2_minggu') == 'Tidak' ? 'checked' : '' }}>
                            <label for="batuk_tidak">✗ Tidak</label>
                        </div>
                    </div>
                    @error('batuk_2_minggu')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <!-- BB Turun -->
                <div class="skrining-item {{ $errors->has('bb_turun') ? 'is-error' : '' }}">
                    <div class="item-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Berat Badan Turun tanpa sebab <span class="req" style="color:#e11d48">*</span>
                    </div>
                    <div class="toggle-group">
                        <div class="opt-ya">
                            <input type="radio" id="bb_ya" name="bb_turun" value="Ya" {{ old('bb_turun') == 'Ya' ? 'checked' : '' }}>
                            <label for="bb_ya">✓ Ya</label>
                        </div>
                        <div class="opt-tidak">
                            <input type="radio" id="bb_tidak" name="bb_turun" value="Tidak" {{ old('bb_turun') == 'Tidak' ? 'checked' : '' }}>
                            <label for="bb_tidak">✗ Tidak</label>
                        </div>
                    </div>
                    @error('bb_turun')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <!-- Keringat Malam -->
                <div class="skrining-item {{ $errors->has('keringat_malam') ? 'is-error' : '' }}">
                    <div class="item-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Keringat Malam berlebihan <span class="req" style="color:#e11d48">*</span>
                    </div>
                    <div class="toggle-group">
                        <div class="opt-ya">
                            <input type="radio" id="km_ya" name="keringat_malam" value="Ya" {{ old('keringat_malam') == 'Ya' ? 'checked' : '' }}>
                            <label for="km_ya">✓ Ya</label>
                        </div>
                        <div class="opt-tidak">
                            <input type="radio" id="km_tidak" name="keringat_malam" value="Tidak" {{ old('keringat_malam') == 'Tidak' ? 'checked' : '' }}>
                            <label for="km_tidak">✗ Tidak</label>
                        </div>
                    </div>
                    @error('keringat_malam')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <!-- Kontak Erat TB -->
                <div class="skrining-item {{ $errors->has('kontak_tb') ? 'is-error' : '' }}">
                    <div class="item-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/></svg>
                        Apakah ada Kontak erat dengan penderita TB? <span class="req" style="color:#e11d48">*</span>
                    </div>
                    <div class="toggle-group">
                        <div class="opt-ya">
                            <input type="radio" id="kontak_ya" name="kontak_tb" value="Ya" {{ old('kontak_tb') == 'Ya' ? 'checked' : '' }}>
                            <label for="kontak_ya">✓ Ya</label>
                        </div>
                        <div class="opt-tidak">
                            <input type="radio" id="kontak_tidak" name="kontak_tb" value="Tidak" {{ old('kontak_tb') == 'Tidak' ? 'checked' : '' }}>
                            <label for="kontak_tidak">✗ Tidak</label>
                        </div>
                    </div>
                    @error('kontak_tb')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <!-- Sudah Pengobatan -->
                <div class="skrining-item {{ $errors->has('sudah_pengobatan') ? 'is-error' : '' }}">
                    <div class="item-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Apakah sudah melakukan Pengobatan TB? <span class="req" style="color:#e11d48">*</span>
                    </div>
                    <div class="toggle-group">
                        <div class="opt-sudah">
                            <input type="radio" id="pengobatan_sudah" name="sudah_pengobatan" value="Sudah" {{ old('sudah_pengobatan') == 'Sudah' ? 'checked' : '' }}>
                            <label for="pengobatan_sudah">✓ Sudah</label>
                        </div>
                        <div class="opt-belum">
                            <input type="radio" id="pengobatan_belum" name="sudah_pengobatan" value="Belum" {{ old('sudah_pengobatan') == 'Belum' ? 'checked' : '' }}>
                            <label for="pengobatan_belum">✗ Belum</label>
                        </div>
                    </div>
                    @error('sudah_pengobatan')<span class="field-error">{{ $message }}</span>@enderror
                </div>

            </div><!-- /skrining items -->

            <!-- Submit -->
            <button type="submit" class="btn-submit">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Kirim Laporan Skrining
            </button>

            <p class="privacy">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                Data Anda bersifat rahasia dan hanya digunakan untuk keperluan pemantauan kesehatan oleh petugas Puskesmas setempat.
            </p>

        </div>{{-- /card --}}
    </form>

</div>
</body>
</html>
