<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@sejucara.test'],
            [
                'name' => 'Admin Sejucara',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@sejucara.test'],
            [
                'name' => 'User Sejucara',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        $products = [
            ['name' => 'Botol Ori 100gr', 'slug' => 'cascara-original', 'description' => 'Cascara original dalam kemasan botol — isi 250ml. Rasa natural dengan sentuhan manis ringan dan aroma buah kering; cocok dinikmati hangat atau dingin sebagai minuman penyegar sehari-hari.', 'price' => 30000, 'stock' => 50, 'image_url' => null],
            ['name' => 'Lemon Breeze', 'slug' => 'cascara-lemon', 'description' => 'Cascara lemon dalam kemasan botol — isi 250ml. Perpaduan cascara dan lemon menghasilkan aroma citrus yang cerah dan rasa segar; sangat nikmat disajikan dingin dengan es untuk sensasi menyegarkan.', 'price' => 30000, 'stock' => 35, 'image_url' => null],
            ['name' => 'Botol Ginger 100gr', 'slug' => 'cascara-jahe', 'description' => 'Cascara ginger dalam kemasan botol — isi 250ml. Kombinasi cascara dan jahe memberikan rasa hangat dengan sentuhan pedas yang lembut; ideal diminum hangat untuk menghangatkan badan.', 'price' => 30000, 'stock' => 40, 'image_url' => null],
            ['name' => 'Cascara dengan Madu 100gr', 'slug' => 'cascara-madu', 'description' => 'Cascara dengan sentuhan madu alami — isi 250ml. Manis lembut dari madu menambah body dan aroma floral yang menenangkan; sangat cocok sebagai minuman santai atau pemulihan setelah aktivitas.', 'price' => 43000, 'stock' => 30, 'image_url' => null],
            ['name' => 'Cascara dengan Daun Mint 100gr', 'slug' => 'cascara-mint', 'description' => 'Varian dengan sensasi daun mint yang segar — isi 250ml. Rasa mint memberikan aftertaste dingin dan menyegarkan, pas dinikmati dingin pada hari panas.', 'price' => 44000, 'stock' => 28, 'image_url' => null],
            ['name' => 'Cascara Berry 100gr', 'slug' => 'cascara-berry', 'description' => 'Perpaduan rasa berry yang fruity dan segar.', 'price' => 45000, 'stock' => 25, 'image_url' => null],
            ['name' => 'Cascara Orange 100gr', 'slug' => 'cascara-orange', 'description' => 'Cascara cita rasa jeruk yang menyegarkan.', 'price' => 42000, 'stock' => 20, 'image_url' => null],
            ['name' => 'Pouch Cascara Original 25gr', 'slug' => 'cascara-pouch', 'description' => 'Pouch cascara original — isi 25gr. Pouch praktis ini pas untuk percobaan atau stok kecil di rumah; setiap pouch menghasilkan beberapa cangkir seduhan dengan karakter rasa natural cascara.', 'price' => 15000, 'stock' => 15, 'image_url' => null],
            ['name' => 'Pouch Cascara Rempah 25gr', 'slug' => 'cascara-pouch-rempah', 'description' => 'Pouch cascara + rempah — isi 25gr. Campuran cascara dengan rempah hangat (seperti kayu manis dan jahe) memberikan rasa kompleks dan aromatik; paling pas disajikan hangat untuk nuansa cozy.', 'price' => 20000, 'stock' => 12, 'image_url' => null],
            ['name' => 'Botol Cascara Original 20gr', 'slug' => 'cascara-botol', 'description' => 'Botol cascara original — isi 20gr. Kemasan kecil praktis untuk dicoba atau sebagai hadiah; mudah diseduh dan cocok untuk konsumsi satu orang.', 'price' => 10000, 'stock' => 40, 'image_url' => null],
            ['name' => 'Botol Cascara Rempah 20gr', 'slug' => 'cascara-botol-rempah', 'description' => 'Botol cascara + rempah — isi 20gr. Perpaduan cascara dan rempah menghasilkan aroma hangat dan rasa penuh karakter; nikmati hangat untuk pengalaman terbaik.', 'price' => 15000, 'stock' => 20, 'image_url' => null],
            ['name' => 'Cascara Sachet 10pcs', 'slug' => 'cascara-sachet-10pcs', 'description' => 'Praktis dalam sachet, siap seduh kapan saja.', 'price' => 30000, 'stock' => 100, 'image_url' => null],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }

        $articles = [
            [
                'user_id' => $admin->id,
                'title' => 'Manfaat Cascara untuk Aktivitas Sehari-hari',
                'slug' => 'manfaat-cascara-untuk-aktivitas-sehari-hari',
                'excerpt' => 'Cascara bisa jadi pilihan minuman ringan untuk menemani rutinitas harian.',
                'published_at' => '2026-03-24 09:00:00',
                'content' => <<<'HTML'
Cascara adalah bagian dari buah ceri kopi yang selama ini kurang dimanfaatkan setelah biji kopi dipanen. Bahan ini mengandung senyawa polifenolik dan antioksidan, serta kandungan kafein yang umumnya lebih rendah daripada kopi seduh, sehingga menawarkan stimulasi ringan yang sesuai untuk konsumsi harian.

Sebagai minuman sehari-hari, cascara dapat menjadi alternatif yang menarik untuk menjaga hidrasi karena rasanya yang lembut dan aromatik. Di pagi hari, secangkir cascara hangat memberi stimulasi ringan tanpa lonjakan kafein yang kuat, sementara versi dingin cocok sebagai penyegar di siang hari. Untuk aktivitas ringan seperti jalan pagi, yoga, atau bersepeda santai, cascara dapat menjadi pilihan minuman pendukung; namun untuk aktivitas intens yang membutuhkan penggantian elektrolit, gunakan minuman yang dirancang khusus.

Praktis menyajikan cascara: seduh 5–8 gram cascara kering per 250 ml air panas (85–95°C) selama 4–6 menit. Untuk varian dingin, dinginkan seduhan dasar dan tambahkan es, atau kombinasikan dengan buah sitrus dan sedikit madu sesuai selera.

Dari perspektif keberlanjutan, pemanfaatan cascara membantu mengubah limbah pasca-panen menjadi produk bernilai tambah, memberikan pendapatan tambahan bagi petani dan mengurangi limbah pertanian. Meskipun studi laboratorium menunjukkan potensi kandungan antioksidan, klaim kesehatan spesifik memerlukan penelitian lebih lanjut pada manusia. Konsumen sensitif terhadap kafein disarankan menyesuaikan konsumsi sesuai toleransi pribadi.
HTML
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Apa Itu Cascara? Panduan Dasar untuk Pemula',
                'slug' => 'apa-itu-cascara-panduan-dasar-untuk-pemula',
                'excerpt' => 'Mengenal asal-usul cascara dan karakter rasanya.',
                'published_at' => '2026-04-07 10:30:00',
                'content' => <<<'HTML'
<p>Cascara adalah kulit dan daging buah kopi yang dikeringkan setelah biji kopi dipisahkan dari buahnya. Dalam proses produksi kopi, bagian yang paling sering dimanfaatkan adalah bijinya, sementara kulit buahnya sering dianggap sebagai sisa panen. Padahal, ketika diproses dengan bersih dan dikeringkan dengan baik, kulit buah kopi ini dapat menjadi bahan minuman yang punya karakter rasa unik, ringan, dan menyegarkan.</p>

<p>Nama cascara berasal dari bahasa Spanyol yang berarti "kulit" atau "sekam". Walaupun berasal dari tanaman kopi, cascara tidak sama dengan kopi seduh biasa. Kopi dibuat dari biji kopi yang disangrai, digiling, lalu diseduh. Cascara dibuat dari bagian buahnya yang dikeringkan, sehingga rasa yang muncul lebih dekat ke teh buah: ada kesan manis alami, aroma buah kering, sedikit asam segar, dan aftertaste yang lembut.</p>

<h2>Asal-Usul Cascara</h2>
<p>Saat buah kopi dipanen, bentuknya menyerupai ceri kecil yang berwarna merah, kuning, atau oranye tergantung varietas dan tingkat kematangannya. Di dalam buah tersebut terdapat biji kopi yang nantinya diproses menjadi kopi. Bagian luar buah, yaitu kulit dan daging buah, kemudian dipisahkan. Dari sinilah cascara berasal.</p>

<p>Dulu, bagian kulit buah kopi ini sering terbuang atau hanya dimanfaatkan sebagai kompos. Seiring berkembangnya perhatian terhadap produk berkelanjutan, cascara mulai dilihat sebagai bahan bernilai. Dengan pengolahan yang tepat, petani dan produsen bisa mengurangi limbah pasca-panen sekaligus menciptakan produk minuman baru yang menarik bagi konsumen.</p>

<h2>Bagaimana Rasa Cascara?</h2>
<p>Rasa cascara biasanya ringan dan mudah diterima, terutama bagi orang yang ingin mencoba minuman selain teh dan kopi. Profil rasanya dapat berbeda-beda tergantung jenis kopi, daerah tanam, kematangan buah, serta cara pengeringan. Secara umum, cascara memiliki rasa fruity dengan aroma seperti kismis, kurma, madu, apel kering, atau hibiscus.</p>

<p>Jika dibandingkan dengan kopi, cascara tidak memiliki rasa pahit dan smoky yang kuat. Jika dibandingkan dengan teh, cascara terasa lebih manis alami dan memiliki aroma buah yang lebih menonjol. Karena itulah cascara cocok untuk pemula yang ingin mencoba minuman ringan, tidak terlalu pekat, tetapi tetap punya karakter.</p>

<h2>Apakah Cascara Mengandung Kafein?</h2>
<p>Cascara tetap berasal dari tanaman kopi, jadi minuman ini dapat mengandung kafein. Namun, kandungan kafeinnya umumnya lebih ringan dibandingkan kopi seduh. Efeknya biasanya terasa lebih lembut, sehingga banyak orang menjadikannya pilihan ketika ingin minuman yang menyegarkan tanpa sensasi kafein yang terlalu kuat.</p>

<p>Meski begitu, respons setiap orang terhadap kafein bisa berbeda. Jika kamu sensitif terhadap kafein, sebaiknya mulai dari porsi kecil terlebih dahulu. Hindari minum terlalu dekat dengan waktu tidur agar tidak mengganggu kualitas istirahat.</p>

<h2>Cara Menyeduh Cascara untuk Pemula</h2>
<p>Cara menyeduh cascara cukup sederhana. Untuk percobaan awal, gunakan sekitar 5 sampai 8 gram cascara kering untuk 250 ml air panas. Seduh selama 4 sampai 6 menit, lalu saring sebelum diminum. Jika ingin rasa yang lebih kuat, kamu bisa menambah jumlah cascara atau memperpanjang waktu seduh sedikit.</p>

<p>Untuk penyajian hangat, cascara cocok diminum apa adanya karena rasa buahnya sudah cukup terasa. Untuk penyajian dingin, seduh cascara terlebih dahulu, diamkan sampai suhu ruang, lalu tambahkan es batu. Kamu juga bisa menambahkan madu, irisan lemon, daun mint, atau sedikit jahe untuk membuat rasa lebih segar dan aromatik.</p>

<h2>Manfaat Cascara dalam Kehidupan Sehari-hari</h2>
<p>Cascara dapat menjadi pilihan minuman harian karena rasanya ringan dan tidak membosankan. Minuman ini cocok diminum saat pagi untuk membuka hari, saat siang sebagai penyegar, atau sore hari ketika ingin minuman hangat yang tidak terlalu berat. Cascara juga bisa menjadi alternatif bagi orang yang ingin mengurangi konsumsi minuman manis kemasan.</p>

<p>Dari sisi bahan, cascara dikenal memiliki kandungan senyawa alami dari buah kopi, termasuk polifenol dan antioksidan. Namun, penting untuk dipahami bahwa cascara sebaiknya dilihat sebagai bagian dari pola konsumsi yang seimbang, bukan sebagai obat. Manfaat terbaiknya akan terasa jika dikombinasikan dengan kebiasaan hidup sehat, seperti cukup minum air, makan bergizi, bergerak aktif, dan tidur cukup.</p>

<h2>Tips Memilih Cascara yang Baik</h2>
<p>Untuk pemula, pilih cascara yang aromanya bersih, tidak apek, dan tidak berbau lembap. Warna cascara bisa bervariasi, tetapi bahan yang baik umumnya tampak kering merata dan tidak menggumpal karena kelembapan. Pastikan juga produk disimpan dalam kemasan tertutup agar kualitas aromanya tetap terjaga.</p>

<p>Produk cascara yang baik sebaiknya memiliki informasi penyajian yang jelas. Ini membantu kamu menentukan takaran, suhu air, dan waktu seduh yang sesuai. Jika baru pertama mencoba, gunakan takaran ringan dulu, lalu sesuaikan dengan selera setelah beberapa kali seduh.</p>

<h2>Kesimpulan</h2>
<p>Cascara adalah minuman dari kulit buah kopi kering yang menawarkan pengalaman rasa berbeda dari kopi maupun teh. Rasanya cenderung fruity, ringan, manis alami, dan fleksibel untuk disajikan hangat maupun dingin. Bagi pemula, cascara adalah pilihan menarik karena mudah diseduh, mudah dikreasikan, dan punya cerita keberlanjutan dari pemanfaatan bagian buah kopi yang sebelumnya sering terbuang.</p>

<p>Jika kamu ingin mulai mencoba, seduh cascara dengan takaran sederhana, nikmati tanpa tambahan terlebih dahulu, lalu eksplorasi dengan lemon, madu, mint, atau rempah sesuai selera. Dengan begitu, kamu bisa mengenali karakter asli cascara sebelum menemukan racikan favoritmu sendiri.</p>
HTML
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Tips Menyimpan Cascara Agar Tetap Segar',
                'slug' => 'tips-menyimpan-cascara-agar-tetap-segar',
                'excerpt' => 'Simpan cascara di tempat kering agar kualitas rasa tetap terjaga.',
                'published_at' => '2026-04-18 08:45:00',
                'content' => <<<'HTML'
<p>Cascara adalah bahan alami yang berasal dari kulit dan daging buah kopi yang dikeringkan. Karena bentuknya kering, banyak orang mengira cascara bisa disimpan begitu saja tanpa perhatian khusus. Padahal, kualitas cascara sangat dipengaruhi oleh cara penyimpanan. Jika terkena udara lembap, panas berlebihan, atau paparan sinar matahari langsung, aroma dan rasa cascara bisa menurun lebih cepat.</p>

<p>Penyimpanan yang baik membantu menjaga karakter rasa cascara tetap segar. Aroma buah kering, rasa manis alami, dan sentuhan asam segar yang menjadi ciri khas cascara akan lebih tahan lama jika bahan disimpan dalam kondisi kering dan tertutup. Karena itu, setelah kemasan dibuka, cascara sebaiknya segera dipindahkan atau ditutup kembali dengan rapat.</p>

<h2>Gunakan Wadah Tertutup Rapat</h2>
<p>Langkah pertama yang paling penting adalah menyimpan cascara dalam wadah yang tertutup rapat. Wadah kaca, kaleng makanan, atau pouch dengan zip lock dapat membantu mengurangi kontak cascara dengan udara luar. Semakin sering cascara terkena udara, semakin besar kemungkinan aromanya memudar.</p>

<p>Jika menggunakan wadah kaca, pastikan tutupnya benar-benar rapat. Hindari wadah yang masih lembap atau baru dicuci tetapi belum kering sempurna. Sedikit air yang tertinggal di dalam wadah dapat meningkatkan risiko cascara menjadi lembap dan kurang enak ketika diseduh.</p>

<h2>Jauhkan dari Sinar Matahari</h2>
<p>Sinar matahari langsung dapat mempercepat penurunan kualitas bahan kering, termasuk cascara. Panas dari matahari bisa mengubah aroma dan membuat rasa cascara menjadi lebih datar. Karena itu, simpan cascara di tempat yang teduh, seperti lemari dapur, rak tertutup, atau laci khusus bahan minuman.</p>

<p>Jika kemasan cascara transparan, usahakan jangan meletakkannya di dekat jendela. Lebih baik gunakan wadah berwarna gelap atau simpan kemasan di dalam kotak agar terlindung dari cahaya berlebih.</p>

<h2>Hindari Tempat yang Lembap</h2>
<p>Kelembapan adalah salah satu musuh utama cascara. Tempat seperti dekat wastafel, dekat kompor, atau area dapur yang sering terkena uap sebaiknya dihindari. Cascara yang menyerap kelembapan dapat menggumpal, kehilangan aroma, bahkan berisiko berjamur jika dibiarkan terlalu lama.</p>

<p>Untuk menjaga kualitasnya, pastikan sendok atau alat yang digunakan untuk mengambil cascara selalu kering. Jangan memasukkan sendok basah ke dalam wadah karena air dapat menyebar ke bahan lain di dalamnya.</p>

<h2>Perhatikan Aroma dan Tampilan</h2>
<p>Sebelum menyeduh, periksa aroma dan tampilan cascara. Cascara yang masih baik biasanya memiliki aroma buah kering yang bersih dan tidak menyengat. Jika aromanya berubah menjadi apek, terlalu asam, atau seperti lembap, sebaiknya jangan digunakan.</p>

<p>Dari segi tampilan, cascara yang baik terlihat kering dan tidak menggumpal basah. Warna bisa berbeda tergantung bahan dan proses pengeringan, tetapi teksturnya harus tetap kering. Jika terlihat ada bercak putih, jamur, atau bau tidak wajar, lebih aman untuk tidak mengonsumsinya.</p>

<h2>Berapa Lama Cascara Bisa Disimpan?</h2>
<p>Lama penyimpanan cascara bergantung pada kualitas awal produk dan kondisi penyimpanan di rumah. Jika disimpan dengan benar dalam wadah tertutup, kering, dan terlindung dari panas, cascara dapat mempertahankan kualitas rasanya lebih lama. Namun, untuk rasa terbaik, sebaiknya konsumsi cascara dalam beberapa bulan setelah kemasan dibuka.</p>

<p>Menulis tanggal buka kemasan juga bisa membantu. Dengan begitu, kamu tahu sudah berapa lama cascara disimpan dan bisa mengatur penggunaannya sebelum aromanya terlalu banyak menurun.</p>

<h2>Kesimpulan</h2>
<p>Menyimpan cascara dengan benar tidak sulit, tetapi sangat berpengaruh terhadap hasil seduhan. Gunakan wadah rapat, jauhkan dari sinar matahari, hindari kelembapan, dan selalu gunakan alat kering saat mengambil bahan. Dengan cara sederhana ini, cascara akan tetap segar, aromanya terjaga, dan rasanya lebih konsisten setiap kali diseduh.</p>
HTML
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Cara Menyeduh Cascara yang Benar di Rumah',
                'slug' => 'cara-menyeduh-cascara-yang-benar-di-rumah',
                'excerpt' => 'Rasio air, suhu, dan waktu seduh untuk hasil terbaik.',
                'published_at' => '2026-05-03 13:15:00',
                'content' => <<<'HTML'
<p>Menyeduh cascara di rumah sebenarnya sangat mudah. Kamu tidak membutuhkan alat yang rumit untuk mendapatkan minuman yang enak. Dengan takaran yang tepat, suhu air yang sesuai, dan waktu seduh yang pas, cascara dapat menghasilkan rasa fruity, ringan, dan menyegarkan.</p>

<p>Karena cascara berasal dari kulit buah kopi yang dikeringkan, cara menyeduhnya lebih mirip membuat teh dibandingkan membuat kopi. Kamu cukup merendam cascara dalam air panas, menunggu beberapa menit, lalu menyaringnya sebelum disajikan. Meski sederhana, beberapa detail kecil bisa membuat hasil seduhan jauh lebih nikmat.</p>

<h2>Siapkan Bahan dan Alat</h2>
<p>Untuk membuat satu gelas cascara, siapkan 5 sampai 10 gram cascara kering dan 250 ml air panas. Kamu bisa menggunakan teko kecil, gelas tahan panas, french press, atau tea infuser. Jika tidak punya alat khusus, gelas biasa dan saringan kecil pun sudah cukup.</p>

<p>Gunakan air bersih dengan rasa netral. Air yang terlalu berbau atau terlalu banyak mineral dapat memengaruhi rasa seduhan. Jika memungkinkan, gunakan air matang yang baru dipanaskan agar hasilnya lebih bersih dan segar.</p>

<h2>Perhatikan Suhu Air</h2>
<p>Suhu air yang ideal untuk cascara berada di kisaran 85 sampai 95 derajat celsius. Air yang terlalu panas dapat membuat rasa menjadi terlalu tajam, sedangkan air yang terlalu dingin dapat membuat rasa kurang keluar. Jika tidak memiliki termometer, diamkan air mendidih sekitar satu menit sebelum digunakan.</p>

<p>Untuk pemula, mulai dari suhu sekitar 90 derajat celsius adalah pilihan aman. Dari sana, kamu bisa menyesuaikan sesuai selera. Jika ingin rasa lebih lembut, gunakan suhu sedikit lebih rendah. Jika ingin rasa lebih kuat, gunakan suhu sedikit lebih tinggi atau tambah waktu seduh.</p>

<h2>Atur Waktu Seduh</h2>
<p>Waktu seduh yang umum untuk cascara adalah 4 sampai 6 menit. Pada menit-menit awal, rasa buah dan aroma manis mulai keluar. Semakin lama diseduh, rasa akan semakin kuat. Namun, jika terlalu lama, beberapa cascara bisa menghasilkan rasa yang terlalu pekat atau kurang seimbang.</p>

<p>Untuk percobaan pertama, gunakan 6 gram cascara dan seduh selama 5 menit. Setelah itu, cicipi hasilnya. Jika terasa terlalu ringan, tambahkan jumlah cascara pada seduhan berikutnya. Jika terasa terlalu kuat, kurangi takaran atau waktu seduh.</p>

<h2>Cara Membuat Cascara Hangat</h2>
<p>Masukkan cascara ke dalam teko atau gelas, tuangkan air panas, lalu tutup selama proses seduh. Menutup wadah membantu menjaga suhu dan aroma agar tidak cepat hilang. Setelah 4 sampai 6 menit, saring cascara dan tuangkan ke gelas saji.</p>

<p>Cascara hangat enak diminum tanpa tambahan apa pun. Namun, jika ingin rasa lebih manis, kamu bisa menambahkan sedikit madu atau gula aren. Untuk sensasi hangat yang lebih aromatik, tambahkan irisan jahe atau sejumput kayu manis.</p>

<h2>Cara Membuat Cascara Dingin</h2>
<p>Untuk versi dingin, seduh cascara seperti biasa terlebih dahulu, lalu diamkan sampai suhu ruang. Setelah itu, masukkan ke kulkas atau sajikan langsung dengan es batu. Kamu bisa menambahkan irisan lemon, daun mint, atau madu agar rasanya lebih segar.</p>

<p>Jika ingin rasa dingin yang lebih halus, kamu juga bisa membuat cold brew cascara. Caranya, rendam cascara dalam air suhu ruang atau air dingin selama 8 sampai 12 jam di kulkas, lalu saring. Hasilnya biasanya lebih lembut, manis, dan tidak terlalu tajam.</p>

<h2>Kesalahan yang Sering Terjadi</h2>
<p>Kesalahan paling umum adalah menggunakan takaran yang terlalu banyak sejak awal. Akibatnya, rasa cascara menjadi terlalu pekat dan kurang nyaman bagi pemula. Kesalahan lain adalah menyeduh terlalu lama atau menggunakan air yang masih mendidih langsung tanpa didiamkan.</p>

<p>Selain itu, pastikan cascara yang digunakan masih segar dan disimpan dengan benar. Bahan yang sudah apek akan sulit menghasilkan seduhan yang enak meskipun teknik seduhnya sudah tepat.</p>

<h2>Kesimpulan</h2>
<p>Kunci menyeduh cascara yang enak adalah keseimbangan antara takaran, suhu air, dan waktu seduh. Mulailah dengan 5 sampai 8 gram cascara untuk 250 ml air, gunakan air panas sekitar 90 derajat celsius, lalu seduh selama 4 sampai 6 menit. Setelah menemukan rasa dasar yang cocok, kamu bisa bereksperimen dengan lemon, madu, mint, jahe, atau penyajian dingin sesuai selera.</p>
HTML
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Cascara Dingin: Cocok untuk Cuaca Panas',
                'slug' => 'cascara-dingin-cocok-untuk-cuaca-panas',
                'excerpt' => 'Versi dingin cascara terasa segar dan cocok diminum saat siang hari.',
                'published_at' => '2026-05-14 11:00:00',
                'content' => <<<'HTML'
<p>Cascara dingin adalah salah satu cara paling menyenangkan untuk menikmati kulit buah kopi. Rasanya ringan, fruity, dan menyegarkan, sehingga cocok diminum saat cuaca panas atau ketika kamu ingin minuman yang tidak terlalu berat. Berbeda dengan minuman manis kemasan, cascara dingin dapat dibuat dengan rasa yang lebih natural dan mudah disesuaikan dengan selera.</p>

<p>Karakter cascara yang memiliki aroma buah kering membuatnya cocok dipadukan dengan es batu, lemon, madu, mint, atau soda. Minuman ini bisa menjadi alternatif untuk menemani aktivitas siang hari, belajar, bekerja, atau bersantai di rumah.</p>

<h2>Mengapa Cascara Enak Disajikan Dingin?</h2>
<p>Cascara memiliki rasa dasar yang cenderung manis alami dan sedikit asam segar. Ketika disajikan dingin, karakter buahnya terasa lebih ringan dan menyegarkan. Es batu membantu membuat rasa cascara lebih clean, sementara tambahan buah sitrus seperti lemon dapat memperkuat sensasi segarnya.</p>

<p>Versi dingin juga cocok untuk orang yang baru mencoba cascara. Jika seduhan hangat terasa terlalu aromatik, penyajian dingin biasanya lebih mudah diterima karena rasanya lebih lembut dan familiar, mirip teh buah ringan.</p>

<h2>Cara Membuat Cascara Dingin Praktis</h2>
<p>Untuk membuat cascara dingin, seduh 5 sampai 8 gram cascara kering dengan 250 ml air panas selama 4 sampai 6 menit. Setelah itu, saring dan diamkan sampai tidak terlalu panas. Tuangkan ke gelas berisi es batu, lalu sajikan.</p>

<p>Jika ingin rasa yang lebih segar, tambahkan irisan lemon atau jeruk nipis. Untuk rasa lebih manis, gunakan madu secukupnya. Aduk perlahan agar rasa menyatu, lalu nikmati selagi dingin.</p>

<h2>Cold Brew Cascara</h2>
<p>Selain diseduh panas lalu didinginkan, cascara juga bisa dibuat dengan metode cold brew. Caranya, masukkan cascara ke dalam botol atau jar, tambahkan air dingin, lalu simpan di kulkas selama 8 sampai 12 jam. Setelah itu, saring dan sajikan dengan es.</p>

<p>Cold brew cascara biasanya menghasilkan rasa yang lebih halus, manis, dan tidak terlalu tajam. Metode ini cocok jika kamu ingin menyiapkan minuman dari malam hari untuk diminum keesokan paginya.</p>

<h2>Ide Variasi Rasa</h2>
<p>Cascara dingin sangat fleksibel untuk dikreasikan. Untuk rasa citrus, tambahkan lemon atau jeruk nipis. Untuk aroma yang lebih segar, tambahkan daun mint. Jika ingin nuansa hangat tetapi tetap segar, masukkan sedikit jahe yang sudah digeprek saat proses seduh panas.</p>

<p>Kamu juga bisa membuat cascara sparkling dengan menambahkan soda tawar setelah seduhan dingin siap. Hasilnya adalah minuman berkarbonasi ringan dengan rasa buah yang unik. Sajian ini cocok untuk acara santai, piknik, atau minuman sore hari.</p>

<h2>Kapan Waktu Terbaik Menikmatinya?</h2>
<p>Cascara dingin paling cocok diminum saat siang atau sore hari. Saat cuaca panas, minuman ini memberi sensasi segar tanpa rasa terlalu berat. Untuk menemani makanan ringan, cascara dingin cocok dipadukan dengan camilan seperti roti, kue kering, buah, atau makanan yang tidak terlalu berminyak.</p>

<p>Karena cascara tetap dapat mengandung kafein meskipun biasanya lebih ringan dari kopi, perhatikan waktu minumnya jika kamu sensitif terhadap kafein. Jika mudah sulit tidur, sebaiknya nikmati cascara dingin sebelum sore menjelang malam.</p>

<h2>Kesimpulan</h2>
<p>Cascara dingin adalah pilihan tepat untuk cuaca panas karena rasanya segar, ringan, dan mudah dikreasikan. Kamu bisa membuatnya dengan metode seduh panas lalu diberi es, atau menggunakan metode cold brew untuk rasa yang lebih lembut. Tambahkan lemon, madu, mint, jahe, atau soda tawar untuk menciptakan varian yang sesuai dengan selera.</p>
HTML
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Mengapa Cascara Jadi Tren Minuman Sehat?',
                'slug' => 'mengapa-cascara-jadi-tren-minuman-sehat',
                'excerpt' => 'Cascara makin populer karena unik, ringan, dan mudah dinikmati.',
                'published_at' => '2026-05-27 15:20:00',
                'content' => <<<'HTML'
<p>Cascara semakin sering dibicarakan sebagai salah satu minuman alternatif yang menarik. Di tengah banyaknya pilihan minuman modern, cascara hadir dengan karakter yang berbeda: berasal dari kulit buah kopi, memiliki rasa fruity yang ringan, dan membawa cerita keberlanjutan dari proses pasca-panen kopi. Kombinasi inilah yang membuat cascara mulai dilirik oleh banyak orang, terutama mereka yang ingin mencoba minuman baru selain kopi dan teh.</p>

<p>Popularitas cascara tidak muncul hanya karena rasanya unik. Minuman ini juga dekat dengan gaya hidup yang lebih sadar bahan, lebih peduli asal-usul produk, dan lebih terbuka terhadap pilihan minuman yang ringan untuk dikonsumsi sehari-hari. Cascara menjawab kebutuhan tersebut dengan cara yang sederhana namun menarik.</p>

<h2>Rasa yang Unik dan Mudah Diterima</h2>
<p>Salah satu alasan utama cascara menjadi tren adalah rasanya yang unik. Walaupun berasal dari tanaman kopi, cascara tidak terasa seperti kopi hitam. Rasanya lebih dekat dengan teh buah, dengan aroma seperti kismis, kurma, madu, apel kering, atau hibiscus, tergantung bahan dan cara pengolahannya.</p>

<p>Karakter rasa ini membuat cascara mudah diterima oleh pemula. Orang yang tidak terlalu suka rasa pahit kopi masih bisa menikmati cascara karena seduhannya cenderung ringan, manis alami, dan menyegarkan. Minuman ini juga fleksibel: bisa disajikan hangat, dingin, dicampur lemon, madu, mint, atau dibuat menjadi sparkling cascara.</p>

<h2>Alternatif Minuman dengan Kafein Lebih Ringan</h2>
<p>Banyak orang ingin tetap menikmati minuman yang memberi semangat, tetapi tidak selalu ingin minum kopi yang kafeinnya terasa kuat. Cascara bisa menjadi pilihan karena kandungan kafeinnya umumnya lebih ringan dibandingkan kopi seduh. Efeknya terasa lebih halus, sehingga cocok untuk orang yang ingin mengurangi konsumsi kopi tetapi tetap ingin minuman berkarakter.</p>

<p>Meski begitu, cascara tetap berasal dari tanaman kopi. Artinya, orang yang sensitif terhadap kafein tetap perlu menyesuaikan porsi dan waktu minum. Untuk konsumsi harian, cascara paling nyaman dinikmati pada pagi, siang, atau sore hari.</p>

<h2>Dekat dengan Gaya Hidup Sehat</h2>
<p>Cascara sering dikaitkan dengan gaya hidup sehat karena dapat menjadi alternatif dari minuman manis kemasan. Tanpa tambahan gula berlebihan, cascara sudah memiliki rasa manis alami dan aroma buah yang menyenangkan. Ini membuatnya cocok untuk orang yang ingin minuman berasa tetapi tetap ringan.</p>

<p>Cascara juga dikenal mengandung senyawa alami dari buah kopi, termasuk polifenol dan antioksidan. Walaupun demikian, cascara sebaiknya tetap dipahami sebagai minuman pendukung gaya hidup sehat, bukan sebagai obat. Manfaat terbaiknya akan terasa jika dikombinasikan dengan pola makan seimbang, cukup istirahat, dan aktivitas fisik teratur.</p>

<h2>Punya Nilai Keberlanjutan</h2>
<p>Alasan lain cascara semakin populer adalah nilai keberlanjutannya. Dalam proses produksi kopi, kulit buah kopi sering dianggap sebagai limbah. Dengan mengolahnya menjadi minuman, bagian yang sebelumnya kurang dimanfaatkan bisa memiliki nilai ekonomi baru.</p>

<p>Pemanfaatan cascara dapat membantu mengurangi limbah pasca-panen sekaligus memberi peluang tambahan bagi petani dan pelaku usaha kopi. Bagi konsumen, memilih cascara berarti ikut mengenal sisi lain dari rantai kopi yang lebih berkelanjutan.</p>

<h2>Cocok untuk Kreasi Minuman Modern</h2>
<p>Cascara mudah dikembangkan menjadi berbagai jenis minuman. Untuk versi sederhana, cascara bisa diseduh seperti teh. Untuk versi modern, cascara bisa dijadikan iced tea, mocktail, sparkling drink, campuran lemon, atau minuman rempah hangat.</p>

<p>Fleksibilitas ini membuat cascara cocok untuk kafe, usaha minuman rumahan, maupun konsumsi pribadi. Satu bahan bisa menghasilkan banyak variasi rasa, sehingga tidak cepat membosankan.</p>

<h2>Kesimpulan</h2>
<p>Cascara menjadi tren karena menawarkan kombinasi yang kuat: rasa unik, penyajian fleksibel, kafein yang lebih ringan, dan cerita keberlanjutan. Minuman ini cocok untuk orang yang ingin mencoba sesuatu yang baru, tetapi tetap mudah dinikmati sehari-hari. Dengan pengolahan yang baik dan penyajian yang tepat, cascara bisa menjadi bagian menarik dari kebiasaan minum yang lebih alami dan beragam.</p>
HTML
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Mengenal Varian Rasa Cascara dan Cara Memilihnya',
                'slug' => 'mengenal-varian-rasa-cascara-dan-cara-memilihnya',
                'excerpt' => 'Panduan memilih varian cascara sesuai selera, dari original sampai rempah.',
                'published_at' => '2026-06-02 09:40:00',
                'content' => <<<'HTML'
<p>Cascara memiliki karakter dasar yang fruity, ringan, dan manis alami. Namun, ketika dikembangkan menjadi berbagai varian rasa, pengalaman minumnya bisa menjadi jauh lebih beragam. Ada cascara original yang menonjolkan rasa asli kulit buah kopi, ada varian lemon yang segar, jahe yang hangat, madu yang lembut, hingga mint yang memberi sensasi dingin.</p>

<p>Memilih varian cascara sebenarnya tidak sulit. Kuncinya adalah mengenali selera pribadi dan tujuan minumnya. Apakah kamu ingin minuman segar untuk siang hari, minuman hangat untuk sore, atau rasa ringan untuk menemani aktivitas harian? Setiap varian punya karakter yang berbeda dan bisa disesuaikan dengan suasana.</p>

<h2>Cascara Original</h2>
<p>Varian original adalah pilihan terbaik untuk mengenal rasa asli cascara. Di sini, kamu bisa merasakan karakter buah kering, manis ringan, dan sedikit asam segar tanpa tambahan rasa lain. Varian ini cocok untuk pemula yang ingin memahami seperti apa rasa cascara sebelum mencoba varian campuran.</p>

<p>Cascara original bisa dinikmati hangat maupun dingin. Jika disajikan hangat, aromanya terasa lebih lembut dan menenangkan. Jika disajikan dingin, rasanya menjadi lebih segar dan ringan.</p>

<h2>Cascara Lemon</h2>
<p>Cascara lemon cocok untuk kamu yang suka minuman segar dengan sentuhan citrus. Rasa lemon membantu menonjolkan sisi asam segar dari cascara, sehingga hasilnya terasa cerah dan cocok diminum saat cuaca panas.</p>

<p>Varian ini enak disajikan dengan es batu. Untuk rasa yang lebih seimbang, tambahkan sedikit madu agar asam lemon dan manis cascara menyatu dengan baik.</p>

<h2>Cascara Jahe</h2>
<p>Jika kamu menyukai minuman hangat, cascara jahe bisa menjadi pilihan. Jahe memberi sensasi hangat dan sedikit pedas yang berpadu dengan rasa buah cascara. Varian ini cocok diminum saat sore hari, saat cuaca dingin, atau ketika ingin minuman yang terasa lebih menenangkan.</p>

<p>Cascara jahe paling nikmat disajikan hangat. Aromanya terasa lebih kuat, dan rasa jahenya memberi lapisan rasa yang lebih dalam tanpa menutupi karakter cascara.</p>

<h2>Cascara Madu</h2>
<p>Varian madu cocok untuk kamu yang menyukai rasa manis lembut. Madu dapat memperkuat body minuman dan membuat cascara terasa lebih halus. Rasa ini biasanya mudah diterima oleh orang yang baru mencoba cascara karena tidak terlalu tajam.</p>

<p>Cascara madu bisa diminum hangat atau dingin. Untuk versi dingin, tambahkan es batu dan sedikit lemon agar rasanya tidak terlalu manis dan tetap segar.</p>

<h2>Cascara Mint</h2>
<p>Cascara mint menawarkan sensasi segar yang berbeda. Mint memberi aftertaste dingin dan membuat cascara terasa lebih ringan. Varian ini cocok untuk siang hari atau setelah makan karena rasanya bersih dan menyegarkan.</p>

<p>Jika ingin membuatnya lebih menarik, cascara mint bisa dipadukan dengan lemon atau soda tawar. Hasilnya menjadi minuman dingin yang modern dan cocok untuk dinikmati santai.</p>

<h2>Tips Memilih Varian</h2>
<p>Untuk pemula, mulai dari original adalah pilihan aman karena kamu bisa mengenal rasa dasar cascara terlebih dahulu. Jika suka rasa segar, pilih lemon atau mint. Jika suka rasa hangat, pilih jahe atau rempah. Jika ingin rasa yang lebih lembut dan manis, pilih madu.</p>

<p>Kamu juga bisa memilih berdasarkan waktu minum. Pagi hari cocok dengan original, siang hari cocok dengan lemon atau mint, sore hari cocok dengan jahe, dan waktu santai cocok dengan madu. Tidak ada aturan yang kaku; yang paling penting adalah menemukan rasa yang paling nyaman untuk seleramu.</p>

<h2>Kesimpulan</h2>
<p>Varian cascara membuat minuman ini semakin fleksibel. Setiap rasa punya karakter sendiri dan bisa disesuaikan dengan suasana. Dengan mencoba beberapa varian, kamu bisa menemukan pilihan favorit, sekaligus memahami betapa luasnya potensi rasa dari kulit buah kopi yang sederhana ini.</p>
HTML
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Cascara dan Keberlanjutan: Dari Limbah Kopi Menjadi Minuman Bernilai',
                'slug' => 'cascara-dan-keberlanjutan-dari-limbah-kopi-menjadi-minuman-bernilai',
                'excerpt' => 'Melihat bagaimana cascara membantu mengurangi limbah kopi dan memberi nilai tambah.',
                'published_at' => '2026-06-08 14:10:00',
                'content' => <<<'HTML'
<p>Dalam proses produksi kopi, bagian yang paling dikenal dan dimanfaatkan adalah bijinya. Setelah buah kopi dipanen, biji kopi dipisahkan dari kulit dan daging buahnya. Bagian luar buah kopi ini sering kali dianggap sebagai sisa produksi. Padahal, jika diolah dengan benar, kulit buah kopi dapat menjadi cascara, bahan minuman yang memiliki nilai rasa dan nilai ekonomi.</p>

<p>Inilah yang membuat cascara menarik dari sisi keberlanjutan. Cascara bukan hanya minuman yang enak, tetapi juga contoh bagaimana bahan yang sebelumnya kurang dimanfaatkan dapat diubah menjadi produk bernilai. Dengan pendekatan ini, rantai produksi kopi menjadi lebih efisien dan lebih ramah lingkungan.</p>

<h2>Mengurangi Limbah Pasca-Panen</h2>
<p>Produksi kopi menghasilkan banyak sisa kulit buah. Jika tidak dikelola dengan baik, limbah organik ini dapat menumpuk dan menimbulkan masalah lingkungan, terutama di sekitar area pengolahan. Dengan mengolah kulit buah kopi menjadi cascara, sebagian limbah tersebut dapat dimanfaatkan kembali.</p>

<p>Pemanfaatan cascara membantu mengubah cara pandang terhadap sisa produksi. Kulit buah kopi tidak lagi hanya dianggap sebagai buangan, tetapi sebagai bahan yang bisa diproses, dikemas, dan dinikmati sebagai minuman.</p>

<h2>Memberi Nilai Tambah bagi Petani</h2>
<p>Cascara juga berpotensi memberi nilai tambah bagi petani kopi. Jika kulit buah kopi dapat dijual sebagai bahan minuman, petani memiliki peluang memperoleh pendapatan tambahan dari bagian tanaman yang sebelumnya kurang bernilai.</p>

<p>Tentu saja, hal ini membutuhkan proses yang baik. Cascara harus dibersihkan, dikeringkan, dan disimpan dengan standar higienis agar aman dikonsumsi. Dengan pelatihan dan kerja sama yang tepat, cascara dapat menjadi bagian dari usaha tani kopi yang lebih berkelanjutan.</p>

<h2>Mendorong Konsumen Lebih Mengenal Asal Produk</h2>
<p>Ketika seseorang minum cascara, ia tidak hanya menikmati rasa, tetapi juga mengenal cerita di balik produk kopi. Cascara memperkenalkan bahwa buah kopi memiliki lebih banyak bagian yang bisa dimanfaatkan selain bijinya. Hal ini membuat konsumen lebih memahami proses panjang dari kebun hingga menjadi minuman.</p>

<p>Kesadaran seperti ini penting karena dapat mendorong pilihan konsumsi yang lebih bijak. Konsumen tidak hanya melihat produk dari rasa dan harga, tetapi juga dari asal-usul, proses, dan dampaknya terhadap lingkungan.</p>

<h2>Tantangan Pengolahan Cascara</h2>
<p>Walaupun potensinya besar, pengolahan cascara tetap memiliki tantangan. Karena berasal dari kulit buah, cascara harus diproses dengan sangat bersih. Pengeringan yang kurang baik dapat menyebabkan kelembapan berlebih dan menurunkan kualitas bahan.</p>

<p>Selain itu, penyimpanan juga harus diperhatikan. Cascara yang sudah kering perlu disimpan dalam wadah tertutup, jauh dari sinar matahari, dan terlindung dari kelembapan. Kualitas bahan baku sangat menentukan rasa dan keamanan produk akhir.</p>

<h2>Cascara sebagai Produk Modern</h2>
<p>Di tangan produsen kreatif, cascara dapat dikembangkan menjadi banyak produk. Ada cascara kering untuk diseduh sendiri, cascara dalam kemasan botol, cascara dengan rempah, hingga minuman dingin siap nikmat. Inovasi ini membuat cascara lebih mudah diterima oleh konsumen modern.</p>

<p>Dengan kemasan yang baik, informasi penyajian yang jelas, dan kualitas rasa yang konsisten, cascara dapat menjadi produk yang tidak hanya unik, tetapi juga punya nilai edukasi dan keberlanjutan.</p>

<h2>Kesimpulan</h2>
<p>Cascara adalah contoh sederhana bagaimana keberlanjutan bisa hadir dalam produk sehari-hari. Dari bagian buah kopi yang dulu sering terbuang, cascara menjadi minuman bernilai yang dapat dinikmati banyak orang. Dengan pengolahan yang baik, cascara membantu mengurangi limbah, memberi peluang tambahan bagi petani, dan mengajak konsumen untuk lebih peduli terhadap asal-usul minuman yang mereka nikmati.</p>
HTML
            ],
        ];

        foreach ($articles as $article) {
            $publishedAt = $article['published_at'];
            unset($article['published_at']);

            $articleModel = Article::updateOrCreate(['slug' => $article['slug']], $article);
            $articleModel->timestamps = false;
            $articleModel->forceFill([
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ])->save();
        }

        $thread = ForumThread::firstOrCreate(
            ['title' => 'Apakah cascara aman diminum setiap hari?'],
            [
                'user_id' => $user->id,
                'body' => 'Saya baru mulai minum cascara. Apakah aman jika diminum rutin setiap hari?',
            ]
        );

        ForumReply::firstOrCreate(
            [
                'forum_thread_id' => $thread->id,
                'body' => 'Secara umum aman jika dikonsumsi wajar. Tetap perhatikan respons tubuh dan batasi jika sensitif terhadap kafein.',
            ],
            [
                'user_id' => $admin->id,
            ]
        );
    }
}
