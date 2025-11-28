<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-red-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <h3 class="font-bold text-gray-800">Konfirmasi Hapus</h3>
            </div>
        </div>
        
        <div class="p-6">
            <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus data ini?</p>
            <p id="deleteItemText" class="text-sm text-gray-500 italic bg-gray-50 p-3 rounded-lg"></p>
            <p class="text-sm text-red-600 mt-3">
                <i class="fas fa-info-circle"></i> Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-lg transition">
                Batal
            </button>
            <button type="button" onclick="confirmDelete()" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700 transition">
                <i class="fas fa-trash mr-2"></i>Hapus
            </button>
        </div>
    </div>
</div>

<script>
    let deleteFormId = null;

    function openDeleteModal(formId, itemText) {
        deleteFormId = formId;
        document.getElementById('deleteItemText').textContent = itemText;
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        deleteFormId = null;
    }

    function confirmDelete() {
        if (deleteFormId) {
            document.getElementById(deleteFormId).submit();
        }
    }
</script>
