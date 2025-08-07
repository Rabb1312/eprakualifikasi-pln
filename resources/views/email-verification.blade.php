<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - PLN E-Prakualifikasi</title>
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('{{ asset("assets/pln1.jpg") }}') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .verification-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        padding: 60px 40px;
        text-align: center;
        max-width: 600px;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .verification-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #1e3c72, #2a5298, #ffd700);
    }

    .icon-container {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5em;
        color: white;
        position: relative;
    }

    .icon-container.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        animation: successPulse 2s infinite;
    }

    .icon-container.error {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        animation: errorShake 0.5s ease-in-out;
    }

    .icon-container.info {
        background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
    }

    @keyframes successPulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    @keyframes errorShake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }

    h1 {
        color: #1e3c72;
        font-size: 2.5em;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .message {
        color: #666;
        font-size: 1.2em;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .details-box {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin: 30px 0;
        border-left: 5px solid #1e3c72;
    }

    .details-box h3 {
        color: #1e3c72;
        margin-bottom: 15px;
        font-size: 1.3em;
    }

    .details-list {
        text-align: left;
        list-style: none;
    }

    .details-list li {
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
    }

    .details-list li:last-child {
        border-bottom: none;
    }

    .details-list i {
        margin-right: 10px;
        color: #1e3c72;
        width: 20px;
    }

    .btn-container {
        margin-top: 40px;
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        padding: 15px 30px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        min-width: 150px;
        justify-content: center;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(30, 60, 114, 0.3);
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }

    .footer-info {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #e9ecef;
        color: #999;
        font-size: 14px;
    }

    .footer-info a {
        color: #1e3c72;
        text-decoration: none;
    }

    .footer-info a:hover {
        color: #ffd700;
    }

    @media (max-width: 768px) {
        .verification-container {
            padding: 40px 25px;
            margin: 10px;
        }

        h1 {
            font-size: 2em;
        }

        .message {
            font-size: 1.1em;
        }

        .btn-container {
            flex-direction: column;
            align-items: center;
        }

        .btn {
            width: 100%;
            max-width: 300px;
        }
    }
    </style>
</head>

<body>
    <div class="verification-container">
        <div class="icon-container {{ $type }}">
            <i class="fas {{ $icon }}"></i>
        </div>

        <h1>{{ $title }}</h1>
        <p class="message">{!! $message !!}</p>

        @if(isset($details) && $details)
        <div class="details-box">
            <h3><i class="fas fa-info-circle"></i> Informasi Akun</h3>
            <ul class="details-list">
                @foreach($details as $key => $value)
                <li>
                    <i
                        class="fas {{ $key === 'email' ? 'fa-envelope' : ($key === 'username' ? 'fa-user' : ($key === 'nomor_vendor' ? 'fa-id-card' : 'fa-check')) }}"></i>
                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                    <span style="margin-left: 10px;">{{ $value }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(isset($next_steps) && $next_steps)
        <div class="details-box">
            <h3><i class="fas fa-list-check"></i> Langkah Selanjutnya</h3>
            <ol style="text-align: left; padding-left: 20px; color: #666;">
                @foreach($next_steps as $step)
                <li style="margin: 10px 0;">{{ $step }}</li>
                @endforeach
            </ol>
        </div>
        @endif

        <div class="btn-container">
            @if($type === 'success')
            <a href="{{ url('/login') }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                Login Sekarang
            </a>
            @endif

            @if($type === 'error')
            <a href="{{ url('/registration') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i>
                Daftar Ulang
            </a>
            @endif

            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i>
                Kembali ke Beranda
            </a>
        </div>

        <div class="footer-info">
            <p>
                <strong>PLN E-Prakualifikasi System</strong><br>
                Jika mengalami kendala, hubungi:
                <a href="mailto:perencanaan@plnnpc.com">perencanaan@plnnpc.com</a>
            </p>
            <p style="margin-top: 10px;">
                © {{ date('Y') }} PT PLN Nusantara Power Construction. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>