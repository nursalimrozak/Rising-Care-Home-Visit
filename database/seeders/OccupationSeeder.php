<?php

namespace Database\Seeders;

use App\Models\Occupation;
use App\Models\Membership;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    public function run(): void
    {
        $gold = Membership::where('name', 'Gold')->first();
        $silver = Membership::where('name', 'Silver')->first();
        $bronze = Membership::where('name', 'Bronze')->first();

        $occupations = [
            // Gold Membership
            ['name' => 'Dokter', 'description' => 'Tenaga medis profesional', 'membership_id' => $gold->id],
            ['name' => 'Pengusaha', 'description' => 'Pemilik usaha', 'membership_id' => $gold->id],
            ['name' => 'Direktur', 'description' => 'Pimpinan perusahaan', 'membership_id' => $gold->id],
            
            // Silver Membership
            ['name' => 'Perawat', 'description' => 'Tenaga kesehatan', 'membership_id' => $silver->id],
            ['name' => 'Guru', 'description' => 'Tenaga pendidik', 'membership_id' => $silver->id],
            ['name' => 'Karyawan Swasta', 'description' => 'Pekerja sektor swasta', 'membership_id' => $silver->id],
            ['name' => 'PNS', 'description' => 'Pegawai Negeri Sipil', 'membership_id' => $silver->id],
            
            // Bronze Membership
            ['name' => 'Mahasiswa', 'description' => 'Pelajar perguruan tinggi', 'membership_id' => $bronze->id],
            ['name' => 'Pelajar', 'description' => 'Siswa sekolah', 'membership_id' => $bronze->id],
            ['name' => 'Ibu Rumah Tangga', 'description' => 'Mengurus rumah tangga', 'membership_id' => $bronze->id],
            ['name' => 'Lainnya', 'description' => 'Pekerjaan lainnya', 'membership_id' => $bronze->id],
        ];

        foreach ($occupations as $occupation) {
            Occupation::create($occupation);
        }
    }
}
