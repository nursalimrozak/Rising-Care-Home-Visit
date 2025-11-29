<!-- Global Delete Confirmation Modal -->
<div id="globalDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center mb-2" id="globalDeleteTitle">Konfirmasi Hapus</h3>
            <p class="text-gray-600 text-center mb-6" id="globalDeleteMessage">
                Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="closeGlobalDeleteModal()" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="button" onclick="confirmGlobalDelete()" class="flex-1 bg-red-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-red-700 transition">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let globalDeleteFormId = null;

/**
 * Open global delete confirmation modal
 * @param {string} formId - ID of the form to submit
 * @param {string} itemName - Name of the item to delete (optional)
 * @param {string} title - Custom modal title (optional)
 */
function openGlobalDeleteModal(formId, itemName = null, title = 'Konfirmasi Hapus') {
    globalDeleteFormId = formId;
    
    // Set title
    document.getElementById('globalDeleteTitle').textContent = title;
    
    // Set message
    let message = 'Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
    if (itemName) {
        message = `Yakin ingin menghapus "<span class="font-semibold">${itemName}</span>"?<br>Tindakan ini tidak dapat dibatalkan.`;
    }
    document.getElementById('globalDeleteMessage').innerHTML = message;
    
    // Show modal
    document.getElementById('globalDeleteModal').classList.remove('hidden');
    document.getElementById('globalDeleteModal').classList.add('flex');
}

function closeGlobalDeleteModal() {
    document.getElementById('globalDeleteModal').classList.add('hidden');
    document.getElementById('globalDeleteModal').classList.remove('flex');
    globalDeleteFormId = null;
}

function confirmGlobalDelete() {
    if (globalDeleteFormId) {
        document.getElementById(globalDeleteFormId).submit();
    }
}

// Close modal when clicking outside
document.getElementById('globalDeleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeGlobalDeleteModal();
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('globalDeleteModal').classList.contains('hidden')) {
        closeGlobalDeleteModal();
    }
});
</script>
