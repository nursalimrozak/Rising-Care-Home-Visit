@extends('layouts.admin')

@section('title', 'Manajemen Rekap Kesehatan - RisingCare')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Pertanyaan Rekap Kesehatan</h1>
    <p class="text-gray-600">Atur kategori dan pertanyaan yang akan muncul di menu Rekap Kesehatan customer</p>
</div>

<div x-data="{ activeTab: 'questions', showCategoryModal: false, showQuestionForm: false, editCategory: null }" class="space-y-6">
    
    <!-- Tab Navigation -->
    <div class="bg-white rounded-xl shadow-sm p-2 flex gap-2">
        <button @click="activeTab = 'questions'" 
            :class="activeTab === 'questions' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
            class="flex-1 px-4 py-2 rounded-lg font-medium transition">
            <i class="fas fa-list-ul mr-2"></i>Pertanyaan
        </button>
        <button @click="activeTab = 'categories'" 
            :class="activeTab === 'categories' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
            class="flex-1 px-4 py-2 rounded-lg font-medium transition">
            <i class="fas fa-folder mr-2"></i>Kategori
        </button>
    </div>

    <!-- Questions Tab -->
    <div x-show="activeTab === 'questions'" class="space-y-6">
        <!-- Add Question Button -->
        <div class="flex justify-end">
            <button type="button" onclick="openAddQuestionModal()" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Pertanyaan Baru
            </button>
        </div>

        <!-- List Pertanyaan by Category -->
        <div class="space-y-4" x-data="{ openCategory: null }">
            @forelse($categories as $category)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-50 to-blue-50 border-b border-gray-200 cursor-pointer hover:bg-gradient-to-r hover:from-teal-100 hover:to-blue-100 transition"
                     @click="openCategory = openCategory === {{ $category->id }} ? null : {{ $category->id }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 flex-1">
                            <i class="fas fa-folder-open text-teal-600"></i>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $category->name }}</h3>
                                @if($category->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $category->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-500">({{ $category->questions->count() }} pertanyaan)</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"
                               :class="{ 'rotate-180': openCategory === {{ $category->id }} }"></i>
                        </div>
                    </div>
                </div>
                
                <div x-show="openCategory === {{ $category->id }}" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     style="display: none;">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 font-medium text-gray-600">Urutan</th>
                                <th class="px-6 py-3 font-medium text-gray-600">Pertanyaan</th>
                                <th class="px-6 py-3 font-medium text-gray-600">Tipe</th>
                                <th class="px-6 py-3 font-medium text-gray-600">Status</th>
                                <th class="px-6 py-3 font-medium text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($category->questions as $question)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-600">{{ $question->order }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $question->question }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($question->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $question->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $question->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button onclick='openEditModal(@json($question))' class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" onclick="openGlobalDeleteModal('deleteForm{{ $question->id }}', '{{ addslashes($question->question) }}', 'Hapus Pertanyaan')" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="deleteForm{{ $question->id }}" action="{{ route('admin.health-screening.destroy', $question) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada pertanyaan di kategori ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm p-8 text-center text-gray-500">
                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                <p>Belum ada kategori. Silakan buat kategori terlebih dahulu.</p>
            </div>
            @endforelse

            <!-- Uncategorized Questions -->
            @php
                $uncategorized = $questions->where('category_id', null);
            @endphp
            @if($uncategorized->count() > 0)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 cursor-pointer hover:bg-gray-100 transition"
                     @click="openCategory = openCategory === 'uncategorized' ? null : 'uncategorized'">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-question-circle text-gray-600"></i>
                            <h3 class="font-bold text-gray-800">Tanpa Kategori</h3>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-500">({{ $uncategorized->count() }} pertanyaan)</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"
                               :class="{ 'rotate-180': openCategory === 'uncategorized' }"></i>
                        </div>
                    </div>
                </div>
                
                <div x-show="openCategory === 'uncategorized'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     style="display: none;">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 font-medium text-gray-600">Urutan</th>
                                <th class="px-6 py-3 font-medium text-gray-600">Pertanyaan</th>
                                <th class="px-6 py-3 font-medium text-gray-600">Tipe</th>
                                <th class="px-6 py-3 font-medium text-gray-600">Status</th>
                                <th class="px-6 py-3 font-medium text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($uncategorized as $question)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-600">{{ $question->order }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $question->question }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($question->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $question->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $question->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button onclick='openEditModal(@json($question))' class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" onclick="openGlobalDeleteModal('deleteForm{{ $question->id }}', '{{ addslashes($question->question) }}', 'Hapus Pertanyaan')" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="deleteForm{{ $question->id }}" action="{{ route('admin.health-screening.destroy', $question) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Categories Tab -->
    <div x-show="activeTab === 'categories'">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800">Daftar Kategori</h3>
                <button @click="showCategoryModal = true; editCategory = null" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Kategori
                </button>
            </div>

            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-600">Urutan</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Nama Kategori</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Deskripsi</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Jumlah Pertanyaan</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-600">{{ $category->order }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $category->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $category->questions->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="openEditCategoryModal({{ json_encode($category) }})" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" onclick="openGlobalDeleteModal('deleteCategoryForm{{ $category->id }}', '{{ addslashes($category->name) }}', 'Hapus Kategori')" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="deleteCategoryForm{{ $category->id }}" action="{{ route('admin.health-categories.destroy', $category) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada kategori.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Category Modal (Add/Edit) -->
    <div x-show="showCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800" x-text="editCategory ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
                <button @click="showCategoryModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="categoryForm" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="_method" value="POST" id="categoryMethod">
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="categoryName" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                    <textarea name="description" id="categoryDescription" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Urutan</label>
                        <input type="number" name="order" id="categoryOrder" value="{{ $nextCategoryOrder }}" min="1" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div x-show="editCategory">
                        <label class="block text-gray-700 font-medium mb-2">Status</label>
                        <select name="is_active" id="categoryIsActive"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="showCategoryModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div x-show="showQuestionForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800">Tambah Pertanyaan Baru</h3>
            <button type="button" onclick="closeAddQuestionModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.health-screening.store') }}" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                <select name="category_id" id="addCategorySelect" onchange="updateOrderBasedOnCategory()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tanpa Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" data-next-order="{{ $nextOrderByCategory[$category->id] ?? 1 }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Pertanyaan</label>
                <textarea name="question" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Tipe Jawaban</label>
                <select name="type" required id="addTypeSelect" onchange="toggleAddOptions()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="text">Teks Bebas</option>
                    <option value="boolean">Ya / Tidak</option>
                    <option value="scale">Skala (1-10)</option>
                    <option value="checklist">Checklist (Pilihan Ganda)</option>
                </select>
            </div>
            <div class="mb-4 hidden" id="addOptionsField">
                <label class="block text-gray-700 font-medium mb-2">Opsi Jawaban (Pisahkan dengan koma)</label>
                <textarea name="options" rows="2" placeholder="Contoh: Demam, Batuk, Pilek, Sakit Kepala"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Urutan</label>
                <input type="number" name="order" id="addOrderInput" value="{{ $nextOrderUncategorized }}" min="1" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeAddQuestionModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">
                    Simpan Pertanyaan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Question Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800">Edit Pertanyaan</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="modalErrors" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-6 mb-0"></div>

        <form id="editForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                <select name="category_id" id="editCategoryId"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tanpa Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Pertanyaan</label>
                <textarea name="question" id="editQuestion" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Tipe Jawaban</label>
                <select name="type" id="editType" required onchange="toggleEditOptions()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="text">Teks Bebas</option>
                    <option value="boolean">Ya / Tidak</option>
                    <option value="scale">Skala (1-10)</option>
                    <option value="checklist">Checklist (Pilihan Ganda)</option>
                </select>
            </div>

            <div class="mb-4 hidden" id="editOptionsField">
                <label class="block text-gray-700 font-medium mb-2">Opsi Jawaban (Pisahkan dengan koma)</label>
                <textarea name="options" id="editOptions" rows="2" placeholder="Contoh: Demam, Batuk, Pilek, Sakit Kepala"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Urutan</label>
                    <input type="number" name="order" id="editOrder" min="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Status</label>
                    <select name="is_active" id="editIsActive"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    // let deleteFormId = null; // Removed
    // let deleteCategoryFormId = null; // Removed

    function toggleOptions() {
        const type = document.getElementById('typeSelect').value;
        const optionsField = document.getElementById('optionsField');
        if (type === 'checklist') {
            optionsField.classList.remove('hidden');
        } else {
            optionsField.classList.add('hidden');
        }
    }

    function toggleEditOptions() {
        const type = document.getElementById('editType').value;
        const optionsField = document.getElementById('editOptionsField');
        if (type === 'checklist') {
            optionsField.classList.remove('hidden');
        } else {
            optionsField.classList.add('hidden');
        }
    }

    function toggleAddOptions() {
        const type = document.getElementById('addTypeSelect').value;
        const optionsField = document.getElementById('addOptionsField');
        if (type === 'checklist') {
            optionsField.classList.remove('hidden');
        } else {
            optionsField.classList.add('hidden');
        }
    }

    window.openAddQuestionModal = function() {
        // Show modal by directly manipulating the DOM
        const modal = document.querySelector('[x-show="showQuestionForm"]');
        if (modal) {
            modal.style.display = 'flex';
            console.log('Add question modal displayed');
        } else {
            console.error('Add question modal not found!');
        }
        
        // Also try to set Alpine data if available
        setTimeout(() => {
            const allAlpineComponents = document.querySelectorAll('[x-data]');
            for (let comp of allAlpineComponents) {
                if (comp.__x && comp.__x.$data && 'showQuestionForm' in comp.__x.$data) {
                    comp.__x.$data.showQuestionForm = true;
                    console.log('Alpine state updated for add question');
                    break;
                }
            }
        }, 100);
    }

    window.closeAddQuestionModal = function() {
        // Hide modal by directly manipulating the DOM
        const modal = document.querySelector('[x-show="showQuestionForm"]');
        if (modal) {
            modal.style.display = 'none';
            console.log('Add question modal hidden');
        }
        
        // Also try to set Alpine data if available
        const allAlpineComponents = document.querySelectorAll('[x-data]');
        for (let comp of allAlpineComponents) {
            if (comp.__x && comp.__x.$data && 'showQuestionForm' in comp.__x.$data) {
                comp.__x.$data.showQuestionForm = false;
                console.log('Alpine state updated - modal closed');
                break;
            }
        }
        
        // Reset form
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            // Reset order to default
            document.getElementById('addOrderInput').value = {{ $nextOrderUncategorized }};
            // Hide options field
            document.getElementById('addOptionsField').classList.add('hidden');
        }
    }

    function updateOrderBasedOnCategory() {
        const categorySelect = document.getElementById('addCategorySelect');
        const orderInput = document.getElementById('addOrderInput');
        
        if (!categorySelect || !orderInput) return;
        
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        
        if (selectedOption.value === '') {
            // Tanpa kategori
            orderInput.value = {{ $nextOrderUncategorized }};
        } else {
            // Ambil next order dari data attribute
            const nextOrder = selectedOption.getAttribute('data-next-order');
            orderInput.value = nextOrder || 1;
        }
    }

    function openEditModal(question) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        form.action = `/admin/health-screening/${question.id}`;
        document.getElementById('editCategoryId').value = question.category_id || '';
        document.getElementById('editQuestion').value = question.question;
        document.getElementById('editType').value = question.type;
        document.getElementById('editOrder').value = question.order;
        document.getElementById('editIsActive').value = question.is_active ? '1' : '0';
        
        if (question.type === 'checklist' && question.options) {
            document.getElementById('editOptions').value = question.options.join(', ');
        } else {
            document.getElementById('editOptions').value = '';
        }
        
        toggleEditOptions();
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Removed old delete modal functions


    window.openEditCategoryModal = function(category) {
        console.log('Edit category clicked:', category);
        
        // Set form action and method
        const form = document.getElementById('categoryForm');
        if (!form) {
            console.error('Category form not found!');
            return;
        }
        
        form.action = `/admin/health-categories/${category.id}`;
        document.getElementById('categoryMethod').value = 'PUT';
        
        // Populate form fields
        document.getElementById('categoryName').value = category.name;
        document.getElementById('categoryDescription').value = category.description || '';
        document.getElementById('categoryOrder').value = category.order;
        document.getElementById('categoryIsActive').value = category.is_active ? '1' : '0';
        
        // Show modal by directly manipulating the DOM
        const modal = document.querySelector('[x-show="showCategoryModal"]');
        if (modal) {
            modal.style.display = 'flex';
            console.log('Modal displayed via DOM');
        } else {
            console.error('Modal element not found!');
        }
        
        // Also try to set Alpine data if available (for proper state management)
        setTimeout(() => {
            const allAlpineComponents = document.querySelectorAll('[x-data]');
            for (let comp of allAlpineComponents) {
                if (comp.__x && comp.__x.$data && 'showCategoryModal' in comp.__x.$data) {
                    comp.__x.$data.showCategoryModal = true;
                    comp.__x.$data.editCategory = category;
                    console.log('Alpine state also updated');
                    break;
                }
            }
        }, 100);
    }
    
    // Test if function is accessible
    console.log('openEditCategoryModal function loaded:', typeof window.openEditCategoryModal);

    // Removed old category delete modal functions

    // Listen for custom event to open category modal
    window.addEventListener('open-category-modal', function(e) {
        const showCategoryModal = document.querySelector('[x-data]').__x.$data.showCategoryModal;
        document.querySelector('[x-data]').__x.$data.showCategoryModal = true;
        document.querySelector('[x-data]').__x.$data.editCategory = e.detail;
    });

    // Set category form action when opening for new category
    document.addEventListener('DOMContentLoaded', function() {
        const categoryForm = document.getElementById('categoryForm');
        if (categoryForm) {
            categoryForm.addEventListener('submit', function(e) {
                const method = document.getElementById('categoryMethod').value;
                if (method === 'POST') {
                    categoryForm.action = '{{ route("admin.health-categories.store") }}';
                }
            });
        }
    });
</script>
@endsection
