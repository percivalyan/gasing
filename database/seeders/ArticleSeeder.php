<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    /**
     * Jalankan seeder artikel tentang Papua.
     */
    public function run(): void
    {
        $categories = Category::all();
        $users = User::all();

        if ($categories->isEmpty() || $users->isEmpty()) {
            $this->command->info('Seeder: Pastikan ada data kategori dan user sebelum menjalankan PapuaArticleSeeder.');
            return;
        }

        $articles = [
            [
                'title' => 'Keindahan Alam Raja Ampat',
                'summary' => 'Raja Ampat menyimpan panorama bawah laut yang memukau.',
                'content' => 'Raja Ampat merupakan salah satu destinasi wisata terindah di dunia. Terletak di Provinsi Papua Barat, kepulauan ini terkenal dengan terumbu karang yang masih alami, keanekaragaman hayati laut, serta pemandangan pulau-pulau yang memukau. Aktivitas populer di sini termasuk snorkeling, diving, dan menjelajahi pulau-pulau kecil yang eksotis.'
            ],
            [
                'title' => 'Budaya Tradisional Suku Asmat',
                'summary' => 'Suku Asmat terkenal dengan seni ukir kayunya yang khas.',
                'content' => 'Suku Asmat adalah salah satu suku asli Papua yang terkenal dengan keahlian mereka dalam seni ukir kayu dan patung. Tradisi ini tidak hanya sebagai karya seni, tetapi juga memiliki makna spiritual yang mendalam. Upacara adat dan tarian tradisional menjadi bagian penting dari kehidupan sehari-hari masyarakat Asmat.'
            ],
            [
                'title' => 'Festival Lembah Baliem',
                'summary' => 'Festival budaya terbesar di Lembah Baliem, Jayawijaya.',
                'content' => 'Festival Lembah Baliem adalah perayaan budaya tahunan yang menampilkan tarian tradisional, kompetisi perang antar suku, dan pameran kerajinan tangan. Festival ini menjadi daya tarik wisata utama di Papua dan memberi kesempatan bagi pengunjung untuk belajar tentang kehidupan suku Dani, Lani, dan Yali.'
            ],
            [
                'title' => 'Potensi Pendidikan di Papua',
                'summary' => 'Upaya meningkatkan akses pendidikan di wilayah terpencil Papua.',
                'content' => 'Pemerintah dan berbagai yayasan di Papua bekerja untuk meningkatkan akses pendidikan bagi anak-anak di wilayah terpencil. Program beasiswa, pembangunan sekolah, dan pelatihan guru menjadi fokus utama, sehingga generasi muda Papua dapat mengembangkan potensi mereka secara maksimal.'
            ],
            [
                'title' => 'Les Gasing Tradisional Papua',
                'summary' => 'Permainan gasing sebagai bagian dari tradisi dan pelatihan anak-anak.',
                'content' => 'Gasing tradisional di Papua bukan sekadar permainan, tetapi juga sarana pendidikan karakter dan keterampilan motorik anak-anak. Les gasing menjadi populer di berbagai kota, dengan pelatihan rutin yang mengajarkan ketekunan, fokus, dan kerjasama dalam komunitas.'
            ],
        ];

        foreach ($articles as $article) {
            Article::create([
                'id' => Str::uuid(),
                'slug' => Str::slug($article['title']) . '-' . Str::random(5),
                'title' => $article['title'],
                'summary' => $article['summary'],
                'content' => $article['content'],
                'image_path' => null,
                'status' => 'published',
                'category_id' => $categories->random()->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
