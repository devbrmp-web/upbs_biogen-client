<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Tidak Tersedia - UPBS BRMP Biogen</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f9fafb, #faf5ff);
            padding: 1rem;
            overflow: hidden;
            position: relative;
        }

        /* Background decoration */
        body::before, body::after {
            content: '';
            position: absolute;
            width: 24rem;
            height: 24rem;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
        }
        body::before {
            top: 25%;
            left: 25%;
            background: rgba(192, 132, 252, 0.15);
            transform: translate(-50%, -50%);
        }
        body::after {
            bottom: 25%;
            right: 25%;
            background: rgba(244, 114, 182, 0.15);
            transform: translate(50%, 50%);
        }

        .card {
            position: relative;
            z-index: 10;
            max-width: 28rem;
            width: 100%;
            text-align: center;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 20px 25px -5px rgba(88, 28, 135, 0.05);
        }

        @media (min-width: 768px) {
            .card { padding: 3rem; }
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(to right, rgba(192, 132, 252, 0.8), rgba(244, 114, 182, 0.8));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            user-select: none;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {
            .error-title { font-size: 1.875rem; }
        }

        .error-message {
            color: #4b5563;
            line-height: 1.625;
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            background: linear-gradient(to right, #9333ea, #db2777);
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            border-radius: 9999px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            box-shadow: 0 10px 15px -3px rgba(147, 51, 234, 0.3);
            transform: translateY(-2px);
        }

        .btn svg {
            width: 1.25rem;
            height: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="error-code">503</h1>
        <div>
            <h2 class="error-title">Sedang Dalam Pemeliharaan</h2>
            <p class="error-message">
                Sistem kami sedang dalam pemeliharaan terjadwal untuk meningkatkan layanan. Silakan kembali lagi beberapa saat lagi.
            </p>
            <div>
                <a href="/" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Coba Lagi
                </a>
            </div>
        </div>
    </div>
</body>
</html>
