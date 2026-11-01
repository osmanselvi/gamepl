<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AiSystem;
use App\Models\Article;
use App\Models\Collection;
use App\Models\Game;
use App\Models\GameAiAnalysis;
use App\Models\GameAward;
use App\Models\GameEngine;
use App\Models\GameLog;
use App\Models\GameVideo;
use App\Models\Interview;
use App\Models\Person;
use App\Models\Review;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 5 Rol İçin Demo Hesaplar
        $admin = User::updateOrCreate(
            ['email' => 'admin@gamepl.local'],
            [
                'name' => 'Osman Selvi (Sistem Yöneticisi)',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'bio' => 'GamePL kurucu sistem yöneticisi, oyun motorları ve yapay zekâ mimarileri küratörü.',
                'studio_name' => 'GamePL Core Lab',
                'github_url' => 'https://github.com/osmanselvi',
                'steam_handle' => 'osmanselvi_dev',
            ]
        );

        $webadmin = User::updateOrCreate(
            ['email' => 'webadmin@gamepl.local'],
            [
                'name' => 'Deniz Arslan (Teknoloji Editörü)',
                'password' => Hash::make('password'),
                'role' => UserRole::WebAdmin,
                'bio' => 'Oyun teknolojileri, gerçek zamanlı ışın izleme (Ray Tracing) ve Günün Oyunu seçkisi baş editörü.',
                'studio_name' => 'GamePL Editorial',
            ]
        );

        $studyo = User::updateOrCreate(
            ['email' => 'studyo@gamepl.local'],
            [
                'name' => 'Larian & CDPR Türkiye Temsilciliği',
                'password' => Hash::make('password'),
                'role' => UserRole::Studyo,
                'bio' => 'Oyun stüdyoları resmi teknik künye, oynanış videoları ve geliştirici söyleşileri koordinatörü.',
                'studio_name' => 'Nova Forge Studios',
            ]
        );

        $gelistirici = User::updateOrCreate(
            ['email' => 'gelistirici@gamepl.local'],
            [
                'name' => 'Dr. Kerem Kaya (Oyun AI Mimarı)',
                'password' => Hash::make('password'),
                'role' => UserRole::Gelistirici,
                'bio' => 'GOAP, Utility AI, Behavior Trees ve Nöral Rendering üzerine çalışan Kıdemli Oyun Yapay Zekâsı Mühendisi.',
                'studio_name' => 'Aether AI Interactive',
                'github_url' => 'https://github.com/keremkaya-ai',
            ]
        );

        $oyuncu = User::updateOrCreate(
            ['email' => 'oyuncu@gamepl.local'],
            [
                'name' => 'Ece Yıldırım (Kıdemli Oyuncu & Eleştirmen)',
                'password' => Hash::make('password'),
                'role' => UserRole::Oyuncu,
                'bio' => 'CRPG, Immersive Sim ve Souls-like tutkunu. Oyun mekaniği ve düşman yapay zekâsı eleştirmeni.',
                'steam_handle' => 'ece_immersivesim',
            ]
        );

        // 2. Oyun Motorları (Game Engines)
        $enginesData = [
            [
                'name' => 'Unreal Engine 5',
                'slug' => 'unreal-engine-5',
                'developer_company' => 'Epic Games',
                'release_year' => 2022,
                'license_model' => 'Kaynak Kod Açık / Ticari Telifli (%5)',
                'primary_languages' => ['C++', 'Blueprint Visual Scripting', 'HLSL'],
                'key_technologies' => ['Nanite Micropolygon Geometry', 'Lumen Global Illumination', 'Chaos Physics', 'MetaHuman', 'MassEntity AI'],
                'ai_capabilities' => 'Behavior Trees, Environment Query System (EQS), State Trees, Smart Objects ve MassAI ile on binlerce otonom ajanın gerçek zamanlı simülasyonu.',
                'description' => 'Nanite sanallaştırılmış mikropoligon geometrisi ve Lumen tam dinamik küresel aydınlatma teknolojileriyle modern AAA oyun endüstrisinin standartlarını belirleyen oyun motoru.',
                'official_url' => 'https://www.unrealengine.com',
            ],
            [
                'name' => 'REDengine 4',
                'slug' => 'redengine-4',
                'developer_company' => 'CD Projekt RED',
                'release_year' => 2020,
                'license_model' => 'Tescilli (Stüdyo İçi Özel Motor)',
                'primary_languages' => ['C++', 'RedScript'],
                'key_technologies' => ['Path Tracing (Overdrive)', 'DLSS 3.5 Ray Reconstruction', 'Fast Crowd Streaming', 'JALI Facial AI'],
                'ai_capabilities' => 'JALI makine öğrenimi tabanlı çok dilli dudak/yüz senkronizasyonu, binlerce şehir sakinini yöneten hiyerarşik Crowd AI ve dinamik çatışma alt-ağaçları.',
                'description' => 'Night City gibi dikey mimariye sahip devasa metropolleri kesintisiz yükleme ekranı olmadan işlemek ve tam Path Tracing aydınlatmayı gerçek zamanlı çalıştırmak üzere tasarlanmış yüksek performanslı motor.',
                'official_url' => 'https://www.cdprojektred.com',
            ],
            [
                'name' => 'RAGE (Rockstar Advanced Game Engine)',
                'slug' => 'rage-engine',
                'developer_company' => 'Rockstar Games (RAGE Technology Group)',
                'release_year' => 2006,
                'license_model' => 'Tescilli (Rockstar Özel Motoru)',
                'primary_languages' => ['C++', 'C'],
                'key_technologies' => ['Euphoria Biokinetik Sinir Ağı', 'Volumetric Cloud & Weather Sim', 'Subsumption NPC World Loop'],
                'ai_capabilities' => 'Euphoria ile kas-iskelet sistemini sinirsel reflekslerle simüle eden fiziksel AI ve her NPC için 24 saatlik hafızalı yaşam döngüsü.',
                'description' => 'Açık dünya fizik simülasyonu, ekosistem etkileşimi ve gerçekçi karakter tepkileri konusunda oyun tarihinin en ileri mühendislik başarılarından biri.',
                'official_url' => 'https://www.rockstargames.com',
            ],
            [
                'name' => 'Divinity Engine 4.0',
                'slug' => 'divinity-engine-4',
                'developer_company' => 'Larian Studios',
                'release_year' => 2023,
                'license_model' => 'Tescilli (Larian Özel Motoru)',
                'primary_languages' => ['C++', 'Osiris Scripting', 'Lua'],
                'key_technologies' => ['Systemic Elemental Surface Chemistry', 'Dynamic Branching Cinematic Graph', 'Tactical Utility AI'],
                'ai_capabilities' => 'Yüzlerce büyü, çevre yüzeyi, yükseklik avantajı ve grup sinerjisini saniyeler içinde puanlayarak en zeki hamleyi seçen Taktiksel Utility AI beyni.',
                'description' => 'Masaüstü rol yapma oyunlarının (D&D 5e) sınırsız özgürlüğünü ve permütasyonlarını sistemik kurallarla simüle eden izometrik/sinematik hibrit motor.',
                'official_url' => 'https://larian.com',
            ],
            [
                'name' => 'Decima Engine',
                'slug' => 'decima-engine',
                'developer_company' => 'Guerrilla Games & Kojima Productions',
                'release_year' => 2013,
                'license_model' => 'Tescilli (Sony Interactive / Kojima Paylaşımlı)',
                'primary_languages' => ['C++'],
                'key_technologies' => ['Procedural Biome Generation', 'Terrain Deformation Physics', 'Multi-Part Machine Destructibility'],
                'ai_capabilities' => 'Çok parçalı robotik veya biyolojik düşmanların kopan silah/sensör parçalarına göre davranış ağacını anında değiştiren adaptif savaş yapay zekâsı.',
                'description' => 'Death Stranding ve Horizon serilerine güç veren, fotogerçekçi arazi topografyası ve prosedürel ekosistem üretiminde uzmanlaşmış motor.',
                'official_url' => 'https://www.guerrilla-games.com',
            ],
            [
                'name' => 'Northlight Engine',
                'slug' => 'northlight-engine',
                'developer_company' => 'Remedy Entertainment',
                'release_year' => 2016,
                'license_model' => 'Tescilli (Remedy Özel Motoru)',
                'primary_languages' => ['C++', 'Lua'],
                'key_technologies' => ['Mesh Shaders', 'Full Ray-Traced Indirect Lighting', 'Voxel Destruction', 'Live-Action Mixed Media Pipeline'],
                'ai_capabilities' => 'Işık-karanlık bölgelerine duyarlı mekânsal farkındalık (Spatial Light-Aware AI) ve psikolojik gerilim temposunu ayarlayan dinamik düşman konumlandırma.',
                'description' => 'Control ve Alan Wake 2 gibi gerçeküstü atmosfer, fiziksel yıkım ve GPU tabanlı Mesh Shader teknolojilerini birleştiren yeni nesil grafik motoru.',
                'official_url' => 'https://www.remedygames.com',
            ],
        ];

        $engines = [];
        foreach ($enginesData as $eData) {
            $engines[$eData['slug']] = GameEngine::updateOrCreate(['slug' => $eData['slug']], $eData);
        }

        // 3. Oyun Teknolojileri (Technologies)
        $techData = [
            [
                'name' => 'NVIDIA DLSS 3.5 & Ray Reconstruction',
                'slug' => 'nvidia-dlss-ray-reconstruction',
                'category' => 'upscaling_ai',
                'vendor' => 'NVIDIA',
                'introduced_year' => 2023,
                'summary' => 'Geleneksel el yapımı gürültü gidericileri (denoisers) süper bilgisayarlarda eğitilmiş derin öğrenme sinir ağıyla değiştiren yapay zekâ görüntü işleme teknolojisi.',
                'technical_deep_dive' => 'Işın izleme (Ray Tracing / Path Tracing) sırasında piksel başına düşen sınırlı ışın örneklerini (samples) zaman ve mekân ekseninde analiz ederek eksik pikselleri ve optik yansımaları nöral ağ ile yeniden inşa eder.',
                'ai_integration_notes' => 'Tensor çekirdekleri üzerinde çalışan Transformer/Autoencoder tabanlı derin sinir ağı modeli, geleneksel algoritmalardan 5 kat daha fazla eğitim verisiyle gerçek zamanlı çıkarım (inference) yapar.',
            ],
            [
                'name' => 'Nanite & Lumen (Sanallaştırılmış Geometri ve Küresel Aydınlatma)',
                'slug' => 'nanite-and-lumen',
                'category' => 'rendering',
                'vendor' => 'Epic Games',
                'introduced_year' => 2022,
                'summary' => 'Milyarlarca poligonluk sinema kalitesindeki 3B modelleri LOD (Level of Detail) kaybı olmadan çizen ve gerçek zamanlı dolaylı ışık sıçramalarını hesaplayan ikili mimari.',
                'technical_deep_dive' => 'Nanite, geometrik veriyi ekran pikseli boyutundaki kümelere (clusters) bölerek yalnızca görünür alt-kümeleri GPU üzerinden stream eder. Lumen ise Signed Distance Fields (SDF) ve donanımsal Ray Tracing kullanarak sahnedeki her yüzeyin ışığı gerçek zamanlı yansıtmasını sağlar.',
                'ai_integration_notes' => 'Görünürlük tahmini (occlusion culling) ve aydınlatma önbellekleme süreçlerinde sezgisel kümeleme algoritmalarıyla çalışma zamanı maliyetini minimize eder.',
            ],
            [
                'name' => 'Euphoria Biokinetik Fizik ve Sinirsel Ragdoll Sentezi',
                'slug' => 'euphoria-neural-physics',
                'category' => 'physics',
                'vendor' => 'NaturalMotion / Rockstar',
                'introduced_year' => 2008,
                'summary' => 'Önceden kaydedilmiş ölüm veya düşme animasyonları yerine, karakterin kaslarını, eklem torklarını ve denge reflekslerini çalışma zamanında simüle eden biyomekanik yapay zekâ.',
                'technical_deep_dive' => 'Karakter vurulduğunda veya tökezlediğinde merkezi bir sinir sistemi modeli devreye girer: karakter düşerken tutunmaya çalışır, vurulduğu bölgeyi korur ve zemin eğimine göre adım atarak dengesini geri kazanmaya uğraşır.',
                'ai_integration_notes' => 'Yapay sinir ağları ve genetik algoritmalarla eğitilmiş sanal merkezi sinir sistemi (CNS) kontrolörleri kullanır.',
            ],
            [
                'name' => 'Motion Matching & Prosedürel Hareket Sentezi',
                'slug' => 'motion-matching',
                'category' => 'animation',
                'vendor' => 'Ubisoft / Endüstri Standardı',
                'introduced_year' => 2016,
                'summary' => 'Karmaşık animasyon durum makineleri (State Machines) yerine, oyuncunun hız ve yön vektörüne en uygun kareyi devasa bir mocap veritabanından milisaniyeler içinde seçen teknoloji.',
                'technical_deep_dive' => 'Binlerce ham hareket yakalama (motion capture) karesi sürekli taranır; karakterin mevcut kemik pozisyonu, hızı ve gelecekteki 1 saniyelik tahmini yörüngesi (trajectory) ile en düşük maliyetli (cost function) kareye kesintisiz geçiş yapılır.',
                'ai_integration_notes' => 'En yakın komşu araması (k-NN) ve Neural Motion Matching (Learned Motion Matching) ile bellek kullanımı %90 azaltılarak sinir ağlarına sıkıştırılır.',
            ],
            [
                'name' => 'JALI Makine Öğrenimi Tabanlı Yüz & Dudak Senkronizasyonu',
                'slug' => 'jali-facial-ai',
                'category' => 'upscaling_ai',
                'vendor' => 'JALI Research / CD Projekt RED',
                'introduced_year' => 2020,
                'summary' => 'Ses dosyalarındaki fonemleri ve duygusal tonlamaları analiz ederek 10+ farklı dilde binlerce NPC için tam otomatik, sinematik kalitede yüz ve dudak animasyonu üreten AI sistemi.',
                'technical_deep_dive' => 'Çene açıklığı (Jaw) ve dudak büzülmesi (Lip) parametrelerini psikodilbilimsel kurallar ve derin öğrenme ses analiziyle birleştirerek göz kırpma, kaş hareketi ve boyun kaslarını konuşma ritmine göre sentezler.',
                'ai_integration_notes' => 'Yüzlerce saatlik diyalog içeren RPG oyunlarında her dil için ayrı motion capture yapma zorunluluğunu ortadan kaldıran üretken AI boru hattı.',
            ],
            [
                'name' => 'Systemic Elemental Surface Chemistry (Sistemik Çevre Kimyası)',
                'slug' => 'systemic-surface-chemistry',
                'category' => 'physics',
                'vendor' => 'Larian Studios / Nintendo',
                'introduced_year' => 2017,
                'summary' => 'Su, kan, yağ, buz, zehir, ateş ve elektriğin birbirleriyle ve oyun dünyasındaki nesnelerle fiziksel/kimyasal kurallar çerçevesinde zincirleme reaksiyona girmesi.',
                'technical_deep_dive' => 'Her yüzey hücresi (grid/voxel) durum bayrakları taşır: su elektrikle buluşursa iletken buluta dönüşür, ateşle buluşursa buharlaşır ve görüş alanını (Line of Sight) kapatır.',
                'ai_integration_notes' => 'Oyun yapay zekâsı (NPC) bu yüzey dönüşümlerini ezberlenmiş scriptlerle değil, gerçek zamanlı simülasyon matrisi üzerinden okuyarak kendi taktiksel tuzaklarını kurar.',
            ],
        ];

        $technologies = [];
        foreach ($techData as $tData) {
            $technologies[$tData['slug']] = Technology::updateOrCreate(['slug' => $tData['slug']], $tData);
        }

        // 4. Oyun Yapay Zekâsı Sistemleri (AI Systems — /ai-lab için)
        $aiSystemsData = [
            [
                'name' => 'GOAP (Goal-Oriented Action Planning / Hedef Odaklı Eylem Planlama)',
                'slug' => 'goap-goal-oriented-action-planning',
                'paradigm' => 'decision_making',
                'invented_by_or_origin' => 'Jeff Orkin (MIT Media Lab & Monolith Productions)',
                'first_notable_game' => 'F.E.A.R. (First Encounter Assault Recon)',
                'first_year' => 2005,
                'complexity_level' => 'İleri Düzey (Otonom Planlayıcı / A* Arama)',
                'concept_summary' => 'NPC\'lere "ne yapacağını" adım adım dikte eden durum makineleri (FSM) yerine, yalnızca "hedefler" (Goals) ve "eylemler" (Actions) tanımlayarak NPC\'nin kendi eylem planını çalışma zamanında A* algoritmasıyla sentezlemesini sağlayan mimari.',
                'how_it_works' => "1. Dünya Durumu (World State): Anahtar-değer çiftleriyle temsil edilir (örn. `TargetVisible: true`, `WeaponLoaded: false`, `InCover: false`).\n2. Hedefler (Goals): Ajanın ulaşmak istediği durumdur (örn. `KillEnemy`, `StayAlive`).\n3. Eylemler (Actions): Her eylemin Önkoşulları (Preconditions) ve Etkileri (Effects) vardır.\n4. Planlayıcı (Planner): A* (A-Star) graf arama algoritması, hedeften geriye doğru (regressive search) giderek en düşük maliyetli eylem zincirini bulur (örn. `Siper Al -> Şarjör Değiştir -> El Bombası At -> Yan Koridordan Kuşat`).",
                'pros_and_cons' => 'Avantajları: Geliştiricinin öngörmediği acil durumlarda bile NPC\'ler şaşırtıcı derecede zeki, organik ve koordineli tepkiler üretir. Yeni bir eylem eklemek tüm diyagramı bozmaz. Dezavantajları: Çok fazla eylem ve ajan olduğunda A* planlama CPU maliyeti artabilir.',
                'pseudocode_or_diagram' => "Goal: KillTarget (Priority: 0.9)\n  <- Action: AttackWithShotgun [Pre: HasAmmo=true, TargetInRange=true] -> [Effect: TargetDead=true]\n  <- Action: FlankTarget       [Pre: KnowsTargetPos=true]              -> [Effect: TargetInRange=true]\n  <- Action: FlushWithGrenade  [Pre: TargetInCover=true, HasGrenade=1] -> [Effect: TargetInCover=false]",
            ],
            [
                'name' => 'Dual-Brain Subsumption & Tether AI (İki Beyinli Avcı Mimarisi)',
                'slug' => 'dual-brain-tether-ai',
                'paradigm' => 'decision_making',
                'invented_by_or_origin' => 'Tommy Thompson & Creative Assembly (Alien: Isolation)',
                'first_notable_game' => 'Alien: Isolation',
                'first_year' => 2014,
                'complexity_level' => 'Çok Yüksek (Makro Yönetmen + Mikro Davranış Ağacı)',
                'concept_summary' => 'Oyuncunun yerini her zaman bilen "Her Şeyi Gören Makro Beyin (Director AI)" ile oyuncunun yerini bilmeyen ama sensörleriyle onu avlayan "Mikro Beyin (Xenomorph Behavior Tree)" arasındaki gerilim dengeleyici iletişim modeli.',
                'how_it_works' => "1. Director AI (Makro Beyin): Oyuncunun tam konumunu ve stres seviyesini ('Menace Gauge') sürekli izler. Gerilim düştüğünde Yaratığa oyuncunun bulunduğu genel bölgeye ('Search Zone') gitmesi için dolaylı ipucu verir; gerilim çok yükseldiğinde Yaratığı havalandırma kanallarına geri çeker.\n2. Xenomorph AI (Mikro Beyin): Yaklaşık 100 düğümlük bir Davranış Ağacıdır (Behavior Tree). Asla hile yapmaz; yalnızca kendi görüş konisi, ayak sesi duyma sensörü, el feneri ışığı ve açılan dolap/kapı izlerini kullanarak oyuncuyu avlar. Ayrıca oyuncu aynı tuzağı (örn. ses yapıcı veya alev silahı) tekrar tekrar kullanırsa öğrenme düğümlerinin kilidi açılır ve Yaratık artık o blöfe kanmaz.",
                'pros_and_cons' => 'Avantajları: Oyuncuya düşmanın gerçekten "düşündüğü, öğrendiği ve adil avlandığı" hissini verir. Dezavantajları: Hassas kalibre edilmezse oyuncu üzerinde aşırı bunaltıcı bir baskı yaratabilir.',
                'pseudocode_or_diagram' => "[Director AI: MenaceGauge < Threshold] ---> SetTetherZone(PlayerSector)\n[Xenomorph BT Root]\n  ├── Selector: ReactToSensoryStimulus (VisualCone / FootstepAudio / LockerNoise)\n  ├── Selector: ThreatLearning (If FlamethrowerUsedCount > 3 -> KeepDistanceAndAmbush)\n  └── Sequence: PatrolTetherZone -> InspectHidingSpots -> EnterVentNetwork",
            ],
            [
                'name' => 'Nemesis System (Prosedürel Sosyal Hafıza ve Hiyerarşi Ağı)',
                'slug' => 'nemesis-procedural-social-memory',
                'paradigm' => 'emergent_social',
                'invented_by_or_origin' => 'Monolith Productions (Michael de Plater)',
                'first_notable_game' => 'Middle-earth: Shadow of Mordor',
                'first_year' => 2014,
                'complexity_level' => 'Yüksek (Dinamik Durum Grafiği & Diyalog Sentezi)',
                'concept_summary' => 'Sıradan prosedürel düşmanların oyuncuyu öldürdüklerinde veya savaştan kaçtıklarında terfi ettiği, yaralarını ve önceki karşılaşmaları hatırlayarak oyuncuya özel intikam hikâyeleri ürettiği sosyal yapay zekâ ağı.',
                'how_it_works' => "Her komutan (Uruk) prosedürel olarak üretilmiş kişilik özelliklerine, korkulara (örn. ateş korkusu, Caragor korkusu), bağışıklıklara ve hafıza kayıtlarına sahiptir. Oyuncu bir düşmanı ateşte yakıp öldürdüğünü sanırsa, o düşman yüzü sargılı şekilde geri dönebilir, oyuncuyu gördüğünde 'Beni ateşte bıraktığın günü unutmadım!' diyerek yeni bir öfke özelliği kazanır.",
                'pros_and_cons' => 'Avantajları: Senaryo yazarlarının yazmadığı, tamamen oyuncunun hatalarından ve zaferlerinden doğan benzersiz kişisel rekabet öyküleri yaratır. Dezavantajları: Warner Bros. tarafından patentlendiği için diğer stüdyolar tarafından doğrudan kopyalanması hukuki olarak kısıtlanmıştır.',
                'pseudocode_or_diagram' => "Event: GruntKillsPlayer(GruntID: #402)\n  -> PromoteToCaptain(GruntID: #402, Title: 'The Tark Slayer')\n  -> RecordMemory(Target: Player, Outcome: Victory, ScarType: None)\n  -> UpdateSocialHierarchy(PowerLevel: +15, UnlockTrait: 'AmbushMaster')",
            ],
            [
                'name' => 'The AI Director (Dinamik Tempo, Stres ve Dramatik Ritim Yönetmeni)',
                'slug' => 'the-ai-director-pacing-engine',
                'paradigm' => 'director_pacing',
                'invented_by_or_origin' => 'Valve Corporation (Michael Booth)',
                'first_notable_game' => 'Left 4 Dead / Left 4 Dead 2',
                'first_year' => 2008,
                'complexity_level' => 'Orta-İleri (Gerçek Zamanlı Biyometrik/İstatistiksel Stres Tahmini)',
                'concept_summary' => 'Düşmanları sabit noktalara yerleştirmek yerine, oyuncu ekibinin yorgunluğunu, can kaybını ve stres düzeyini saniye saniye ölçerek oyunun temposunu bir sinema yönetmeni gibi yöneten üst-yapay zekâ.',
                'how_it_works' => "AI Director dört aşamalı bir dramatik eğri işletir:\n1. Build-Up (Gerilimi Tırmandırma): Sürüleri ve özel düşmanları aktif olarak oyuncunun ilerlediği rotanın arkasına/önüne yerleştirir.\n2. Sustain Peak (Zirveyi Koruma): Oyuncuların duygusal yoğunluğu (Emotional Intensity) maksimum eşiğe ulaştığında 3-5 saniye boyunca baskıyı sürdürür.\n3. Peak Fade (Zirveden İniş): Yeni düşman üretimini durdurur, mevcut çatışmanın bitmesini bekler.\n4. Relax (Dinlenme): 30-45 saniye boyunca sakinlik sağlar, düşük canlı oyuncuların önüne ilk yardım çantası ve cephane yerleştirir, müzikleri yumuşatır.",
                'pros_and_cons' => 'Avantajları: Aynı haritayı 100 kez oynasanız bile her seferinde farklı bir sinematik ritim ve sürpriz deneyimi sunar.',
                'pseudocode_or_diagram' => "PlayerIntensity = f(DamageTaken, NearbyInfectedCount, IncapacitatedTeammates, AdrenalineDecay)\nif (MaxTeamIntensity > PEAK_THRESHOLD) {\n    TransitionState(PEAK_FADE -> RELAX_PHASE);\n    SpawnHealthPacksBasedOnTeamDeficit();\n}",
            ],
            [
                'name' => 'Tactical Utility AI (Çok Değişkenli Taktiksel Fayda Puanlama)',
                'slug' => 'tactical-utility-ai',
                'paradigm' => 'decision_making',
                'invented_by_or_origin' => 'Dave Mark & Larian Studios AI Team',
                'first_notable_game' => 'Baldur\'s Gate 3 / Divinity: Original Sin 2',
                'first_year' => 2017,
                'complexity_level' => 'Çok Yüksek (Kombinatoryal Olasılık & Fayda Fonksiyonu)',
                'concept_summary' => 'Sıra tabanlı veya gerçek zamanlı taktik oyunlarında yüzlerce olası büyü, silah, çevre yüzeyi ve pozisyon kombinasyonunu matematiksel fayda eğrileriyle (Utility Curves) puanlayarak en akılcı hamleyi seçen sistem.',
                'how_it_works' => "NPC sırası geldiğinde olası tüm hamleleri simüle eder: 'Ok atmak (Skor: 42)', 'Yağ varilini patlatıp 3 oyuncuyu yakmak (Skor: 88)', 'Uçurumun kenarındaki oyuncuyu aşağı itmek (Skor: 96)', 'Kendi yaralı şifacısını iyileştirmek (Skor: 91)'. Ayrıca NPC'nin zekâ (Archetype Intelligence) puanına göre kasıtlı olarak alt-optimal hamleler seçilerek bir goblin ile bir zihin yüzücünün (Mind Flayer) savaş zekâsı birbirinden ayrıştırılır.",
                'pros_and_cons' => 'Avantajları: Yüzlerce kuralın kesiştiği karmaşık RPG sistemlerinde ezber bozan, yaratıcı ve insan zekâsına yakın kombolar üretir.',
                'pseudocode_or_diagram' => "Score(Action) = (DamageWeight * ExpectedDamage) + (ControlWeight * KnockbackValue) + (SurfaceSynergyBonus) - (FriendlyFirePenalty * ArchetypeEmpathy)\nSelectedAction = ArgMax(AllValidActionsInTurn)",
            ],
            [
                'name' => 'Neural Rendering & Generative NPC Microservices (Nöral Grafik ve Üretken AI)',
                'slug' => 'neural-rendering-and-generative-npc',
                'paradigm' => 'neural_rendering',
                'invented_by_or_origin' => 'NVIDIA Research & Modern Oyun Endüstrisi',
                'first_notable_game' => 'Cyberpunk 2077: Overdrive / Modern AI Tech Demos',
                'first_year' => 2023,
                'complexity_level' => 'Çok Yüksek (Derin Sinir Ağları & Transformer Modelleri)',
                'concept_summary' => 'Yapay zekânın yalnızca NPC kararlarında değil; piksellerin çizilmesinde (DLSS Ray Reconstruction, Frame Generation), ses sentezinde ve açık uçlu diyalog üretiminde doğrudan GPU Tensor çekirdekleriyle kullanılması.',
                'how_it_works' => 'Grafik tarafında: Geleneksel rasterizasyon yerine az sayıda ışın izlenir ve geri kalan pikseller + ara kareler (Optical Flow + AI Frame Gen) derin öğrenme modeli tarafından tahmin edilir. NPC tarafında ise küçük dil modelleri (SLM) ve RAG (Retrieval-Augmented Generation) ile karakterin dünya bilgisi sınırları içinde serbest doğaçlama konuşması sağlanır.',
                'pros_and_cons' => 'Avantajları: Fotogerçekçi Path Tracing aydınlatmayı gerçek zamanlı oynanabilir kare hızlarına (60-120 FPS) taşır. Dezavantajları: Özel donanım hızlandırıcıları (Tensor/NPU) gerektirir.',
                'pseudocode_or_diagram' => "Input: NoisyRayTracedBuffer + MotionVectors + DepthBuffer + PreviousHistoryFrame\n  -> [Deep Convolutional Autoencoder / Transformer on Tensor Cores]\nOutput: Denoised4KFrame + SynthesizedIntermediateFrame",
            ],
        ];

        $aiSystems = [];
        foreach ($aiSystemsData as $aiData) {
            $aiSystems[$aiData['slug']] = AiSystem::updateOrCreate(['slug' => $aiData['slug']], $aiData);
        }

        // 5. Oyunlar (Games — Kült ve Modern Başyapıtlar)
        $gamesData = [
            [
                'title' => 'Baldur\'s Gate 3',
                'original_title' => 'Baldur\'s Gate III',
                'slug' => 'baldurs-gate-3',
                'release_year' => 2023,
                'developer_studio' => 'Larian Studios',
                'publisher' => 'Larian Studios',
                'game_director' => 'Swen Vincke',
                'lead_ai_programmer' => 'Larian Combat & Systemic AI Team',
                'composer' => 'Borislav Slavov',
                'country' => 'Belçika',
                'game_engine_id' => $engines['divinity-engine-4']->id,
                'platforms' => ['PC', 'PlayStation 5', 'Xbox Series X/S', 'macOS'],
                'genres' => ['CRPG', 'Taktiksel Strateji', 'Fantezi', 'Immersive Sim'],
                'moods' => ['Epik', 'Seçim Odaklı', 'Taktiksel', 'Karanlık Fantezi'],
                'perspective' => 'İzometrik / Sinematik Üçüncü Şahıs',
                'multiplayer_modes' => ['Tek Oyunculu', '4 Kişilik Çevrimiçi Co-op', 'Bölünmüş Ekran (Split-Screen)'],
                'main_story_hours' => 75,
                'completionist_hours' => 165,
                'metacritic_score' => 96,
                'rating' => 9.7,
                'synopsis' => 'Zihin Yüzücülerin (Mind Flayers) beyninize yerleştirdiği Illithid parazitiyle uyanan bir grup maceracı, hem dönüşümü durdurmak hem de Unutulmuş Diyarlar\'ın (Forgotten Realms) kaderini değiştirecek kadim bir komployla yüzleşmek zorundadır.',
                'technical_overview' => 'Divinity Engine 4.0 üzerinde çalışan yapım, 17.000\'den fazla son varyasyonu, 174 saatlik motion-capture ara sahnesi ve tamamen sistemik fizik/element kimyası kurallarıyla modern RPG mühendisliğinin zirvesini temsil eder.',
                'ai_architecture_summary' => 'Taktiksel Utility AI mimarisi; düşmanların zekâ arketipine göre çevre yüzeylerini (ateş, buz, elektrik, asit), yükseklik avantajını, itme (shove) fiziğini ve büyü sinerjilerini gerçek zamanlı puanlayarak hamle seçmesini sağlar.',
                'cultural_impact' => 'The Game Awards, BAFTA, Golden Joystick, D.I.C.E. ve GDC olmak üzere 5 büyük ödül töreninin tamamında "Yılın Oyunu (GOTY)" seçilen tarihteki ilk oyun olmuştur.',
                'why_play' => 'Oyuncunun aklına gelebilecek en çılgın taktiksel veya anlatısal denemeye bile "Evet, bunu yapabilirsin ve dünya buna tepki verir" diyebilen eşsiz özgürlüğü için.',
                'poster_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=1T22wNvoNiU',
                'pc_min_specs' => ['CPU' => 'Intel i5-4690 / Ryzen 5 1500X', 'GPU' => 'GTX 970 / RX 480', 'RAM' => '8 GB', 'SSD' => '150 GB'],
                'pc_rec_specs' => ['CPU' => 'Intel i7-8700K / Ryzen 5 3600', 'GPU' => 'RTX 2060 Super / RX 5700 XT', 'RAM' => '16 GB', 'SSD' => '150 GB NVMe'],
                'is_featured_daily' => true,
                'status' => 'approved',
                'submitted_by' => $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [
                    'systemic-surface-chemistry' => 'Su, kan, ateş, yağ ve yıldırım büyüleri savaş alanında gerçek zamanlı zincirleme reaksiyona girer.',
                ],
            ],
            [
                'title' => 'Cyberpunk 2077: Phantom Liberty',
                'original_title' => 'Cyberpunk 2077: Widmo Wolności',
                'slug' => 'cyberpunk-2077-phantom-liberty',
                'release_year' => 2023,
                'developer_studio' => 'CD Projekt RED',
                'publisher' => 'CD Projekt',
                'game_director' => 'Gabe Amatangelo',
                'lead_ai_programmer' => 'CDPR Crowd & Combat AI Team',
                'composer' => 'Marcin Przybyłowicz, P.T. Adamczyk',
                'country' => 'Polonya',
                'game_engine_id' => $engines['redengine-4']->id,
                'platforms' => ['PC', 'PlayStation 5', 'Xbox Series X/S'],
                'genres' => ['Aksiyon RPG', 'Siberpunk', 'Birinci Şahıs Nişancı', 'Açık Dünya'],
                'moods' => ['Neon-Noir', 'Distopik', 'Casus Gerilimi', 'Yüksek Teknoloji'],
                'perspective' => 'Birinci Şahıs (FPS)',
                'multiplayer_modes' => ['Tek Oyunculu'],
                'main_story_hours' => 38,
                'completionist_hours' => 115,
                'metacritic_score' => 90,
                'rating' => 9.3,
                'synopsis' => 'Megakorporasyonların yönettiği Night City\'nin kanunsuz bölgesi Dogtown\'da, paralı asker V ve zihnindeki dijital hayalet Johnny Silverhand, Yeni Amerika Birleşik Devletleri Başkanı\'nı kurtarmak için ölümcül bir siber-casusluk ağına girer.',
                'technical_overview' => 'REDengine 4\'ün Path Tracing (Overdrive Mode) ve NVIDIA DLSS 3.5 Ray Reconstruction entegrasyonuyla gerçek zamanlı grafik teknolojilerinin vitrini haline gelmiş açık dünya başyapıtı.',
                'ai_architecture_summary' => '2.0 güncellemesiyle tamamen yeniden yazılan alt-ağaç tabanlı Polis/MaxTac taktiksel takip yapay zekâsı, araçlı çatışma (Vehicular Combat AI) ve JALI sinir ağıyla 11 dilde prosedürel dudak/mimik sentezi.',
                'cultural_impact' => 'Oyun tarihinde teknik bir krizden çıkıp mühendislik ve nöral grafik devriminin en büyük referans noktasına dönüşen en etkileyici geri dönüş hikayesi.',
                'why_play' => 'Night City\'nin yağmurlu neon sokaklarında Path Tracing aydınlatmanın ve siber-casusluk anlatısının yarattığı eşsiz atmosferi deneyimlemek için.',
                'poster_url' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=PbVKGoJh4OI',
                'pc_min_specs' => ['CPU' => 'Core i7-6700 / Ryzen 5 1600', 'GPU' => 'GTX 1060 6GB / RX 580', 'RAM' => '12 GB', 'SSD' => '70 GB SSD'],
                'pc_rec_specs' => ['CPU' => 'Core i7-12700 / Ryzen 7 7800X3D', 'GPU' => 'RTX 4070 Ti / RX 7900 XT', 'RAM' => '20 GB', 'SSD' => '70 GB NVMe'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $webadmin->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [
                    'nvidia-dlss-ray-reconstruction' => 'Tam Path Tracing modunda piksel yansımalarını ve neon gölgelerini yapay zekâ ile yeniden inşa eder.',
                    'jali-facial-ai' => 'On binlerce diyalog satırı için 11 dilde tam otomatik yüz, göz ve dudak animasyonu üretir.',
                ],
            ],
            [
                'title' => 'Alien: Isolation',
                'original_title' => 'Alien: Isolation',
                'slug' => 'alien-isolation',
                'release_year' => 2014,
                'developer_studio' => 'Creative Assembly',
                'publisher' => 'SEGA',
                'game_director' => 'Alistair Hope',
                'lead_ai_programmer' => 'Gary Napper, Tommy Thompson (Research)',
                'composer' => 'Christian Henson, Joe Henson',
                'country' => 'Birleşik Krallık',
                'game_engine_id' => $engines['decima-engine']->id,
                'platforms' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch'],
                'genres' => ['Hayatta Kalma Korku', 'Gizlilik (Stealth)', 'Immersive Sim', 'Bilim Kurgu'],
                'moods' => ['Klostrofobik', 'Yüksek Gerilim', 'Retro-Fütüristik', 'Acımasız'],
                'perspective' => 'Birinci Şahıs (FPS)',
                'multiplayer_modes' => ['Tek Oyunculu'],
                'main_story_hours' => 18,
                'completionist_hours' => 34,
                'metacritic_score' => 81,
                'rating' => 9.4,
                'synopsis' => 'Ellen Ripley\'nin kızı mühendis Amanda Ripley, annesinin kayboluşunun ardındaki gerçeği bulmak için terk edilmiş Sevastopol uzay istasyonuna gider; ancak istasyonda kendisini avlayan durdurulamaz bir Xenomorph vardır.',
                'technical_overview' => '1979 yapımı Ridley Scott filminin CRT monitörlerini, analog kasetlerini ve hacimsel duman/ışık efektlerini kusursuz kopyalayan özel motor mimarisi.',
                'ai_architecture_summary' => 'Oyun yapay zekâsı literatürüne geçen "İki Beyinli (Director AI + Xenomorph Behavior Tree)" mimarisi: Yaratık asla scriptlerle ışınlanmaz; oyuncunun çıkardığı sesleri, el feneri ışığını ve tekrarlayan taktiklerini öğrenerek avlanır.',
                'cultural_impact' => 'Akademik oyun yapay zekâsı derslerinde (AI and Games) "Adil ama Öngörülemez Düşman Tasarımı" konusunda en çok incelenen referans oyun olmuştur.',
                'why_play' => 'Bir video oyununda karşınızdaki yapay zekânın gerçekten sizi dinlediğini, blöflerinizden ders çıkardığını ve sizi köşeye sıkıştırdığını hissetmek için.',
                'poster_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=7h0cgmvIrZw',
                'pc_min_specs' => ['CPU' => 'Core 2 Duo E8500', 'GPU' => 'GT 430 / HD 5550', 'RAM' => '4 GB', 'SSD' => '35 GB'],
                'pc_rec_specs' => ['CPU' => 'Core 2 Quad Q9650 / Phenom II X4', 'GPU' => 'GTX 660 / R9 270', 'RAM' => '8 GB', 'SSD' => '35 GB SSD'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $gelistirici->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [],
            ],
            [
                'title' => 'F.E.A.R. (First Encounter Assault Recon)',
                'original_title' => 'F.E.A.R.',
                'slug' => 'fear-first-encounter-assault-recon',
                'release_year' => 2005,
                'developer_studio' => 'Monolith Productions',
                'publisher' => 'Vivendi Universal Games',
                'game_director' => 'Craig Hubbard',
                'lead_ai_programmer' => 'Jeff Orkin',
                'composer' => 'Nathan Grigg',
                'country' => 'ABD',
                'game_engine_id' => $engines['northlight-engine']->id,
                'platforms' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'genres' => ['Taktiksel FPS', 'Psikolojik Korku', 'Aksiyon'],
                'moods' => ['Adrenalin', 'Paranoya', 'Karanlık', 'Taktiksel'],
                'perspective' => 'Birinci Şahıs (FPS)',
                'multiplayer_modes' => ['Tek Oyunculu', 'Çok Oyunculu Arena'],
                'main_story_hours' => 11,
                'completionist_hours' => 16,
                'metacritic_score' => 88,
                'rating' => 9.2,
                'synopsis' => 'Telepatik bir komutan olan Paxton Fettel\'in kontrolündeki klonlanmış Replica özel kuvvetler taburunu durdurmakla görevlendirilen özel birimin (F.E.A.R.) ağır çekim (Slow-Mo) reflekslere sahip operatörünün hikâyesi.',
                'technical_overview' => 'Dinamik hacimsel gölgeler, parçacık fiziği, duvarlardan kopan beton tozları ve ağır çekim ses distorsiyonuyla FPS türünde çığır açan teknik altyapı.',
                'ai_architecture_summary' => 'Jeff Orkin tarafından geliştirilen GOAP (Goal-Oriented Action Planning) mimarisinin ilk büyük zaferi: Replica askerleri masaları devirip siper alır, camlardan atlayarak oyuncuyu arkadan kuşatır ve telsizle birbirlerinin durumunu gerçek zamanlı raporlar.',
                'cultural_impact' => 'Yayınlanmasının üzerinden 20 yıl geçmesine rağmen, FPS oyunlarındaki çatışma yapay zekâsı tartışmalarında hâlâ bir numaralı kıyas noktasıdır.',
                'why_play' => 'Yapay zekâ askerlerinin "El fenerini gördüm, arka koridordan dolaşıyorum, beni koru!" diyerek gerçekten koordineli bir tim gibi savaştığına tanık olmak için.',
                'poster_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=9yFp3I9M6H0',
                'pc_min_specs' => ['CPU' => 'Pentium 4 1.7 GHz', 'GPU' => 'GeForce 4 Ti / Radeon 9000', 'RAM' => '512 MB', 'SSD' => '5 GB'],
                'pc_rec_specs' => ['CPU' => 'Pentium 4 3.0 GHz', 'GPU' => 'Radeon 9800 Pro / GeForce 6600', 'RAM' => '2 GB', 'SSD' => '5 GB'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $gelistirici->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [],
            ],
            [
                'title' => 'Red Dead Redemption 2',
                'original_title' => 'Red Dead Redemption II',
                'slug' => 'red-dead-redemption-2',
                'release_year' => 2018,
                'developer_studio' => 'Rockstar Games',
                'publisher' => 'Rockstar Games',
                'game_director' => 'Rob Nelson',
                'lead_ai_programmer' => 'Rockstar RAGE AI & World Simulation Team',
                'composer' => 'Woody Jackson',
                'country' => 'ABD',
                'game_engine_id' => $engines['rage-engine']->id,
                'platforms' => ['PC', 'PlayStation 4', 'Xbox One'],
                'genres' => ['Açık Dünya', 'Aksiyon-Macera', 'Vahşi Batı (Western)', 'Simülasyon'],
                'moods' => ['Melankolik', 'Sinematik', 'Doğalcı', 'Epik'],
                'perspective' => 'Üçüncü Şahıs / Birinci Şahıs',
                'multiplayer_modes' => ['Tek Oyunculu', 'Red Dead Online'],
                'main_story_hours' => 50,
                'completionist_hours' => 185,
                'metacritic_score' => 97,
                'rating' => 9.8,
                'synopsis' => '1899 yılı Amerika\'sında Vahşi Batı çağının sonuna gelinirken, başarısız Blackwater soygununun ardından federal ajanlardan kaçan Van der Linde çetesinin kıdemli üyesi Arthur Morgan, sadakat ile kendi vicdanı arasında parçalanır.',
                'technical_overview' => 'RAGE motorunun hacimsel bulut simülasyonu, deforme olabilen kar/çamur topografyası ve Euphoria biyokinetik fizik motoruyla yaşayan bir kıta simülasyonu.',
                'ai_architecture_summary' => '200\'den fazla hayvan türünün av-avcı besin zinciriyle yaşadığı ekosistem AI\'sı ve her kasaba sakininin oyuncunun kıyafetindeki çamurdan kılıfındaki silaha kadar her detayı algılayıp bağlamsal diyalog ürettiği etkileşim mimarisi.',
                'cultural_impact' => 'Açık dünya tasarımında "dünyanın oyuncunun etrafında dönmesi" yerine "oyuncunun yaşayan bir ekosistemin parçası olması" felsefesinin zirvesi.',
                'why_play' => 'Oyun tarihinin en derin karakter yazımlarından biri olan Arthur Morgan\'ın yolculuğunu, yaşayan bir doğa ve NPC simülasyonu içinde deneyimlemek için.',
                'poster_url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=eaW0tYpxyp0',
                'pc_min_specs' => ['CPU' => 'Core i5-2500K / FX-6300', 'GPU' => 'GTX 770 / R9 280', 'RAM' => '8 GB', 'SSD' => '150 GB'],
                'pc_rec_specs' => ['CPU' => 'Core i7-4770K / Ryzen 5 1500X', 'GPU' => 'GTX 1060 6GB / RX 480', 'RAM' => '12 GB', 'SSD' => '150 GB SSD'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [
                    'euphoria-neural-physics' => 'Karakterlerin ve atların vurulma, düşme ve çarpışma anlarındaki biyomekanik kas tepkilerini gerçek zamanlı sentezler.',
                    'motion-matching' => 'Arthur Morgan ve atının engebeli arazideki ağır, fiziksel ağırlığa sahip adım geçişlerini yönetir.',
                ],
            ],
            [
                'title' => 'Middle-earth: Shadow of Mordor',
                'original_title' => 'Middle-earth: Shadow of Mordor',
                'slug' => 'middle-earth-shadow-of-mordor',
                'release_year' => 2014,
                'developer_studio' => 'Monolith Productions',
                'publisher' => 'Warner Bros. Interactive Entertainment',
                'game_director' => 'Michael de Plater',
                'lead_ai_programmer' => 'Monolith Nemesis Architecture Team',
                'composer' => 'Garry Schyman',
                'country' => 'ABD',
                'game_engine_id' => $engines['unreal-engine-5']->id,
                'platforms' => ['PC', 'PlayStation 4', 'Xbox One'],
                'genres' => ['Aksiyon RPG', 'Açık Dünya', 'Gizlilik', 'Fantezi'],
                'moods' => ['İntikam', 'Karanlık', 'Dinamik', 'Akıcı Savaş'],
                'perspective' => 'Üçüncü Şahıs (TPS)',
                'multiplayer_modes' => ['Tek Oyunculu'],
                'main_story_hours' => 16,
                'completionist_hours' => 38,
                'metacritic_score' => 84,
                'rating' => 8.9,
                'synopsis' => 'Kara Kapı\'da ailesiyle birlikte katledilen Gondor kolcusu Talion, Elf Lordu Celebrimbor\'un tayfıyla birleşerek ölümden döner ve Sauron\'un Mordor\'daki ordusunu içeriden çökertmek için kişisel bir intikam savaşına girişir.',
                'technical_overview' => 'LithTech Firebird motoru üzerinde çalışan prosedürel karakter varyasyonu, ses filtreleme ve sosyal hiyerarşi simülasyonu.',
                'ai_architecture_summary' => 'Oyun dünyasına "Nemesis Sistemi"ni kazandıran yapım: Sıradan bir Ork askeri sizi öldürürse rütbe alır, isim kazanır, önceki savaşta ona verdiğiniz yanık veya kılıç yarasını taşır ve bir sonraki karşılaşmada bununla ilgili alaycı diyaloglar kurar.',
                'cultural_impact' => 'GDC\'de Yılın İnovasyon Ödülü\'nü kazanmış ve "Emergent Narrative" (Oyuncunun Etkileşiminden Doğan Öyküleme) kavramını yeniden tanımlamıştır.',
                'why_play' => 'Senaryo yazarlarının değil, tamamen sizin yenilgilerinizin ve zaferlerinizin yarattığı baş düşmanlarla yüzleşmek için.',
                'poster_url' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=4rbOTQ2788Y',
                'pc_min_specs' => ['CPU' => 'Core i5-750 / Phenom II X4 965', 'GPU' => 'GTX 460 / HD 5850', 'RAM' => '4 GB', 'SSD' => '44 GB'],
                'pc_rec_specs' => ['CPU' => 'Core i7-3770 / FX-8350', 'GPU' => 'GTX 660 / R9 270', 'RAM' => '8 GB', 'SSD' => '44 GB SSD'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $gelistirici->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [],
            ],
            [
                'title' => 'Left 4 Dead 2',
                'original_title' => 'Left 4 Dead 2',
                'slug' => 'left-4-dead-2',
                'release_year' => 2009,
                'developer_studio' => 'Valve',
                'publisher' => 'Valve',
                'game_director' => 'Chet Faliszek, Tom Leonard',
                'lead_ai_programmer' => 'Michael Booth',
                'composer' => 'Mike Morasky',
                'country' => 'ABD',
                'game_engine_id' => $engines['unreal-engine-5']->id,
                'platforms' => ['PC', 'Xbox 360', 'macOS', 'Linux'],
                'genres' => ['Kooperatif FPS', 'Hayatta Kalma', 'Aksiyon'],
                'moods' => ['Kaotik', 'Takım Oyunu', 'Yüksek Tempo', 'Sinematik'],
                'perspective' => 'Birinci Şahıs (FPS)',
                'multiplayer_modes' => ['4 Kişilik Co-op', '8 Kişilik Versus', 'Tek Oyunculu (AI Botlarla)'],
                'main_story_hours' => 10,
                'completionist_hours' => 85,
                'metacritic_score' => 89,
                'rating' => 9.1,
                'synopsis' => 'Yeşil Grip (Green Flu) salgınının kasıp kavurduğu Amerika\'nın güney eyaletlerinde mahsur kalan dört hayatta kalan (Coach, Nick, Ellis ve Rochelle), tahliye noktalarına ulaşmak için enfekte sürüleriyle omuz omuza çarpışır.',
                'technical_overview' => 'Source motorunun prosedürel yara/uzuv kopma sistemi, dinamik ses ipuçları (leitmotif) ve düşük gecikmeli çok oyunculu ağ mimarisi.',
                'ai_architecture_summary' => 'Michael Booth\'un tasarladığı "The AI Director 2.0": Oyuncuların stres seviyesini takip ederek yalnızca zombi sürülerini, Tank ve Witch konumlarını değil; aynı zamanda haritanın koridor düzenini, fırtına/sis hava durumunu ve çalan müzik katmanlarını çalışma zamanında değiştirir.',
                'cultural_impact' => 'Kooperatif oyun tasarımında "Tekrar Oynanabilirlik (Replayability)" sorununu yapay zekâ yönetmeniyle çözen ölümsüz klasik.',
                'why_play' => 'Görünmez bir yapay zekâ yönetmeninin 4 kişilik ekibinizin nabzını nasıl tuttuğunu ve her oyunda farklı bir gerilim filmi kurguladığını görmek için.',
                'poster_url' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=N1Z9f3a3G20',
                'pc_min_specs' => ['CPU' => 'Pentium 4 3.0 GHz', 'GPU' => 'Shader Model 2.0 / 128 MB', 'RAM' => '2 GB', 'SSD' => '13 GB'],
                'pc_rec_specs' => ['CPU' => 'Intel Core 2 Duo 2.4 GHz', 'GPU' => 'GeForce 7600 / Radeon X1600', 'RAM' => '4 GB', 'SSD' => '13 GB'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $webadmin->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [],
            ],
            [
                'title' => 'Alan Wake 2',
                'original_title' => 'Alan Wake II',
                'slug' => 'alan-wake-2',
                'release_year' => 2023,
                'developer_studio' => 'Remedy Entertainment',
                'publisher' => 'Epic Games Publishing',
                'game_director' => 'Sam Lake, Kyle Rowley',
                'lead_ai_programmer' => 'Remedy Northlight AI & Encounter Team',
                'composer' => 'Petri Alanko',
                'country' => 'Finlandiya',
                'game_engine_id' => $engines['northlight-engine']->id,
                'platforms' => ['PC', 'PlayStation 5', 'Xbox Series X/S'],
                'genres' => ['Hayatta Kalma Korku', 'Psikolojik Gerilim', 'Dedektiflik'],
                'moods' => ['Sürreal', 'Karanlık', 'Meta-Anlatı', 'Sinematik'],
                'perspective' => 'Üçüncü Şahıs (Omuz Üstü)',
                'multiplayer_modes' => ['Tek Oyunculu'],
                'main_story_hours' => 21,
                'completionist_hours' => 32,
                'metacritic_score' => 89,
                'rating' => 9.2,
                'synopsis' => 'Pasifik Kuzeybatısı\'ndaki Bright Falls kasabasında işlenen ritüelistik cinayetleri araştıran FBI profil uzmanı Saga Anderson ile 13 yıldır Karanlık Boyut\'ta (Dark Place) kendi yazdığı korku romanından kaçmaya çalışan yazar Alan Wake\'in gerçeklikleri birbirine karışır.',
                'technical_overview' => 'Northlight motorunun GPU Mesh Shaders, Path Tracing ve gerçek zamanlı sahne/senaryo yeniden yazma (Plot Board Instant World Morphing) teknolojisiyle sinema ve oyunu iç içe geçiren başyapıt.',
                'ai_architecture_summary' => 'Gölge düşmanlar (Taken), oyuncunun bakış açısına ve el feneri hüzmesine göre gerçek mi yoksa halüsinasyon mu olduklarını gizleyen mekânsal algılayıcılarla hareket eder.',
                'cultural_impact' => 'The Game Awards 2023\'te En İyi Oyun Yönetimi, En İyi Anlatı ve En İyi Sanat Yönetimi ödüllerini kazanarak etkileşimli hikâye anlatımında yeni bir sayfa açtı.',
                'why_play' => 'Karanlık Boyut\'ta bir sahnenin olay örgüsünü değiştirdiğiniz anda tüm sokağın geometrisinin ve ışıklarının milisaniyeler içinde dönüşmesine tanıklık etmek için.',
                'poster_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=dlQ3FeNu5Yw',
                'pc_min_specs' => ['CPU' => 'Intel i5-7600K / Ryzen 7 3700X', 'GPU' => 'RTX 2060 / RX 6600', 'RAM' => '16 GB', 'SSD' => '90 GB SSD'],
                'pc_rec_specs' => ['CPU' => 'Ryzen 7 3700X / Core i7-10700K', 'GPU' => 'RTX 3070 / RX 6700 XT', 'RAM' => '16 GB', 'SSD' => '90 GB NVMe'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $studyo->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [
                    'nvidia-dlss-ray-reconstruction' => 'Bright Falls ormanlarındaki yoğun sis ve su birikintisi yansımalarını Path Tracing ile işler.',
                ],
            ],
        ];

        $games = [];
        foreach ($gamesData as $gData) {
            $techSlugs = $gData['tech_slugs'] ?? [];
            unset($gData['tech_slugs']);

            $game = Game::updateOrCreate(['slug' => $gData['slug']], $gData);
            $game->syncPeopleFromStrings();

            if ($techSlugs !== []) {
                $syncTech = [];
                foreach ($techSlugs as $tSlug => $notes) {
                    if (isset($technologies[$tSlug])) {
                        $syncTech[$technologies[$tSlug]->id] = ['implementation_notes' => $notes];
                    }
                }
                $game->technologies()->sync($syncTech);
            }

            $games[$game->slug] = $game;
        }

        // 6. Oyun & Yapay Zekâ Vaka Analizleri (Game AI Case Studies — game_ai_analyses)
        $aiAnalysesData = [
            [
                'game_id' => $games['alien-isolation']->id,
                'ai_system_id' => $aiSystems['dual-brain-tether-ai']->id,
                'author_id' => $gelistirici->id,
                'title' => 'Alien: Isolation — İki Beyinli Xenomorph ve Oyuncudan Öğrenen Davranış Ağacı',
                'slug' => 'alien-isolation-iki-beyinli-xenomorph-ai-analizi',
                'ai_role_in_game' => 'Tekil Ana Avcı (Stalker / Predator NPC) & Gerilim Yönetmeni',
                'architecture_breakdown' => 'Creative Assembly mühendisleri Xenomorph için iki ayrı yapay zekâ katmanı tasarladı: Oyuncunun tam koordinatlarını bilen "Director AI" ve oyuncunun yerini bilmeyen ama kendi sensörleriyle istasyonu tarayan 100+ düğümlü "Alien Behavior Tree". Director AI asla yaratığa "Oyuncu 3 numaralı dolapta, git öldür" demez; yalnızca gerilim barometresi (Menace Gauge) düştüğünde yaratığa oyuncunun bulunduğu sektörü genel arama bölgesi olarak atar.',
                'perception_and_sensors' => 'Xenomorph dört farklı duyusal girdi (Sensory Input) kullanır: 1) Birincil ve çevresel görüş konileri, 2) Koşma, silah sesi ve hareket dedektörünün "bip" sesini dahi duyan akustik sensör, 3) Açık bırakılan kapılar ve duman izleri, 4) Havalandırma kanallarındaki titreşimler.',
                'decision_loop_details' => 'Davranış ağacındaki bazı dallar oyunun başında kilitlidir (Locked Subtrees). Oyuncu yaratığı alev silahıyla (Flamethrower) ilk kez korkuttuğunda yaratık hemen kaçar; ancak oyuncu bunu 3-4 kez tekrarladığında ağaçtaki "Flamethrower Habituation" düğümü açılır: Yaratık artık alev silahını gördüğünde kaçmak yerine güvenli mesafede bekler, oyuncunun yakıtı bitene kadar blöf yapar veya arkadan saldırır.',
                'player_experience_impact' => 'Oyuncu sabit bir devriye rotası ezberleyemediği için her karşılaşma organik bir kedi-fare oyununa dönüşür. Yaratığın "öğrendiğini" fark eden oyuncu sürekli taktik değiştirmek zorunda kalır.',
                'limitations_and_tricks' => 'Yaratığın havalandırma kanallarında gerçekten sürünmesi yerine bazı uzak geçişlerde ses efekti eşliğinde kanal ağında hızlı düğüm atlaması (Node Teleportation) yapması CPU bütçesini korur.',
                'innovation_score' => 98,
                'complexity_score' => 94,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=P7d5l3fU0eQ',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'game_id' => $games['fear-first-encounter-assault-recon']->id,
                'ai_system_id' => $aiSystems['goap-goal-oriented-action-planning']->id,
                'author_id' => $gelistirici->id,
                'title' => 'F.E.A.R. — GOAP Mimarisi ve Kendi Planını Yapan Replica Klon Askerleri',
                'slug' => 'fear-goap-mimarisi-ve-plan-yapan-askerler',
                'ai_role_in_game' => 'Taktiksel Manga Çatışma Yapay Zekâsı (Squad Combat AI)',
                'architecture_breakdown' => 'Jeff Orkin, F.E.A.R.\'da geleneksel Sonlu Durum Makinelerini (FSM) yalnızca 3 temel duruma indirdi: `Goto` (Bir noktaya git), `Animate` (Bir animasyon oynat), `UseSmartObject` (Nesneyle etkileşime gir). Askerlerin hangi sırayla ne yapacağına ise STRIPS tabanlı GOAP planlayıcısı gerçek zamanlı A* aramasıyla karar verir.',
                'perception_and_sensors' => 'Askerler yalnızca kendi görüş açılarını değil, oyuncunun el fenerinin duvara vuran ışık hüzmesini, kırılan cam seslerini ve manga arkadaşlarının telsiz bildirimlerini (Squad Blackboard) ortak hafızada birleştirir.',
                'decision_loop_details' => 'Oyuncu bir odada siper aldığında GOAP planlayıcısı `KillEnemy` hedefini çözmek için şu zinciri sentezler: Asker A baskı ateşi açarken (`SuppressTarget`), Asker B el bombası atarak oyuncuyu siperden çıkarır (`FlushTarget`), Asker C ise yan koridordaki pencereden atlayarak (`VaultThroughWindow`) oyuncuyu arkadan vurur. En önemlisi, askerler yaptıkları planı telsizden sesli söyledikleri için ("Arkadan dolaşıyorum!", "El bombası atıyorum!") oyuncu yapay zekânın zekâsını doğrudan duyar.',
                'player_experience_impact' => 'Çatışmalar sinematik bir John Woo aksiyon filmi gibi hissettirir; her çatışma alanı masa devirme, raf yıkma ve cam kırma eylemleriyle dinamik olarak değişir.',
                'limitations_and_tricks' => 'GOAP\'ın başarısının sırrı yalnızca algoritmada değil, bölüm tasarımcılarının (Level Designers) odaları çoklu koridorlar, pencereler ve devrilebilir Smart Object nesneleriyle donatmasında yatar.',
                'innovation_score' => 97,
                'complexity_score' => 91,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=PaOLBOuyswI',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'game_id' => $games['baldurs-gate-3']->id,
                'ai_system_id' => $aiSystems['tactical-utility-ai']->id,
                'author_id' => $admin->id,
                'title' => 'Baldur\'s Gate 3 — D&D 5e Kurallarında Taktiksel Utility AI ve Sistemik Zekâ',
                'slug' => 'baldurs-gate-3-taktiksel-utility-ai-analizi',
                'ai_role_in_game' => 'Sıra Tabanlı Taktiksel Savaş & Sistemik Çevre Etkileşimi',
                'architecture_breakdown' => 'Baldur\'s Gate 3\'te bir NPC\'nin sırası geldiğinde kullanabileceği yüzlerce büyü, iksir, parşömen, zıplama, itme ve çevre nesnesi vardır. Larian\'ın Utility AI motoru, olası her eylem ve hareket kombinasyonu için matematiksel bir "Fayda Skoru (Utility Score)" hesaplar.',
                'perception_and_sensors' => 'AI; savaş alanındaki yükseklik farklarını, uçurum kenarlarını, yerdeki su/kan/yağ birikintilerini, ışık/gölge durumunu ve oyuncu karakterlerin Konsantrasyon (Concentration) büyülerini tam matris olarak okur.',
                'decision_loop_details' => 'Örneğin bir düşman okçusu, doğrudan hasar vermek yerine oyuncunun altında durduğu avizeyi vurup düşürmenin veya konsantrasyon büyüsü yapan büyücüyü uçurumdan aşağı itmenin (Shove) toplam fayda skorunu daha yüksek bulursa o hamleyi seçer. Ayrıca "Honor Mode" zorluğunda düşmanların eylem derinliği ve efsanevi direnç tepkileri üst düzeye çıkarılır.',
                'player_experience_impact' => 'Karşınızda gerçek bir masaüstü Dungeon Master (DM) varmış hissi uyandırır; düşmanlar sizin kurduğunuz tuzakları size karşı kullanabilir.',
                'limitations_and_tricks' => 'Çok kalabalık savaşlarda (30+ NPC) kombinatoryal patlamayı önlemek için AI, uzak veya düşük olasılıklı eylemleri erken budama (Heuristic Pruning) ile eler.',
                'innovation_score' => 95,
                'complexity_score' => 96,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=1T22wNvoNiU',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'game_id' => $games['middle-earth-shadow-of-mordor']->id,
                'ai_system_id' => $aiSystems['nemesis-procedural-social-memory']->id,
                'author_id' => $gelistirici->id,
                'title' => 'Shadow of Mordor — Nemesis Sistemi ve Prosedürel İntikam Hafızası',
                'slug' => 'shadow-of-mordor-nemesis-sistemi-analizi',
                'ai_role_in_game' => 'Prosedürel Sosyal Hiyerarşi, Kişilik ve Hafıza Sentezi',
                'architecture_breakdown' => 'Nemesis Sistemi, Sauron\'un ordusundaki her Uruk yüzbaşısını ve savaş şefini dinamik bir durum grafiğinde (Social State Graph) tutar. Siz ölürken bile zaman ilerler; yüzbaşılar birbirlerine düello açar, ziyafet düzenler ve rütbe atlar.',
                'perception_and_sensors' => 'Her komutan oyuncunun savaş stilini kaydeder: Sürekli üstten suikast yapıyorsanız bir sonraki karşılaşmada "Vault Breaker / Vigilant Against Stealth" özelliği kazanarak o hamleye bağışıklık geliştirir.',
                'decision_loop_details' => 'Karşılaşma başladığında diyalog motoru, komutanın hafıza günlüğündeki son olayı (`LastEncounter: PlayerFled`, `Scar: BurnedFace`, `KilledPlayerCount: 2`) okuyarak binlerce seslendirilmiş parçadan o ana özel bir alay konuşması birleştirir.',
                'player_experience_impact' => 'İsmini bile bilmediğiniz sıradan bir mızrakçının sizi öldürüp oyun sonu baş düşmanınıza dönüşmesi, her oyuncuya benzersiz bir kişisel öykü yaşatır.',
                'limitations_and_tricks' => 'Sistemin açık dünyadaki tüm Orkları simüle etmesi yerine yalnızca aktif bölgedeki 20-25 komutan slotunu gerçek zamanlı güncellemesi bellek bütçesini dengeler.',
                'innovation_score' => 96,
                'complexity_score' => 89,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=4rbOTQ2788Y',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'game_id' => $games['left-4-dead-2']->id,
                'ai_system_id' => $aiSystems['the-ai-director-pacing-engine']->id,
                'author_id' => $webadmin->id,
                'title' => 'Left 4 Dead 2 — The AI Director 2.0 ve Duygusal Yoğunluk Algoritması',
                'slug' => 'left-4-dead-2-ai-director-tempo-analizi',
                'ai_role_in_game' => 'Prosedürel Tempo, Düşman Üretimi, Harita Topolojisi ve Müzik Yönetmeni',
                'architecture_breakdown' => 'AI Director, her dört oyuncu için 0.0 ile 1.0 arasında bir "Emotional Intensity (Duygusal Yoğunluk)" değişkeni tutar. Oyuncu hasar aldığında, bir arkadaşı yere düştüğünde veya yakın mesafede çok sayıda enfekte öldürdüğünde bu değer yükselir; çatışma olmadığında ise zamanla azalır.',
                'perception_and_sensors' => 'Oyuncuların isabet oranı, kalan mermi sayısı, can durumu ve birbirlerinden ne kadar uzaklaştıkları (Lone Wolf Detection) sürekli izlenir.',
                'decision_loop_details' => 'Takımdan tek başına ayrılan bir oyuncuyu cezalandırmak için Hunter veya Smoker gibi özel enfekteleri o oyuncunun yakınındaki görünmez (Out-of-Sight) noktalarda üretir. Ayrıca L4D2\'de AI Director mezarlık gibi bölümlerde duvarların yerini dahi değiştirerek ezberlenmiş rotaları bozar.',
                'player_experience_impact' => 'Yorgun düştüğünüz anda bir odada ilk yardım çantası bulmanın verdiği rahatlama ile tam nefes aldığınız anda başlayan Tank müziğinin yarattığı panik tamamen matematiksel bir senfonidir.',
                'limitations_and_tricks' => 'Düşmanlar her zaman oyuncuların görüş konisinin (Frustum) hemen dışındaki köşelerde üretilerek haritanın tamamının dolu olduğu illüzyonu yaratılır.',
                'innovation_score' => 95,
                'complexity_score' => 88,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=N1Z9f3a3G20',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'game_id' => $games['cyberpunk-2077-phantom-liberty']->id,
                'ai_system_id' => $aiSystems['neural-rendering-and-generative-npc']->id,
                'author_id' => $gelistirici->id,
                'title' => 'Cyberpunk 2077 — DLSS 3.5 Ray Reconstruction ve JALI Nöral Yüz Sentezi',
                'slug' => 'cyberpunk-2077-dlss-ray-reconstruction-ve-jali-ai',
                'ai_role_in_game' => 'Nöral Görüntü İşleme, Path Tracing Gürültü Giderme ve Otomatik Yüz Animasyonu',
                'architecture_breakdown' => 'Cyberpunk 2077, yapay zekâyı hem üretim hattında (JALI) hem de çalışma zamanında (DLSS 3.5 Ray Reconstruction & Frame Generation) en yoğun kullanan AAA oyundur.',
                'perception_and_sensors' => 'Ray Reconstruction sinir ağı; sahnedeki albedo, normal vektörleri, hareket vektörleri, derinlik tamponu ve önceki karelerin geçmişini girdi olarak alır.',
                'decision_loop_details' => 'Islak Night City asfaltındaki neon tabela yansımalarını ve hareketli araç farlarının gölgelerini bulanıklaştırmadan keskinleştirmek için her karede milyonlarca matris çarpımı Tensor çekirdeklerinde yürütülür.',
                'player_experience_impact' => 'Daha önce yalnızca çevrimdışı (offline) animasyon filmlerinde görülebilen tam Path Tracing ışık fiziğini 4K çözünürlükte akıcı oynanabilir hale getirir.',
                'limitations_and_tricks' => 'Çok hızlı kamera dönüşlerinde hayaletlenme (ghosting) oluşmaması için hareket vektörü güven ağırlıkları dinamik olarak ayarlanır.',
                'innovation_score' => 96,
                'complexity_score' => 95,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=PbVKGoJh4OI',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
        ];

        foreach ($aiAnalysesData as $aData) {
            GameAiAnalysis::updateOrCreate(['slug' => $aData['slug']], $aData);
        }

        // 7. Örnek Oyun Videoları & AI Analiz Videoları (game_videos)
        $videosData = [
            [
                'game_id' => $games['baldurs-gate-3']->id,
                'user_id' => $admin->id,
                'title' => 'Baldur\'s Gate 3 — Sistemik Savaş, Taktiksel Utility AI ve Sinematik Oynanış Kesiti',
                'slug' => 'baldurs-gate-3-taktiksel-oynanis-ve-ai-kesiti',
                'video_type' => 'gameplay_sample',
                'video_url' => 'https://www.youtube.com/watch?v=1T22wNvoNiU',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '06:42',
                'resolution_tag' => '4K 60FPS',
                'description' => 'Divinity Engine 4.0 üzerinde element yüzeylerinin zincirleme reaksiyonu ve düşman yapay zekâsının yükseklik/itme mekaniklerini nasıl kullandığını gösteren örnek oynanış.',
                'technical_highlights' => 'Utility AI Puanlama, Sistemik Yüzey Kimyası, Dinamik Kamera Geçişleri',
                'is_featured' => true,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['alien-isolation']->id,
                'user_id' => $gelistirici->id,
                'title' => 'Alien: Isolation — Xenomorph Yapay Zekâsı Nasıl Düşünür? (İki Beyinli Mimari)',
                'slug' => 'alien-isolation-xenomorph-yapay-zeka-analiz-videosu',
                'video_type' => 'ai_showcase',
                'video_url' => 'https://www.youtube.com/watch?v=7h0cgmvIrZw',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '14:18',
                'resolution_tag' => '1080p 60FPS',
                'description' => 'Director AI ile Xenomorph Davranış Ağacı (Behavior Tree) arasındaki gerilim bağının ve alev silahına karşı gelişen öğrenme düğümlerinin uygulamalı analizi.',
                'technical_highlights' => 'Dual-Brain Architecture, Menace Gauge, Sensory Cone Detection',
                'is_featured' => true,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['cyberpunk-2077-phantom-liberty']->id,
                'user_id' => $webadmin->id,
                'title' => 'Cyberpunk 2077 Overdrive — Path Tracing & DLSS 3.5 Ray Reconstruction Teknik İnceleme',
                'slug' => 'cyberpunk-2077-path-tracing-dlss-35-teknik-video',
                'video_type' => 'tech_breakdown',
                'video_url' => 'https://www.youtube.com/watch?v=PbVKGoJh4OI',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '09:15',
                'resolution_tag' => '4K HDR Path Traced',
                'description' => 'REDengine 4 üzerinde geleneksel aydınlatma ile tam Path Tracing + Nöral Ray Reconstruction arasındaki yansıma ve gölge farklarının kare kare karşılaştırması.',
                'technical_highlights' => 'NVIDIA DLSS 3.5, Real-Time Path Tracing, Neural Denoising',
                'is_featured' => true,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['fear-first-encounter-assault-recon']->id,
                'user_id' => $gelistirici->id,
                'title' => 'F.E.A.R. — GOAP Planlayıcısı ve Klon Askerlerin Kuşatma Taktikleri',
                'slug' => 'fear-goap-planlayici-ve-taktik-analizi',
                'video_type' => 'ai_showcase',
                'video_url' => 'https://www.youtube.com/watch?v=9yFp3I9M6H0',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '11:30',
                'resolution_tag' => '1080p 60FPS',
                'description' => 'Jeff Orkin\'in GOAP (Goal-Oriented Action Planning) mimarisinin çatışma sırasında nasıl gerçek zamanlı A* planları ürettiğini gösteren vaka videosu.',
                'technical_highlights' => 'GOAP Regressive A* Search, Smart Objects, Squad Blackboard',
                'is_featured' => false,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['red-dead-redemption-2']->id,
                'user_id' => $studyo->id,
                'title' => 'Red Dead Redemption 2 — RAGE Motoru, Euphoria Fiziği ve Ekosistem Simülasyonu',
                'slug' => 'red-dead-redemption-2-rage-ve-euphoria-oynanis',
                'video_type' => 'gameplay_sample',
                'video_url' => 'https://www.youtube.com/watch?v=eaW0tYpxyp0',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '06:05',
                'resolution_tag' => '4K 60FPS',
                'description' => 'Vahşi yaşam besin zinciri, NPC günlük rutinleri ve Euphoria biyokinetik sinir ağının çatışma anlarındaki fiziksel tepkileri.',
                'technical_highlights' => 'RAGE Engine, Euphoria CNS Physics, Volumetric Weather',
                'is_featured' => true,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['alan-wake-2']->id,
                'user_id' => $studyo->id,
                'title' => 'Alan Wake 2 — Northlight Engine Mesh Shaders & Gerçek Zamanlı Sahne Dönüşümü',
                'slug' => 'alan-wake-2-northlight-mesh-shaders-analiz',
                'video_type' => 'tech_breakdown',
                'video_url' => 'https://www.youtube.com/watch?v=dlQ3FeNu5Yw',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '08:20',
                'resolution_tag' => '4K Path Traced',
                'description' => 'Yazar Odası (Writer\'s Room) olay örgüsü tahtasında yapılan bir değişikliğin tüm bölüm geometrisini yükleme ekranı olmadan nasıl dönüştürdüğünün teknik incelemesi.',
                'technical_highlights' => 'Northlight Mesh Shaders, Instant World State Morphing, Volumetric Fog',
                'is_featured' => false,
                'status' => 'approved',
            ],
        ];

        foreach ($videosData as $vData) {
            GameVideo::updateOrCreate(['slug' => $vData['slug']], $vData);
        }

        // 8. Oyun Ödülleri (Game Awards)
        $swen = Person::where('slug', 'swen-vincke')->first();
        $samLake = Person::where('slug', 'sam-lake')->first();

        $awardsData = [
            [
                'game_id' => $games['baldurs-gate-3']->id,
                'person_id' => $swen?->id,
                'ceremony_name' => 'The Game Awards',
                'ceremony_slug' => 'the-game-awards',
                'year' => 2023,
                'category' => 'Game of the Year (Yılın Oyunu)',
                'recipient_name' => 'Larian Studios / Swen Vincke',
                'result' => 'winner',
                'notes' => 'En İyi RPG, En İyi Topluluk Desteği ve Oyuncuların Sesi ödülleriyle birlikte 총 6 ödül kazandı.',
            ],
            [
                'game_id' => $games['baldurs-gate-3']->id,
                'person_id' => $swen?->id,
                'ceremony_name' => 'BAFTA Games Awards',
                'ceremony_slug' => 'bafta-games-awards',
                'year' => 2024,
                'category' => 'Best Game & Best Narrative',
                'recipient_name' => 'Larian Studios',
                'result' => 'winner',
                'notes' => '5 büyük küresel ödül töreninin tamamında Yılın Oyunu seçilen ilk yapım oldu.',
            ],
            [
                'game_id' => $games['alan-wake-2']->id,
                'person_id' => $samLake?->id,
                'ceremony_name' => 'The Game Awards',
                'ceremony_slug' => 'the-game-awards',
                'year' => 2023,
                'category' => 'Best Game Direction & Best Art Direction',
                'recipient_name' => 'Sam Lake & Kyle Rowley (Remedy Entertainment)',
                'result' => 'winner',
                'notes' => 'Canlı aksiyon sinema ile gerçek zamanlı oyun motorunu birleştiren vizyoner yönetim.',
            ],
            [
                'game_id' => $games['middle-earth-shadow-of-mordor']->id,
                'person_id' => null,
                'ceremony_name' => 'Game Developers Choice Awards (GDC)',
                'ceremony_slug' => 'gdc-awards',
                'year' => 2015,
                'category' => 'Game of the Year & Best Technology (Nemesis System)',
                'recipient_name' => 'Monolith Productions',
                'result' => 'winner',
                'notes' => 'Prosedürel sosyal hafıza ve Nemesis yapay zekâ mimarisiyle kazanıldı.',
            ],
        ];

        foreach ($awardsData as $aw) {
            GameAward::updateOrCreate(
                [
                    'game_id' => $aw['game_id'],
                    'ceremony_slug' => $aw['ceremony_slug'],
                    'year' => $aw['year'],
                    'category' => $aw['category'],
                ],
                $aw
            );
        }

        // 9. Geliştirici & AI Mimarı Röportajları (Interviews)
        $interviewsData = [
            [
                'title' => 'Swen Vincke ile Söyleşi: "Oyuncunun Yaratıcılığından Korkmayan Bir Yapay Zekâ ve Dünya Kurmak"',
                'slug' => 'swen-vincke-baldurs-gate-3-sistemik-tasarim-soylesisi',
                'subtitle' => 'Larian Studios kurucusu Swen Vincke, Baldur\'s Gate 3\'ün 17.000 varyasyonlu anlatı grafiğini ve Taktiksel Utility AI mimarisini anlatıyor.',
                'interviewee_name' => 'Swen Vincke',
                'interviewee_role' => 'director',
                'person_id' => $swen?->id,
                'game_id' => $games['baldurs-gate-3']->id,
                'interviewer_name' => 'Deniz Arslan (GamePL Editörü)',
                'source_publication' => 'GamePL Özel Dosya / GDC Derlemesi',
                'interview_year' => 2024,
                'featured_quote' => 'Eğer oyuncu 40 tane patlayıcı varili üst üste dizip ejderhayı tek hamlede havaya uçurmak istiyorsa, oyun motorunun ve yapay zekânın görevi bunu engellemek değil; o patlamanın fiziksel sonuçlarını dürüstçe simüle etmektir.',
                'excerpt' => 'Masaüstü D&D özgürlüğünü dijital koda dökerken karşılaşacağınız en büyük sınav, öngörülemez oyuncu kararlarına karşı yapay zekânın soğukkanlılığını korumasıdır.',
                'body' => "Soru: Baldur's Gate 3'te düşman yapay zekâsı bazen gerçek bir Dungeon Master kadar kurnaz davranıyor. Bu taktiksel zekâyı nasıl kurguladınız?\n\nCevap: Geleneksel RPG'lerde düşmanlara sabit scriptler yazılır: 'İlk turda şu büyüyü at, ikinci turda en yakın savaşçıya saldır.' Ancak sistemik bir oyunda bu çalışmaz çünkü oyuncu savaş başlamadan önce zemini buza dönüştürmüş veya merdivenleri kırmış olabilir. Bizim Utility AI sistemimiz her turda savaş alanını sıfırdan okur; düşman bir uçurumun kenarında durduğunuzu görürse elindeki en güçlü büyüyü harcamak yerine sizi tek bir tekmeyle boşluğa itmeyi tercih eder.\n\nSoru: Peki yapay zekânın fazla zeki olup oyuncuyu ezmesini nasıl engellediniz?\n\nCevap: Her yaratık tipine bir 'Bilişsel Arketip (Archetype Intelligence)' tanımladık. Sıradan bir goblin paniğe kapılıp kendi arkadaşının da bulunduğu alana ateş topu atabilirken, yüzlerce yıllık bir Lich veya Zihin Yüzücü (Mind Flayer) sizin şifacınızın konsantrasyonunu bozmak için cerrahi bir hassasiyetle hedef seçer. Yapay zekâ yalnızca matematiksel optimizasyon değildir; karakterin kişiliğini yansıtan bir oyunculuk aracıdır.",
                'cover_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200&q=80',
                'status' => 'approved',
                'submitted_by' => $webadmin->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'title' => 'Jeff Orkin & Tommy Thompson: "Neden 2005 Yapımı F.E.A.R. ve Alien: Isolation Hâlâ Aşılamadı?"',
                'slug' => 'jeff-orkin-tommy-thompson-oyun-ai-mimarisi-soylesisi',
                'subtitle' => 'GOAP mimarisinin yaratıcısı Jeff Orkin ve AI and Games araştırmacısı Dr. Tommy Thompson ile oyun yapay zekâsının altın kuralı üzerine.',
                'interviewee_name' => 'Jeff Orkin & Dr. Tommy Thompson',
                'interviewee_role' => 'ai_architect',
                'person_id' => Person::where('slug', 'jeff-orkin')->value('id'),
                'game_id' => $games['fear-first-encounter-assault-recon']->id,
                'interviewer_name' => 'Dr. Kerem Kaya',
                'source_publication' => 'GamePL AI Lab Özel Söyleşi',
                'interview_year' => 2025,
                'featured_quote' => 'Oyunlarda iyi yapay zekâ, oyuncuyu 0.1 saniyede kafasından vuran kusursuz bir nişancı botu değildir; düşüncesini oyuncuya okutabilen ve oyuncuyu zeki hissettiren illüzyon sanatıdır.',
                'excerpt' => 'Modern oyunların grafik bütçeleri katlanırken neden NPC yapay zekâları bazen 20 yıl öncesinin gerisinde kalıyor? GOAP ve İki Beyinli Xenomorph mimarisinin sırrı.',
                'body' => "Soru: F.E.A.R.'daki klon askerlerin bugün bile oyuncuları şaşırtmasının temel mühendislik sebebi nedir?\n\nCevap: Çoğu kişi sırrın yalnızca A* tabanlı GOAP planlayıcısında olduğunu sanır. Oysa GOAP madalyonun sadece bir yüzüydü; diğer yüzü 'İletişimsel Şeffaflık'tı. Askerler sessizce arkadan dolaşsaydı oyuncu sadece arkadan vurulduğunu sanıp oyunun hile yaptığını düşünürdü. Ama asker telsizden 'El fenerini gördüm, arka koridordan kuşatıyorum!' diye bağırdığında, oyuncu yapay zekânın akıl yürütme sürecine tanık oluyor.\n\nSoru: Alien: Isolation'da ise tam tersine sessiz bir avcı var. Orada adalet hissi nasıl korundu?\n\nCevap: Xenomorph'a oyuncunun koordinatlarını doğrudan verseydik oyun 5 dakikada biterdi. Bunun yerine 'Director AI' ile 'Xenomorph AI'yı birbirinden ayırdık. Director AI sahne arkasındaki gerilim yönetmenidir; Xenomorph ise gerçekten yalnız kendi gözü ve kulağıyla avlanan dürüst bir davranış ağacıdır. Oyuncu dolapta nefesini tuttuğunda hayatta kalıyorsa, bu bir script olduğu için değil, yaratığın sensör konisinin gerçekten onu ıskalaması sayesindedir.",
                'cover_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200&q=80',
                'status' => 'approved',
                'submitted_by' => $gelistirici->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
        ];

        foreach ($interviewsData as $iData) {
            Interview::updateOrCreate(['slug' => $iData['slug']], $iData);
        }

        // 10. Oyun Teknolojileri & AI Makaleleri (Articles)
        $articlesData = [
            [
                'title' => 'Sonlu Durum Makinelerinden (FSM) Üretken NPC\'lere: Oyunlarda Yapay Zekânın 30 Yıllık Evrimi',
                'slug' => 'fsm-den-uretken-npclere-oyunlarda-yapay-zekanin-evrimi',
                'subtitle' => 'Pac-Man\'in 4 satırlık hedef algoritmasından GOAP, Behavior Trees, Utility AI ve GPU üzerinde çalışan sinirsel ajanlara uzanan teknik yolculuk.',
                'category' => 'ai_deep_dive',
                'author_id' => $gelistirici->id,
                'author_name' => 'Dr. Kerem Kaya',
                'author_title' => 'Kıdemli Oyun Yapay Zekâsı Mimarı',
                'game_id' => $games['alien-isolation']->id,
                'person_id' => null,
                'reading_time_minutes' => 9,
                'excerpt' => 'Akademik yapay zekâ problemi en kısa sürede çözüp oyuncuyu yenmeyi hedeflerken, oyun yapay zekâsı oyuncuya unutulmaz bir dramatik mücadele yaşatmayı hedefler.',
                'pull_quote' => 'Satranç oynayan Deep Blue\'nun amacı Kasparov\'u ezmekti; bir oyun yapay zekâsının amacı ise oyuncuya son kurşunuyla kazandığı destansı bir zafer hikayesi armağan etmektir.',
                'body' => "Oyun dünyasında 'Yapay Zekâ (Game AI)' dendiğinde aslında birbirini tamamlayan üç dev disiplinden söz ederiz:\n\n1. Bilişsel Karar Mimarileri (Symbolic & Heuristic Decision AI): 1990'larda Half-Life'ın kullandığı Hiyerarşik Sonlu Durum Makineleri (HFSM), Halo 2 ile standartlaşan Davranış Ağaçları (Behavior Trees), F.E.A.R.'ın öncülük ettiği GOAP ve Baldur's Gate 3'ün kullandığı Taktiksel Utility AI. Bu sistemlerin ortak özelliği deterministik, hata ayıklanabilir (debuggable) ve oyun tasarımcısının sanatsal vizyonuna tam itaatkâr olmalarıdır.\n\n2. Nöral Grafik ve Fizik Sentezi (Neural Rendering & Biomechanics): NVIDIA DLSS 3.5 Ray Reconstruction, Optical Flow Frame Generation, Learned Motion Matching ve Euphoria sinirsel fizik motoru. Burada derin öğrenme modelleri NPC'nin ne söyleyeceğine değil, ışığın ıslak asfalttan nasıl yansıyacağına veya vurulan bir karakterin kaslarının nasıl kasılacağına saniyede 60 kez karar verir.\n\n3. Üretken ve Otonom Ajanlar (Generative & RL Agents): Küçük dil modelleri (SLM), ses sentezi ve Pekiştirmeli Öğrenme (Reinforcement Learning - örn. Gran Turismo Sophy) ile önceden yazılmamış durumlara doğaçlama yanıt verebilen yeni nesil mimariler.",
                'cover_url' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'title' => 'Gerçek Zamanlı Path Tracing ve Nöral Rendering: Piksel Çizmekten Piksel Tahmin Etmeye Geçiş',
                'slug' => 'gercek-zamanli-path-tracing-ve-noral-rendering-devrimi',
                'subtitle' => 'REDengine 4, Northlight ve Unreal Engine 5\'in ışık fiziğini derin öğrenme sinir ağlarıyla birleştirerek sinema kalitesini gerçek zamana taşıma serüveni.',
                'category' => 'engine_architecture',
                'author_id' => $webadmin->id,
                'author_name' => 'Deniz Arslan',
                'author_title' => 'Oyun Teknolojileri Editörü',
                'game_id' => $games['cyberpunk-2077-phantom-liberty']->id,
                'person_id' => null,
                'reading_time_minutes' => 7,
                'excerpt' => 'Geleneksel rasterizasyon teknikleri sınırlarına ulaştığında, GPU mimarisi kaba kuvvetle piksel boyamak yerine yapay zekâ ile eksik ışınları yeniden inşa etmeye başladı.',
                'pull_quote' => 'Modern bir karedeki piksellerin dörtte üçü artık geleneksel gölgelendiriciler tarafından çizilmiyor; Tensor çekirdeklerindeki eğitilmiş sinir ağları tarafından gerçek zamanlı sentezleniyor.',
                'body' => "Sinema endüstrisinde Pixar veya Weta Digital bir kareyi (frame) Path Tracing ile işlemek için saatlerce süper bilgisayar çalıştırabilir. Ancak bir video oyununun aynı fiziksel doğruluktaki ışık sıçramalarını saniyede 60 kez (yani her 16.6 milisaniyede bir) hesaplaması gerekir.\n\nCyberpunk 2077 Overdrive ve Alan Wake 2 bu imkânsız görünen denklemi 'Nöral Gürültü Giderme (Neural Ray Reconstruction)' ile çözdü. Piksel başına binlerce ışın göndermek yerine yalnızca birkaç ışın gönderilir; oluşan karıncalı (noisy) görüntü ise on binlerce yüksek çözünürlüklü referans kareyle eğitilmiş bir yapay zekâ modelinden geçirilerek ayna netliğinde 4K görüntüye dönüştürülür.",
                'cover_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
        ];

        foreach ($articlesData as $artData) {
            Article::updateOrCreate(['slug' => $artData['slug']], $artData);
        }

        // 11. Oyuncu İncelemeleri (Reviews), Koleksiyonlar ve Favori 4 Oyun
        Review::updateOrCreate(
            ['game_id' => $games['baldurs-gate-3']->id, 'user_id' => $oyuncu->id],
            [
                'author_name' => $oyuncu->name,
                'author_role' => 'Kıdemli CRPG Eleştirmeni',
                'rating' => 9.8,
                'gameplay_rating' => 9.9,
                'ai_quality_rating' => 9.6,
                'optimization_rating' => 9.2,
                'played_platform' => 'PC (RTX 4080 / 4K)',
                'hours_played' => 142,
                'title' => 'Taktiksel Zekânın ve Anlatı Özgürlüğünün Kusursuz Sentezi',
                'body' => 'Honor Mode zorluğunda oynarken düşman yapay zekâsının büyücümün konsantrasyonunu bozmak için yerdeki su birikintisini elektriklendirmesi ve ardından okçumu uçurumdan aşağı itmesi, bir video oyununda yaşadığım en unutulmaz taktiksel şoktu.',
                'contains_spoilers' => false,
                'is_editorial' => true,
            ]
        );

        Review::updateOrCreate(
            ['game_id' => $games['alien-isolation']->id, 'user_id' => $gelistirici->id],
            [
                'author_name' => $gelistirici->name,
                'author_role' => 'Oyun Yapay Zekâsı Mimarı',
                'rating' => 9.5,
                'gameplay_rating' => 9.4,
                'ai_quality_rating' => 10.0,
                'optimization_rating' => 9.6,
                'played_platform' => 'PC',
                'hours_played' => 38,
                'title' => 'Davranış Ağaçları ve Duyusal Algı Tasarımında Tarihin En İyi Dersi',
                'body' => 'Xenomorph\'un hile yapmadan, yalnızca ses ve ışık konileriyle istasyonu taraması ve alev silahı blöflerime bağışıklık kazanması oyun yapay zekâsı mühendisliğinin zirvesidir.',
                'contains_spoilers' => false,
                'is_editorial' => true,
            ]
        );

        $col1 = Collection::updateOrCreate(
            ['slug' => 'yapay-zekasiyla-cigir-acan-oyunlar'],
            [
                'user_id' => $gelistirici->id,
                'title' => 'Yapay Zekâ Mimarisiyle Çığır Açan Başyapıtlar',
                'curator_name' => $gelistirici->name,
                'description' => 'GOAP, İki Beyinli Avcı, Nemesis Sosyal Hafıza ve Taktiksel Utility AI ile oyun tarihini değiştiren seçki.',
                'accent_color' => 'emerald',
                'is_public' => true,
            ]
        );
        $col1->games()->sync([
            $games['alien-isolation']->id,
            $games['fear-first-encounter-assault-recon']->id,
            $games['middle-earth-shadow-of-mordor']->id,
            $games['baldurs-gate-3']->id,
            $games['left-4-dead-2']->id,
        ]);

        // Oyuncu Günlüğü ve Favori 4 Oyun
        GameLog::updateOrCreate(
            ['user_id' => $oyuncu->id, 'game_id' => $games['baldurs-gate-3']->id],
            [
                'play_status' => 'mastered',
                'platform' => 'PC',
                'hours_played' => 165,
                'completed_on' => now()->subDays(12),
                'personal_rating' => 9.8,
                'notes' => 'Honor Mode altın zar (Golden Dice) başarımı tamamlandı.',
            ]
        );

        GameLog::updateOrCreate(
            ['user_id' => $oyuncu->id, 'game_id' => $games['cyberpunk-2077-phantom-liberty']->id],
            [
                'play_status' => 'playing',
                'platform' => 'PC',
                'hours_played' => 64,
                'personal_rating' => 9.5,
                'notes' => 'Dogtown Netrunner + Katana build ile Path Tracing açık deneyimleniyor.',
            ]
        );

        $oyuncu->favoriteGames()->sync([
            $games['baldurs-gate-3']->id => ['rank' => 1],
            $games['red-dead-redemption-2']->id => ['rank' => 2],
            $games['alien-isolation']->id => ['rank' => 3],
            $games['cyberpunk-2077-phantom-liberty']->id => ['rank' => 4],
        ]);

        $this->call(Faz2ExpansionSeeder::class);
        $this->call(GamingHardwareSeeder::class);
    }
}
