<?php

namespace Database\Seeders;

use App\Models\AiSystem;
use App\Models\Collection;
use App\Models\Game;
use App\Models\GameAiAnalysis;
use App\Models\GameEngine;
use App\Models\GameVideo;
use App\Models\Person;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Seeder;

class Faz2ExpansionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gamepl.local')->firstOrFail();
        $webadmin = User::where('email', 'webadmin@gamepl.local')->firstOrFail();
        $studyo = User::where('email', 'studyo@gamepl.local')->firstOrFail();
        $gelistirici = User::where('email', 'gelistirici@gamepl.local')->firstOrFail();
        $oyuncu = User::where('email', 'oyuncu@gamepl.local')->firstOrFail();

        // 1. Yeni Oyun Motorları (Toplam 8 Motora Tamamlama)
        $source2 = GameEngine::updateOrCreate(
            ['slug' => 'source-2'],
            [
                'name' => 'Source 2',
                'developer_company' => 'Valve Corporation',
                'release_year' => 2015,
                'license_model' => 'Tescilli (Valve Özel Motoru & Workshop SDK)',
                'primary_languages' => ['C++', 'Lua', 'C# (S&box)'],
                'key_technologies' => ['Rubikon Physics Engine', 'AnimGraph2', 'Steam Audio Spatial Ray-Tracing', 'Sub-Tick Network Architecture'],
                'ai_capabilities' => 'VR ve FPS ortamlarında milimetrik uzamsal ses/görüş algısı, fiziksel nesne etkileşimli Combine manga yapay zekâsı ve dinamik diyalog yönlendirme.',
                'description' => 'Half-Life: Alyx, Counter-Strike 2 ve Dota 2\'ye güç veren; gerçek zamanlı Rubikon fizik simülasyonu ve düşük gecikmeli VR/FPS etkileşiminde endüstri lideri motor.',
                'official_url' => 'https://www.valvesoftware.com',
            ]
        );

        $fromEngine = GameEngine::updateOrCreate(
            ['slug' => 'fromsoftware-dantelion-engine'],
            [
                'name' => 'FromSoftware Dantelion Engine',
                'developer_company' => 'FromSoftware',
                'release_year' => 2015,
                'license_model' => 'Tescilli (FromSoftware Stüdyo İçi Motor)',
                'primary_languages' => ['C++', 'Havok Script / Lua'],
                'key_technologies' => ['Frame-Exact Hitbox & Hurtbox System', 'Input-Queue Action State Machine', 'Seamless Open-Field Streaming'],
                'ai_capabilities' => 'Oyuncunun girdi kuyruğunu (Heal/Roll Input Reading), mesafesini ve duruş kırılmasını (Posture/Stance Break) milisaniyelik kare hassasiyetiyle okuyan çok fazlı Boss Davranış Ağaçları.',
                'description' => 'Bloodborne, Sekiro ve Elden Ring gibi aksiyon-RPG başyapıtlarının kare hassasiyetindeki (frame-exact) savaş mekaniklerini ve devasa boss karşılaşmalarını çalıştıran özel motor.',
                'official_url' => 'https://www.fromsoftware.jp',
            ]
        );

        $decima = GameEngine::where('slug', 'decima-engine')->firstOrFail();
        $unreal5 = GameEngine::where('slug', 'unreal-engine-5')->firstOrFail();

        // 2. Yeni Oyun Teknolojileri (Toplam 10 Teknolojiye Tamamlama)
        $extraTechs = [
            [
                'name' => 'Rubikon VR Fizik ve Sıvı/Kumaş Simülasyonu',
                'slug' => 'rubikon-vr-physics',
                'category' => 'physics',
                'vendor' => 'Valve Corporation',
                'introduced_year' => 2020,
                'summary' => 'Sanal gerçeklikte (VR) oyuncunun ellerinin her kabloya, kapıya, cam şişeye ve düşman zırhına gerçek fiziksel kütle ve sürtünmeyle temas etmesini sağlayan fizik motoru.',
                'technical_deep_dive' => 'Şişelerin içindeki sıvının çalkalanmasını (shader + atalet momenti) ve sanal ellerin katı nesnelerin içinden geçmeden kuvvet geri bildirimi üretmesini hesaplar.',
                'ai_integration_notes' => 'Combine askerleri oyuncunun fiziksel olarak kapıyı aralamasını veya el bombasını havada yakalayıp geri fırlatmasını gerçek zamanlı algılar.',
            ],
            [
                'name' => 'NVIDIA ACE (Avatar Cloud Engine) & Küçük Dil Modeli (SLM) NPC\'ler',
                'slug' => 'nvidia-ace-generative-npc',
                'category' => 'upscaling_ai',
                'vendor' => 'NVIDIA',
                'introduced_year' => 2024,
                'summary' => 'Oyuncunun mikrofondan konuştuğu sesi metne çeviren (Riva ASR), karakterin kişilik hafızasıyla yanıt üreten (Nemotron SLM) ve yüz kaslarını canlandıran (Audio2Face) uçtan uca üretken AI.',
                'technical_deep_dive' => 'Yerel GPU Tensor çekirdeklerinde veya bulutta çalışan mikro-servis mimarisi; sabit diyalog ağaçları yerine oyun evreni kurallarıyla sınırlandırılmış (Guardrailed RAG) dinamik sohbet üretir.',
                'ai_integration_notes' => 'Geleneksel senaryolu RPG diyaloglarıyla açık uçlu üretken dil modellerini birleştiren hibrit NPC mimarisi.',
            ],
            [
                'name' => 'Asenkron Sosyal Strand Ağı ve Topografik Arazi Aşınması',
                'slug' => 'asynchronous-social-strand-terrain',
                'category' => 'procedural',
                'vendor' => 'Kojima Productions',
                'introduced_year' => 2019,
                'summary' => 'Farklı oyuncuların aynı dağ yamacında yürüdükçe kayaları ezip gerçek patikalar oluşturduğu ve kurdukları köprü/merdivenlerin diğer oyuncuların dünyasında belirdiği topografik ağ.',
                'technical_deep_dive' => 'Arazi yükseklik haritası (Heightmap) ve malzeme pürüzlülüğü, küresel oyuncu ayak izi yoğunluk ısı haritasına (Heatmap) göre dinamik olarak aşındırılır.',
                'ai_integration_notes' => 'MULE kargo avcıları ve BT varlıkları, oyuncuların oluşturduğu bu yeni patikaları ve köprüleri kendi navigasyon ağlarına (NavMesh) dahil eder.',
            ],
            [
                'name' => 'Binaural 3D Spatial Ray-Traced Audio (Akustik Işın İzleme)',
                'slug' => 'binaural-spatial-ray-traced-audio',
                'category' => 'audio',
                'vendor' => 'Valve Steam Audio / Dolby / Sony Tempest 3D',
                'introduced_year' => 2019,
                'summary' => 'Ses dalgalarının tıpkı ışık ışınları gibi duvarlardan sekmesini, kalın beton arkasında boğuklaşmasını ve koridor köşelerinden kırılmasını (diffraction) simüle eden akustik teknoloji.',
                'technical_deep_dive' => 'Sahne geometrisine binlerce ses ışını gönderilerek erken yansımalar (early reflections) ve yankı sönümlenmesi HRTF (Head-Related Transfer Function) ile iki kulağa ayrı fazlarda iletilir.',
                'ai_integration_notes' => 'Gizlilik (Stealth) oyunlarında hem oyuncunun hem de NPC\'lerin sesin doğrudan yönünü değil, koridordan seken akustik yolunu duymasını sağlar.',
            ],
        ];

        $techMap = [];
        foreach ($extraTechs as $tData) {
            $techMap[$tData['slug']] = Technology::updateOrCreate(['slug' => $tData['slug']], $tData);
        }
        foreach (Technology::all() as $t) {
            $techMap[$t->slug] = $t;
        }

        // 3. Yeni Yapay Zekâ Sistemleri (Toplam 8 AI Paradigmasına Tamamlama)
        $extraAiSystems = [
            [
                'name' => 'A-Life & Offline/Online Ekosistem Simülasyonu (Yaşayan Bölge AI\'sı)',
                'slug' => 'a-life-ecosystem-simulation',
                'paradigm' => 'emergent_social',
                'invented_by_or_origin' => 'Dmitriy Iassenev & GSC Game World (S.T.A.L.K.E.R.)',
                'first_notable_game' => 'S.T.A.L.K.E.R.: Shadow of Chernobyl',
                'first_year' => 2007,
                'complexity_level' => 'Çok Yüksek (Çift Katmanlı Çevrimdışı Graf + Çevrimiçi Taktik AI)',
                'concept_summary' => 'Oyuncunun görüş alanında olmayan yüzlerce NPC ve mutantın dahi tüm harita genelinde kendi hedefleriyle seyahat ettiği, ganimet topladığı, kamp kurduğu ve birbirleriyle savaştığı otonom ekosistem simülasyonu.',
                'how_it_works' => "A-Life iki katmanda çalışır:\n1. Offline Simulation (Çevrimdışı Makro Katman): Oyuncudan kilometrelerce uzaktaki NPC ve yaratıklar, 3B fizik ve animasyon yükü olmadan düşük maliyetli bir graf (navigation graph) üzerinde hareket eder, bölgeleri ele geçirir ve ticaret yapar.\n2. Online Simulation (Çevrimiçi Mikro Katman): Oyuncu bir NPC grubuna belirli bir yarıçap (örn. 150 metre) kadar yaklaştığında, o ajanlar anında tam 3B fiziksel dünyaya ('Online' moda) aktarılır ve GOAP/FSM tabanlı taktiksel çatışma yapay zekâsıyla hareket etmeye başlar.",
                'pros_and_cons' => 'Avantajları: Dünyanın gerçekten oyuncudan bağımsız yaşadığı hissini en güçlü veren mimaridir; oyuncu bir göreve gittiğinde hedefindeki karakterin yolda bir mutant sürüsü tarafından çoktan öldürülmüş olduğunu görebilir. Dezavantajları: Serbest bırakıldığında ana hikâye NPC\'lerini bile öldürebildiği için görev korumaları (quest locks) gerektirir.',
                'pseudocode_or_diagram' => "ForEach Agent in ZonePopulation (N = 1200):\n  if Distance(Agent, Player) > SWITCH_RADIUS (150m):\n    UpdateOfflineGraphTravel(Agent, GlobalTerrainGraph, ProbabilisticCombat)\n  else:\n    Materialize3DEntity(Agent) -> ExecuteTacticalGOAP(Sight, Sound, Morale, Cover)",
            ],
            [
                'name' => 'Deep Reinforcement Learning & Multi-Agent League Training (AlphaStar & GT Sophy)',
                'slug' => 'deep-reinforcement-learning-alphastar-sophy',
                'paradigm' => 'reinforcement_learning',
                'invented_by_or_origin' => 'Google DeepMind (AlphaStar) & Sony AI (Gran Turismo Sophy)',
                'first_notable_game' => 'StarCraft II / Gran Turismo 7',
                'first_year' => 2019,
                'complexity_level' => 'Uç Düzey (Transformer + LSTM + Pekiştirmeli Öğrenme Lig Eğitimi)',
                'concept_summary' => 'El yazısı kurallar (if-else, Behavior Tree) olmadan, derin sinir ağlarının kendi kendisiyle yüzlerce yıllık oyun oynayarak (Self-Play) Dünya Şampiyonu e-sporcuları (Grandmaster) yenebilecek strateji ve refleks geliştirmesi.',
                'how_it_works' => "1. Taklit Öğrenimi (Imitation Learning): İlk aşamada milyonlarca gerçek oyuncu tekrar kaydı (replay) incelenerek temel stratejiler öğrenilir.\n2. Çok Ajanlı Lig Eğitimi (Multi-Agent League RL): Tek bir ajan yerine; 'Ana Ajanlar (Main Agents)', 'Sömürücü Ajanlar (Main Exploiters - ana ajanın zayıf noktasını bulmaya çalışanlar)' ve 'Lig Sömürücüleri'nden oluşan bir ligde milyarlarca adım eğitim yapılır.\n3. İnsanî Sınırlamalar (Fairness Constraints): AlphaStar'ın insanüstü tıklama hızıyla (APM) kazanmasını önlemek için kamera görüş alanı ve dakikadaki eylem sayısı profesyonel insan sınırlarına kısıtlanır.",
                'pros_and_cons' => 'Avantajları: Eksik bilgiye (Savaş Sisi / Fog of War) sahip gerçek zamanlı strateji ve simülasyon oyunlarında insan ötesi yaratıcı taktikler keşfeder. Dezavantajları: Eğitimi devasa TPU/GPU kümeleri gerektirir.',
                'pseudocode_or_diagram' => "Observation(Minimap, VisibleUnits, Resources) -> [Spatial Transformer + Deep LSTM Core]\n  -> PointerNetworkAction(ActionType, SelectedUnits, TargetCoord, Delay)\n  -> Reward = WinLossOutcome + PseudoReward(HumanStrategySimilarity)",
            ],
        ];

        $aiMap = [];
        foreach ($extraAiSystems as $aiData) {
            $aiMap[$aiData['slug']] = AiSystem::updateOrCreate(['slug' => $aiData['slug']], $aiData);
        }
        foreach (AiSystem::all() as $ai) {
            $aiMap[$ai->slug] = $ai;
        }

        // 4. 8 Yeni Kült ve Modern Oyun (Toplamı 15 Oyuna Tamamlama)
        $extraGamesData = [
            [
                'title' => 'Elden Ring',
                'original_title' => 'ELDEN RING',
                'slug' => 'elden-ring',
                'release_year' => 2022,
                'developer_studio' => 'FromSoftware',
                'publisher' => 'Bandai Namco Entertainment',
                'game_director' => 'Hidetaka Miyazaki, Yui Tanimura',
                'lead_ai_programmer' => 'FromSoftware Combat & Boss AI Team',
                'composer' => 'Tsukasa Saitoh, Yuka Kitamura',
                'country' => 'Japonya',
                'game_engine_id' => $fromEngine->id,
                'platforms' => ['PC', 'PlayStation 5', 'PlayStation 4', 'Xbox Series X/S', 'Xbox One'],
                'genres' => ['Aksiyon RPG', 'Souls-like', 'Açık Dünya', 'Karanlık Fantezi'],
                'moods' => ['Destansı', 'Acımasız', 'Gizemli', 'Melankolik'],
                'perspective' => 'Üçüncü Şahıs (TPS)',
                'multiplayer_modes' => ['Tek Oyunculu', 'Çevrimiçi Co-op Çağrı', 'PvP İstila & Arena'],
                'main_story_hours' => 60,
                'completionist_hours' => 135,
                'metacritic_score' => 96,
                'rating' => 9.6,
                'synopsis' => 'Kırılış (The Shattering) savaşının ardından parçalanan Elden Yüzüğü\'nün (Elden Ring) kudretini ele geçiren Yarı-Tanrılarla yüzleşmek üzere Aradaki Topraklar\'a (Lands Between) geri çağrılan bir Tarnished\'in (Lekeli) Elden Lordu olma yolculuğu.',
                'technical_overview' => 'FromSoftware Dantelion motorunun devasa dikey zindanları (Legacy Dungeons) açık dünyayla yükleme ekranı olmadan birleştiren akış mimarisi ve kare-hassasiyetli (frame-exact) çarpışma kutuları.',
                'ai_architecture_summary' => 'Margit, Malenia ve Messmer gibi bossların oyuncunun konumuna, iyileşme iksiri (Flask) basma girdisine ve yuvarlanma yönüne (Roll-Catching Delayed Attacks) göre saldırı zincirini anında uzatan veya yarıda kesen dallanmalı durum makineleri.',
                'cultural_impact' => '300\'den fazla Yılın Oyunu (GOTY) ödülü kazanarak Souls-like türünü niş bir alt türden küresel ana akım феноmenine dönüştürdü.',
                'why_play' => 'Ezberlenmiş yuvarlanma reflekslerinizi boşa çıkaran gecikmeli boss yapay zekâsına karşı zafer kazanmanın eşsiz tatmini için.',
                'poster_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=E3Huy2cdih0',
                'pc_min_specs' => ['CPU' => 'Intel Core i5-8400 / Ryzen 3 3300X', 'GPU' => 'GTX 1060 3GB / RX 580 4GB', 'RAM' => '12 GB', 'SSD' => '60 GB'],
                'pc_rec_specs' => ['CPU' => 'Intel Core i7-8700K / Ryzen 5 3600X', 'GPU' => 'GTX 1070 8GB / RX Vega 56', 'RAM' => '16 GB', 'SSD' => '60 GB SSD'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [
                    'motion-matching' => 'Bossların oyuncunun açısına göre saldırı animasyonunu orta karede bükerek yön değiştirmesi.',
                ],
            ],
            [
                'title' => 'S.T.A.L.K.E.R.: Shadow of Chernobyl',
                'original_title' => 'S.T.A.L.K.E.R.: Тінь Чорнобиля',
                'slug' => 'stalker-shadow-of-chernobyl',
                'release_year' => 2007,
                'developer_studio' => 'GSC Game World',
                'publisher' => 'THQ',
                'game_director' => 'Anton Bolshakov',
                'lead_ai_programmer' => 'Dmitriy Iassenev',
                'composer' => 'MoozE (Vladimir Frey)',
                'country' => 'Ukrayna',
                'game_engine_id' => $unreal5->id,
                'platforms' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch'],
                'genres' => ['Hayatta Kalma FPS', 'Immersive Sim', 'Açık Dünya', 'Korku'],
                'moods' => ['Kasvetli', 'Radyoaktif', 'Yalnız', 'Öngörülemez'],
                'perspective' => 'Birinci Şahıs (FPS)',
                'multiplayer_modes' => ['Tek Oyunculu'],
                'main_story_hours' => 22,
                'completionist_hours' => 55,
                'metacritic_score' => 82,
                'rating' => 9.3,
                'synopsis' => 'Çernobil Nükleer Santrali çevresindeki ikinci bir patlamanın ardından fizik kurallarının bozulduğu "Bölge (The Zone)" içinde hafızasını kaybetmiş bir Stalker ("Marked One"), sadece kolundaki PDA\'da yazan "Strelok\'u Öldür" notunun peşine düşer.',
                'technical_overview' => 'X-Ray Engine\'in ertelenmiş aydınlatma (Deferred Shading), balistik mermi düşüş/sekme fiziği ve dinamik hava/anomali döngüsü.',
                'ai_architecture_summary' => 'Dmitriy Iassenev\'in geliştirdiği efsanevi "A-Life" ekosistemi: Haritadaki yüzlerce Stalker ve mutant, oyuncunun görüş alanında olmasalar dahi ("Offline AI") bölgeler arası seyahat eder, anomali tarar, ganimet toplar ve kamp ateşinin başında gitar çalar.',
                'cultural_impact' => 'Açık dünya oyunlarında NPC\'lerin "oyuncu yaklaşınca doğan kuklalar" değil, kendi yaşam mücadelesi olan özerk ajanlar olabileceğini kanıtlayan kült başyapıt.',
                'why_play' => 'Sizin hiçbir müdahaleniz olmadan iki fraksiyonun gece karanlığında çarpıştığına ve hayatta kalan NPC\'nin ölenlerin silahlarını yağmaladığına tanık olmak için.',
                'poster_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=kX8o2a2gY4U',
                'pc_min_specs' => ['CPU' => 'Pentium 4 2.0 GHz / Athlon XP 2200+', 'GPU' => 'GeForce 5700 / Radeon 9600', 'RAM' => '512 MB', 'SSD' => '10 GB'],
                'pc_rec_specs' => ['CPU' => 'Intel Core 2 Duo E6400', 'GPU' => 'GeForce 7900 / Radeon X1950', 'RAM' => '2 GB', 'SSD' => '10 GB'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $gelistirici->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [],
            ],
            [
                'title' => 'The Last of Us Part II',
                'original_title' => 'The Last of Us Part II',
                'slug' => 'the-last-of-us-part-2',
                'release_year' => 2020,
                'developer_studio' => 'Naughty Dog',
                'publisher' => 'Sony Interactive Entertainment',
                'game_director' => 'Neil Druckmann, Anthony Newman, Kurt Margenau',
                'lead_ai_programmer' => 'Travis McIntosh, Ming-Lun "Allen" Chou',
                'composer' => 'Gustavo Santaolalla, Mac Quayle',
                'country' => 'ABD',
                'game_engine_id' => $decima->id,
                'platforms' => ['PlayStation 4', 'PlayStation 5', 'PC'],
                'genres' => ['Aksiyon-Macera', 'Gizlilik (Stealth)', 'Hayatta Kalma'],
                'moods' => ['Çarpıcı', 'Gerilimli', 'Duygusal', 'Gerçekçi'],
                'perspective' => 'Üçüncü Şahıs (TPS)',
                'multiplayer_modes' => ['Tek Oyunculu', 'No Return (Roguelike Modu)'],
                'main_story_hours' => 25,
                'completionist_hours' => 45,
                'metacritic_score' => 93,
                'rating' => 9.4,
                'synopsis' => 'Salgın sonrası Amerika\'da Jackson kasabasındaki huzurlu yaşamları şiddetli bir trajediyle yıkılan Ellie, adaleti kendi elleriyle sağlamak ve intikam almak için Seattle\'ın savaş halindeki iki grubu (WLF ve Seraphites) arasına dalar.',
                'technical_overview' => 'Motion Matching animasyon devrimi, gerçek zamanlı kalp atışı/nefes sistemi ve her düşmanın şahsi ismine sahip olduğu sinematik ses/fizik entegrasyonu.',
                'ai_architecture_summary' => 'WLF askerleri birbirlerine gerçek isimleriyle seslenir, öldürülen arkadaşlarını ya da köpeklerini gördüklerinde duygusal şok/öfke durumuna geçer; Seraphite tarikatı ise ıslık kodlarıyla (Whistle Language) oyuncunun konumunu birbirine iletir.',
                'cultural_impact' => 'Motion Matching ve takım içi sesli koordinasyon yapay zekâsıyla gizlilik-aksiyon (Stealth-Action) türünde teknik çıtayı zirveye taşıdı.',
                'why_play' => 'Çimenlerin arasında sürünürken düşmanların ıslıklarla ve el işaretleriyle etrafınızı nasıl daralttığını nefesinizi tutarak deneyimlemek için.',
                'poster_url' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=qPNiIeKMHyg',
                'pc_min_specs' => ['CPU' => 'Core i5-8600 / Ryzen 5 3600', 'GPU' => 'RTX 2060 / RX 5700', 'RAM' => '16 GB', 'SSD' => '100 GB SSD'],
                'pc_rec_specs' => ['CPU' => 'Core i7-10700K / Ryzen 7 5800X', 'GPU' => 'RTX 3070 / RX 6800', 'RAM' => '16 GB', 'SSD' => '100 GB NVMe'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $studyo->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [
                    'motion-matching' => 'Ellie ve düşmanların dar koridorlarda, suya girerken ve yüzüstü yatarken kesintisiz animasyon geçişi yapmasını sağlar.',
                    'binaural-spatial-ray-traced-audio' => 'Seraphite ıslıklarının ve Clicker ekolokasyon seslerinin hangi odadan sektiğini 3B olarak hesaplar.',
                ],
            ],
            [
                'title' => 'Half-Life: Alyx',
                'original_title' => 'Half-Life: Alyx',
                'slug' => 'half-life-alyx',
                'release_year' => 2020,
                'developer_studio' => 'Valve',
                'publisher' => 'Valve',
                'game_director' => 'Robin Walker',
                'lead_ai_programmer' => 'Valve Source 2 AI & VR Team',
                'composer' => 'Mike Morasky',
                'country' => 'ABD',
                'game_engine_id' => $source2->id,
                'platforms' => ['PC VR (SteamVR / Index / Quest / Vive)'],
                'genres' => ['VR Birinci Şahıs Nişancı', 'Bilim Kurgu', 'Hayatta Kalma Korku'],
                'moods' => ['Sürükleyici', 'Gerilimli', 'Fiziksel', 'Distopik'],
                'perspective' => 'Tam Sanal Gerçeklik (Room-Scale VR)',
                'multiplayer_modes' => ['Tek Oyunculu'],
                'main_story_hours' => 12,
                'completionist_hours' => 18,
                'metacritic_score' => 93,
                'rating' => 9.5,
                'synopsis' => 'Half-Life 2\'deki olaylardan beş yıl önce, Alyx Vance ve babası Eli, Şehir 17\'yi işgal eden uzaylı Combine imparatorluğunun Karantina Bölgesi\'ndeki gizli süper silah mahzenine (The Vault) sızmak için mücadele eder.',
                'technical_overview' => 'Source 2 ve Rubikon fizik motoruyla dünyadaki her çekmecenin, şişenin, radyonun ve silah şarjörünün gerçek el hareketleriyle manipüle edildiği VR standardı.',
                'ai_architecture_summary' => 'Kör ama sese aşırı duyarlı "Jeff" bölümündeki akustik avcı yapay zekâsı: Oyuncu sanal gerçeklikte eliyle ağzını fiziksel olarak kapatmazsa Alyx öksürür ve Jeff anında o noktaya saldırır.',
                'cultural_impact' => 'Sanal gerçekliğin (VR) yalnızca küçük teknoloji demolarından ibaret olmadığını, tam teşekküllü bir AAA başyapıt taşıyabileceğini kanıtlayan zirve nokta.',
                'why_play' => 'Karanlık bir odada şarjörünüzü eliniz titreyerek fiziksel olarak silaha takmaya çalışırken Combine askerlerinin kapıyı kırmasını yaşamak için.',
                'poster_url' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=O2W0N3uKXmo',
                'pc_min_specs' => ['CPU' => 'Core i5-7500 / Ryzen 5 1600', 'GPU' => 'GTX 1060 6GB / RX 580 8GB', 'RAM' => '12 GB', 'SSD' => '67 GB'],
                'pc_rec_specs' => ['CPU' => 'Core i7-9700K / Ryzen 7 3700X', 'GPU' => 'RTX 2070 Super / RX 6700 XT', 'RAM' => '16 GB', 'SSD' => '67 GB NVMe'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $webadmin->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [
                    'rubikon-vr-physics' => 'Her nesnenin ağırlık merkezi, kırılma direnci ve sıvı çalkalanması gerçek zamanlı hesaplanır.',
                    'binaural-spatial-ray-traced-audio' => 'Steam Audio ile ayak sesleri ve kırılan şişe yankıları 360 derece HRTF ile işlenir.',
                ],
            ],
            [
                'title' => 'Death Stranding: Director\'s Cut',
                'original_title' => 'DEATH STRANDING',
                'slug' => 'death-stranding-directors-cut',
                'release_year' => 2019,
                'developer_studio' => 'Kojima Productions',
                'publisher' => 'Sony Interactive Entertainment / 505 Games',
                'game_director' => 'Hideo Kojima',
                'lead_ai_programmer' => 'Kojima Productions & Guerrilla Decima Team',
                'composer' => 'Ludvig Forssell (ve Low Roar)',
                'country' => 'Japonya',
                'game_engine_id' => $decima->id,
                'platforms' => ['PlayStation 4', 'PlayStation 5', 'PC', 'macOS'],
                'genres' => ['Aksiyon', 'Açık Dünya', 'Asenkron Sosyal "Strand" Oyunu', 'Bilim Kurgu'],
                'moods' => ['Yalnız', 'Felsefi', 'Sinematik', 'Atmosferik'],
                'perspective' => 'Üçüncü Şahıs (TPS)',
                'multiplayer_modes' => ['Tek Oyunculu + Asenkron Sosyal Strand Ağı'],
                'main_story_hours' => 42,
                'completionist_hours' => 115,
                'metacritic_score' => 86,
                'rating' => 9.1,
                'synopsis' => 'Ölüm Kıyısı (Death Stranding) felaketiyle yaşayanlar ve ölüler dünyasının birbirine karıştığı kıyamet sonrası Amerika\'da, efsanevi kurye Sam Porter Bridges izole şehirleri Kiral Ağ (Chiral Network) ile yeniden birbirine bağlamak için kıtayı yürüyerek aşar.',
                'technical_overview' => 'Decima motorunun fotogrametri tabanlı volkanik bazalt arazileri, gerçek zamanlı kütle merkezi/denge fiziği ve asenkron çok oyunculu arazi deformasyonu.',
                'ai_architecture_summary' => 'Sırtınızdaki kargonun ağırlık dağılımına göre her adımın kayma riskini hesaplayan biyomekanik denge kontrolörü ve kargo sinyallerini tarayarak pusu kuran MULE avcı grupları.',
                'cultural_impact' => '"Yürüme eyleminin kendisini" derin bir fiziksel ve taktiksel oyun mekaniğine dönüştüren en cesur AAA tasarım deneyi.',
                'why_play' => 'Fırtınalı bir dağ zirvesini aştıktan sonra vadiye inerken başka bir oyuncunun bıraktığı ipi bulmanın ve Low Roar müziğinin girmesinin verdiği benzersiz duygu için.',
                'poster_url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=tCI396HyhbQ',
                'pc_min_specs' => ['CPU' => 'Core i5-3470 / Ryzen 3 1200', 'GPU' => 'GTX 1050 3GB / RX 560 4GB', 'RAM' => '8 GB', 'SSD' => '80 GB'],
                'pc_rec_specs' => ['CPU' => 'Core i7-3770 / Ryzen 5 1600', 'GPU' => 'GTX 1060 6GB / RX 590', 'RAM' => '8 GB', 'SSD' => '80 GB SSD'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $studyo->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [
                    'asynchronous-social-strand-terrain' => 'Binlerce oyuncunun yürüdüğü rotalar zamanla kayalıkları aşındırarak gerçek patikalara dönüşür.',
                ],
            ],
            [
                'title' => 'Disco Elysium: The Final Cut',
                'original_title' => 'Disco Elysium',
                'slug' => 'disco-elysium-the-final-cut',
                'release_year' => 2019,
                'developer_studio' => 'ZA/UM',
                'publisher' => 'ZA/UM',
                'game_director' => 'Robert Kurvitz',
                'lead_ai_programmer' => 'ZA/UM Narrative Dialogue Graph Team',
                'composer' => 'British Sea Power',
                'country' => 'Estonya',
                'game_engine_id' => $unreal5->id,
                'platforms' => ['PC', 'PlayStation 5', 'PlayStation 4', 'Xbox Series X/S', 'Nintendo Switch'],
                'genres' => ['İzometrik CRPG', 'Dedektiflik', 'Psikolojik Anlatı'],
                'moods' => ['Melankolik', 'Politik', 'Kara Mizah', 'Edebi'],
                'perspective' => 'İzometrik',
                'multiplayer_modes' => ['Tek Oyunculu'],
                'main_story_hours' => 24,
                'completionist_hours' => 46,
                'metacritic_score' => 97,
                'rating' => 9.6,
                'synopsis' => 'Revachol şehrinin yıkık dökük Martinaise bölgesinde, bir otel odasında adını bile hatırlamayacak kadar ağır bir hafıza kaybıyla uyanan dedektif Harrier Du Bois, ortağı Teğmen Kim Kitsuragi ile birlikte linç edilmiş bir cesedin sırrını çözmeye çalışır.',
                'technical_overview' => '1.2 milyon kelimelik seslendirilmiş metin ağını ve 24 farklı içsel kişilik yeteneğinin (Skills) gerçek zamanlı araya girmesini yöneten articy:draft + Unity diyalog motoru.',
                'ai_architecture_summary' => 'Geleneksel savaş yapay zekâsı yerine "Psikolojik İç Ses Ajanları (24 Cognitive Sub-Agents)" mimarisi: Mantık (Logic), Empati, Otorite ve İçtihat (Inland Empire) sizin zihninizde birbirleriyle tartışarak olasılık zarlarına göre diyaloğu yönlendirir.',
                'cultural_impact' => 'Tek bir geleneksel silahlı çatışma mekaniği olmadan yalnızca diyalog, psikoloji ve zar sistemleriyle 97 Metacritic puanına ulaşan edebi başyapıt.',
                'why_play' => 'Kendi beyninizin 24 farklı parçasının sizinle ve birbirleriyle kavga ettiği en özgün rol yapma deneyimini yaşamak için.',
                'poster_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=UWWA1jnI5N4',
                'pc_min_specs' => ['CPU' => 'Intel Core 2 Duo', 'GPU' => 'DirectX 11 uyumlu 512MB', 'RAM' => '2 GB', 'SSD' => '20 GB'],
                'pc_rec_specs' => ['CPU' => 'Intel Core i7 / Ryzen 1800', 'GPU' => 'GTX 1060', 'RAM' => '8 GB', 'SSD' => '20 GB SSD'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $webadmin->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [],
            ],
            [
                'title' => 'StarCraft II (DeepMind AlphaStar RL Edition)',
                'original_title' => 'StarCraft II: Wings of Liberty & AlphaStar',
                'slug' => 'starcraft-2-deepmind-alphastar',
                'release_year' => 2010,
                'developer_studio' => 'Blizzard Entertainment & Google DeepMind',
                'publisher' => 'Blizzard Entertainment',
                'game_director' => 'Dustin Browder, David Silver (AlphaStar Lead)',
                'lead_ai_programmer' => 'Oriol Vinyals & DeepMind AlphaStar Team',
                'composer' => 'Glenn Stafford, Derek Duke',
                'country' => 'ABD / Birleşik Krallık',
                'game_engine_id' => $source2->id,
                'platforms' => ['PC', 'macOS'],
                'genres' => ['Gerçek Zamanlı Strateji (RTS)', 'E-Spor', 'Bilim Kurgu'],
                'moods' => ['Rekabetçi', 'Stratejik', 'Yüksek APM', 'Taktiksel'],
                'perspective' => 'Üstten Görünüm (RTS Kamera)',
                'multiplayer_modes' => ['1v1 Rekabetçi Merdiven (Ladder)', 'Takım Stratejisi', 'Tek Oyunculu Kampanya'],
                'main_story_hours' => 18,
                'completionist_hours' => 120,
                'metacritic_score' => 93,
                'rating' => 9.4,
                'synopsis' => 'Koprulu Sektörü\'nde üç farklı ırkın (Terran, Protoss ve Zerg) asimetrik güçlerle yürüttüğü galaktik savaş ve 2019\'da Google DeepMind\'ın AlphaStar yapay zekâsının Grandmaster seviyesine ulaştığı tarihi test alanı.',
                'technical_overview' => 'Deterministik Lockstep ağ senkronizasyonu ile yüzlerce birimin milisaniyelik gecikmeyle kontrol edildiği RTS motoru ve PySC2 makine öğrenimi arayüzü.',
                'ai_architecture_summary' => 'DeepMind AlphaStar: Savaş Sisi (Fog of War) altında eksik bilgiyle karar veren, Transformer + Derin LSTM sinir ağları ve Çok Ajanlı Lig Eğitimi (Multi-Agent RL) ile oyuncuların %99.8\'ini geride bırakan Grandmaster yapay zekâ.',
                'cultural_impact' => 'Satranç (Deep Blue) ve Go (AlphaGo) sonrasında, gerçek zamanlı ve eksik bilgili bir video oyununda yapay zekânın profesyonel e-sporcuları adil APM sınırlarıyla yendiği bilimsel dönüm noktası (Nature dergisi kapak makalesi, 2019).',
                'why_play' => 'Hem RTS tarihinin en dengeli e-spor tasarımını deneyimlemek hem de yapay zekâ araştırmalarının en zorlu sınav alanını incelemek için.',
                'poster_url' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=c96SN4734sU',
                'pc_min_specs' => ['CPU' => 'Intel Core 2 Duo', 'GPU' => 'GeForce 7600 GT / Radeon HD 2600', 'RAM' => '4 GB', 'SSD' => '30 GB'],
                'pc_rec_specs' => ['CPU' => 'Intel Core i5 / Ryzen 5', 'GPU' => 'GTX 650 / Radeon HD 7790', 'RAM' => '8 GB', 'SSD' => '30 GB SSD'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $gelistirici->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [],
            ],
            [
                'title' => 'DOOM Eternal',
                'original_title' => 'DOOM Eternal',
                'slug' => 'doom-eternal',
                'release_year' => 2020,
                'developer_studio' => 'id Software',
                'publisher' => 'Bethesda Softworks',
                'game_director' => 'Hugo Martin',
                'lead_ai_programmer' => 'id Software Combat Chess AI Team',
                'composer' => 'Mick Gordon',
                'country' => 'ABD',
                'game_engine_id' => $fromEngine->id,
                'platforms' => ['PC', 'PlayStation 5', 'Xbox Series X/S', 'Nintendo Switch'],
                'genres' => ['Hızlı Arena FPS', 'Aksiyon', 'Bilim Kurgu'],
                'moods' => ['Adrenalin', 'Agresif', 'Ritim Odaklı', 'Metal'],
                'perspective' => 'Birinci Şahıs (FPS)',
                'multiplayer_modes' => ['Tek Oyunculu', 'Battlemode (2v1 Asimetrik)'],
                'main_story_hours' => 15,
                'completionist_hours' => 28,
                'metacritic_score' => 88,
                'rating' => 9.1,
                'synopsis' => 'Cehennem ordularının Dünya\'yı istila etmesiyle geri dönen Doom Slayer, insanlığı kurtarmak ve Cennet (Urdak) ile Cehennem arasındaki kadim anlaşmayı yıkmak için durmaksızın ileri atıldığı bir "Savaş Satrancı"na (Combat Puzzle) girer.',
                'technical_overview' => 'id Tech 7 motoruyla 1000 FPS\'e kadar çıkabilen ultra optimize Vulkan boru hattı ve iblislerin etlerinin/silahlarının parça parça koptuğu "Destructible Demons" sistemi.',
                'ai_architecture_summary' => 'Saldırı Jetonu (Attack Token) ve Rol Tabanlı Satranç AI\'sı: Arenadaki 20 iblis aynı anda körlemesine ateş etmez; "Baskı Kurucu (Pressure)", "Kanat Kuşatıcı (Flanker)" ve "Keskin Nişancı" rollerini paylaşarak oyuncuyu sürekli hareket etmeye zorlar.',
                'cultural_impact' => 'Modern FPS tasarımında "siper alıp can yenileme" hantallığını yıkarak "Saldırganlık = Kaynak Yönetimi (Push-Forward Combat)" felsefesini zirveye çıkardı.',
                'why_play' => 'Testere ile mermi, alev makinesi ile zırh ve Glory Kill ile can topladığınız 200 nabızlık savaş dansını deneyimlemek için.',
                'poster_url' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&w=900&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1600&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=_UuktemkCFI',
                'pc_min_specs' => ['CPU' => 'Core i5 3.3 GHz / Ryzen 3 3.1 GHz', 'GPU' => 'GTX 1050Ti / RX 470', 'RAM' => '8 GB', 'SSD' => '50 GB'],
                'pc_rec_specs' => ['CPU' => 'Core i7-6700K / Ryzen 7 1800X', 'GPU' => 'RTX 2060 / RX 5700', 'RAM' => '8 GB', 'SSD' => '50 GB NVMe'],
                'is_featured_daily' => false,
                'status' => 'approved',
                'submitted_by' => $studyo->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'tech_slugs' => [],
            ],
        ];

        $games = [];
        foreach (Game::all() as $g) {
            $games[$g->slug] = $g;
        }

        foreach ($extraGamesData as $gData) {
            $techSlugs = $gData['tech_slugs'] ?? [];
            unset($gData['tech_slugs']);

            $game = Game::updateOrCreate(['slug' => $gData['slug']], $gData);
            $game->syncPeopleFromStrings();

            if ($techSlugs !== []) {
                $syncTech = [];
                foreach ($techSlugs as $tSlug => $notes) {
                    if (isset($techMap[$tSlug])) {
                        $syncTech[$techMap[$tSlug]->id] = ['implementation_notes' => $notes];
                    }
                }
                $game->technologies()->sync($syncTech);
            }

            $games[$game->slug] = $game;
        }

        // 5. Yeni Oyun & AI Vaka Analizleri (Toplam 10 Vaka Analizine Tamamlama)
        $extraAnalyses = [
            [
                'game_id' => $games['stalker-shadow-of-chernobyl']->id,
                'ai_system_id' => $aiMap['a-life-ecosystem-simulation']->id,
                'author_id' => $gelistirici->id,
                'title' => 'S.T.A.L.K.E.R. — A-Life Simülasyonu ve Oyuncu Yokken Bile Yaşayan Çernobil Ekosistemi',
                'slug' => 'stalker-a-life-simulasyonu-ve-yasayan-ekosistem-analizi',
                'ai_role_in_game' => 'Tam Harita Çevrimdışı/Çevrimiçi Ekosistem ve Otonom NPC Yaşam Döngüsü',
                'architecture_breakdown' => 'Çoğu açık dünya oyununda NPC\'ler yalnızca oyuncunun etrafındaki 100 metrelik bir balonun içinde var olur ve oyuncu uzaklaştığında silinir. S.T.A.L.K.E.R.\'ın A-Life sistemi ise Çernobil Bölgesi\'ndeki yüzlerce Stalker, haydut ve mutantı kalıcı kimliklerle simüle eder. Oyuncudan uzaktayken "Offline Graph" üzerinde matematiksel olarak seyahat eden ajanlar, oyuncunun yakınına girdiklerinde "Online 3D" dünyaya kesintisiz aktarılır.',
                'perception_and_sensors' => 'Online moda geçen NPC\'ler görüş açısı, çalı hışırtısı, mermi sekme sesi ve grup morali (Squad Morale) değişkenleriyle GOAP tabanlı kararlar alır.',
                'decision_loop_details' => 'Gündüzleri anomali bölgelerinde "Artifact" arayan bir Stalker grubu, akşam olduğunda güvenli bir kamp ateşine çekilir, yemek yer ve gitar çalar. Eğer yolda bir "Bloodsucker" sürüsüyle karşılaşırlarsa oyuncunun haberi bile olmadan çatışmaya girerler; oyuncu ertesi gün o bölgeden geçerken bu çatışmanın izlerini ve ganimetlerini bulur.',
                'player_experience_impact' => 'Oyuncuya "Bu dünya benim için kurulmuş bir tiyatro sahnesi değil; ben tehlikeli ve yaşayan bir ekosistemdeki sıradan bir avcıyım" hissini verir.',
                'limitations_and_tricks' => 'İlk testlerde A-Life ajanları oyunun son görevini oyuncu daha oyunun başındayken kendi başlarına tamamlayıp oyunu bitirdiği için geliştiriciler hikâye kritik bölgelere A-Life kısıtlamaları eklemek zorunda kalmıştır.',
                'innovation_score' => 98,
                'complexity_score' => 95,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=kX8o2a2gY4U',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'game_id' => $games['starcraft-2-deepmind-alphastar']->id,
                'ai_system_id' => $aiMap['deep-reinforcement-learning-alphastar-sophy']->id,
                'author_id' => $gelistirici->id,
                'title' => 'StarCraft II & DeepMind AlphaStar — Pekiştirmeli Öğrenme ile Grandmaster Zirvesi',
                'slug' => 'starcraft-2-deepmind-alphastar-reinforcement-learning-analizi',
                'ai_role_in_game' => 'Uçtan Uca Derin Pekiştirmeli Öğrenme (Deep RL) Strateji Ajanı',
                'architecture_breakdown' => 'StarCraft II, 10^26\'dan fazla olası eylem uzayı, gerçek zamanlı mikro/makro yönetimi ve Savaş Sisi (Fog of War) nedeniyle yapay zekâ tarihinin en büyük sınavlarından biriydi. DeepMind\'ın AlphaStar mimarisi; birimleri analiz eden bir Transformer gövdesi, geçmiş gözlemleri hatırlayan derin bir LSTM hafızası ve hedef koordinatları seçen Pointer Network katmanlarından oluşur.',
                'perception_and_sensors' => 'AlphaStar, hile yaparak tüm haritayı görmez; tıpkı bir insan oyuncu gibi kamera penceresini haritada kaydırmak ve yalnızca kendi birimlerinin görüş alanındaki verileri okumak zorundadır.',
                'decision_loop_details' => 'AlphaStar liginde üç tip ajan birbirine karşı 200 yıllık gerçek zamanlı oyuna eşdeğer maç yapmıştır: Main Agents (her stratejiyi yenen genel şampiyonlar), Main Exploiters (ana ajanın kör noktalarını ve "cheese" taktiklerini bulan ajanlar) ve League Exploiters.',
                'player_experience_impact' => 'Profesyonel e-sporcuların bile daha önce denemediği işçi doygunluğu (worker oversaturation) ve prizma mikro-yönetimi taktiklerini keşfederek RTS strateji teorisini zenginleştirdi.',
                'limitations_and_tricks' => 'Adil bir maç olması için AlphaStar\'ın dakikadaki etkili tıklama hızı (EPM) insan Grandmaster ortalamasının altında tutulmuş ve 350 milisaniyelik görsel reaksiyon gecikmesi eklenmiştir.',
                'innovation_score' => 99,
                'complexity_score' => 100,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=c96SN4734sU',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'game_id' => $games['elden-ring']->id,
                'ai_system_id' => $aiMap['tactical-utility-ai']->id,
                'author_id' => $admin->id,
                'title' => 'Elden Ring — Margit ve Malenia Boss Yapay Zekâlarında Girdi Okuma ve Dallanan Kombolar',
                'slug' => 'elden-ring-boss-yapay-zekasi-ve-dallanan-kombolar',
                'ai_role_in_game' => 'Çok Fazlı Boss Savaş Mimarisi & Mesafe/Girdi Duyarlı Saldırı Ağaçları',
                'architecture_breakdown' => 'Önceki Dark Souls oyunlarında bir boss kombosuna başladığında oyuncu saldırı sayısını ezberleyip kombo sonunda güvenle vurabiliyordu. Elden Ring\'de ise FromSoftware, boss animasyonlarının son karelerine "Koşullu Dallanma Düğümleri (Conditional Branching Nodes)" yerleştirdi.',
                'perception_and_sensors' => 'Boss yapay zekâsı her 0.1 saniyede bir oyuncunun tam açısını (Azimuth), mesafesini ve kontrolcüdeki "Eşya Kullanma (Heal Flask)" animasyon bayrağını kontrol eder.',
                'decision_loop_details' => 'Örneğin Margit havaya kalkıp sopasını indirdikten sonra eğer oyuncu tam önünde durmaya devam ediyorsa diğer elinde ışık hançeri (Holy Dagger) oluşturup komboyu uzatır; eğer oyuncu geriye kaçıp can iksiri içmeye kalkarsa 0.2 saniye içinde fırlatma bıçağı atarak iyileşmeyi cezalandırır.',
                'player_experience_impact' => 'Boss savaşlarını pasif bir "sıranı bekle" döngüsünden çıkarıp agresif pozisyon alma (positioning) ve duruş kırma (stance break) düellosuna dönüştürür.',
                'limitations_and_tricks' => 'Bossların oyuncunun tuşa bastığı ilk karede (Input Reading) tepki vermesi bazen "zihin okuma" hissi yarattığı için yamalarla bu tepkilere kısa insani algı gecikmeleri eklendi.',
                'innovation_score' => 94,
                'complexity_score' => 92,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=E3Huy2cdih0',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'game_id' => $games['the-last-of-us-part-2']->id,
                'ai_system_id' => $aiMap['goap-goal-oriented-action-planning']->id,
                'author_id' => $webadmin->id,
                'title' => 'The Last of Us Part II — İsimli Düşman İletişimi, Köpek Koku İzleri ve Islık Kodları',
                'slug' => 'the-last-of-us-part-2-isimli-dusman-ve-islik-ai-analizi',
                'ai_role_in_game' => 'Koordineli Arama-Tarama Mangası (Squad Search Grid) & Duygusal Tepki Sistemi',
                'architecture_breakdown' => 'Naughty Dog, düşman mangalarının alanı tararken birbirleriyle çakışmaması için haritayı görünmez tehdit hücrelerine (Threat Grid) böler. Bir düşman şüpheli bir ses duyduğunda yanındaki arkadaşına ismiyle seslenerek ("Omar, sağ kanadı kontrol et, ben önden gidiyorum!") arama görevini paylaştırır.',
                'perception_and_sensors' => 'Görüş ve işitme konilerine ek olarak üçüncü bir duyusal katman eklendi: Koku İzi (Olfactory Scent Trail). Oyuncu çimenlerde görünmez olsa bile arkasında bıraktığı koku vektörü eğitimli köpekler tarafından takip edilir.',
                'decision_loop_details' => 'Seraphite tarikatı üyeleri ise kelimeler yerine farklı frekanslardaki ıslıklarla haberleşir: Tek tiz ıslık "Temiz", çift kesik ıslık "Şüpheli hareket var", uzun yükselen ıslık ise "Hedefi gördüm, kuşatın!" anlamına gelir ve oyuncu bu ıslık dilini öğrenerek yapay zekânın bir sonraki adımını tahmin edebilir.',
                'player_experience_impact' => 'Gizlilik (Stealth) sekanslarında oyuncunun aynı çalının içinde dakikalarca saklanmasını imkânsız kılar; sürekli doğaçlama hareket ve gerilim üretir.',
                'limitations_and_tricks' => 'Müttefik NPC\'lerin (Dina, Jesse) düşmanların görüş konisine girdiğinde fark edilmemesi için müttefik AI\'sı son anda düşman bakış vektörünün dışına kaydırılır.',
                'innovation_score' => 96,
                'complexity_score' => 93,
                'video_evidence_url' => 'https://www.youtube.com/watch?v=qPNiIeKMHyg',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
        ];

        foreach ($extraAnalyses as $aData) {
            GameAiAnalysis::updateOrCreate(['slug' => $aData['slug']], $aData);
        }

        // 6. Yeni Örnek Oyun & AI Analiz Videoları (Toplam 12 Videoya Tamamlama)
        $extraVideos = [
            [
                'game_id' => $games['elden-ring']->id,
                'user_id' => $admin->id,
                'title' => 'Elden Ring — Boss Yapay Zekâsı, Gecikmeli Saldırı Ağaçları ve Oynanış Analizi',
                'slug' => 'elden-ring-boss-ai-ve-oynanis-kesiti',
                'video_type' => 'ai_showcase',
                'video_url' => 'https://www.youtube.com/watch?v=E3Huy2cdih0',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '12:10',
                'resolution_tag' => '4K 60FPS',
                'description' => 'FromSoftware\'in Dantelion motorunda bossların oyuncunun mesafesine ve girdi kuyruğuna göre kombolarını nasıl değiştirdiğini gösteren örnek inceleme.',
                'technical_highlights' => 'Conditional Animation Branching, Input Queue Reading, Posture System',
                'is_featured' => true,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['stalker-shadow-of-chernobyl']->id,
                'user_id' => $gelistirici->id,
                'title' => 'S.T.A.L.K.E.R. — A-Life Ekosistemi: Oyuncu Yokken Haritada Neler Oluyor?',
                'slug' => 'stalker-a-life-ekosistem-yapay-zeka-videosu',
                'video_type' => 'ai_showcase',
                'video_url' => 'https://www.youtube.com/watch?v=kX8o2a2gY4U',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '15:40',
                'resolution_tag' => '1080p 60FPS',
                'description' => 'Offline ve Online AI katmanlarının Çernobil Bölgesi\'ndeki yüzlerce NPC\'yi nasıl otonom yaşattığının vaka videosu.',
                'technical_highlights' => 'Offline/Online Graph Switching, Autonomous Looting, Squad Morale',
                'is_featured' => true,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['the-last-of-us-part-2']->id,
                'user_id' => $studyo->id,
                'title' => 'The Last of Us Part II — Motion Matching, Koku İzleri ve Seraphite Islık Kodu AI',
                'slug' => 'the-last-of-us-part-2-motion-matching-ve-ai',
                'video_type' => 'gameplay_sample',
                'video_url' => 'https://www.youtube.com/watch?v=qPNiIeKMHyg',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '10:25',
                'resolution_tag' => '4K 60FPS',
                'description' => 'Seattle ormanlarında Seraphite avcılarının ıslık sinyalleriyle haberleşmesi ve Motion Matching animasyon geçişleri.',
                'technical_highlights' => 'Motion Matching, Olfactory Scent Tracking, Acoustic Whistle Signalling',
                'is_featured' => false,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['half-life-alyx']->id,
                'user_id' => $webadmin->id,
                'title' => 'Half-Life: Alyx — Source 2 Rubikon VR Fiziği ve Akustik Yapay Zekâ Gösterimi',
                'slug' => 'half-life-alyx-source-2-rubikon-vr-fizigi',
                'video_type' => 'tech_breakdown',
                'video_url' => 'https://www.youtube.com/watch?v=O2W0N3uKXmo',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '11:08',
                'resolution_tag' => '4K VR 90Hz',
                'description' => 'Sanal gerçeklikte her nesnenin fiziksel ağırlığı, sıvı shaderları ve Combine askerlerinin VR etkileşimlerine verdiği tepkiler.',
                'technical_highlights' => 'Source 2 Rubikon Physics, Steam Audio Ray-Tracing, VR Hand Interaction',
                'is_featured' => false,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['starcraft-2-deepmind-alphastar']->id,
                'user_id' => $gelistirici->id,
                'title' => 'DeepMind AlphaStar vs. Grandmaster — Pekiştirmeli Öğrenme E-Spor Karşılaşması',
                'slug' => 'deepmind-alphastar-starcraft-2-rl-mac-analizi',
                'video_type' => 'speedrun_pro',
                'video_url' => 'https://www.youtube.com/watch?v=c96SN4734sU',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '18:50',
                'resolution_tag' => '1080p 60FPS',
                'description' => 'DeepMind AlphaStar sinir ağının Savaş Sisi altında kamera görünümünü nasıl yönettiğini ve birim mikro-kontrolünü gösteren tarihi kayıt.',
                'technical_highlights' => 'Multi-Agent League RL, Pointer Networks, Human APM Constraints',
                'is_featured' => true,
                'status' => 'approved',
            ],
            [
                'game_id' => $games['doom-eternal']->id,
                'user_id' => $oyuncu->id,
                'title' => 'DOOM Eternal — id Tech 7 Motoru, 1000 FPS Vulkan Mimarisi ve Savaş Satrancı AI',
                'slug' => 'doom-eternal-id-tech-7-ve-savas-satranci-ai',
                'video_type' => 'gameplay_sample',
                'video_url' => 'https://www.youtube.com/watch?v=_UuktemkCFI',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=900&q=80',
                'duration_label' => '07:35',
                'resolution_tag' => '4K 120FPS',
                'description' => 'Saldırı jetonu (Attack Token) paylaşımıyla oyuncuyu sürekli harekete zorlayan iblis yapay zekâsı ve parça parça yıkılabilir düşman modelleri.',
                'technical_highlights' => 'id Tech 7 Vulkan, Destructible Demons, Attack Token AI',
                'is_featured' => false,
                'status' => 'approved',
            ],
        ];

        foreach ($extraVideos as $vData) {
            GameVideo::updateOrCreate(['slug' => $vData['slug']], $vData);
        }

        // 7. Yaratıcılar, Performans Sanatçıları & E-Sporcular (People zenginleştirme)
        $extraPeople = [
            [
                'name' => 'Amelia Tyler',
                'slug' => 'amelia-tyler',
                'primary_role' => 'actor',
                'birth_year' => 1984,
                'nationality' => 'Birleşik Krallık',
                'studio_affiliation' => 'Larian Studios / Supergiant Games',
                'bio' => 'Baldur\'s Gate 3\'ün unutulmaz Anlatıcısı (The Narrator / Dungeon Master) ve Hades II\'deki Hecate performansıyla dijital oyunlarda seslendirme sanatının zirve isimlerinden biri.',
                'signature_style' => 'Yüzlerce saatlik dallanan RPG diyaloglarında oyuncunun zihninin içindeki alaycı, karanlık ve büyüleyici Dungeon Master sesi.',
                'notable_achievements' => ['BAFTA Performer in a Leading Role Adaylığı (Baldur\'s Gate 3)', 'Golden Joystick En İyi Anlatım Performansı'],
            ],
            [
                'name' => 'Roger Clark',
                'slug' => 'roger-clark',
                'primary_role' => 'actor',
                'birth_year' => 1978,
                'nationality' => 'ABD / İrlanda',
                'studio_affiliation' => 'Rockstar Games',
                'bio' => 'Red Dead Redemption 2\'de Arthur Morgan karakterine 5 yıl boyunca tam hareket yakalama (Performance Capture) ve seslendirmeyle hayat vererek The Game Awards En İyi Performans ödülünü kazanan aktör.',
                'signature_style' => 'Binlerce bağlamsal açık dünya tepkisi (at sakinleştirme, kasabalılarla selamlaşma, kamp içi diyaloglar) ve derin dramatik mikro-mimikler.',
                'notable_achievements' => ['The Game Awards 2018 — En İyi Performans Ödülü (Arthur Morgan)'],
            ],
            [
                'name' => 'Lee "Faker" Sang-hyeok',
                'slug' => 'lee-faker-sang-hyeok',
                'primary_role' => 'esports_player',
                'birth_year' => 1996,
                'nationality' => 'Güney Kore',
                'studio_affiliation' => 'T1 Esports',
                'bio' => 'Dijital oyun ve e-spor tarihinin en büyük oyuncusu ("The Unkillable Demon King"). 5 kez Dünya Şampiyonu olarak insan reflekslerinin, mikro-karar hızının ve oyun okuma zekâsının sınırlarını tanımlayan efsane.',
                'signature_style' => 'Saniyede 400+ kamera geçişi (F-key toggling) ile tüm harita bilgisini gerçek zamanlı işleme ve milisaniyelik yetenek savuşturma.',
                'notable_achievements' => ['5x Dünya Şampiyonu (2013, 2015, 2016, 2023, 2024)', 'The Game Awards En İyi E-Spor Oyuncusu', 'Hall of Legends İlk Üyesi'],
            ],
        ];

        foreach ($extraPeople as $pData) {
            $person = Person::updateOrCreate(['slug' => $pData['slug']], $pData);
            if ($person->slug === 'amelia-tyler') {
                $games['baldurs-gate-3']->people()->syncWithoutDetaching([
                    $person->id => ['credit_type' => 'actor', 'character_name' => 'The Narrator (Dungeon Master)', 'credit_order' => 1],
                ]);
            } elseif ($person->slug === 'roger-clark') {
                $games['red-dead-redemption-2']->people()->syncWithoutDetaching([
                    $person->id => ['credit_type' => 'actor', 'character_name' => 'Arthur Morgan', 'credit_order' => 1],
                ]);
            } elseif ($person->slug === 'lee-faker-sang-hyeok') {
                $games['starcraft-2-deepmind-alphastar']->people()->syncWithoutDetaching([
                    $person->id => ['credit_type' => 'actor', 'character_name' => 'E-Spor & İnsan APM Referans İkonu', 'credit_order' => 1],
                ]);
            }
        }

        // 8. Ek Koleksiyon (Nöral Grafik & Fizik Başyapıtları)
        $col2 = Collection::updateOrCreate(
            ['slug' => 'noral-grafik-ve-gercek-zamanli-fizik-devrimi'],
            [
                'user_id' => $webadmin->id,
                'title' => 'Nöral Grafik, Path Tracing ve Gerçek Zamanlı Fizik Devrimi',
                'curator_name' => $webadmin->name,
                'description' => 'DLSS 3.5 Ray Reconstruction, Euphoria biyomekanik sinir ağı, Rubikon VR fiziği ve Decima topografyasıyla oyun teknolojilerinin sınırlarını zorlayan yapımlar.',
                'accent_color' => 'cyan',
                'is_public' => true,
            ]
        );
        $col2->games()->sync([
            $games['cyberpunk-2077-phantom-liberty']->id,
            $games['alan-wake-2']->id,
            $games['red-dead-redemption-2']->id,
            $games['half-life-alyx']->id,
            $games['death-stranding-directors-cut']->id,
        ]);
    }
}
