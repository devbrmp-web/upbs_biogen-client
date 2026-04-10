<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesalahan Server - UPBS BRMP Biogen</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f9fafb, #fef2f2);
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
            background: rgba(252, 165, 165, 0.15);
            transform: translate(-50%, -50%);
        }
        body::after {
            bottom: 25%;
            right: 25%;
            background: rgba(253, 186, 116, 0.15);
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
            box-shadow: 0 20px 25px -5px rgba(127, 29, 29, 0.05);
        }

        @media (min-width: 768px) {
            .card { padding: 3rem; }
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(to right, rgba(248, 113, 113, 0.8), rgba(251, 146, 60, 0.8));
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
            background: linear-gradient(to right, #dc2626, #ea580c);
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
            box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.3);
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
        <h1 class="error-code">500</h1>
        <div>
            <h2 class="error-title">Terjadi Kesalahan Server</h2>
            <p class="error-message">
                Maaf, terjadi kesalahan internal pada server kami. Tim kami sedang bekerja untuk memperbaikinya.
            </p>
            <div>
                <a href="/" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
