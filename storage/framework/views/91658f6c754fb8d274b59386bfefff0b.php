

<?php $__env->startSection('title', 'Articles - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Articles</h1>
        <p class="text-gray-600">Kelola artikel/blog di landing page</p>
    </div>
    <button onclick="openCreateModal()" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Article
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Section Settings</h2>
    <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Section Title</label>
                <input type="text" name="settings[landing_articles_title]" value="<?php echo e($sectionTitle ?? 'Artikel & Berita'); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Section Subtitle</label>
                <input type="text" name="settings[landing_articles_subtitle]" value="<?php echo e($sectionSubtitle ?? 'Informasi kesehatan terkini untuk Anda dan keluarga'); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
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
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Author</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Published</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <?php if($article->featured_image): ?>
                                <img src="<?php echo e(asset('storage/' . $article->featured_image)); ?>" alt="<?php echo e($article->title); ?>" class="w-12 h-12 rounded object-cover mr-3">
                            <?php else: ?>
                                <div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <div class="font-medium text-gray-900"><?php echo e($article->title); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($article->slug); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($article->category): ?>
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                <?php echo e($article->category); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?php echo e($article->author->name ?? '-'); ?>

                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?php echo e($article->published_at ? $article->published_at->format('d M Y') : '-'); ?>

                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium <?php echo e($article->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?> rounded-full">
                            <?php echo e($article->is_published ? 'Published' : 'Draft'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-3">
                            <button type="button" onclick="openEditModal(<?php echo e($article->id); ?>)" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form id="deleteForm<?php echo e($article->id); ?>" action="<?php echo e(route('admin.landing.articles.destroy', $article)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="button" onclick="if(confirm('Yakin ingin menghapus?')) document.getElementById('deleteForm<?php echo e($article->id); ?>').submit()" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada article yang ditambahkan
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-gray-800">Tambah Article</h2>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="createForm" method="POST" action="<?php echo e(route('admin.landing.articles.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Category</label>
                    <input type="text" name="category" list="categories" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <datalist id="categories">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category); ?>">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </datalist>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Excerpt <span class="text-red-500">*</span></label>
                    <textarea name="excerpt" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Content <span class="text-red-500">*</span></label>
                    <textarea id="create_content" name="content" rows="10" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Featured Image</label>
                    <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_published" value="1" class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <span class="ml-2 text-gray-700">Publish Immediately</span>
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
    <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-gray-800">Edit Article</h2>
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
                    <label class="block text-gray-700 font-medium mb-2">Category</label>
                    <input type="text" name="category" id="edit_category" list="categories" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Excerpt <span class="text-red-500">*</span></label>
                    <textarea name="excerpt" id="edit_excerpt" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" id="edit_content" rows="10" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Featured Image</label>
                    <div id="current_image"></div>
                    <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg mt-2">
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_published" id="edit_is_published" value="1" class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <span class="ml-2 text-gray-700">Published</span>
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

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createModal').classList.add('flex');
    
    // Initialize Summernote
    $('#create_content').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                uploadImage(files[0], $(this));
            }
        }
    });
}

function closeCreateModal() {
    $('#create_content').summernote('destroy');
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createModal').classList.remove('flex');
    document.getElementById('createForm').reset();
}

function openEditModal(id) {
    fetch(`/admin/landing/articles/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit_title').value = data.title || '';
        document.getElementById('edit_category').value = data.category || '';
        document.getElementById('edit_excerpt').value = data.excerpt || '';
        document.getElementById('edit_is_published').checked = data.is_published;
        
        if (data.featured_image) {
            document.getElementById('current_image').innerHTML = `<img src="/storage/${data.featured_image}" class="w-32 h-32 object-cover rounded mb-2">`;
        } else {
            document.getElementById('current_image').innerHTML = '';
        }
        
        document.getElementById('editForm').action = `/admin/landing/articles/${id}`;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
        
        // Initialize Summernote with content
        $('#edit_content').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    uploadImage(files[0], $(this));
                }
            }
        });
        
        $('#edit_content').summernote('code', data.content || '');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data');
    });
}

function closeEditModal() {
    $('#edit_content').summernote('destroy');
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    document.getElementById('editForm').reset();
}

// Upload image function
function uploadImage(file, editor) {
    const data = new FormData();
    data.append('upload', file);
    data.append('_token', '<?php echo e(csrf_token()); ?>');

    fetch('/admin/upload-image', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(result => {
        if (result.url) {
            editor.summernote('insertImage', result.url);
        } else {
            alert('Upload failed');
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('Upload error');
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/landing/articles/index.blade.php ENDPATH**/ ?>