<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create(['is_admin' => true]);

                $data = [
                    'user_id' => $user->id,
                    'title' => 'Sejucara Perkenalkan "Cascara Nusantara": Minuman Fungsional dari Kulit Kopi',
                    'slug' => 'sejucara-perkenalkan-cascara-nusantara',
                    'excerpt' => 'Sejucara meluncurkan Cascara Nusantara, sebuah inovasi minuman berbasis kulit buah kopi yang kaya antioksidan dan dirancang untuk dinikmati hangat maupun dingin.',
                    'content' => <<<'HTML'
            <p><strong>Bandung, 4 Juni 2026</strong> — Sejucara meluncurkan <em>Cascara Nusantara</em>, rangkaian minuman inovatif yang mengolah kulit buah ceri kopi (cascara) menjadi produk siap seduh berkualitas. Peluncuran ini merupakan bagian dari inisiatif Sejucara untuk mempromosikan praktik berkelanjutan dan memberikan nilai tambah bagi petani kopi lokal.</p>

            <p>Cascara selama ini kerap menjadi limbah pasca-panen. Sejucara bekerja sama dengan petani lokal untuk mengumpulkan cascara terpilih, lalu menerapkan proses pembersihan dan pengeringan yang memenuhi standar higienis. Hasilnya adalah bahan siap seduh yang stabil, aman, dan mempertahankan profil rasa khas cascara: manis alami, aroma buah kering, dan aftertaste yang lembut.</p>

            <h3>Varian dan Rasa</h3>
            <p>Untuk peluncuran awal, Cascara Nusantara hadir dalam dua varian:</p>
            <ul>
                <li><strong>Original Cascara</strong> — menonjolkan rasa natural cascara yang segar dan manis.</li>
                <li><strong>Rempah Cascara</strong> — perpaduan cascara dengan rempah nusantara (mis. kayu manis, jahe) untuk sensasi hangat dan aromatik.</li>
            </ul>

            <h3>Cara Penyajian</h3>
            <p>Rekomendasi penyajian:
            <ul>
                <li>Hangat: seduh 5–8 g cascara kering dengan 250 ml air 90°C selama 4–6 menit.</li>
                <li>Dingin: seduh seperti di atas, dinginkan, sajikan dengan es dan irisan jeruk nipis.</li>
                <li>Variasi: tambahkan gula aren atau susu nabati untuk sentuhan kekinian.</li>
            </ul>
            </p>

            <h3>Manfaat Potensial dan Keamanan</h3>
            <p>Beberapa studi pada <em>coffee by-products</em> menunjukkan cascara kaya akan polifenol dan antioksidan. Meskipun penelitian awal menunjukkan potensi manfaat antioksidan, klaim kesehatan memerlukan bukti klinis lebih lanjut. Sejucara menegaskan bahwa cascara disajikan sebagai minuman fungsional — bukan pengobatan — dan prioritas utama adalah keamanan pangan melalui pengolahan dan pengemasan yang benar.</p>

            <h3>Tren Pasar</h3>
            <p>Permintaan konsumen terhadap minuman alternatif yang rendah kafein namun kaya rasa meningkat. Cascara menjembatani kebutuhan ini dengan menawarkan kompleksitas rasa yang menarik tanpa intensitas kafein kopi, sehingga cocok bagi konsumen urban yang mencari variasi minuman harian.</p>

            <h3>Dukungan untuk Petani</h3>
            <p>Inisiatif Cascara Nusantara memberi peluang pendapatan tambahan bagi petani kopi melalui pembelian cascara yang adil dan program pelatihan pasca panen. Langkah ini mendukung keberlanjutan ekonomi dan lingkungan di daerah penghasil kopi.</p>

            <h3>Ketersediaan</h3>
            <p>Cascara Nusantara hanya tersedia melalui platform daring resmi Sejucara untuk pemesanan — kami belum memiliki toko fisik. Untuk memesan dan melihat informasi harga serta promosi, kunjungi laman produk kami atau platform online resmi Sejucara.</p>

            
            HTML,
                ];

                $article = Article::updateOrCreate(['slug' => $data['slug']], $data);
                $article->timestamps = false;
                $article->forceFill([
                    'created_at' => '2026-03-12 09:00:00',
                    'updated_at' => '2026-03-12 09:00:00',
                ])->save();
    }
}
