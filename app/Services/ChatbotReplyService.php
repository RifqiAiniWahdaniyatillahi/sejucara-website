<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Str;

class ChatbotReplyService
{
    public function replyTo(ChatMessage $message): ?ChatMessage
    {
        if ($message->image_path || blank($message->message)) {
            return null;
        }

        $admin = User::where('is_admin', true)->oldest()->first();

        if (! $admin) {
            return null;
        }

        $reply = $this->matchReply($message->message);

        if (! $reply) {
            return null;
        }

        return $message->conversation->messages()->create([
            'sender_id' => $admin->id,
            'message' => $reply,
        ]);
    }

    private function matchReply(string $message): ?string
    {
        $text = Str::of($message)->lower()->squish()->value();

        $rules = [
            [
                'keywords' => ['apa itu sejucara', 'sejucara itu apa', 'sejucara apa', 'arti sejucara', 'makna sejucara', 'tentang sejucara'],
                'reply' => 'Sejucara adalah singkatan dari Sejuk Cascara, yang melambangkan kesegaran minuman cascara dari kulit buah kopi pilihan. Nama ini menggambarkan produk cascara yang ringan, alami, dan menyegarkan untuk dinikmati dalam berbagai suasana.',
            ],
            [
                'keywords' => ['jam operasional', 'jam buka', 'admin buka', 'admin aktif', 'dibalas jam'],
                'reply' => 'Pesan di live chat dapat dikirim kapan saja. Jika admin sedang tidak tersedia, pesan akan tetap tersimpan dan dibalas saat admin aktif kembali.',
            ],
            [
                'keywords' => ['lokasi', 'alamat toko', 'toko dimana', 'offline store', 'datang langsung'],
                'reply' => 'Sejucara saat ini belum memiliki toko fisik tetap dan penjualan dilakukan secara online. Pada beberapa kesempatan Sejucara dapat mengikuti event tertentu, sedangkan pengiriman produk dilakukan dari wilayah Kabupaten Bandung.',
            ],
            [
                'keywords' => ['berapa lama', 'estimasi', 'sampai kapan', 'lama pengiriman'],
                'reply' => 'Estimasi pengiriman mengikuti layanan kurir yang dipilih saat checkout. Setelah memilih wilayah tujuan dan kurir, sistem akan menampilkan estimasi tiba dari RajaOngkir.',
            ],
            [
                'keywords' => ['ongkir', 'ongkos kirim', 'biaya kirim', 'pengiriman'],
                'reply' => 'Ongkir dihitung otomatis di halaman checkout melalui RajaOngkir. Silakan isi alamat lengkap, pilih wilayah untuk perhitungan ongkir, pilih kurir, lalu klik Cek Ongkir.',
            ],
            [
                'keywords' => ['ongkir tidak muncul', 'ongkir kosong', 'tidak ada ongkir', 'kurir tidak tersedia'],
                'reply' => 'Jika ongkir tidak muncul, pastikan wilayah tujuan sudah dipilih dan coba gunakan kata kunci kecamatan, kelurahan, atau kode pos. Jika kurir tertentu tidak tersedia, silakan coba kurir lain.',
            ],
            [
                'keywords' => ['resi', 'nomor resi', 'tracking'],
                'reply' => 'Nomor resi akan tersedia setelah pembayaran diverifikasi dan paket diproses oleh admin bersama pihak ekspedisi. Jika sudah tersedia, resi dapat dilihat pada menu Pesanan.',
            ],
            [
                'keywords' => ['cod', 'bayar ditempat', 'bayar di tempat'],
                'reply' => 'Saat ini pembayaran belum mendukung COD. Pembayaran dilakukan menggunakan QRIS, lalu bukti pembayaran diunggah melalui halaman checkout.',
            ],
            [
                'keywords' => ['qris', 'bayar', 'pembayaran', 'transfer'],
                'reply' => 'Pembayaran dilakukan menggunakan QRIS pada halaman checkout. Setelah membayar sesuai total pesanan, upload bukti pembayaran agar admin dapat melakukan verifikasi.',
            ],
            [
                'keywords' => ['harga termasuk ongkir', 'sudah termasuk ongkir', 'harga plus ongkir'],
                'reply' => 'Harga produk belum termasuk ongkir. Ongkir dihitung otomatis saat checkout berdasarkan wilayah tujuan, berat produk, dan kurir yang dipilih.',
            ],
            [
                'keywords' => ['bukti pembayaran', 'bukti bayar', 'upload gagal', 'gagal upload'],
                'reply' => 'Bukti pembayaran dapat diunggah pada halaman checkout dengan format JPG atau PNG. Jika upload gagal, pastikan ukuran file tidak terlalu besar dan coba unggah ulang.',
            ],
            [
                'keywords' => ['status pesanan', 'pesanan saya', 'diproses', 'dikirim'],
                'reply' => 'Status pesanan dapat dicek melalui menu Pesanan setelah login. Admin akan memperbarui status pesanan setelah pembayaran diverifikasi dan paket diproses.',
            ],
            [
                'keywords' => ['ubah alamat', 'ganti alamat', 'alamat salah'],
                'reply' => 'Jika ingin mengubah alamat setelah checkout, segera hubungi admin sebelum pesanan diproses atau dikirim. Jika paket sudah diproses ekspedisi, perubahan alamat mungkin tidak dapat dilakukan.',
            ],
            [
                'keywords' => ['batalkan', 'batal pesanan', 'cancel', 'pembatalan'],
                'reply' => 'Pesanan dapat dibatalkan selama status masih menunggu pembayaran atau menunggu verifikasi. Jika pesanan sudah diproses admin, pembatalan perlu dikonfirmasi terlebih dahulu.',
            ],
            [
                'keywords' => ['stok', 'tersedia', 'habis'],
                'reply' => 'Stok produk dapat dilihat pada halaman detail produk. Jika stok habis, produk belum bisa dimasukkan ke keranjang sampai admin memperbarui stok.',
            ],
            [
                'keywords' => ['rekomendasi', 'varian enak', 'rasa apa', 'paling enak'],
                'reply' => 'Rekomendasi varian bergantung pada selera. Original cocok untuk rasa dasar cascara, lemon terasa segar, ginger memberi sensasi hangat, mint terasa sejuk, dan madu cocok untuk rasa lebih manis.',
            ],
            [
                'keywords' => ['tahan berapa lama', 'expired', 'kadaluarsa', 'kedaluwarsa'],
                'reply' => 'Ketahanan produk bergantung pada jenis produk dan cara penyimpanan. Cascara kering sebaiknya disimpan rapat di tempat kering, sedangkan produk siap minum mengikuti tanggal kedaluwarsa dan petunjuk penyimpanan pada kemasan.',
            ],
            [
                'keywords' => ['cara simpan', 'simpan dimana', 'penyimpanan'],
                'reply' => 'Cascara kering sebaiknya disimpan di wadah tertutup, tempat kering, dan terhindar dari sinar matahari langsung. Produk siap minum sebaiknya disimpan dingin setelah diterima atau setelah dibuka.',
            ],
            [
                'keywords' => ['aman diminum', 'setiap hari', 'tiap hari'],
                'reply' => 'Cascara dapat dikonsumsi sebagai minuman herbal, tetapi tetap disarankan dikonsumsi sewajarnya sesuai kebutuhan masing-masing.',
            ],
            [
                'keywords' => ['reseller', 'grosir', 'pesan banyak', 'partai besar'],
                'reply' => 'Untuk pemesanan banyak, reseller, atau kebutuhan event, silakan sampaikan jumlah dan kebutuhan produk melalui live chat agar admin dapat membantu mengecek ketersediaan dan informasi lebih lanjut.',
            ],
            [
                'keywords' => ['cara seduh', 'seduh', 'menyeduh', 'takaran'],
                'reply' => 'Cascara dapat diseduh seperti teh. Gunakan air panas, diamkan beberapa menit sesuai selera, lalu saring sebelum diminum. Untuk rasa dingin, seduhan dapat ditambahkan es setelah tidak terlalu panas.',
            ],
            [
                'keywords' => ['cascara kering', 'siap minum', 'beda', 'perbedaan'],
                'reply' => 'Cascara kering adalah produk seduh yang perlu diseduh terlebih dahulu, sedangkan cascara siap minum merupakan produk minuman botol yang praktis dikonsumsi langsung.',
            ],
            [
                'keywords' => ['halo', 'hai', 'assalamualaikum', 'pagi', 'siang', 'sore', 'malam'],
                'reply' => 'Halo, terima kasih sudah menghubungi Sejucara. Saya bisa membantu menjawab pertanyaan umum seputar produk, pembayaran, ongkir, resi, dan status pesanan.',
            ],
        ];

        foreach ($rules as $rule) {
            foreach ($rule['keywords'] as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $rule['reply']."\n\nJika pertanyaan kamu belum terjawab, admin akan membantu membalas saat tersedia.";
                }
            }
        }

        return 'Terima kasih sudah menghubungi Sejucara. Pesan kamu sudah diterima. Untuk pertanyaan umum, kamu bisa menanyakan ongkir, pembayaran QRIS, resi, status pesanan, stok produk, rekomendasi varian, cara simpan, atau cara seduh cascara. Jika butuh bantuan lebih lanjut, admin akan membalas saat tersedia.';
    }
}
