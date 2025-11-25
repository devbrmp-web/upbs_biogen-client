<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>503 — Server Busy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">

    <div class="text-center max-w-xl mx-auto">
        <h1 class="text-7xl font-extrabold text-gray-800">503</h1>
        <h2 class="text-2xl font-semibold text-gray-600 mt-2">Error</h2>

        <p class="mt-4 text-gray-700">
            the server is temporarily unable to handle the request, 
        </p>

        <p class="mt-2 text-gray-500 text-sm tracking-widest">
            ERR_SERVICE_UNAVAILABLE
        </p>
    </div>

    <!-- Cards Stack -->
    <div class="relative mt-12 flex justify-center">
        <!-- Card Layers -->
        <div class="relative w-[300px] h-[200px]">
            
            <!-- Layer 1 -->
            <div class="absolute inset-0 bg-white shadow-lg rounded-xl transform -translate-x-[125px] -translate-y-[25px] scale-[0.75]">
                <div class="p-4">
                    <div class="flex gap-2 mb-3">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                    <ul class="text-gray-300">
                    </ul>
                </div>
            </div>

            <!-- Layer 2 -->
            <div class="absolute inset-0 bg-white shadow-lg rounded-xl transform -translate-x-[100px] -translate-y-[20px] scale-[0.8]">
                <div class="p-4">
                    <div class="flex gap-2 mb-3">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                </div>
            </div>

            <!-- Layer 3 -->
            <div class="absolute inset-0 bg-white shadow-lg rounded-xl transform -translate-x-[75px] -translate-y-[15px] scale-[0.85]">
                <div class="p-4">
                    <div class="flex gap-2 mb-3">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                </div>
            </div>

            <!-- Layer 4 -->
            <div class="absolute inset-0 bg-white shadow-lg rounded-xl transform -translate-x-[50px] -translate-y-[10px] scale-[0.9]">
                <div class="p-4">
                    <div class="flex gap-2 mb-3">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                </div>
            </div>

            <!-- Layer 5 -->
            <div class="absolute inset-0 bg-white shadow-lg rounded-xl transform -translate-x-[25px] -translate-y-[5px] scale-[0.95]">
                <div class="p-4">
                    <div class="flex gap-2 mb-3">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                </div>
            </div>

            <!-- Top Layer -->
            <div class="absolute inset-0 bg-white shadow-xl rounded-xl">
                <div class="p-4">
                    <div class="flex gap-2 mb-3">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="fixed bottom-0 w-full h-12 border-t border-gray-300 flex items-center justify-center">
        <p class="text-gray-500 text-sm">
            UPBS BRMP BIOGEN 2025
        </p>
    </footer>

</body>
</html>
