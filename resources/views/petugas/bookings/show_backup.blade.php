@extends('layouts.petugas')

@section('title', 'Detail Booking - RisingCare')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Detail Booking</h1>
        <a href="{{ route('petugas.bookings') }}" class="text-teal-600 hover:text-teal-800 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content (Left - 2 columns) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Booking Info Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Informasi Booking</h2>
                    <p class="text-sm text-gray-500 font-mono">{{ $booking->booking_number }}</p>
                </div>
                <div>
                    @php
                        $statusClasses = [
                            'scheduled' => 'bg-yellow-100 text-yellow-800',
                            'checked_in' => 'bg-blue-100 text-blue-800',
                            'in_progress' => 'bg-purple-100 text-purple-800',
                            'pending_payment' => 'bg-orange-100 text-orange-800',
                            'completed' => 'bg-green-100 text-green-800',
                        ];
                        $statusLabels = [
                            'scheduled' => 'Terjadwal',
                            'checked_in' => 'Check-in',
                            'in_progress' => 'Diproses',
                            'pending_payment' => 'Menunggu Pembayaran',
                            'completed' => 'Selesai',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            <!-- Timer Display -->
            @if($booking->status == 'in_progress' && $booking->started_at)
            <div class="mb-6 p-4 bg-purple-50 border-2 border-purple-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-purple-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-purple-600 font-medium">Durasi Treatment</p>
                            <p id="timer" class="text-3xl font-bold text-purple-900">00:00:00</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Dimulai</p>
                        <p class="text-sm font-medium">{{ $booking->started_at->format('H:i') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Duration (if completed) -->
            @if($booking->duration_minutes)
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-600">Total Durasi Treatment</p>
                <p class="text-2xl font-bold text-green-900">{{ floor($booking->duration_minutes / 60) }} jam {{ $booking->duration_minutes % 60 }} menit</p>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Customer</label>
                    <a href="{{ route('petugas.customers.show', $booking->customer->code) }}" class="font-medium text-teal-600 hover:text-teal-800 hover:underline">
                        {{ $booking->customer->name }}
                    </a>
                    <p class="text-sm text-gray-600">{{ $booking->customer->phone }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Layanan</label>
                    <p class="font-medium text-gray-900">{{ $booking->service->name }}</p>
                    <p class="text-sm text-gray-600">Rp {{ number_format($booking->estimated_price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Jadwal</label>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d F Y') }}</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('H:i') }} WIB</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Tipe</label>
                    <p class="font-medium text-gray-900">{{ $booking->booking_type == 'home_visit' ? 'Home Visit' : 'Klinik' }}</p>
                </div>
            </div>

            @if($booking->booking_type == 'home_visit' && $booking->customer_address)
            <div class="mt-6 pt-6 border-t">
                <label class="text-sm text-gray-500 block mb-1">Alamat</label>
                <p class="text-gray-900">{{ $booking->customer_address }}</p>
            </div>
            @endif

            @if($booking->customer_notes)
            <div class="mt-6 pt-6 border-t">
                <label class="text-sm text-gray-500 block mb-1">Catatan Customer</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $booking->customer_notes }}</p>
            </div>
            @endif
        </div>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <div id="proofUpload" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                        <input type="file" name="proof_image" accept="image/*" class="w-full px-3 py-2 border rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                    </div>

                    <button type="submit" id="paymentSubmitBtn" disabled class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i class="fas fa-check mr-2"></i> Proses Pembayaran
                    </button>
                </div>
            </form>
            @else
            <!-- Payment Status -->
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Metode:</span>
                    <span class="font-medium">{{ $booking->payment->payment_method == 'cash' ? 'Cash' : 'Transfer' }}</span>
                </div>
                
                <div class="flex justify-between items-center gap-3">
                    <span class="text-gray-600">Status:</span>
                    <div class="flex items-center gap-2">
                        @if($booking->payment->payment_proof)
                        <span onclick="openImageModal('{{ asset('storage/' . $booking->payment->payment_proof) }}')" 
                            class="cursor-pointer px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium hover:bg-blue-200 transition flex items-center gap-1">
                            <i class="fas fa-receipt"></i> Bukti Transfer
                        </span>
                        @endif
                        
                        @if($booking->payment->status == 'paid')
                            <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-lg text-sm font-medium whitespace-nowrap">Lunas</span>
                        @else
                            <span class="px-3 py-1.5 bg-orange-100 text-orange-800 rounded-lg text-sm font-medium whitespace-nowrap">Menunggu Verifikasi</span>
                        @endif
                    </div>
                </div>

                @if($booking->payment->status == 'pending')
                <form action="{{ route('petugas.bookings.payment.verify', $booking) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-check-circle mr-2"></i> Verifikasi Pembayaran
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Right Sidebar (1 column) -->
    <div class="space-y-6">
        <!-- Add-ons Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Add-ons Layanan</h3>
            
            @if($booking->bookingAddons->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach($booking->bookingAddons as $bookingAddon)
                <div class="p-3 bg-gray-50 rounded">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-gray-900 font-medium text-sm">{{ $bookingAddon->addon->name }}</span>
                        <span class="text-xs text-gray-500">x{{ $bookingAddon->quantity }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">@ Rp {{ number_format($bookingAddon->price_per_item, 0, ',', '.') }}</span>
                        <span class="text-teal-600 font-medium text-sm">Rp {{ number_format($bookingAddon->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
                <div class="pt-2 border-t">
                    <div class="flex justify-between items-center font-bold text-sm">
                        <span>Total:</span>
                        <span class="text-teal-600">Rp {{ number_format($booking->bookingAddons->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @else
            <p class="text-gray-500 text-sm mb-4">Belum ada add-on</p>
            @endif

            @if($booking->status == 'pending_payment' && !$booking->payment)
            <form action="{{ route('petugas.bookings.add-addon', $booking) }}" method="POST" id="addonForm">
                @csrf
                <div id="addonRows" class="space-y-3 mb-3">
                    <!-- Dynamic rows will be added here -->
                </div>
                
                <button type="button" onclick="addAddonRow()" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 text-sm mb-3 border-2 border-dashed border-gray-300">
                    <i class="fas fa-plus mr-2"></i> Tambah Add-on
                </button>
                
                <button type="submit" id="submitAddons" class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 text-sm hidden">
                    <i class="fas fa-check mr-2"></i> Simpan Add-ons
                </button>
            </form>

            <script>
                let addonRowCount = 0;
                const availableAddons = @json($availableAddons);

                function addAddonRow() {
                    const container = document.getElementById('addonRows');
                    const rowId = addonRowCount++;
                    
                    const row = document.createElement('div');
                    row.className = 'p-3 bg-gray-50 rounded-lg border border-gray-200';
                    row.id = `addonRow${rowId}`;
                    row.innerHTML = `
                        <div class="flex items-start gap-2">
                            <div class="flex-1 space-y-2">
                                <select name="addons[${rowId}][addon_id]" required class="w-full px-2 py-1.5 border rounded text-sm">
                                    <option value="">-- Pilih --</option>
                                    ${availableAddons.map(addon => 
                                        `<option value="${addon.id}">${addon.name} - Rp ${new Intl.NumberFormat('id-ID').format(addon.price)}</option>`
                                    ).join('')}
                                </select>
                                <input type="number" name="addons[${rowId}][quantity]" value="1" min="1" required 
                                    class="w-full px-2 py-1.5 border rounded text-sm" placeholder="Qty">
                            </div>
                            <button type="button" onclick="removeAddonRow(${rowId})" 
                                class="text-red-600 hover:text-red-800 p-1.5">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    
                    container.appendChild(row);
                    toggleSubmitButton();
                }

                function removeAddonRow(rowId) {
                    const row = document.getElementById(`addonRow${rowId}`);
                    if (row) {
                        row.remove();
                        toggleSubmitButton();
                    }
                }

                function toggleSubmitButton() {
                    const container = document.getElementById('addonRows');
                    const submitBtn = document.getElementById('submitAddons');
                    submitBtn.classList.toggle('hidden', container.children.length === 0);
                }

                // Add first row by default
                addAddonRow();
            </script>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($booking->booking_type == 'on_site' && $booking->status == 'scheduled')
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Check-in Customer</h3>
            <form action="{{ route('petugas.bookings.checkin', $booking) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-check-circle mr-2"></i> Check-in
                </button>
            </form>
        </div>
        @endif

        @if(in_array($booking->status, ['scheduled', 'checked_in']))
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Mulai Treatment</h3>
            <form action="{{ route('petugas.bookings.start', $booking) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-play mr-2"></i> Mulai
                </button>
            </form>
        </div>
        @endif

        @if($booking->status == 'in_progress')
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Selesaikan Treatment</h3>
            <form action="{{ route('petugas.bookings.complete', $booking) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-stop mr-2"></i> Selesai
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- Image Modal for Bukti Transfer with Zoom -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative w-full h-full flex items-center justify-center" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 text-3xl z-10 bg-black bg-opacity-50 w-12 h-12 rounded-full">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Zoom Controls -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
            <button onclick="zoomOut()" class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70">
                <i class="fas fa-search-minus"></i>
            </button>
            <button onclick="resetZoom()" class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70">
                <i class="fas fa-compress"></i> Reset
            </button>
            <button onclick="zoomIn()" class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70">
                <i class="fas fa-search-plus"></i>
            </button>
        </div>
        
        <!-- Image Container -->
        <div class="overflow-auto max-w-full max-h-full" id="imageContainer">
            <img id="modalImage" src="" alt="Bukti Transfer" class="transition-transform duration-200" style="cursor: grab;">
        </div>
    </div>
</div>

<script>
    // Image modal with zoom functionality
    let currentZoom = 1;
    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;

    function openImageModal(imageUrl) {
        const modal = document.getElementById('imageModal');
        const img = document.getElementById('modalImage');
        img.src = imageUrl;
        modal.classList.remove('hidden');
        resetZoom();
        
        // Add wheel zoom
        const container = document.getElementById('imageContainer');
        container.addEventListener('wheel', handleWheel, { passive: false });
        
        // Add drag to pan
        img.addEventListener('mousedown', startDrag);
        img.addEventListener('mousemove', drag);
        img.addEventListener('mouseup', stopDrag);
        img.addEventListener('mouseleave', stopDrag);
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        currentZoom = 1;
    }

    function zoomIn() {
        currentZoom = Math.min(currentZoom + 0.25, 5);
        applyZoom();
    }

    function zoomOut() {
        currentZoom = Math.max(currentZoom - 0.25, 0.5);
        applyZoom();
    }

    function resetZoom() {
        currentZoom = 1;
        applyZoom();
    }

    function applyZoom() {
        const img = document.getElementById('modalImage');
        img.style.transform = `scale(${currentZoom})`;
    }

    function handleWheel(e) {
        e.preventDefault();
        if (e.deltaY < 0) {
            zoomIn();
        } else {
            zoomOut();
        }
    }

    function startDrag(e) {
        if (currentZoom > 1) {
            isDragging = true;
            const container = document.getElementById('imageContainer');
            startX = e.pageX - container.offsetLeft;
            startY = e.pageY - container.offsetTop;
            scrollLeft = container.scrollLeft;
            scrollTop = container.scrollTop;
            e.target.style.cursor = 'grabbing';
        }
    }

    function drag(e) {
        if (!isDragging) return;
        e.preventDefault();
        const container = document.getElementById('imageContainer');
        const x = e.pageX - container.offsetLeft;
        const y = e.pageY - container.offsetTop;
        const walkX = (x - startX) * 2;
        const walkY = (y - startY) * 2;
        container.scrollLeft = scrollLeft - walkX;
        container.scrollTop = scrollTop - walkY;
    }

    function stopDrag(e) {
        isDragging = false;
        e.target.style.cursor = 'grab';
    }

    // Timer functionality
    @if($booking->status == 'in_progress' && $booking->started_at)
    const startTime = new Date('{{ $booking->started_at->toIso8601String() }}').getTime();
    
    function updateTimer() {
        const now = new Date().getTime();
        const elapsed = now - startTime;
        
        const hours = Math.floor(elapsed / (1000 * 60 * 60));
        const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);
        
        document.getElementById('timer').textContent = 
            String(hours).padStart(2, '0') + ':' + 
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0');
    }
    
    updateTimer();
    setInterval(updateTimer, 1000);
    @endif

    // Payment method toggle
    const paymentMethod = document.getElementById('paymentMethod');
    const proofUpload = document.getElementById('proofUpload');
    const paymentSubmitBtn = document.getElementById('paymentSubmitBtn');
    
    if (paymentMethod) {
        paymentMethod.addEventListener('change', function() {
            // Show/hide upload field
            if (this.value === 'transfer') {
                proofUpload.classList.remove('hidden');
            } else {
                proofUpload.classList.add('hidden');
            }
            
            // Enable/disable submit button
            if (this.value) {
                paymentSubmitBtn.disabled = false;
            } else {
                paymentSubmitBtn.disabled = true;
            }
        });
    }
</script>
@endsection
