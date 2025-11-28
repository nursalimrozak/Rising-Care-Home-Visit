<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;
use App\Models\LandingServicesHighlight;
use App\Models\LandingCtaAppointment;
use App\Models\LandingHowWeWork;
use App\Models\LandingTestimonial;
use App\Models\LandingArticle;
use App\Models\SiteSetting;
use App\Models\User;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        // Hero Slides
        $heroSlides = [
            [
                'title' => 'The Backbone of Good Health',
                'subtitle' => 'Perawatan kesehatan profesional dengan tenaga medis berpengalaman untuk keluarga Indonesia',
                'description' => 'Dapatkan layanan kesehatan terbaik dengan tim medis profesional yang siap melayani Anda',
                'cta_text' => 'Buat Janji',
                'cta_link' => '/member/buat-janji',
                'background_image' => 'landingpage/hero-1.jpg',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Layanan Kesehatan di Rumah Anda',
                'subtitle' => 'Home visit tersedia untuk kenyamanan dan kemudahan Anda',
                'description' => 'Tidak perlu repot ke klinik, kami datang ke rumah Anda',
                'cta_text' => 'Lihat Layanan',
                'cta_link' => '/layanan',
                'background_image' => 'landingpage/hero-2.jpg',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Membership dengan Benefit Menarik',
                'subtitle' => 'Bergabunglah dengan program membership kami dan nikmati berbagai keuntungan',
                'description' => 'Dapatkan diskon dan prioritas layanan untuk member setia',
                'cta_text' => 'Daftar Sekarang',
                'cta_link' => '/registrasi',
                'background_image' => 'landingpage/hero-3.jpg',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($heroSlides as $index => $slide) {
            HeroSlide::updateOrCreate(
                ['order' => $slide['order']],
                $slide
            );
        }

        // Services Highlight
        $servicesHighlight = [
            [
                'service_id' => null,
                'title' => 'Health Checkup',
                'description' => 'Pemeriksaan kesehatan rutin dan menyeluruh untuk deteksi dini berbagai penyakit',
                'icon' => 'stethoscope',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'service_id' => null,
                'title' => 'Home Visit',
                'description' => 'Layanan perawatan kesehatan langsung di rumah Anda dengan peralatan medis lengkap',
                'icon' => 'home',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'service_id' => null,
                'title' => 'Expert Care',
                'description' => 'Perawatan oleh tenaga medis profesional berpengalaman dan bersertifikat',
                'icon' => 'user-nurse',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'service_id' => null,
                'title' => 'Emergency Service',
                'description' => 'Layanan darurat 24/7 untuk kebutuhan medis mendesak Anda',
                'icon' => 'ambulance',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'service_id' => null,
                'title' => 'Lab Test',
                'description' => 'Pemeriksaan laboratorium lengkap dengan hasil akurat dan cepat',
                'icon' => 'vial',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'service_id' => null,
                'title' => 'Consultation',
                'description' => 'Konsultasi kesehatan dengan dokter spesialis berpengalaman',
                'icon' => 'comments',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($servicesHighlight as $service) {
            LandingServicesHighlight::updateOrCreate(
                ['title' => $service['title']],
                $service
            );
        }

        // How We Work
        $howWeWork = [
            [
                'step_number' => 1,
                'title' => 'Make Appointment',
                'description' => 'Buat janji dengan mudah melalui website kami kapan saja',
                'icon' => 'calendar-check',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'step_number' => 2,
                'title' => 'Get Confirmation',
                'description' => 'Dapatkan konfirmasi dan jadwal perawatan dari tim kami',
                'icon' => 'check-circle',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'step_number' => 3,
                'title' => 'Receive Treatment',
                'description' => 'Terima perawatan dari tenaga medis profesional kami',
                'icon' => 'user-md',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'step_number' => 4,
                'title' => 'Get Well Soon',
                'description' => 'Pulih dengan cepat dan sehat kembali bersama keluarga',
                'icon' => 'heart',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($howWeWork as $step) {
            LandingHowWeWork::updateOrCreate(
                ['step_number' => $step['step_number']],
                $step
            );
        }

        // Testimonials
        $testimonials = [
            [
                'customer_name' => 'Ibu Siti Nurhaliza',
                'customer_avatar' => null,
                'rating' => 5,
                'comment' => 'Pelayanan sangat memuaskan! Petugas datang tepat waktu dan sangat profesional. Saya sangat merekomendasikan RisingCare untuk keluarga Indonesia.',
                'service_name' => 'Home Visit',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'customer_name' => 'Bapak Ahmad Dahlan',
                'customer_avatar' => null,
                'rating' => 5,
                'comment' => 'Sangat puas dengan layanan health checkup. Hasil cepat dan akurat. Tim medis sangat ramah dan menjelaskan dengan detail.',
                'service_name' => 'Health Checkup',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'customer_name' => 'Ibu Dewi Lestari',
                'customer_avatar' => null,
                'rating' => 5,
                'comment' => 'Membership RisingCare sangat worth it! Banyak benefit dan diskon yang didapat. Pelayanan juga prioritas.',
                'service_name' => 'Membership',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'customer_name' => 'Bapak Rudi Hartono',
                'customer_avatar' => null,
                'rating' => 4,
                'comment' => 'Layanan bagus dan harga terjangkau. Petugas medis sangat berpengalaman dan peralatan lengkap.',
                'service_name' => 'Expert Care',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'customer_name' => 'Ibu Maya Sari',
                'customer_avatar' => null,
                'rating' => 5,
                'comment' => 'Terima kasih RisingCare! Ibu saya mendapat perawatan terbaik di rumah. Sangat membantu keluarga kami.',
                'service_name' => 'Home Visit',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'customer_name' => 'Bapak Andi Wijaya',
                'customer_avatar' => null,
                'rating' => 5,
                'comment' => 'Proses booking mudah, konfirmasi cepat, dan pelayanan excellent. Highly recommended!',
                'service_name' => 'Consultation',
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            LandingTestimonial::updateOrCreate(
                ['customer_name' => $testimonial['customer_name']],
                $testimonial
            );
        }

        // Articles
        $admin = User::where('role', 'superadmin')->first();
        
        $articles = [
            [
                'title' => '5 Tips Menjaga Kesehatan di Musim Hujan',
                'slug' => '5-tips-menjaga-kesehatan-di-musim-hujan',
                'excerpt' => 'Musim hujan sering kali membawa berbagai penyakit. Berikut tips untuk menjaga kesehatan Anda dan keluarga.',
                'content' => '<p>Musim hujan adalah waktu yang rentan terhadap berbagai penyakit seperti flu, demam berdarah, dan diare. Berikut adalah 5 tips penting untuk menjaga kesehatan di musim hujan:</p>
                
                <h3>1. Konsumsi Makanan Bergizi</h3>
                <p>Perbanyak konsumsi buah dan sayuran yang kaya vitamin C untuk meningkatkan daya tahan tubuh.</p>
                
                <h3>2. Jaga Kebersihan Lingkungan</h3>
                <p>Pastikan tidak ada genangan air di sekitar rumah untuk mencegah perkembangbiakan nyamuk.</p>
                
                <h3>3. Istirahat Cukup</h3>
                <p>Tidur 7-8 jam setiap malam untuk menjaga sistem imun tetap kuat.</p>
                
                <h3>4. Olahraga Teratur</h3>
                <p>Tetap aktif bergerak meskipun hujan dengan olahraga ringan di dalam ruangan.</p>
                
                <h3>5. Cuci Tangan Rutin</h3>
                <p>Biasakan mencuci tangan dengan sabun untuk mencegah penyebaran kuman.</p>',
                'featured_image' => null,
                'author_id' => $admin?->id,
                'category' => 'Tips Kesehatan',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Pentingnya Medical Checkup Rutin untuk Keluarga',
                'slug' => 'pentingnya-medical-checkup-rutin-untuk-keluarga',
                'excerpt' => 'Medical checkup rutin adalah investasi kesehatan jangka panjang. Ketahui manfaatnya untuk keluarga Anda.',
                'content' => '<p>Medical checkup atau pemeriksaan kesehatan rutin adalah langkah preventif yang sangat penting untuk mendeteksi penyakit sejak dini.</p>
                
                <h3>Manfaat Medical Checkup</h3>
                <ul>
                    <li>Deteksi dini penyakit serius seperti diabetes, hipertensi, dan kanker</li>
                    <li>Monitoring kondisi kesehatan secara berkala</li>
                    <li>Mendapatkan saran medis yang tepat</li>
                    <li>Menghemat biaya pengobatan di masa depan</li>
                </ul>
                
                <h3>Kapan Harus Medical Checkup?</h3>
                <p>Disarankan melakukan medical checkup minimal 1 tahun sekali, atau lebih sering jika memiliki riwayat penyakit tertentu.</p>',
                'featured_image' => null,
                'author_id' => $admin?->id,
                'category' => 'Informasi Kesehatan',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Keuntungan Layanan Home Visit untuk Lansia',
                'slug' => 'keuntungan-layanan-home-visit-untuk-lansia',
                'excerpt' => 'Layanan home visit memberikan kemudahan dan kenyamanan bagi lansia yang membutuhkan perawatan medis.',
                'content' => '<p>Layanan home visit atau kunjungan dokter ke rumah menjadi solusi ideal untuk perawatan kesehatan lansia.</p>
                
                <h3>Keuntungan Home Visit</h3>
                <ol>
                    <li><strong>Kenyamanan</strong> - Lansia tidak perlu repot pergi ke klinik</li>
                    <li><strong>Hemat Waktu</strong> - Tidak perlu antri dan menunggu lama</li>
                    <li><strong>Perawatan Personal</strong> - Perhatian penuh dari tenaga medis</li>
                    <li><strong>Lingkungan Familiar</strong> - Perawatan di lingkungan yang nyaman</li>
                    <li><strong>Monitoring Rutin</strong> - Pemantauan kesehatan secara berkala</li>
                </ol>
                
                <h3>Layanan yang Tersedia</h3>
                <p>RisingCare menyediakan berbagai layanan home visit seperti pemeriksaan rutin, perawatan luka, fisioterapi, dan konsultasi kesehatan.</p>',
                'featured_image' => null,
                'author_id' => $admin?->id,
                'category' => 'Layanan',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($articles as $article) {
            LandingArticle::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }

        // CTA Appointment
        LandingCtaAppointment::updateOrCreate(
            ['id' => 1],
            [
                'section_title' => 'Ready to Get Started?',
                'section_subtitle' => 'Buat janji dengan kami sekarang dan dapatkan perawatan kesehatan terbaik untuk Anda dan keluarga',
                'background_image' => null,
                'background_color' => '#0d9488',
                'button_text' => 'Buat Janji Sekarang',
                'is_active' => true,
            ]
        );

        // Site Settings - Stats
        $stats = [
            ['key' => 'stat_happy_patients', 'value' => '1000', 'type' => 'text', 'group' => 'stats'],
            ['key' => 'stat_expert_doctors', 'value' => '35', 'type' => 'text', 'group' => 'stats'],
            ['key' => 'stat_years_experience', 'value' => '15', 'type' => 'text', 'group' => 'stats'],
            ['key' => 'stat_clinic_rooms', 'value' => '5', 'type' => 'text', 'group' => 'stats'],
        ];

        foreach ($stats as $stat) {
            SiteSetting::updateOrCreate(
                ['key' => $stat['key']],
                $stat
            );
        }

        // Site Settings - General
        $general = [
            ['key' => 'site_name', 'value' => 'RisingCare', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Layanan Kesehatan Terpercaya', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'RisingCare menyediakan layanan kesehatan berkualitas dengan tenaga medis profesional dan berpengalaman untuk keluarga Indonesia', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '0812-3456-7890', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'info@risingcare.com', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_address', 'value' => 'Jl. Kesehatan No. 123, Jakarta Selatan', 'type' => 'textarea', 'group' => 'general'],
        ];

        foreach ($general as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
