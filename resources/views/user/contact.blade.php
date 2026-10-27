@extends('layouts.app')

@section('content')
    <style>
        .contact-hero {
            margin-bottom: 28px;
        }

        .contact-hero h1 {
            font-size: 36px;
            margin: 0 0 8px 0;
            color: #8b3a0f;
            line-height: 1.15;
        }

        .contact-hero p {
            font-size: 15px;
            color: #6f5b4b;
            margin: 0;
            line-height: 1.7;
            max-width: 720px;
        }

        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .contact-info-section h2 {
            font-size: 28px;
            margin: 0 0 28px 0;
            color: #8b3a0f;
        }

        .contact-item {
            display: flex;
            gap: 18px;
            margin-bottom: 28px;
            padding: 16px;
            background: #fffaf2;
            border-radius: 12px;
            border-left: 4px solid #c96a31;
            transition: all 0.3s ease;
            color: inherit;
            text-decoration: none;
        }

        .contact-item:hover {
            background: #fff2de;
            transform: translateX(4px);
        }

        .contact-item-icon {
            font-size: 32px;
            min-width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-item-icon svg {
            width: 28px;
            height: 28px;
            fill: currentColor;
            color: #c96a31;
        }

        .contact-item-content h3 {
            margin: 0 0 4px 0;
            font-size: 15px;
            font-weight: 700;
            color: #3b2a1f;
            letter-spacing: 0.5px;
        }

        .contact-item-content a,
        .contact-item-content p,
        .contact-item-content span {
            margin: 0;
            color: #6f5b4b;
            text-decoration: none;
            font-size: 14px;
        }

        .contact-item-content a,
        .contact-item-content span {
            color: #c96a31;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .contact-item:hover .contact-item-content span,
        .contact-item-content a:hover {
            color: #a84f1f;
        }

        .contact-form-section {
            background: linear-gradient(135deg, #fffaf2 0%, #fff2de 100%);
            padding: 32px;
            border-radius: 16px;
            border: 1px solid #e9cfaa;
        }

        .contact-form-section h2 {
            font-size: 28px;
            margin: 0 0 12px 0;
            color: #8b3a0f;
        }

        .contact-form-section > p {
            color: #6f5b4b;
            margin: 0 0 24px 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #3b2a1f;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e9cfaa;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #3b2a1f;
            background: #ffffff;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #a8937f;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #c96a31;
            background: #fffef9;
            box-shadow: 0 0 0 3px rgba(201, 106, 49, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .btn-whatsapp {
            width: 100%;
            background: linear-gradient(135deg, #c96a31 0%, #a84f1f 100%);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
            display: inline-block;
            box-sizing: border-box;
            letter-spacing: 0.5px;
        }

        .btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(201, 106, 49, 0.3);
        }

        .btn-whatsapp:active {
            transform: translateY(0);
        }

        .info-badge {
            display: inline-block;
            background: #c96a31;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 8px;
            letter-spacing: 0.5px;
        }

        .additional-info-section {
            margin-top: 48px;
        }

        .additional-info-title {
            text-align: center;
            margin-bottom: 32px;
        }

        .additional-info-title h2 {
            font-size: 28px;
            margin: 0 0 8px 0;
            color: #8b3a0f;
        }

        .additional-info-title p {
            margin: 0;
            color: #6f5b4b;
            font-size: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .info-card {
            background: linear-gradient(135deg, #fffaf2 0%, #fff4e5 100%);
            border: 1px solid #e9cfaa;
            border-radius: 14px;
            padding: 28px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #c96a31 0%, #d07a3e 100%);
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(90, 44, 20, 0.12);
        }

        .info-card-icon {
            width: 54px;
            height: 54px;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: rgba(201, 106, 49, 0.1);
            color: #c96a31;
        }

        .info-card-icon svg {
            width: 26px;
            height: 26px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .info-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #3b2a1f;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }

        .info-card-content {
            font-size: 14px;
            color: #6f5b4b;
            margin: 0;
            line-height: 1.65;
        }

        .info-card-content strong {
            color: inherit;
            font-weight: 400;
        }

        @media (max-width: 768px) {
            .contact-hero h1 {
                font-size: 28px;
            }

            .contact-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .contact-form-section {
                padding: 24px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .info-card {
                padding: 20px;
            }
        }
    </style>

    @php
        $whatsappUrl = 'https://wa.me/6283181197907?text='.rawurlencode('Halo Sejucara, saya mau pesan produk Sejucara. Bisa dibantu informasinya?');
        $tiktokUrl = 'https://www.tiktok.com/@sejucara.id?is_from_webapp=1&sender_device=pc';
    @endphp

    <!-- Hero Section -->
    <div class="contact-hero">
        <h1>Hubungi Kami</h1>
        <p>Kami siap membantu Anda melalui kanal online yang responsif dan mudah diakses. Pilih media yang paling nyaman untuk Anda.</p>
    </div>

    <!-- Main Contact Section -->
    <section class="contact-container">
        <!-- Bagian Kiri: Informasi Kontak -->
        <div class="contact-info-section">
            <h2>Informasi Kontak</h2>

            <!-- Email -->
            <a class="contact-item" href="mailto:sejucara26@gmail.com" aria-label="Kirim email ke Sejucara">
                <div class="contact-item-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="m2 6 10 7 10-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="contact-item-content">
                    <h3>Email</h3>
                    <span>sejucara26@gmail.com</span>
                </div>
            </a>

            <!-- Telepon -->
            <a class="contact-item" href="{{ $whatsappUrl }}" target="_blank" rel="noopener" aria-label="Hubungi Sejucara melalui WhatsApp">
                <div class="contact-item-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="contact-item-content">
                    <h3>Telepon</h3>
                    <span>0831-8119-7907</span>
                </div>
            </a>

            <!-- Instagram -->
            <a class="contact-item" href="https://instagram.com/sejucara" target="_blank" rel="noopener" aria-label="Buka Instagram Sejucara">
                <div class="contact-item-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="12" r="5" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="18.5" cy="5.5" r="1.5" fill="currentColor"/>
                    </svg>
                </div>
                <div class="contact-item-content">
                    <h3>Instagram</h3>
                    <span>@sejucara</span>
                </div>
            </a>

            <!-- Facebook -->
            <a class="contact-item" href="https://facebook.com/sejucara" target="_blank" rel="noopener" aria-label="Buka Facebook Sejucara">
                <div class="contact-item-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 8h2V5h-2.4C10.9 5 10 6.7 10 8.6V11H8v3h2v7h3v-7h2.4l.6-3h-3V8.8c0-.6.2-.8 1-.8z"/>
                    </svg>
                </div>
                <div class="contact-item-content">
                    <h3>Facebook</h3>
                    <span>Sejucara</span>
                </div>
            </a>

            <!-- TikTok -->
            <a class="contact-item" href="{{ $tiktokUrl }}" target="_blank" rel="noopener" aria-label="Buka TikTok Sejucara">
                <div class="contact-item-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 3c.4 2.7 1.8 4.3 4 4.7V11c-1.6-.1-3-.6-4-1.5v5.7c0 3.1-2.1 5.3-5.1 5.3A5 5 0 0 1 5 15.4c0-3.1 2.3-5.2 5.5-5.1v3.3c-1.3-.1-2.2.7-2.2 1.8 0 1 .7 1.8 1.7 1.8 1.1 0 1.8-.7 1.8-2.1V3h3.2z"/>
                    </svg>
                </div>
                <div class="contact-item-content">
                    <h3>TikTok</h3>
                    <span>@sejucara.id</span>
                </div>
            </a>
        </div>

        <!-- Bagian Kanan: Form Kirim Pesan -->
        <div class="contact-form-section">
            <h2>Kirim Pesan Cepat</h2>
            <p>Isi formulir di bawah ini dan kami akan merespons secepatnya. Atau klik tombol WhatsApp untuk chat langsung dengan pesan otomatis.</p>

            <form id="quick-contact-form" style="display: flex; flex-direction: column; gap: 0;">
                <div class="form-group">
                    <label for="contact-name">Nama Lengkap</label>
                    <input id="contact-name" type="text" placeholder="Siapa nama Anda?">
                </div>
                
                <div class="form-group">
                    <label for="contact-email">Alamat Email</label>
                    <input id="contact-email" type="email" placeholder="email@example.com">
                </div>
                
                <div class="form-group">
                    <label for="contact-message">Pesan Anda</label>
                    <textarea id="contact-message" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..."></textarea>
                </div>
                
                <button class="btn-whatsapp" type="submit">Kirim via WhatsApp</button>
            </form>
        </div>
    </section>

    <!-- Additional Info -->
    <section class="additional-info-section">
        <div class="additional-info-title">
            <h2>Informasi Bisnis</h2>
            <p>Ketahui lebih lanjut tentang layanan dan operasional kami</p>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <span class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21s6-4.5 6-11a6 6 0 0 0-12 0c0 6.5 6 11 6 11z"/>
                        <circle cx="12" cy="10" r="2.5"/>
                    </svg>
                </span>
                <h3 class="info-card-title">Lokasi Produksi</h3>
                <p class="info-card-content">Daerah Kabupaten Bandung<br><strong>Jawa Barat</strong></p>
            </div>

            <div class="info-card">
                <span class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </span>
                <h3 class="info-card-title">Jam Layanan</h3>
                <p class="info-card-content">Senin - Sabtu<br><strong>08:00 - 20:00 WIB</strong></p>
            </div>

            <div class="info-card">
                <span class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 8v5l3 2"/>
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M9 3h6"/>
                    </svg>
                </span>
                <h3 class="info-card-title">Respons Layanan</h3>
                <p class="info-card-content">Maksimal <strong>1x24 jam</strong><br>pada hari kerja</p>
            </div>
        </div>
    </section>

    <script>
        (() => {
            const form = document.getElementById('quick-contact-form');
            const name = document.getElementById('contact-name');
            const email = document.getElementById('contact-email');
            const message = document.getElementById('contact-message');

            form?.addEventListener('submit', (event) => {
                event.preventDefault();

                const text = [
                    'Halo Sejucara, saya ingin bertanya.',
                    '',
                    `Nama: ${name.value.trim() || '-'}`,
                    `Email: ${email.value.trim() || '-'}`,
                    `Pesan: ${message.value.trim() || '-'}`,
                ].join('\n');

                window.open(`https://wa.me/6283181197907?text=${encodeURIComponent(text)}`, '_blank', 'noopener');
            });
        })();
    </script>
@endsection
