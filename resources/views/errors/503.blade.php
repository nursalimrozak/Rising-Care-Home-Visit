<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Sedang Maintenance | RisingCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Error Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header with Icon -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full mb-4">
                    <i class="fas fa-wrench text-white text-5xl"></i>
                </div>
                <h1 class="text-6xl font-bold text-white mb-2">503</h1>
                <p class="text-xl text-purple-100 font-medium">Sedang Maintenance</p>
            </div>

            <!-- Content -->
            <div class="p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    Kami Sedang Melakukan Perawatan
                </h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Website sedang dalam mode maintenance untuk peningkatan kualitas layanan. 
                    Kami akan kembali sebentar lagi. Terima kasih atas kesabaran Anda.
                </p>

                <!-- Info Box -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-clock text-purple-600 mt-1"></i>
                        <div class="text-left">
                            <p class="text-sm text-purple-800 font-medium mb-1">Informasi:</p>
                            <ul class="text-sm text-purple-700 space-y-1">
                                <li>• Maintenance biasanya berlangsung 15-30 menit</li>
                                <li>• Silakan coba lagi dalam beberapa saat</li>
                                <li>• Data Anda tetap aman selama proses ini</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button onclick="location.reload()" 
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg font-medium hover:from-purple-600 hover:to-purple-700 transition shadow-lg">
                        <i class="fas fa-sync-alt"></i>
                        <span>Coba Lagi</span>
                    </button>
                </div>

                <!-- Support Link -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Ada pertanyaan? 
                        <a href="mailto:support@risingcare.com" class="text-teal-600 hover:text-teal-700 font-medium">
                            Hubungi Support
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} RisingCare. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Auto Refresh Script -->
    <script>
        // Auto refresh setiap 30 detik
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
