# 🎮 GamePL — Dijital Oyun Kültürü, Oyun Teknolojileri ve Yapay Zekâ Platformu

**GamePL**, dijital oyunları yalnızca bir eğlence ürünü olarak değil; **oyun tasarımı, oyun motoru teknolojileri, yapay zekâ (Game AI) mimarileri, örnek oynanış/teknik analiz videoları ve oyuncu kültürü** ekseninde inceleyen çok dilli, yeni nesil bir **Dijital Oyun Kültür ve Teknoloji Platformudur**.

---

## 1. Vizyon ve Amaç

Standart oyun mağazaları veya yüzeysel inceleme siteleri bir oyunun yalnızca "puanına" odaklanırken; **GamePL** şu beş temel sütunu tek bir ilişkisel bilgi grafında birleştirir:

1. **🕹️ Oyun Künyesi & Yaratıcı Ekip Grafı:** Oyun yönetmeni (*Hideo Kojima, Miyazaki Hidetaka, Sam Lake, Swen Vincke*), baş oyun tasarımcısı, grafik/AI mimarları, besteciler, seslendirme sanatçıları (Voice Cast + Karakter Adları), geliştirici stüdyo ve yayıncı ilişkileri.
2. **⚙️ Oyun Teknolojileri & Motor Mimarisi (Tech Stack):** Oyunun hangi oyun motoruyla (*Unreal Engine 5, Decima, REDengine 4, id Tech 7, RAGE, Source 2, Unity, Godot*) geliştirildiği; kullandığı grafik, fizik ve ses teknolojileri (*Path Tracing, Lumen, Nanite, Havok, PhysX, Wwise, Vulkan, DirectX 12 Ultimate*).
3. **🧠 Oyun Teknolojileri ve Yapay Zekâ İlişkisi (Game AI Lab):**
   - **Karar Mekanizmaları & Oynanış AI:** *Finite State Machines (FSM), Behavior Trees (Davranış Ağaçları), GOAP (Goal-Oriented Action Planning — F.E.A.R., S.T.A.L.K.E.R.), Utility AI, Director AI (Left 4 Dead), Nemesis Sistemi (Shadow of Mordor)*.
   - **Sinirsel Render & Performans AI:** *NVIDIA DLSS (Deep Learning Super Sampling), Ray Reconstruction, Frame Generation, AMD FSR, Intel XeSS*.
   - **Üretken Yapay Zekâ & Modern Sistemler:** *Prosedürel İçerik Üretimi (PCG — No Man's Sky), Pekiştirmeli Öğrenme (Reinforcement Learning) NPC Ajanları, LLM/NVIDIA ACE Tabanlı Dinamik Diyalog Sistemleri, AI Destekli Motion Matching Animasyon*.
4. **🎬 Örnek Oyun Videoları & Teknik Analiz Laboratuvarı:** Her oyuna ve teknolojiye bağlı **Sayfa İçi Sinema Modu Oynatıcı (Lightbox)** ile; *Örnek Oynanış Kesitleri (Gameplay Showcase)*, *Yapay Zekâ Davranış Analizi Videoları*, *Oyun Motoru & Ray Tracing Kare Hızı Analizleri* ve *Geliştirici Söyleşileri / Belgeselleri*.
5. **👥 Oyuncu Topluluğu, Kişisel Oyun Belleği ve Rol Tabanlı Yönetim:** Oyuncuların *Oynuyor (Playing), Tamamladı (Completed), İstek Listesi (Backlog)* günlükleri, **Favori 4 Oyun Vitrini**, derinlikli teknik/kültürel incelemeler ve 5 farklı kullanıcı rolüne göre ayrıştırılmış **Dashboard** iş akışı.

---

## 2. Küresel Platform İncelemesi ve Kıyaslama (Benchmark)

GamePL tasarlanırken dünyadaki en yetkin 6 oyun kültürü ve oyun teknolojisi platformu incelenmiş ve her birinin en güçlü yönleri sentezlenmiştir:

| İncelenen Platform | Odak Alanı | Güçlü Yönü | GamePL'deki Karşılığı |
| :--- | :--- | :--- | :--- |
| **Backloggd & IGDB** | Oyun Kataloglama & Sosyal Arşiv | Letterboxd benzeri *Played / Backlog* takibi, Favori 4 Oyun vitrini, puan dağılım histogramı, stüdyo ve yaratıcı ekip bağlantıları | **Kişisel Oyun Belleği, Favori 4 Oyun Vitrini, Bayesian Ağırlıklı Puanlama & Yaratıcı Ekip Grafı** |
| **Digital Foundry (Eurogamer)** | Oyun Teknolojileri & Motor Analizi | Oyun motorlarının (*UE5, Decima, id Tech*) kare hızı, Ray Tracing, gölgelendirici (shader) ve DLSS/FSR performans incelemeleri | **Oyun Teknolojileri (`technologies`) & Teknik Analiz Videoları Modülü** |
| **AI and Games (Tommy Thompson)** | Oyunlarda Yapay Zekâ Mimarisi | NPC'lerin nasıl düşündüğünü (*GOAP, Behavior Trees, Director AI, PCG*) akademik ve görsel örneklerle açıklaması | **Oyun Teknolojileri & AI İlişkisi (`ai_architectures` & `game_ai_cases`) Özel Bölümü** |
| **PCGamingWiki** | Teknik Künye & API Veritabanı | Her oyunun kullandığı oyun motoru, grafik API'si, ses motoru, fizik motoru ve yükseltme (upscaling) teknolojilerinin yapısal tablosu | **Oyun Detay Sayfasındaki İlişkisel "Teknoloji & Motor Künyesi" Tablosu** |
| **Game Developer (Gamasutra)** | Geliştirici Makaleleri & Post-Mortem | Oyun yönetmenleri ve mühendislerin yazdığı geliştirme günlükleri, mimari makaleler ve röportajlar | **Oyun & Teknoloji Yazıları (`/articles`) ve Geliştirici Röportajları (`/interviews`)** |
| **NoClip & GMTK** | Oyun Belgeselleri & Video Analiz | Oyun mekaniklerini ve stüdyo hikayelerini video essay formatında sunması | **Örnek Oyun Videoları (`game_videos`: Oynanış, AI Analizi, Motor İncelemesi, Fragman)** |

---

## 3. Kullanıcı Rolleri ve Görev Dağılımı

Platformda içerik üretimi, teknik doğruluk ve topluluk etkileşimi **5 farklı kullanıcı rolü** arasında paylaştırılmıştır:

| Rol Kodu | Rol Başlığı | Temel Sorumluluklar ve Dashboard Yetkileri |
| :--- | :--- | :--- |
| **`admin`** | Sistem Yöneticisi | Kullanıcı rollerini yönetir, tüm oyun/teknoloji/AI/makale kayıtlarını onaylar veya doğrudan yayınlar. |
| **`webadmin`** | Web & Teknoloji Editörü | Geliştirici, stüdyo ve oyuncuların gönderdiği oyunları, AI vaka analizlerini, örnek videoları ve makaleleri denetler (**Moderasyon Kuyruğu**); **"Haftanın Oyunu & AI Dosyası"** vitrinini belirler. |
| **`studyo`** | Stüdyo & Yayıncı Temsilcisi | Yeni oyun künyesi, teknik motor/API bilgileri, resmi oynanış/teknik tanıtım videoları ve geliştirici röportajlarını sisteme girer (`webadmin` onayına düşer). |
| **`gelistirici`** | Oyun Geliştiricisi & AI Mühendisi | **Oyun Teknolojileri ve Yapay Zekâ İlişkisi** üzerine teknik vaka analizleri (`Game AI Case Study`), oyun motoru inceleme yazıları, geliştirici söyleşileri ve teknik analiz videoları ekler. |
| **`oyuncu`** | Oyuncu & Eleştirmen | Oyunlara puan ve inceleme yazar (Spoiler korumalı), kendi **Oyun Günlüğünü** (*Oynuyor / Bitirdi / Backlog*) ve **Favori 4 Oyun Vitrinini** yönetir, tematik oyun listeleri oluşturur ve makale önerir. |

---

## 4. Teknoloji Yığını (Tech Stack)

- **Backend:** PHP 8.4+, Laravel 13 (Eloquent ORM, Sanctum API & Stateful Session Auth, Policies, FormRequests)
- **Veritabanı:** MySQL 8.0+ (`gamepl_db`) — İlişkisel Oyun, Motor, AI Mimarisi, Video ve Kişi Grafı
- **Frontend:** Blade + Tailwind CSS v4 (Vite 8) + Modern Koyu Tema (Cyber-Slate / Neon Emerald & Violet Vurgular)
- **Çok Dilli Mimari:** 7 Dilde (`TR, EN, FR, DE, ES, IT, JA`) oyun açıklamaları, teknik incelemeler ve oyuncu yorumları + Google Translate senkronizasyonu
- **Video Altyapısı:** Sayfa içi Sinema/Laboratuvar Modu Lightbox Oynatıcı (YouTube / Vimeo / Doğrudan MP4 zaman damgalı teknik analiz videoları)

---

## 5. Proje Dizin Yapısı ve Dokümantasyon

- [`README.md`](file:///var/www/gamepl/README.md): Platform vizyonu, küresel benchmark analizi ve modül özeti.
- [`implementation_plan.md`](file:///var/www/gamepl/implementation_plan.md): Veritabanı şeması (SQL & Eloquent), Oyun Teknolojileri + AI veri modeli, rotalar, ekran tasarımları ve 6 fazlı uygulama yol haritası.
