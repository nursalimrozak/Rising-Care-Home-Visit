

<?php $__env->startSection('title', 'Profil Saya - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Profil Saya</h1>
        <p class="text-gray-500 mt-1">Kelola informasi pribadi dan data pembayaran Anda.</p>
    </div>
    <div x-data>
        <button @click="$dispatch('open-profile-modal')" class="bg-teal-600 text-white px-5 py-2.5 rounded-xl hover:bg-teal-700 font-medium flex items-center shadow-lg shadow-teal-600/20 transition-all hover:shadow-teal-600/30 hover:-translate-y-0.5">
            <i class="fas fa-edit mr-2"></i> Edit Profil
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left Column: Profile Card (4 cols) -->
    <div class="lg:col-span-4 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
            <div class="h-32 bg-gradient-to-r from-teal-500 to-emerald-600"></div>
            <div class="px-6 pb-6">
                <div class="relative flex justify-center -mt-16 mb-4">
                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-md overflow-hidden bg-white">
                        <?php if($user->avatar): ?>
                            <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-4xl font-bold text-gray-400">
                                <?php echo e(substr($user->name, 0, 1)); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900"><?php echo e($user->name); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo e($user->email); ?></p>
                    <div class="mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <span class="w-2 h-2 mr-1.5 rounded-full <?php echo e($user->is_active ? 'bg-green-500' : 'bg-red-500'); ?>"></span>
                            <?php echo e($user->is_active ? 'Akun Aktif' : 'Non-Aktif'); ?>

                        </span>
                    </div>
                </div>

                <div class="space-y-4 border-t border-gray-100 pt-6">
                    <div class="flex items-center text-sm">
                        <div class="w-8 flex-shrink-0 text-center text-gray-400">
                            <i class="fas fa-phone"></i>
                        </div>
                        <span class="text-gray-600 truncate"><?php echo e($user->phone ?? '-'); ?></span>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-8 flex-shrink-0 text-center text-gray-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <span class="text-gray-600 line-clamp-2"><?php echo e($user->address ?? '-'); ?></span>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-8 flex-shrink-0 text-center text-gray-400">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <span class="text-gray-600"><?php echo e($user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d M Y') : '-'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-alt text-teal-500 mr-2"></i> Dokumen
            </h3>
            <div class="space-y-3">
                <?php
                    $documents = $user->documents->keyBy('document_type');
                    $docTypes = [
                        'ktp' => 'KTP',
                        'sim' => 'SIM',
                        'certificate' => 'Sertifikat'
                    ];
                ?>

                <?php $__currentLoopData = $docTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-3 rounded-xl <?php echo e(isset($documents[$type]) ? 'bg-green-50 border border-green-100' : 'bg-gray-50 border border-gray-100'); ?>">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e(isset($documents[$type]) ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-400'); ?>">
                                <i class="fas <?php echo e(isset($documents[$type]) ? 'fa-check' : 'fa-times'); ?> text-xs"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium <?php echo e(isset($documents[$type]) ? 'text-gray-900' : 'text-gray-500'); ?>"><?php echo e($label); ?></span>
                        </div>
                        <?php if(isset($documents[$type])): ?>
                            <a href="<?php echo e(asset('storage/' . $documents[$type]->file_path)); ?>" target="_blank" class="text-teal-600 hover:text-teal-800 text-xs font-medium">Lihat</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Details (8 cols) -->
    <div class="lg:col-span-8 space-y-6">
        <!-- Payout Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class="fas fa-credit-card text-teal-500 mr-2"></i> Detail Pembayaran
                </h3>
                <?php if(!$user->payoutDetail): ?>
                    <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-lg">Belum Lengkap</span>
                <?php endif; ?>
            </div>
            <div class="p-6">
                <?php if($user->payoutDetail): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl p-6 text-white shadow-lg relative overflow-hidden group">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl group-hover:scale-110 transition-transform duration-500"></div>
                            <div class="relative z-10">
                                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Provider</p>
                                <p class="text-xl font-bold mb-6"><?php echo e($user->payoutDetail->provider_name); ?></p>
                                
                                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Nomor Akun</p>
                                <p class="text-lg font-mono tracking-wider mb-4"><?php echo e($user->payoutDetail->account_number); ?></p>
                                
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Atas Nama</p>
                                        <p class="font-medium"><?php echo e($user->payoutDetail->account_holder_name); ?></p>
                                    </div>
                                    <div class="bg-white/20 px-2 py-1 rounded text-xs font-bold uppercase">
                                        <?php echo e($user->payoutDetail->payment_type); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col justify-center space-y-4 text-sm text-gray-600">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-teal-500 mt-0.5 mr-3"></i>
                                <p>Pastikan data pembayaran ini benar. Komisi akan ditransfer ke rekening ini secara berkala.</p>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-shield-alt text-teal-500 mt-0.5 mr-3"></i>
                                <p>Data rekening Anda aman dan hanya digunakan untuk keperluan pembayaran komisi.</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
                            <i class="fas fa-wallet text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Data Pembayaran</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">Silakan lengkapi data pembayaran Anda agar komisi dapat dicairkan tepat waktu.</p>
                        <button @click="$dispatch('open-profile-modal')" class="text-teal-600 font-medium hover:text-teal-700 hover:underline">
                            Lengkapi Sekarang &rarr;
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Activity / Stats (Placeholder for future) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Total Komisi</p>
                <p class="text-2xl font-bold text-gray-900">Rp <?php echo e(number_format($user->commissions->sum('amount'), 0, ',', '.')); ?></p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Booking Selesai</p>
                <p class="text-2xl font-bold text-gray-900"><?php echo e($user->bookingsAsPetugas()->where('status', 'completed')->count()); ?></p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Rating</p>
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-gray-900 mr-2">5.0</span>
                    <div class="flex text-yellow-400 text-sm">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div x-data="{ 
    open: <?php echo e($errors->any() ? 'true' : 'false'); ?>,
    paymentType: '<?php echo e(old('payment_type', $user->payoutDetail->payment_type ?? 'bank')); ?>',
    tab: '<?php echo e($errors->hasAny(['payment_type', 'provider_name', 'account_number', 'account_holder_name']) ? 'payment' : ($errors->hasAny(['document_ktp', 'document_sim', 'document_certificate']) ? 'documents' : ($errors->hasAny(['current_password', 'new_password']) ? 'password' : 'personal'))); ?>'
}" 
@open-profile-modal.window="open = true"
@keydown.escape.window="open = false"
class="relative z-50" 
aria-labelledby="modal-title" 
role="dialog" 
aria-modal="true"
x-show="open" 
x-cloak>

    <!-- Backdrop -->
    <div x-show="open" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div x-show="open" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                
                <!-- Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900" id="modal-title">Edit Profil</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form action="<?php echo e(route('petugas.profile.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="px-6 py-6">
                        <!-- Tabs -->
                        <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl mb-6">
                            <button type="button" @click="tab = 'personal'" :class="{'bg-white text-teal-700 shadow-sm': tab === 'personal', 'text-gray-500 hover:text-gray-700': tab !== 'personal'}" class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                Data Pribadi
                            </button>
                            <button type="button" @click="tab = 'payment'" :class="{'bg-white text-teal-700 shadow-sm': tab === 'payment', 'text-gray-500 hover:text-gray-700': tab !== 'payment'}" class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                Pembayaran
                            </button>
                            <button type="button" @click="tab = 'documents'" :class="{'bg-white text-teal-700 shadow-sm': tab === 'documents', 'text-gray-500 hover:text-gray-700': tab !== 'documents'}" class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                Dokumen
                            </button>
                            <button type="button" @click="tab = 'password'" :class="{'bg-white text-teal-700 shadow-sm': tab === 'password', 'text-gray-500 hover:text-gray-700': tab !== 'password'}" class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                Password
                            </button>
                        </div>

                        <!-- Personal Tab -->
                        <div x-show="tab === 'personal'" class="space-y-5">
                            <div class="flex items-center space-x-6 mb-6">
                                <div class="shrink-0">
                                    <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-2 border-gray-100">
                                        <?php if($user->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" class="h-full w-full object-cover">
                                        <?php else: ?>
                                            <span class="text-2xl font-bold text-gray-400"><?php echo e(substr($user->name, 0, 1)); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                                    <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition-colors">
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG or GIF. Max 2MB.</p>
                                    <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                                    <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth', $user->date_of_birth)); ?>" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                    <select name="gender" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Pilih</option>
                                        <option value="male" <?php echo e(old('gender', $user->gender) == 'male' ? 'selected' : ''); ?>>Laki-laki</option>
                                        <option value="female" <?php echo e(old('gender', $user->gender) == 'female' ? 'selected' : ''); ?>>Perempuan</option>
                                    </select>
                                    <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea name="address" rows="2" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('address', $user->address)); ?></textarea>
                                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Tab -->
                        <div x-show="tab === 'payment'" class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Pembayaran</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_type" value="bank" x-model="paymentType" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-teal-500 peer-checked:bg-teal-50 transition-all text-center">
                                            <i class="fas fa-university text-2xl mb-2 text-gray-400 peer-checked:text-teal-600"></i>
                                            <p class="text-sm font-medium text-gray-700 peer-checked:text-teal-800">Transfer Bank</p>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_type" value="ewallet" x-model="paymentType" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-teal-500 peer-checked:bg-teal-50 transition-all text-center">
                                            <i class="fas fa-mobile-alt text-2xl mb-2 text-gray-400 peer-checked:text-teal-600"></i>
                                            <p class="text-sm font-medium text-gray-700 peer-checked:text-teal-800">E-Wallet</p>
                                        </div>
                                    </label>
                                </div>
                                <?php $__errorArgs = ['payment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" x-text="paymentType === 'bank' ? 'Nama Bank' : 'Nama E-Wallet'">Nama Provider</label>
                                    <input type="text" name="provider_name" value="<?php echo e(old('provider_name', $user->payoutDetail->provider_name ?? '')); ?>" placeholder="Contoh: BCA, Mandiri, OVO, Dana" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['provider_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['provider_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" x-text="paymentType === 'bank' ? 'Nomor Rekening' : 'Nomor HP E-Wallet'">Nomor Akun</label>
                                    <input type="text" name="account_number" value="<?php echo e(old('account_number', $user->payoutDetail->account_number ?? '')); ?>" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Atas Nama</label>
                                    <input type="text" name="account_holder_name" value="<?php echo e(old('account_holder_name', $user->payoutDetail->account_holder_name ?? '')); ?>" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['account_holder_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['account_holder_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Tab -->
                        <div x-show="tab === 'documents'" class="space-y-5">
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                                <p class="text-sm text-blue-700">
                                    Upload dokumen terbaru jika ingin memperbarui. Kosongkan jika tidak ada perubahan.
                                    <br>Format: JPG, PNG, PDF. Max: 2MB.
                                </p>
                            </div>

                            <div class="space-y-4">
                                <?php $__currentLoopData = ['ktp' => 'KTP (Kartu Tanda Penduduk)', 'sim' => 'SIM (Surat Izin Mengemudi)', 'certificate' => 'Sertifikat Keahlian']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-xl p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e($label); ?></label>
                                    <input type="file" name="document_<?php echo e($key); ?>" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition-colors">
                                    <?php $__errorArgs = ['document_'.$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Password Tab -->
                        <div x-show="tab === 'password'" class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                                <input type="password" name="current_password" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <input type="password" name="new_password" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex flex-row-reverse gap-3">
                        <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-teal-500 sm:w-auto transition-colors">Simpan Perubahan</button>
                        <button type="button" @click="open = false" class="inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto transition-colors">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/petugas/profile.blade.php ENDPATH**/ ?>