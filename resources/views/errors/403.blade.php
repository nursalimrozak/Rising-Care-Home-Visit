<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | RisingCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Error Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header with Icon -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full mb-4">
                    <i class="fas fa-ban text-white text-5xl"></i>
                </div>
                <h1 class="text-6xl font-bold text-white mb-2">403</h1>
                <p class="text-xl text-red-100 font-medium">Akses Ditolak</p>
            </div>

            <!-- Content -->
            <div class="p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    Maaf, Anda Tidak Memiliki Izin
                </h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Anda tidak memiliki izin untuk mengakses halaman ini. 
                    Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
                </p>

                <!-- Info Box -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-red-600 mt-1"></i>
                        <div class="text-left">
                            <p class="text-sm text-red-800 font-medium mb-1">Kemungkinan Penyebab:</p>
                            <ul class="text-sm text-red-700 space-y-1">
                                <li>• Anda tidak login dengan akun yang tepat</li>
                                <li>• Role/peran Anda tidak memiliki akses ke halaman ini</li>
                                <li>• Sesi login Anda telah berakhir</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ url()->previous() }}" 
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <a href="{{ route('beranda') }}" 
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg font-medium hover:from-teal-600 hover:to-teal-700 transition shadow-lg">
                        <i class="fas fa-home"></i>
                        <span>Ke Beranda</span>
                    </a>
                </div>

                <!-- Support Link -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Butuh bantuan? 
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
</body>
</html>
