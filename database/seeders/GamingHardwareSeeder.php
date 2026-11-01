<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GamingHardware;
use App\Models\User;
use Illuminate\Database\Seeder;

class GamingHardwareSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gamepl.local')->first();
        $webadmin = User::where('email', 'webadmin@gamepl.local')->first();
        $gelistirici = User::where('email', 'gelistirici@gamepl.local')->first();

        $hardwareList = [
            // 1. EN İYİ OYUNCU MONİTÖRLERİ (Monitors)
            [
                'name' => 'ASUS ROG Swift OLED PG32UCDM (32" 4K 240Hz QD-OLED)',
                'slug' => 'asus-rog-swift-oled-pg32ucdm-4k-240hz',
                'category' => 'monitor',
                'manufacturer' => 'ASUS Republic of Gamers (Samsung Display QD-OLED)',
                'release_year' => 2024,
                'price_tier' => 'Amiral Gemisi (Flagship 4K OLED)',
                'badge_label' => '🏆 En İyi 4K HDR & E-Spor Monitörü',
                'rating' => 9.8,
                'key_specs' => [
                    'Panel Teknolojisi' => '3. Nesil QD-OLED (Quantum Dot OLED)',
                    'Çözünürlük & Yenileme' => '3840 x 2160 (4K UHD) @ 240Hz',
                    'Tepki Süresi' => '0.03 ms (GtG) — Anlık Piksel Geçişi',
                    'HDR & Parlaklık' => 'Dolby Vision & VESA DisplayHDR True Black 400 (1000 nit tepe)',
                    'Senkronizasyon & Portlar' => 'G-SYNC Compatible, FreeSync Premium Pro, HDMI 2.1, DP 1.4, USB-C (90W)',
                ],
                'short_intro' => 'Hem Cyberpunk 2077 ve Alan Wake 2 gibi Path Tracing başyapıtlarını sonsuz kontrastlı 4K HDR kalitesinde oynamak hem de rekabetçi oyunlarda 240Hz / 0.03ms sıfır bulanıklık elde etmek isteyenler için endüstri referansı monitör.',
                'technical_deep_dive' => '3. nesil Kuantum Nokta (QD-OLED) alt piksel dizilimi sayesinde metin netliğini önemli ölçüde artıran PG32UCDM, özel grafen film soğutucu bloğu ile OLED yanma (burn-in) riskini pasif ve sessiz biçimde minimize eder. 0.03 milisaniyelik piksel tepki süresi, geleneksel 360Hz IPS panellerden bile daha net bir hareketli görüntü (Motion Clarity) üretir.',
                'ai_and_game_synergy' => 'NVIDIA DLSS 3.5 Frame Generation ile 4K\'da üretilen 120-240 FPS kare hızlarını yırtılma olmadan gerçek siyahlarla gösterir; ROG AI Assistant (Dynamic Shadow Boost & AI Crosshair) karanlık sahneleri parlak bölgeleri patlatmadan optimize eder.',
                'pros' => 'Kusursuz gerçek siyahlar, %99 DCI-P3 renk hacmi, 0.03ms sıfır hayaletlenme, Dolby Vision desteği, dahili KVM anahtarı.',
                'cons' => 'Çok aydınlık odalarda QD-OLED kaplaması hafif morumsu yansıma yapabilir.',
                'recommended_for' => '4K AAA Hikâyeli/Ray Tracing Oyunlar + Yüksek FPS Rekabetçi Oyunlar',
                'image_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=1200&q=80',
                'showcase_video_url' => 'https://www.youtube.com/watch?v=PbVKGoJh4OI',
                'is_featured' => true,
                'status' => 'approved',
                'submitted_by' => $admin?->id,
                'game_links' => [
                    'cyberpunk-2077-phantom-liberty' => 'Night City neonlarını 1000 nit HDR tepe parlaklığı ve gerçek OLED siyahlarında kusursuz gösterir.',
                    'alan-wake-2' => 'Karanlık Boyut sahnelerinde IPS parlaması (IPS Glow) olmadan tam atmosferik derinlik sağlar.',
                    'baldurs-gate-3' => '4K çözünürlükte taktiksel kamera uzaklaştırmalarında bile keskin detay ve Dolby Vision renk doğruluğu sunar.',
                ],
            ],
            [
                'name' => 'BenQ ZOWIE XL2586X (24.1" 540Hz Fast-TN DyAc 2)',
                'slug' => 'benq-zowie-xl2586x-540hz-esports',
                'category' => 'monitor',
                'manufacturer' => 'BenQ ZOWIE',
                'release_year' => 2024,
                'price_tier' => 'Profesyonel E-Spor Standardı',
                'badge_label' => '⚡ En İyi Rekabetçi E-Spor (540Hz) Monitörü',
                'rating' => 9.5,
                'key_specs' => [
                    'Panel Teknolojisi' => 'Yeni Nesil Fast-TN (Canlı Renk Film Katmanlı)',
                    'Çözünürlük & Yenileme' => '1920 x 1080 (Full HD) @ 540Hz',
                    'Bulanıklık Azaltma' => 'DyAc 2 (Çift Arka Aydınlatmalı Dinamik Stroboskopik Teknoloji)',
                    'Giriş Gecikmesi' => '< 1.8 ms Tam Sistem Gecikmesi',
                    'Özel Donanım' => 'S Switch Profil Kontrolcüsü, Endüstriyel Rulmanlı Yükseklik Standı',
                ],
                'short_intro' => 'Counter-Strike 2, Valorant, StarCraft II ve DOOM Eternal gibi saniyenin binde birinin maçı belirlediği arenalar için tasarlanmış, 540Hz yenileme hızına ve çift stroboskopik DyAc 2 teknolojisine sahip safkan e-spor monitörü.',
                'technical_deep_dive' => 'Geleneksel tek arka aydınlatma yerine çift arka aydınlatma (Dual Backlight) kullanan DyAc 2 teknolojisi, hızlı fare çevirmelerinde (flick shots) ve silah geri tepmelerinde (recoil) gözün algıladığı hareket bulanıklığını neredeyse sıfıra indirir.',
                'ai_and_game_synergy' => 'Auto Game Mode yazılımı çalışan oyunu algılayarak renk ve gama profillerini otomatik değiştirir; 540Hz akış hızı insan-makine reaksiyon süresindeki darboğazı ortadan kaldırır.',
                'pros' => 'Dünyanın en net hareketli nişan takibi (DyAc 2), turnuva dayanıklılığı, önceki TN panellere göre %35 daha geniş renk gamı.',
                'cons' => '1080p çözünürlük ve TN görüş açıları sinematik AAA oyunlardan ziyade saf rekabetçi odak taşır.',
                'recommended_for' => 'Profesyonel E-Sporcular, FPS (CS2/Valorant/DOOM) ve Yüksek APM RTS Oyuncuları',
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200&q=80',
                'showcase_video_url' => 'https://www.youtube.com/watch?v=_UuktemkCFI',
                'is_featured' => true,
                'status' => 'approved',
                'submitted_by' => $webadmin?->id,
                'game_links' => [
                    'doom-eternal' => 'id Tech 7 motorunun 500+ FPS çıktısını kare atlamadan 540Hz hızında çizer.',
                    'starcraft-2-deepmind-alphastar' => 'Hızlı ekran kaydırmalarda (Camera Panning) birimlerin okunabilirliğini korur.',
                ],
            ],
            [
                'name' => 'Samsung Odyssey OLED G9 (49" 5120x1440 240Hz 32:9 Ultra-Wide)',
                'slug' => 'samsung-odyssey-oled-g9-49-ultrawide',
                'category' => 'monitor',
                'manufacturer' => 'Samsung Electronics',
                'release_year' => 2023,
                'price_tier' => 'Ultra-Geniş Simülasyon & Atmosfer',
                'badge_label' => '🌌 En İyi Ultra-Geniş (32:9) Simülasyon Monitörü',
                'rating' => 9.4,
                'key_specs' => [
                    'Panel Teknolojisi' => '1800R Kavisli QD-OLED',
                    'Çözünürlük & Oran' => '5120 x 1440 (Dual QHD — Yan Yana İki 27" 2K Ekran) @ 240Hz',
                    'İşlemci' => 'Neo Quantum Processor Pro (Yapay Zekâ Görüntü İyileştirme)',
                    'Tepki Süresi' => '0.03 ms (GtG)',
                    'Ek Özellikler' => 'Smart TV Hub, GeForce NOW / Xbox Cloud Gaming Dahili Uygulamalar',
                ],
                'short_intro' => 'Yan yana iki adet 27 inçlik QHD ekranı ortada çerçeve olmadan tek bir kavisli QD-OLED yüzeyde birleştiren, oyuncunun çevresel görüş alanını (Peripheral Vision) tamamen dolduran dev ekran.',
                'technical_deep_dive' => 'Neo Quantum Processor Pro çipi, sahnedeki her kareyi derin öğrenme algoritmalarıyla analiz ederek kontrastı ve parlaklığı piksel bazında optimize eder.',
                'ai_and_game_synergy' => '32:9 en-boy oranı, açık dünya ve simülasyon oyunlarında kamera açısını (FOV) genişleterek düşman hareketlerini ve doğa manzaralarını sinemaskop ötesi bir perspektifle sunar.',
                'pros' => 'Eşsiz 32:9 sarmalayıcılık, 240Hz QD-OLED akıcılığı, şık metal gövde.',
                'cons' => 'Geniş masa derinliği ve güçlü bir ekran kartı gerektirir.',
                'recommended_for' => 'Açık Dünya RPG, Yarış/Uçuş Simülasyonu ve Geniş Görüş Açılı Taktik Oyunlar',
                'image_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200&q=80',
                'showcase_video_url' => 'https://www.youtube.com/watch?v=eaW0tYpxyp0',
                'is_featured' => false,
                'status' => 'approved',
                'submitted_by' => $webadmin?->id,
                'game_links' => [
                    'red-dead-redemption-2' => 'Vahşi Batı ufuk çizgisini ve fırtına bulutlarını 32:9 panoramik açıyla sunar.',
                    'death-stranding-directors-cut' => 'Director\'s Cut sürümünün ultra-geniş sinematik modunu tam ekran doldurur.',
                ],
            ],

            // 2. EN İYİ OYUN KONSOLLARI VE EL BİLGİSAYARLARI (Consoles & Handhelds)
            [
                'name' => 'PlayStation 5 Pro (PS5 Pro — PSSR AI Upscaling)',
                'slug' => 'sony-playstation-5-pro-pssr',
                'category' => 'console',
                'manufacturer' => 'Sony Interactive Entertainment',
                'release_year' => 2024,
                'price_tier' => 'Amiral Gemisi Ev Konsolu',
                'badge_label' => '👑 En Güçlü AI Destekli Oyun Konsolu',
                'rating' => 9.6,
                'key_specs' => [
                    'GPU Mimarisi' => 'Özel AMD RDNA (%67 Daha Fazla İşlem Birimi, 16.7 TFLOPS + Özel RT Çekirdekleri)',
                    'Yapay Zekâ Hızlandırıcı' => 'PSSR (PlayStation Spectral Super Resolution — 300 TOPS 8-bit AI Donanımı)',
                    'Bellek & Depolama' => '16 GB GDDR6 (576 GB/s) + 2 GB DDR5 Sistem RAM, 2 TB Özel NVMe SSD',
                    'Işın İzleme (Ray Tracing)' => 'Standart PS5\'e Göre 2x - 3x Daha Hızlı Donanımsal Işın Kesişimi',
                    'Kontrolcü & Ses' => 'DualSense Haptic Feedback + Adaptive Triggers, Tempest 3D AudioTech',
                ],
                'short_intro' => 'Konsol oyuncularını "30 FPS Kalite Modu mu, yoksa bulanık 60 FPS Performans Modu mu?" ikileminden kurtarmak için özel makine öğrenimi donanımı (PSSR) ve gelişmiş Ray Tracing mimarisiyle tasarlanmış en güçlü ev konsolu.',
                'technical_deep_dive' => 'Mark Cerny liderliğinde geliştirilen PS5 Pro mimarisi; geleneksel FSR ölçekleme yerine Sony\'nin özel evrişimli sinir ağı (CNN) tabanlı PSSR (PlayStation Spectral Super Resolution) donanımını kullanır. PSSR, 1080p/1440p dahili çözünürlüğü milisaniyeler içinde keskin 4K 60 FPS görüntüye dönüştürür.',
                'ai_and_game_synergy' => 'Konsol tarihinde ilk kez özel bir derin öğrenme hızlandırıcı bloğu (300 TOPS) doğrudan grafik boru hattına entegre edilerek gerçek zamanlı nöral görüntü yeniden inşası (Neural Upscaling) sağlanmıştır.',
                'pros' => 'PSSR ile 4K netliğinde 60 FPS akıcılık, 2 TB dahili hızlı SSD, Wi-Fi 7 desteği ve eşsiz DualSense dokunsal geri bildirimi.',
                'cons' => 'Disk sürücüsü ve dikey stant ayrı satılmaktadır.',
                'recommended_for' => '4K OLED TV Sahipleri, Sinematik Sony Özel Oyunları ve GTA VI Hazırlığı',
                'image_url' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&w=1200&q=80',
                'showcase_video_url' => 'https://www.youtube.com/watch?v=qPNiIeKMHyg',
                'is_featured' => true,
                'status' => 'approved',
                'submitted_by' => $admin?->id,
                'game_links' => [
                    'the-last-of-us-part-2' => 'PS5 Pro modunda PSSR ile 4K kalitesinde sabit 60 FPS ve DualSense yay gerilimi sunar.',
                    'alan-wake-2' => 'Konsolda gerçek zamanlı Ray Tracing yansımalarını aktif hale getirir.',
                    'elden-ring' => 'Aradaki Topraklar\'da kare hızını 60 FPS tavanına çok daha yakın tutar.',
                ],
            ],
            [
                'name' => 'Valve Steam Deck OLED (1TB HDR Taşınabilir PC Konsolu)',
                'slug' => 'valve-steam-deck-oled-handheld',
                'category' => 'console',
                'manufacturer' => 'Valve Corporation',
                'release_year' => 2023,
                'price_tier' => 'En İyi Taşınabilir PC / Konsol Hibriti',
                'badge_label' => '🚀 En İyi El Konsolu & Taşınabilir Oyun Bilgisayarı',
                'rating' => 9.7,
                'key_specs' => [
                    'İşlemci (APU)' => '6nm AMD "Sephiroth" Özel APU (Zen 2 4C/8T + RDNA 2 8 CU)',
                    'Ekran' => '7.4 inç 1280x800 HDR OLED @ 90Hz (1000 nit HDR, <0.1ms tepki)',
                    'Bellek & Pil' => '16 GB LPDDR5 (6400 MT/s) + 50Wh Uzun Ömürlü Batarya',
                    'İşletim Sistemi' => 'SteamOS 3.0 (Arch Linux + Proton Uyumluluk Katmanı)',
                    'Giriş Kontrolleri' => 'Çift Kapasitif Trackpad, Jiroskop, 4 Arka Makro Tuşu',
                ],
                'short_intro' => 'Devasa Steam kütüphanenizi, bağımsız başyapıtları ve CRPG klasikleriyle birlikte 90Hz HDR OLED ekranda avucunuzun içine sığdıran, Proton mühendisliğiyle Linux üzerinde Windows oyunlarını sorunsuz çalıştıran taşınabilir devrim.',
                'technical_deep_dive' => 'Valve\'in 6nm sürecine taşıdığı yeni APU ve 50Wh batarya, cihazı hem daha serin hem daha hafif yaparken pil ömrünü %30-%50 oranında artırdı. Proton uyumluluk katmanı DirectX 11/12 çağrılarını gerçek zamanlı olarak Vulkan API\'sine çevirir ve önceden derlenmiş gölgelendirici önbelleği (Shader Pre-Caching) ile takılmaları önler.',
                'ai_and_game_synergy' => 'Çift dokunmatik yüzey (Trackpad) sayesinde normalde sadece fareyle oynanabilen strateji ve izometrik RPG oyunlarını bile koltukta oynanabilir kılar.',
                'pros' => 'Muhteşem 90Hz HDR OLED ekran, kusursuz uyku/uyanma (Quick Suspend) özelliği, açık ekosistem ve tam onarılabilirlik.',
                'cons' => 'Çok ağır 2024-2025 Unreal Engine 5 oyunlarında 30 FPS sınırına inebilir.',
                'recommended_for' => 'CRPG, Indie, Roguelike, Emülasyon ve Seyahat Halindeki PC Oyuncuları',
                'image_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200&q=80',
                'showcase_video_url' => 'https://www.youtube.com/watch?v=1T22wNvoNiU',
                'is_featured' => true,
                'status' => 'approved',
                'submitted_by' => $gelistirici?->id,
                'game_links' => [
                    'disco-elysium-the-final-cut' => 'OLED ekranda yağlıboya sanat tasarımı ve seslendirilmiş diyaloglarla kusursuz bir kitap gibi okunur.',
                    'baldurs-gate-3' => 'Sıra tabanlı taktik yapısı sayesinde taşınabilir modda yüzlerce saatlik macera sunar.',
                ],
            ],
            [
                'name' => 'Microsoft Xbox Series X (Velocity Architecture & Game Pass Hub)',
                'slug' => 'microsoft-xbox-series-x',
                'category' => 'console',
                'manufacturer' => 'Microsoft',
                'release_year' => 2020,
                'price_tier' => 'Yüksek Performanslı Ev Konsolu',
                'badge_label' => '💎 En İyi Geriye Uyumluluk & Quick Resume Konsolu',
                'rating' => 9.3,
                'key_specs' => [
                    'İşlemci & GPU' => 'Özel Zen 2 (8 Çekirdek 3.8 GHz) + 12.15 TFLOPS RDNA 2 GPU (52 CU)',
                    'Depolama Mimarisi' => 'Xbox Velocity Architecture (DirectStorage + DonanımsalBDecompression)',
                    'Çoklu Görev' => 'Quick Resume (Aynı Anda 5+ Oyunu Saniyeler İçinde Kaldığı Yerden Açma)',
                    'Ses & Görüntü' => 'Dolby Vision Gaming, Dolby Atmos, 4K 120Hz VRR (Değişken Yenileme Hızı)',
                ],
                'short_intro' => 'Fısıltı sessizliğindeki dikey kule tasarımı, aynı anda 5 farklı oyunun RAM durumunu SSD\'ye dondurup saniyeler içinde geçiş yapan Quick Resume teknolojisi ve 4 nesillik geriye uyumluluk (Auto HDR / FPS Boost) lideri.',
                'technical_deep_dive' => 'Xbox Velocity Architecture, PC dünyasına da DirectStorage olarak öncülük eden özel bir donanım sıkıştırma açıcı (Hardware Decompression Block) barındırır; böylece CPU yükü olmadan saniyede 4.8 GB sıkıştırılmış oyun dokusu doğrudan GPU belleğine aktarılır.',
                'ai_and_game_synergy' => 'Makine öğrenimi tabanlı Auto HDR algoritması, 20 yıl önce çıkmış SDR oyunların bile renk ve parlaklık dinamik aralığını analiz ederek modern OLED TV\'ler için 1000 nit HDR\'a yükseltir.',
                'pros' => 'Rakipsiz Quick Resume konforu, VRR (FreeSync) desteği, aşırı sessiz soğutma ve FPS Boost ile eski klasikleri 60/120 FPS oynatma.',
                'cons' => 'Kontrolcüde jiroskop ve adaptif tetik bulunmaması.',
                'recommended_for' => 'Game Pass Kütüphanesi, Geriye Uyumlu Klasikler ve Çoklu Oyun Arasında Hızlı Geçiş',
                'image_url' => 'https://images.unsplash.com/photo-1605901309584-818e25960a8f?auto=format&fit=crop&w=1200&q=80',
                'showcase_video_url' => 'https://www.youtube.com/watch?v=N1Z9f3a3G20',
                'is_featured' => false,
                'status' => 'approved',
                'submitted_by' => $webadmin?->id,
                'game_links' => [
                    'left-4-dead-2' => 'Geriye uyumluluk ve yüksek kare hızıyla kooperatif klasiği kesintisiz çalıştırır.',
                    'middle-earth-shadow-of-mordor' => 'Quick Resume ile Nemesis karşılaşmalarına 4 saniyede geri dönmenizi sağlar.',
                ],
            ],

            // 3. GRAFİK KARTLARI & NÖRAL AI HIZLANDIRICILAR (GPU & AI)
            [
                'name' => 'NVIDIA GeForce RTX 4090 (Ada Lovelace — 512 Tensor Çekirdeği)',
                'slug' => 'nvidia-geforce-rtx-4090-ada-lovelace',
                'category' => 'gpu_ai',
                'manufacturer' => 'NVIDIA Corporation',
                'release_year' => 2022,
                'price_tier' => 'Uç Düzey İş İstasyonu & 4K Path Tracing GPU',
                'badge_label' => '🧠 En Güçlü Nöral Rendering & Oyun AI Kartı',
                'rating' => 9.9,
                'key_specs' => [
                    'Mimari & Çekirdek' => 'TSMC 4N Ada Lovelace — 16.384 CUDA Çekirdeği',
                    'Yapay Zekâ Gücü' => '512 adet 4. Nesil Tensor Çekirdeği (1321 AI TOPS — FP8)',
                    'Işın İzleme' => '128 adet 3. Nesil RT Çekirdeği (Shader Execution Reordering - SER)',
                    'Video Belleği (VRAM)' => '24 GB GDDR6X (384-bit, 1008 GB/s Bant Genişliği)',
                ],
                'short_intro' => 'Hem gerçek zamanlı tam Path Tracing aydınlatmayı 4K çözünürlükte mümkün kılan grafik canavarı hem de yerel dil modellerini (LLM / NVIDIA ACE) ve DLSS 3.5 Ray Reconstruction sinir ağlarını saniyede trilyonlarca işlemle koşturan AI motoru.',
                'technical_deep_dive' => 'Ada Lovelace mimarisindeki "Shader Execution Reordering (SER)" teknolojisi, sahnede farklı yönlere dağılan dağınık ışın izleme (Ray Tracing) iş parçacıklarını çalışma zamanında yeniden gruplayarak GPU verimliliğini 2 katına çıkarır. Optik Akış Hızlandırıcısı (Optical Flow Accelerator) ise ardışık iki kare arasındaki piksel vektörlerini analiz ederek tamamen yapay zekâ tarafından sentezlenen ara kareler (Frame Generation) üretir.',
                'ai_and_game_synergy' => 'Oyun teknolojileri ve yapay zekâ birleşiminin fiziksel kalbidir: DLSS 3.5 Ray Reconstruction, yerel NPC dil modelleri ve yüksek VRAM gerektiren 4K doku paketlerini aynı anda sırtlar.',
                'pros' => 'Rakipsiz 4K Path Tracing performansı, 24 GB VRAM geleceğe yatırım, DLSS 3.5 ekosistemi.',
                'cons' => 'Yüksek güç tüketimi (450W TDP) ve fiziksel kasa boyutu.',
                'recommended_for' => '4K 120Hz+ Path Tracing Tutkunları ve Oyun Geliştiricileri / AI Araştırmacıları',
                'image_url' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&w=1200&q=80',
                'showcase_video_url' => 'https://www.youtube.com/watch?v=PbVKGoJh4OI',
                'is_featured' => true,
                'status' => 'approved',
                'submitted_by' => $gelistirici?->id,
                'game_links' => [
                    'cyberpunk-2077-phantom-liberty' => 'Ray Tracing: Overdrive (Tam Path Tracing) modunu 4K 100+ FPS hızında çalıştırabilen referans donanım.',
                    'alan-wake-2' => 'Northlight motorunun Mesh Shader ve Path Traced dolaylı aydınlatmasını maksimum detayda işler.',
                ],
            ],

            // 4. SANAL GERÇEKLİK & DÜŞÜK GECİKMELİ EKİPMANLAR (VR & Peripherals)
            [
                'name' => 'Valve Index VR & Meta Quest 3 (Pancake Lens & Parmak İzleme)',
                'slug' => 'valve-index-and-meta-quest-3-vr',
                'category' => 'vr_headset',
                'manufacturer' => 'Valve & Meta Reality Labs',
                'release_year' => 2023,
                'price_tier' => 'Sanal & Karma Gerçeklik Referansı',
                'badge_label' => '🥽 En İyi Sanal Gerçeklik (VR) Deneyim Ekipmanı',
                'rating' => 9.5,
                'key_specs' => [
                    'Optik & Ekran' => 'Pancake Optik Lensler / Çift 2064x2208 LCD @ 120Hz-144Hz',
                    'Kontrolcü Teknolojisi' => 'Index Knuckles (87 Sensörlü Bireysel 5 Parmak Kavrama İzleme)',
                    'Ses Mimarisi' => 'Kulağa Değmeyen Yakın Alan (Off-Ear BMR) 3B Uzamsal Hoparlörler',
                    'Takip Sistemi' => 'Sub-Milimetrik Lazer / Inside-Out Bilgisayarlı Görü (Computer Vision) Takibi',
                ],
                'short_intro' => 'Half-Life: Alyx gibi fiziksel etkileşim başyapıtlarında elinizdeki şişeyi gerçekten parmaklarınızla sıkarak kırmanızı ve sesin hangi koridor köşesinden geldiğini milimetrik hissetmenizi sağlayan VR donanım ikilisi.',
                'technical_deep_dive' => 'Bilgisayarlı görü (Computer Vision SLAM) kameraları ve atalet ölçüm birimleri (IMU), oyuncunun baş ve el hareketlerini milisaniyenin altında gecikmeyle sanal uzaya aktarır.',
                'ai_and_game_synergy' => 'Yapay zekâ destekli el/parmak iskelet tahmini ve uzamsal ses ışın izleme (Steam Audio) ile beynin sanal dünyayı gerçek kabul etmesini ("Presence") sağlar.',
                'pros' => '5 parmak bağımsız kavrama hissi, uçtan uca net Pancake optikler, kablosuz PCVR (Wi-Fi 6E) özgürlüğü.',
                'cons' => 'Uzun seanslar için ergonomik kafa bandı eklentisi önerilir.',
                'recommended_for' => 'Half-Life: Alyx, Simülasyon Tutkunları ve Mekânsal Oyun Deneyimi',
                'image_url' => 'https://images.unsplash.com/photo-1592478411213-6153e4ebc07d?auto=format&fit=crop&w=1200&q=80',
                'showcase_video_url' => 'https://www.youtube.com/watch?v=O2W0N3uKXmo',
                'is_featured' => false,
                'status' => 'approved',
                'submitted_by' => $gelistirici?->id,
                'game_links' => [
                    'half-life-alyx' => 'Oyun bu donanımın parmak izleme ve 3B ses yeteneklerini sergilemek üzere sıfırdan tasarlanmıştır.',
                ],
            ],
            [
                'name' => 'Wooting 80HE & Sony DualSense Edge (Hall-Effect & Haptic Kontrol)',
                'slug' => 'wooting-80he-dualsense-edge-peripherals',
                'category' => 'peripherals',
                'manufacturer' => 'Wooting & Sony Interactive Entertainment',
                'release_year' => 2024,
                'price_tier' => 'Profesyonel Kontrol & Giriş Ekipmanı',
                'badge_label' => '⌨️ En İyi Rapid Trigger Klavye & Haptic Kontrolcü',
                'rating' => 9.6,
                'key_specs' => [
                    'Anahtar Teknolojisi' => 'Lekker L60 Hall-Effect Manyetik Sensörlü Analog Anahtarlar',
                    'Tepki Özelliği' => 'Rapid Trigger (0.1mm Hassasiyetle Parmağı Kaldırdığınız Anda Sıfırlama)',
                    'Yoklama Hızı (Polling)' => 'Gerçek 8000Hz (0.125ms USB Raporlama Hızı)',
                    'Kontrolcü Özelliği' => 'Değiştirilebilir Analog Modüller, Kilitlenebilir Tetik Mesafesi, Çift Aktüatörlü Haptic',
                ],
                'short_intro' => 'Mekanik anahtarların fiziksel temas gecikmesini manyetik alan (Hall Effect) sensörleriyle ortadan kaldıran Wooting 80HE klavye ve ok kirişinin gerginliğini parmak ucunuzda hissettiren DualSense Edge kontrolcü.',
                'technical_deep_dive' => 'Geleneksel mekanik klavyelerde tuşun tekrar basılabilmesi için sabit bir sıfırlama noktasına (Reset Point) kadar yukarı çıkması gerekir. Wooting\'in manyetik Hall-Effect sensörleri ise tuşun konumunu 0.1mm hassasiyetle sürekli ölçer; parmağınızı 0.1 milimetre bile yukarı kaldırdığınız anda tuş sıfırlanır (Rapid Trigger).',
                'ai_and_game_synergy' => 'Elden Ring veya DOOM Eternal gibi kare-hassasiyetli (frame-exact) yapay zekâ saldırılarından kaçarken insan girdisi ile oyun motoru arasındaki donanımsal gecikmeyi sıfıra yaklaştırır.',
                'pros' => '0.1mm Rapid Trigger ile anlık durma/hareket (Counter-Strafing), analog yürüme hızı kontrolü, 8000Hz yoklama.',
                'cons' => 'Yüksek talep nedeniyle stok bulmak zaman alabilir.',
                'recommended_for' => 'Rekabetçi FPS, Hızlı Aksiyon (Souls-like / DOOM) ve Simülasyon Oyuncuları',
                'image_url' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=1200&q=80',
                'showcase_video_url' => 'https://www.youtube.com/watch?v=E3Huy2cdih0',
                'is_featured' => false,
                'status' => 'approved',
                'submitted_by' => $admin?->id,
                'game_links' => [
                    'elden-ring' => 'Arka pedallar ve kısa tetik kilidiyle yuvarlanma/saldırı tepki süresini hızlandırır.',
                    'doom-eternal' => 'Rapid Trigger sayesinde silah değiştirme ve hareket kombolarını milisaniyelik hızda yürütür.',
                ],
            ],
        ];

        $gamesBySlug = Game::all()->keyBy('slug');

        foreach ($hardwareList as $hwData) {
            $gameLinks = $hwData['game_links'] ?? [];
            unset($hwData['game_links']);

            $hw = GamingHardware::updateOrCreate(['slug' => $hwData['slug']], $hwData);

            if ($gameLinks !== []) {
                $syncData = [];
                foreach ($gameLinks as $gameSlug => $note) {
                    if (isset($gamesBySlug[$gameSlug])) {
                        $syncData[$gamesBySlug[$gameSlug]->id] = ['experience_note' => $note];
                    }
                }
                $hw->games()->sync($syncData);
            }
        }
    }
}
