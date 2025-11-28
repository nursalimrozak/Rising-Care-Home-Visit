

<?php $__env->startSection('title', 'Manajemen Pekerjaan - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Pekerjaan</h1>
        <p class="text-gray-600">Kelola daftar pekerjaan customer berdasarkan membership tier</p>
    </div>
    <button onclick="openModal('createModal')" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Pekerjaan
    </button>
</div>

<div x-data="{ activeTab: '<?php echo e($memberships->first()->id ?? 'default'); ?>' }">
    <!-- Tabs -->
    <div class="flex space-x-4 mb-6 border-b border-gray-200">
        <?php $__currentLoopData = $memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button @click="activeTab = '<?php echo e($membership->id); ?>'" 
            :class="{ 'border-teal-600 text-teal-600': activeTab === '<?php echo e($membership->id); ?>', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== '<?php echo e($membership->id); ?>' }"
            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-<?php echo e($membership->level == 1 ? 'user' : ($membership->level == 2 ? 'briefcase' : ($membership->level == 3 ? 'crown' : 'gem'))); ?> mr-2"></i> <?php echo e($membership->name); ?>

        </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Tab Content -->
    <?php $__currentLoopData = $memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div x-show="activeTab === '<?php echo e($membership->id); ?>'" class="bg-white rounded-xl shadow-sm overflow-hidden" <?php echo e($loop->first ? '' : 'style=display:none;'); ?>>
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nama Pekerjaan</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Membership Tier</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jumlah User</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $occupationsByMembership[$membership->id] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $occupation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($occupation->name); ?></td>
                    <td class="px-6 py-4 text-gray-600"><?php echo e($occupation->description ?? '-'); ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: <?php echo e($membership->color); ?>20; color: <?php echo e($membership->color); ?>">
                            <?php echo e($membership->name); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            <?php echo e($occupation->users_count); ?> User
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-3">
                            <button onclick="editOccupation(<?php echo e($occupation->id); ?>, '<?php echo e(addslashes($occupation->name)); ?>', '<?php echo e(addslashes($occupation->description)); ?>', <?php echo e($occupation->membership_id); ?>)" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form id="deleteForm<?php echo e($occupation->id); ?>" action="<?php echo e(route('admin.occupations.destroy', $occupation)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="button" onclick="openDeleteModal('deleteForm<?php echo e($occupation->id); ?>', '<?php echo e(addslashes($occupation->name)); ?>')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        Belum ada pekerjaan untuk membership <?php echo e($membership->name); ?>

                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tambah Pekerjaan Baru</h3>
            <form action="<?php echo e(route('admin.occupations.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pekerjaan</label>
                    <input type="text" name="name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Membership Tier</label>
                    <select name="membership_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">-- Pilih Membership --</option>
                        <?php $__currentLoopData = $memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($membership->id); ?>"><?php echo e($membership->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Pekerjaan</h3>
            <form id="editForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pekerjaan</label>
                    <input type="text" name="name" id="editName" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea name="description" id="editDescription" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Membership Tier</label>
                    <select name="membership_id" id="editMembershipId" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">-- Pilih Membership --</option>
                        <?php $__currentLoopData = $memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($membership->id); ?>"><?php echo e($membership->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php echo $__env->make('components.admin.delete-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function editOccupation(id, name, description, membershipId) {
        document.getElementById('editName').value = name;
        document.getElementById('editDescription').value = description;
        document.getElementById('editMembershipId').value = membershipId;
        document.getElementById('editForm').action = `/admin/occupations/${id}`;
        openModal('editModal');
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/occupations/index.blade.php ENDPATH**/ ?>