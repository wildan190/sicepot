<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Terkirim – SICEPOT</title>
    <meta name="description" content="Laporan skrining TBC Anda berhasil dikirimkan.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #1a1a3e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            color: #fff;
        }
        .card {
            background: #fff;
            border-radius: 28px;
            padding: 3rem 2.5rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 30px 70px rgba(0,0,0,.4);
            color: #1e293b;
        }
        .icon-wrap {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669, #34d399);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(5,150,105,.35);
            animation: pop .5s cubic-bezier(.36,1.4,.64,1) both;
        }
        @keyframes pop {
            from { transform: scale(.4); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }
        h1 { font-size: 1.75rem; font-weight: 900; color: #1e293b; margin-bottom: .5rem; }
        .sub { font-size: .9rem; color: #64748b; line-height: 1.7; margin-bottom: 2rem; }
        .info-box {
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            font-size: .8rem;
            color: #065f46;
            text-align: left;
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        .info-box strong { font-weight: 700; }
        .btn {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .8rem 2rem;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            color: #fff;
            font-family: inherit;
            font-size: .875rem;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(59,130,246,.35);
            transition: opacity .2s;
        }
        .btn:hover { opacity: .88; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon-wrap">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5">
            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
    </div>
    <h1>Laporan Terkirim!</h1>
    <p class="sub">Terima kasih telah mengisi formulir skrining TBC. Data Anda berhasil dikirimkan dan akan segera ditindaklanjuti oleh petugas kesehatan setempat.</p>

    <div class="info-box">
        <strong>Langkah selanjutnya:</strong><br>
        Petugas Puskesmas akan menghubungi Anda dalam waktu dekat untuk jadwal pemeriksaan lebih lanjut. Pastikan nomor HP yang didaftarkan aktif.
    </div>

    <a href="{{ route('tb.form') }}" class="btn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
        Isi Formulir Lagi
    </a>
</div>
</body>
</html>
