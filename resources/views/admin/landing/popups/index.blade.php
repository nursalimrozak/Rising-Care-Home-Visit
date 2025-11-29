@extends('layouts.admin')

@section('title', 'Kelola Pop-ups - Admin RisingCare')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Pop-ups Landing Page</h1>
        <p class="text-gray-600">Maksimal 3 pop-up yang bisa diaktifkan</p>
    </div>
    <button onclick="openCreateModal()" class="bg-teal-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-teal-700 transition shadow-md">
        <i class="fas fa-plus mr-2"></i> Tambah Pop-up
    </button>
</div>

@if($activeCount >= 3)
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
        <p class="font-bold">Perhatian</p>
        <p>Sudah ada {{ $activeCount }} pop-up aktif. Nonaktifkan salah satu untuk mengaktifkan yang lain.</p>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Preview</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Link</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($popups as $popup)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}" class="h-16 w-24 object-cover rounded">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $popup->title }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($popup->link)
                            <a href="{{ $popup->link }}" target="_blank" class="text-teal-600 hover:underline">
                                <i class="fas fa-external-link-alt mr-1"></i> Link
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $popup->order }}
                    </td>
                    <td class="px-6 py-4">
                        <button 
                            onclick="toggleActive({{ $popup->id }}, this)"
                            class="px-3 py-1 rounded-full text-xs font-medium {{ $popup->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}"
                        >
                            {{ $popup->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="openEditModal({{ $popup->id }})" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" onclick="openGlobalDeleteModal('deleteForm{{ $popup->id }}', '{{ $popup->title }}', 'Hapus Pop-up')" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="deleteForm{{ $popup->id }}" action="{{ route('admin.landing.popups.destroy', $popup) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada pop-up. <button onclick="openCreateModal()" class="text-teal-600 hover:underline">Tambah pop-up pertama</button>
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
            <h2 class="text-xl font-bold text-gray-800">Tambah Pop-up</h2>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="createForm" method="POST" action="{{ route('admin.landing.popups.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" placeholder="Contoh: Promo Spesial November">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Gambar Pop-up <span class="text-red-500">*</span></label>
                    <input type="file" name="image" accept="image/*" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" onchange="previewCreateImage(event)">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Rekomendasi: 800x600px</p>
                    <div id="createImagePreview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <img id="createPreviewImg" src="" alt="Preview" class="max-w-md rounded-lg shadow-md">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Link (Optional)</label>
                    <input type="url" name="link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" placeholder="https://example.com">
                    <p class="mt-1 text-sm text-gray-500">URL yang akan dibuka saat gambar diklik</p>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan pop-up ini</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-500">Maksimal 3 pop-up yang bisa diaktifkan</p>
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
            <h2 class="text-xl font-bold text-gray-800">Edit Pop-up</h2>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="edit_title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Gambar Pop-up</label>
                    <div id="current_image"></div>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg mt-2" onchange="previewEditImage(event)">
                    <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah gambar</p>
                    <div id="editImagePreview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview Baru:</p>
                        <img id="editPreviewImg" src="" alt="Preview" class="max-w-md rounded-lg shadow-md">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Link (Optional)</label>
                    <input type="url" name="link" id="edit_link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Urutan Tampilan <span class="text-red-500">*</span></label>
                    <input type="number" name="order" id="edit_order" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <p class="mt-1 text-sm text-gray-500">Angka lebih kecil akan ditampilkan lebih dulu</p>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan pop-up ini</span>
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
    document.getElementById('createImagePreview').classList.add('hidden');
}

function previewCreateImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('createPreviewImg').src = e.target.result;
            document.getElementById('createImagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function openEditModal(popupId) {
    fetch(`/admin/landing/popups/${popupId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit_title').value = data.title || '';
        document.getElementById('edit_link').value = data.link || '';
        document.getElementById('edit_order').value = data.order || 1;
        document.getElementById('edit_is_active').checked = data.is_active;
        
        if (data.image) {
            document.getElementById('current_image').innerHTML = `
                <p class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</p>
                <img src="/storage/${data.image}" class="max-w-md rounded-lg shadow-md mb-2">
            `;
        } else {
            document.getElementById('current_image').innerHTML = '';
        }
        
        document.getElementById('editImagePreview').classList.add('hidden');
        document.getElementById('editForm').action = `/admin/landing/popups/${popupId}`;
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
    document.getElementById('editImagePreview').classList.add('hidden');
}

function previewEditImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('editPreviewImg').src = e.target.result;
            document.getElementById('editImagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function toggleActive(popupId, button) {
    fetch(`/admin/landing/popups/${popupId}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button appearance
            if (data.is_active) {
                button.className = 'px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                button.textContent = 'Aktif';
            } else {
                button.className = 'px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                button.textContent = 'Nonaktif';
            }
            
            // Show success message and reload
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah status');
    });
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
