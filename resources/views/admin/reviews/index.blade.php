@extends('layouts.admin')

@section('title', 'Reviews - RisingCare')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Customer Reviews</h1>
    <p class="text-gray-600">Kelola review dari customer dan tampilkan di landing page</p>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Section Settings</h2>
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Section Title</label>
                <input type="text" name="settings[landing_reviews_title]" value="{{ $sectionTitle ?? 'Apa Kata Mereka' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Section Subtitle</label>
                <input type="text" name="settings[landing_reviews_subtitle]" value="{{ $sectionSubtitle ?? 'Testimoni dari pasien yang telah menggunakan layanan kami' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
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
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Service</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Comment</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Show on Landing</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $review->customer->name }}</div>
                        <div class="text-sm text-gray-500">{{ $review->customer->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $review->booking->service->name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : ' text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600">{{ $review->rating }}/5</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600">{{ Str::limit($review->comment, 50) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <button 
                            onclick="toggleLanding({{ $review->id }}, this)"
                            class="toggle-btn px-3 py-1 rounded-full text-xs font-medium {{ $review->show_on_landing ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $review->show_on_landing ? 'Ditampilkan' : 'Disembunyikan' }}
                        </button>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $review->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <!-- Actions removed as per request -->
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Belum ada review
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($reviews->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $reviews->links() }}
    </div>
    @endif
</div>

<script>
function toggleLanding(reviewId, button) {
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                  document.querySelector('input[name="_token"]')?.value;
    
    if (!token) {
        alert('CSRF token not found');
        return;
    }

    fetch(`/admin/reviews/${reviewId}/toggle-landing`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.show_on_landing) {
                button.classList.remove('bg-red-100', 'text-red-800');
                button.classList.add('bg-green-100', 'text-green-800');
                button.textContent = 'Ditampilkan';
            } else {
                button.classList.remove('bg-green-100', 'text-green-800');
                button.classList.add('bg-red-100', 'text-red-800');
                button.textContent = 'Disembunyikan';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
}
</script>
@endsection
