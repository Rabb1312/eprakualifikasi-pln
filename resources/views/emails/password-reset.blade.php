<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password PLN E-Prakualifikasi</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #007bff 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .content {
            padding: 40px 30px;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #dc3545 0%, #007bff 100%);
            color: white !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }

        .btn:hover {
            background: linear-gradient(135deg, #007bff 0%, #dc3545 100%);
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">
                🔑 Reset Password Anda
            </h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">
                PLN E-Prakualifikasi System
            </p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="color: #dc3545; margin-top: 0;">
                Halo, {{ $user->fullname }}!
            </h2>

            <p>
                Kami menerima permintaan untuk mereset password akun Anda di
                <strong>PLN E-Prakualifikasi</strong>.
            </p>

            <p>
                Jika Anda meminta reset password, silakan klik tombol di bawah ini:
            </p>

            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn">
                    🔑 Reset Password Sekarang
                </a>
            </div>

            <div class="warning">
                <strong>⚠️ Penting:</strong>
                <ul style="margin: 10px 0;">
                    <li>Link reset password ini akan kedaluwarsa dalam 1 jam</li>
                    <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
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
                <strong>Keamanan Akun:</strong>
            </p>
            <ul>
                <li>Gunakan password yang kuat (minimal 6 karakter)</li>
                <li>Jangan gunakan password yang sama dengan situs lain</li>
                <li>Hubungi support jika ada aktivitas mencurigakan</li>
            </ul>
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
                Jika mengalami masalah, hubungi:
                <a href="mailto:perencanaan@plnnpc.com">perencanaan@plnnpc.com</a>
            </p>
        </div>
    </div>
</body>

</html>