@extends('layouts.admin')

@section('title', 'How We Work - RisingCare')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">How We Work</h1>
        <p class="text-gray-600">Kelola langkah-langkah cara kerja di landing page</p>
    </div>
    <button onclick="openCreateModal()" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Step
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Section Settings</h2>
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Section Title</label>
                <input type="text" name="settings[landing_how_we_work_title]" value="{{ $sectionTitle ?? 'Alur Layanan' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Section Subtitle</label>
                <input type="text" name="settings[landing_how_we_work_subtitle]" value="{{ $sectionSubtitle ?? 'Proses mudah dan cepat untuk mendapatkan layanan kesehatan' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                Simpan Settings
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Step #</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Icon</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($steps as $step)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="bg-teal-600 text-white w-10 h-10 rounded-lg flex items-center justify-center font-bold">
                            {{ $step->step_number }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $step->order }}</td>
                    <td class="px-6 py-4">
                        @if($step->icon)
                            <i class="fas fa-{{ $step->icon }} text-2xl text-teal-600"></i>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $step->title }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">{{ Str::limit($step->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium {{ $step->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                            {{ $step->is_active ? 'Aktif' : 'Non-aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-3">
                            <button type="button" onclick="openEditModal({{ $step->id }})" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form id="deleteForm{{ $step->id }}" action="{{ route('admin.landing.how-we-work.destroy', $step) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="if(confirm('Yakin ingin menghapus?')) document.getElementById('deleteForm{{ $step->id }}').submit()" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Belum ada step yang ditambahkan
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
            <h2 class="text-xl font-bold text-gray-800">Tambah Step</h2>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="createForm" method="POST" action="{{ route('admin.landing.how-we-work.store') }}">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Step Number <span class="text-red-500">*</span></label>
                    <input type="number" name="step_number" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Icon (Font Awesome)</label>
                    <input type="text" name="icon" placeholder="e.g., calendar-check" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <p class="text-xs text-gray-500 mt-1">Tanpa prefix 'fa-'</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Order <span class="text-red-500">*</span></label>
                    <input type="number" name="order" value="0" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
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
            <h2 class="text-xl font-bold text-gray-800">Edit Step</h2>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST" action="">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Step Number <span class="text-red-500">*</span></label>
                    <input type="number" name="step_number" id="edit_step_number" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="edit_title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Icon (Font Awesome)</label>
                    <input type="text" name="icon" id="edit_icon" placeholder="e.g., calendar-check" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="edit_description" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Order <span class="text-red-500">*</span></label>
                    <input type="number" name="order" id="edit_order" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
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
}

function openEditModal(id) {
    fetch(`/admin/landing/how-we-work/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit_step_number').value = data.step_number || 1;
        document.getElementById('edit_title').value = data.title || '';
        document.getElementById('edit_icon').value = data.icon || '';
        document.getElementById('edit_description').value = data.description || '';
        document.getElementById('edit_order').value = data.order || 0;
        document.getElementById('edit_is_active').checked = data.is_active;
        
        document.getElementById('editForm').action = `/admin/landing/how-we-work/${id}`;
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
