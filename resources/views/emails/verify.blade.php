<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email PLN</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .header img {
        max-width: 200px;
        margin-bottom: 20px;
    }

    .content {
        padding: 40px 30px;
    }

    .btn {
        display: inline-block;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white !important;
        padding: 15px 30px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        text-align: center;
        margin: 20px 0;
    }

    .btn:hover {
        background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
    }

    .footer {
        background-color: #f8f9fa;
        padding: 20px 30px;
        text-align: center;
        font-size: 12px;
        color: #666;
    }

    .warning {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
    }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">
                🔐 Verifikasi Email Anda
            </h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">
                PLN E-Prakualifikasi System
            </p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="color: #1e3c72; margin-top: 0;">
                Halo, {{ $user->fullname }}!
            </h2>

            <p>
                Terima kasih telah mendaftar di <strong>PLN E-Prakualifikasi</strong>
                sebagai vendor <strong>{{ $user->type }}</strong>.
            </p>

            <p>
                Untuk melengkapi proses registrasi, silakan verifikasi alamat email Anda
                dengan mengklik tombol di bawah ini:
            </p>

            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn">
                    ✅ Verifikasi Email Sekarang
                </a>
            </div>

            <div class="warning">
                <strong>⚠️ Penting:</strong>
                <ul style="margin: 10px 0;">
                    <li>Link verifikasi ini akan kedaluwarsa dalam 24 jam</li>
                    <li>Setelah verifikasi, Anda dapat login dan melengkapi profil vendor</li>
                    <li>Jangan bagikan link ini kepada orang lain</li>
                </ul>
            </div>

            <p>
                Jika tombol di atas tidak berfungsi, copy dan paste URL berikut ke browser Anda:
            </p>
            <p
                style="word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace;">
                {{ $url }}
            </p>

            <p>
                <strong>Langkah selanjutnya:</strong>
            </p>
            <ol>
                <li>Klik link verifikasi di atas</li>
                <li>Login ke akun Anda</li>
                <li>Lengkapi profil perusahaan</li>
                <li>Tunggu approval dari admin PLN</li>
            </ol>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Email ini dikirim secara otomatis, mohon tidak membalas email ini.
            </p>
            <p>
                © {{ date('Y') }} PLN E-Prakualifikasi. All rights reserved.
            </p>
            <p>
                <strong>PT PLN Nusantara Power Construction</strong><br>
                Building 18 Office Park Lt. 18<br>
                Jl. T.B. Simatupang No.18,<br />
                Pasar Minggu,<br />
                South Jakarta - 12520<br />
                Indonesia
            </p>
        </div>
    </div>
</body>

</html>