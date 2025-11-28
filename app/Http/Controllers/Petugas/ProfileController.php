<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('petugas.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            // Password Validation
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            // Payout Details Validation
            'payment_type' => 'nullable|in:bank,ewallet',
            'provider_name' => 'required_with:payment_type|string|max:255',
            'account_number' => 'required_with:payment_type|string|max:255',
            'account_holder_name' => 'required_with:payment_type|string|max:255',
            // Document Validation
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'document_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'document_sim' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'document_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'required_with' => 'Kolom :attribute wajib diisi jika memilih jenis pembayaran.',
            'mimes' => 'Format :attribute harus berupa :values.',
            'max' => 'Ukuran :attribute maksimal :max kilobyte.',
            'confirmed' => 'Konfirmasi password tidak cocok.',
            'min' => 'Password minimal :min karakter.',
        ], [
            'name' => 'Nama Lengkap',
            'email' => 'Email',
            'phone' => 'No. Telepon',
            'current_password' => 'Password Saat Ini',
            'new_password' => 'Password Baru',
            'payment_type' => 'Jenis Pembayaran',
            'provider_name' => 'Nama Provider (Bank/E-Wallet)',
            'account_number' => 'Nomor Rekening/Akun',
            'account_holder_name' => 'Atas Nama',
            'avatar' => 'Foto Profil',
            'document_ktp' => 'Dokumen KTP',
            'document_sim' => 'Dokumen SIM',
            'document_certificate' => 'Sertifikat Keahlian',
        ]);

        // Check current password if changing password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            $validated['password'] = Hash::make($request->new_password);
        }

        // Remove password fields from validated data if not changing
        unset($validated['current_password'], $validated['new_password'], $validated['new_password_confirmation']);

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Update User Profile
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'avatar' => $user->avatar, // Ensure avatar is updated
            // Add other user fields if they exist in the User model fillable
        ]);

        // Update Payout Details
        if ($request->filled('payment_type')) {
            $user->payoutDetail()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'payment_type' => $validated['payment_type'],
                    'provider_name' => $validated['provider_name'],
                    'account_number' => $validated['account_number'],
                    'account_holder_name' => $validated['account_holder_name'],
                ]
            );
        }

        // Handle Document Uploads
        $documentTypes = ['ktp', 'sim', 'certificate'];
        foreach ($documentTypes as $type) {
            if ($request->hasFile("document_{$type}")) {
                $file = $request->file("document_{$type}");
                $path = $file->store('documents/' . $user->id, 'public');
                
                $user->documents()->updateOrCreate(
                    ['document_type' => $type],
                    [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]
                );
            }
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
