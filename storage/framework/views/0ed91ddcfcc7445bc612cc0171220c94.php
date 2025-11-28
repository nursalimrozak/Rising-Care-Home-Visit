

<?php $__env->startSection('title', 'Hero Slides - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Hero Slides</h1>
        <p class="text-gray-600">Kelola hero slider di landing page</p>
    </div>
    <button onclick="openCreateModal()" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Hero Slide
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subtitle</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">CTA</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?php echo e($slide->order); ?></td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900"><?php echo e($slide->title); ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600"><?php echo e(Str::limit($slide->subtitle, 50)); ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600"><?php echo e($slide->cta_text ?? '-'); ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium <?php echo e($slide->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?> rounded-full">
                            <?php echo e($slide->is_active ? 'Aktif' : 'Non-aktif'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-3">
                            <button type="button" onclick="openEditModal(<?php echo e($slide->id); ?>)" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form id="deleteForm<?php echo e($slide->id); ?>" action="<?php echo e(route('admin.landing.hero-slides.destroy', $slide)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="button" onclick="if(confirm('Yakin ingin menghapus?')) document.getElementById('deleteForm<?php echo e($slide->id); ?>').submit()" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada hero slide yang ditambahkan
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-gray-800">Tambah Hero Slide</h2>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="createForm" method="POST" action="<?php echo e(route('admin.landing.hero-slides.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Subtitle</label>
                    <textarea name="subtitle" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">CTA Text</label>
                        <input type="text" name="cta_text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">CTA Link</label>
                        <input type="text" name="cta_link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Background Image</label>
                    <input type="file" name="background_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
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
            <h2 class="text-xl font-bold text-gray-800">Edit Hero Slide</h2>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="PUT">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="edit_title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Subtitle</label>
                    <textarea name="subtitle" id="edit_subtitle" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">CTA Text</label>
                        <input type="text" name="cta_text" id="edit_cta_text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">CTA Link</label>
                        <input type="text" name="cta_link" id="edit_cta_link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Background Image</label>
                    <div id="current_image"></div>
                    <input type="file" name="background_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg mt-2">
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

function openEditModal(slideId) {
    fetch(`/admin/landing/hero-slides/${slideId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit_title').value = data.title || '';
        document.getElementById('edit_subtitle').value = data.subtitle || '';
        document.getElementById('edit_description').value = data.description || '';
        document.getElementById('edit_cta_text').value = data.cta_text || '';
        document.getElementById('edit_cta_link').value = data.cta_link || '';
        document.getElementById('edit_order').value = data.order || 0;
        document.getElementById('edit_is_active').checked = data.is_active;
        
        if (data.background_image) {
            document.getElementById('current_image').innerHTML = `<img src="/storage/${data.background_image}" class="w-full h-32 object-cover rounded-lg mb-2">`;
        } else {
            document.getElementById('current_image').innerHTML = '';
        }
        
        document.getElementById('editForm').action = `/admin/landing/hero-slides/${slideId}`;
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/landing/hero-slides/index.blade.php ENDPATH**/ ?>