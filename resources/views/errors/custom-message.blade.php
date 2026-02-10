<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-xl p-10 max-w-lg text-center">
        <h1 class="text-2xl font-bold text-red-600 mb-4">Terjadi Kesalahan</h1>
        <p class="text-gray-700 text-lg mb-6">{{ $message }}</p>

        <a href="/katalog"
           class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            Kembali ke Katalog
        </a>
    </div>

</body>
</html>
