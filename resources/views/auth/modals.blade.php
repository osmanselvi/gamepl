<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="relative max-w-md w-full bg-card border border-gray-700 rounded-2xl p-6 shadow-xl">
        <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl leading-none cursor-pointer" onclick="hideModals()" aria-label="Kapat">&times;</button>
        <h2 class="text-2xl font-bold text-center mb-4">Giriş Yap</h2>

        <div class="mb-5 bg-gray-800/80 border border-gray-700 rounded-xl p-3">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-2">Hızlı Rol Test Girişi (Tek Tıkla Doldur):</p>
            <div class="flex flex-wrap gap-1.5">
                <button type="button" onclick="fillDemoLogin('admin@cine.local')" class="text-xs px-2.5 py-1 rounded bg-red-500/20 text-red-300 border border-red-500/40 hover:bg-red-500/30 cursor-pointer">Admin</button>
                <button type="button" onclick="fillDemoLogin('webadmin@cine.local')" class="text-xs px-2.5 py-1 rounded bg-amber-500/20 text-amber-300 border border-amber-500/40 hover:bg-amber-500/30 cursor-pointer">WebAdmin</button>
                <button type="button" onclick="fillDemoLogin('yonetmen@cine.local')" class="text-xs px-2.5 py-1 rounded bg-purple-500/20 text-purple-300 border border-purple-500/40 hover:bg-purple-500/30 cursor-pointer">Yönetmen</button>
                <button type="button" onclick="fillDemoLogin('yapimci@cine.local')" class="text-xs px-2.5 py-1 rounded bg-blue-500/20 text-blue-300 border border-blue-500/40 hover:bg-blue-500/30 cursor-pointer">Yapımcı</button>
                <button type="button" onclick="fillDemoLogin('test@example.com')" class="text-xs px-2.5 py-1 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 hover:bg-emerald-500/30 cursor-pointer">İzleyici</button>
            </div>
        </div>
        
        <form id="loginForm">
            <div class="mb-4">
                <label class="block text-sm text-gray-300 mb-2">E-posta</label>
                <input 
                    type="email" 
                    name="email" 
                    id="loginEmailInput"
                    class="w-full bg-gray-700 border border-gray-500 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                >
            </div>
            
            <div class="mb-4">
                <label class="block text-sm text-gray-300 mb-2">Şifre</label>
                <input 
                    type="password" 
                    name="password" 
                    id="loginPasswordInput"
                    class="w-full bg-gray-700 border border-gray-500 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                >
            </div>
            
            <button type="submit" class="w-full btn btn-primary py-3 mt-4">
                Giriş Yap
            </button>
        </form>
        
        <p class="text-center text-sm text-gray-400 mt-6">
            Hesabınız yok mu? <a href="javascript:void(0)" class="text-primary underline" onclick="showRegisterModal()">Kayıt Ol</a>
        </p>
    </div>
</div>

<!-- Register Modal -->
<div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="relative max-w-md w-full bg-card border border-gray-700 rounded-2xl p-6 shadow-xl">
        <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl leading-none cursor-pointer" onclick="hideModals()" aria-label="Kapat">&times;</button>
        <h2 class="text-2xl font-bold text-center mb-6">Kayıt Ol</h2>
        
        <form id="registerForm">
            <div class="mb-4">
                <label class="block text-sm text-gray-300 mb-2">Ad Soyad</label>
                <input 
                    type="text" 
                    name="name" 
                    class="w-full bg-gray-700 border border-gray-500 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                >
            </div>
            
            <div class="mb-4">
                <label class="block text-sm text-gray-300 mb-2">E-posta</label>
                <input 
                    type="email" 
                    name="email" 
                    class="w-full bg-gray-700 border border-gray-500 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                >
            </div>
            
            <div class="mb-4">
                <label class="block text-sm text-gray-300 mb-2">Şifre</label>
                <input 
                    type="password" 
                    name="password" 
                    class="w-full bg-gray-700 border border-gray-500 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                    minlength="8"
                >
                <small class="text-sm text-gray-400">En az 8 karakter</small>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm text-gray-300 mb-2">Şifreyi Tekrar</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="w-full bg-gray-700 border border-gray-500 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                >
            </div>
            
            <button type="submit" class="w-full btn btn-primary py-3 mt-4">
                Kayıt Ol
            </button>
        </form>
        
        <p class="text-center text-sm text-gray-400 mt-6">
            Zaten hesabınız var mı? <a href="javascript:void(0)" class="text-primary underline" onclick="showLoginModal()">Giriş Yap</a>
        </p>
    </div>
</div>

<!-- Mobile Menu -->
<div id="mobileMenu" class="fixed inset-0 bg-black/80 z-40 hidden">
    <div class="relative h-screen p-6 flex flex-col">
        <button type="button" class="absolute top-4 right-4 text-white text-2xl leading-none cursor-pointer" onclick="toggleMenu()" aria-label="Menüyü Kapat">&times;</button>
        <nav class="flex-1 flex flex-col items-center justify-center gap-4 py-8">
            <a href="#popularFilms" class="text-lg text-white hover:text-primary" onclick="toggleMenu()">Popüler Filmler</a>
            <a href="#latestReviews" class="text-lg text-white hover:text-primary" onclick="toggleMenu()">Eleştiriler</a>
            <div id="mobileGuestLinks" class="flex flex-col items-center gap-4 @auth hidden @endauth">
                <a href="javascript:void(0)" class="text-lg text-white hover:text-primary" onclick="showLoginModal()">Giriş Yap</a>
                <a href="javascript:void(0)" class="text-lg text-white hover:text-primary" onclick="showRegisterModal()">Kayıt Ol</a>
            </div>
            <a href="javascript:void(0)" id="mobileLogout" class="text-lg text-danger hover:underline @guest hidden @endguest" onclick="logout()">Çıkış Yap</a>
        </nav>
    </div>
</div>
