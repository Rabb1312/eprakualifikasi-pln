<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - PLN E-Prakualifikasi</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(rgba(30, 60, 114, 0.8), rgba(30, 60, 114, 0.8)),
        url('{{ asset("assets/pln1.jpg") }}') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .result-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        padding: 60px 40px;
        text-align: center;
        max-width: 500px;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .result-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #1e3c72, #2a5298, #ffd700);
    }

    .icon-container {
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3em;
        color: white;
    }

    .icon-container.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .icon-container.error {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    }

    h1 {
        color: #1e3c72;
        font-size: 2.2em;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .message {
        color: #666;
        font-size: 1.1em;
        line-height: 1.6;
        margin-bottom: 40px;
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
        margin: 0 10px 10px 0;
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

    @media (max-width: 768px) {
        .result-container {
            padding: 40px 25px;
            margin: 10px;
        }

        .btn {
            width: 100%;
            justify-content: center;
            margin: 10px 0;
        }
    }
    </style>
</head>

<body>
    <div class="result-container">
        <div class="icon-container {{ $type }}">
            @if($type === 'success')
            <i class="fas fa-check-circle"></i>
            @else
            <i class="fas fa-exclamation-triangle"></i>
            @endif
        </div>

        <h1>{{ $title }}</h1>
        <p class="message">{{ $message }}</p>

        <div class="btn-container">
            @if($type === 'success')
            <a href="{{ url('/login') }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                Login Sekarang
            </a>
            @endif

            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

</html>