@extends('layouts.admin')

@section('title', 'Testimonials - RisingCare')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Testimonials</h1>
        <p class="text-gray-600">Kelola testimoni pelanggan di landing page</p>
    </div>
    <button onclick="openCreateModal()" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Testimonial
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Comment</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Service</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Featured</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($testimonials as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($item->customer_avatar)
                                <img src="{{ asset('storage/' . $item->customer_avatar) }}" alt="{{ $item->customer_name }}" class="w-10 h-10 rounded-full object-cover mr-3">
                            @else
                                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-teal-600"></i>
                                </div>
                            @endif
                            <div class="font-medium text-gray-900">{{ $item->customer_name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $item->rating ? '' : ' text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">{{ Str::limit($item->comment, 50) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ $item->service_name ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->is_featured)
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                <i class="fas fa-star"></i> Featured
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                            {{ $item->is_active ? 'Aktif' : 'Non-aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-3">
                            <button type="button" onclick="openEditModal({{ $item->id }})" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" onclick="openGlobalDeleteModal('deleteForm{{ $item->id }}', '{{ $item->customer_name }}', 'Hapus Testimonial')" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="deleteForm{{ $item->id }}" action="{{ route('admin.landing.testimonials.destroy', $item) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Belum ada testimonial yang ditambahkan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-gray-800">Tambah Testimonial</h2>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="createForm" method="POST" action="{{ route('admin.landing.testimonials.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Customer Name <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Service Name</label>
                    <input type="text" name="service_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Rating <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-4">
                        <input type="range" name="rating" min="1" max="5" value="5" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="document.getElementById('rating_val').innerText = this.value">
                        <span id="rating_val" class="font-bold text-lg text-teal-600">5</span> <i class="fas fa-star text-yellow-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Comment <span class="text-red-500">*</span></label>
                    <textarea name="comment" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Avatar</label>
                    <input type="file" name="customer_avatar" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <span class="ml-2 text-gray-700">Featured</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <span class="ml-2 text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end gap-3 sticky bottom-0 bg-white">
                <button type="button" onclick="closeCreateModal()" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition">Batal</button>
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-gray-800">Edit Testimonial</h2>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Customer Name <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" id="edit_customer_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Service Name</label>
                    <input type="text" name="service_name" id="edit_service_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Rating <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-4">
                        <input type="range" name="rating" id="edit_rating" min="1" max="5" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="document.getElementById('edit_rating_val').innerText = this.value">
                        <span id="edit_rating_val" class="font-bold text-lg text-teal-600">5</span> <i class="fas fa-star text-yellow-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Comment <span class="text-red-500">*</span></label>
                    <textarea name="comment" id="edit_comment" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Avatar</label>
                    <div id="current_avatar"></div>
                    <input type="file" name="customer_avatar" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg mt-2">
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" id="edit_is_featured" value="1" class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <span class="ml-2 text-gray-700">Featured</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <span class="ml-2 text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end gap-3 sticky bottom-0 bg-white">
                <button type="button" onclick="closeEditModal()" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition">Batal</button>
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createModal').classList.add('flex');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createModal').classList.remove('flex');
    document.getElementById('createForm').reset();
    document.getElementById('rating_val').innerText = '5';
}

function openEditModal(id) {
    fetch(`/admin/landing/testimonials/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit_customer_name').value = data.customer_name || '';
        document.getElementById('edit_service_name').value = data.service_name || '';
        document.getElementById('edit_rating').value = data.rating || 5;
        document.getElementById('edit_rating_val').innerText = data.rating || 5;
        document.getElementById('edit_comment').value = data.comment || '';
        document.getElementById('edit_is_featured').checked = data.is_featured;
        document.getElementById('edit_is_active').checked = data.is_active;
        
        if (data.customer_avatar) {
            document.getElementById('current_avatar').innerHTML = `<img src="/storage/${data.customer_avatar}" class="w-16 h-16 rounded-full object-cover mb-2">`;
        } else {
            document.getElementById('current_avatar').innerHTML = '';
        }
        
        document.getElementById('editForm').action = `/admin/landing/testimonials/${id}`;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data');
    });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    document.getElementById('editForm').reset();
}

// Close modal when clicking outside
document.getElementById('createModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeCreateModal();
});
document.getElementById('editModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endsection
