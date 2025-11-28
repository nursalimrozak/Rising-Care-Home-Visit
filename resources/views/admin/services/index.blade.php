@extends('layouts.admin')

@section('title', 'Manajemen Layanan - RisingCare')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Layanan</h1>
        <p class="text-gray-600">Kelola daftar layanan dan harga</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.service-categories.index') }}" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-tags mr-2"></i> Kategori
        </a>
        <a href="{{ route('admin.services.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
            <i class="fas fa-plus mr-2"></i> Tambah Layanan
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nama Layanan</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Durasi</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Harga Dasar</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($services as $service)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $service->name }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($service->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($service->category)
                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                {{ $service->category->name }}
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                Tidak ada kategori
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $service->duration_minutes }} Menit
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        Rp {{ number_format($service->base_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                            {{ $service->is_active ? 'Aktif' : 'Non-aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-3">
                            <button type="button" onclick="openEditModal({{ $service->id }})" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <form id="deleteForm{{ $service->id }}" action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal('deleteForm{{ $service->id }}', '{{ addslashes($service->name) }}')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada layanan yang ditambahkan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-gray-800">Edit Layanan</h2>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST" action="">
            @csrf
            <input type="hidden" name="_method" value="PUT" id="edit_method">
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h3>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Nama Layanan</label>
                            <input type="text" name="name" id="edit_name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                            <select name="category_id" id="edit_category" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Tipe Layanan</label>
                            <select name="service_type" id="edit_service_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                <option value="both">On-Site & Home Visit</option>
                                <option value="on_site">On-Site Saja</option>
                                <option value="home_visit">Home Visit Saja</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Durasi (Menit)</label>
                            <input type="number" name="duration_minutes" id="edit_duration" required min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                            <textarea name="description" id="edit_description" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                    class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <span class="ml-2 text-gray-700">Layanan Aktif</span>
                            </label>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Harga Membership</h3>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Harga per Membership</p>
                            
                            <div id="membershipPrices">
                                @foreach($memberships ?? [] as $membership)
                                    <div class="mb-3">
                                        <label class="block text-sm text-gray-600 mb-1">{{ $membership->name }}</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                            <input type="number" name="prices[{{ $membership->id }}]" 
                                                id="edit_price_{{ $membership->id }}"
                                                min="0"
                                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm"
                                                placeholder="Kosongkan untuk ikut harga dasar">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex justify-end gap-3 sticky bottom-0 bg-white">
                <button type="button" onclick="closeEditModal()" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">
                    Perbarui Layanan
                </button>
            </div>
        </form>
    </div>
</div>

@include('components.admin.delete-modal')

<script>
function openEditModal(serviceId) {
    // Fetch service data with proper headers
    fetch(`/admin/services/${serviceId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Populate form fields
            document.getElementById('edit_name').value = data.service.name || '';
            document.getElementById('edit_category').value = data.service.category_id || '';
            document.getElementById('edit_service_type').value = data.service.service_type || 'both';
            document.getElementById('edit_duration').value = data.service.duration_minutes || '';
            document.getElementById('edit_description').value = data.service.description || '';
            document.getElementById('edit_is_active').checked = data.service.is_active;
            
            // Populate membership prices
            if (data.memberships) {
                data.memberships.forEach(membership => {
                    const priceInput = document.getElementById(`edit_price_${membership.id}`);
                    if (priceInput && data.currentPrices[membership.id]) {
                        priceInput.value = data.currentPrices[membership.id];
                    } else if (priceInput) {
                        priceInput.value = '';
                    }
                });
            }
            
            // Set form action
            document.getElementById('editForm').action = `/admin/services/${serviceId}`;
            
            // Show modal
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data layanan');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    document.getElementById('editForm').reset();
}

// Close modal when clicking outside
document.getElementById('editModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endsection
