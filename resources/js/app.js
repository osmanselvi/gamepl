// Authentication & Language state
let authToken = localStorage.getItem('authToken') || null;
let userName = window.SERVER_AUTH?.user?.name || localStorage.getItem('userName') || null;
let userRole = window.SERVER_AUTH?.user?.role || localStorage.getItem('userRole') || 'izleyici';
let currentLang = localStorage.getItem('siteLang') || 'tr';
let currentSort = 'latest';
let currentDecade = '';
let currentPage = 1;
let currentSearch = '';
let cachedFilms = [];
let cachedFilmsTotal = null;
let cachedPagination = null;
let cachedReviews = [];

const LANG_LABELS = {
    tr: 'Türkçe',
    en: 'English',
    fr: 'Français',
    de: 'Deutsch',
    es: 'Español',
    it: 'Italiano',
    ja: '日本語',
};

function updateLangButtonsUI() {
    document.querySelectorAll('[data-lang-btn]').forEach(btn => {
        const isSelected = btn.getAttribute('data-lang-btn') === currentLang;
        btn.classList.toggle('bg-primary', isSelected);
        btn.classList.toggle('bg-gray-900', !isSelected);
        btn.classList.toggle('text-white', isSelected);
        btn.classList.toggle('text-gray-300', !isSelected);
        btn.classList.toggle('border-primary', isSelected);
        btn.classList.toggle('border-gray-800', !isSelected);
    });
}

function setSiteLanguage(lang) {
    currentLang = lang;
    localStorage.setItem('siteLang', lang);
    updateLangButtonsUI();

    if (cachedFilms.length > 0) {
        renderFilms(cachedFilms, cachedFilmsTotal);
    }
    if (cachedReviews.length > 0) {
        renderLatestReviews(cachedReviews);
    }

    // Trigger Google Translate combo if present
    const gtCombo = document.querySelector('.goog-te-combo');
    if (gtCombo) {
        gtCombo.value = lang;
        gtCombo.dispatchEvent(new Event('change'));
    }

    window.dispatchEvent(new CustomEvent('siteLanguageChanged', { detail: { lang } }));
}

window.setSiteLanguage = setSiteLanguage;

// Show/hide modals
function showLoginModal() {
    document.getElementById('loginModal')?.classList.remove('hidden');
    document.getElementById('registerModal')?.classList.add('hidden');
    document.getElementById('mobileMenu')?.classList.add('hidden');
}

function showRegisterModal() {
    document.getElementById('registerModal')?.classList.remove('hidden');
    document.getElementById('loginModal')?.classList.add('hidden');
    document.getElementById('mobileMenu')?.classList.add('hidden');
}

function hideModals() {
    document.getElementById('loginModal')?.classList.add('hidden');
    document.getElementById('registerModal')?.classList.add('hidden');
    document.getElementById('mobileMenu')?.classList.add('hidden');
}

// Toggle mobile menu
function toggleMenu() {
    document.getElementById('mobileMenu')?.classList.toggle('hidden');
}

// Update UI based on auth state
function updateAuthUI(loggedIn) {
    const guestActions = document.getElementById('guestActions');
    const userActions = document.getElementById('userActions');
    const userNameDisplay = document.getElementById('userNameDisplay');
    const userRoleDisplay = document.getElementById('userRoleDisplay');
    const mobileGuestLinks = document.getElementById('mobileGuestLinks');
    const mobileLogout = document.getElementById('mobileLogout');

    if (loggedIn) {
        guestActions?.classList.add('hidden');
        userActions?.classList.remove('hidden');
        if (userNameDisplay && userName) {
            userNameDisplay.textContent = userName;
        }
        if (userRoleDisplay) {
            userRoleDisplay.textContent = userRole || 'izleyici';
        }
        mobileGuestLinks?.classList.add('hidden');
        mobileLogout?.classList.remove('hidden');
    } else {
        guestActions?.classList.remove('hidden');
        userActions?.classList.add('hidden');
        mobileGuestLinks?.classList.remove('hidden');
        mobileLogout?.classList.add('hidden');
    }
}

function fillDemoLogin(email) {
    const emailInput = document.getElementById('loginEmailInput');
    const passwordInput = document.getElementById('loginPasswordInput');
    if (emailInput) emailInput.value = email;
    if (passwordInput) passwordInput.value = 'password123';
}
window.fillDemoLogin = fillDemoLogin;

// Logout
async function logout() {
    try {
        const headers = { 'Accept': 'application/json' };
        if (authToken) {
            headers['Authorization'] = `Bearer ${authToken}`;
        }

        await fetch('/api/logout', {
            method: 'POST',
            headers
        });
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        localStorage.removeItem('authToken');
        localStorage.removeItem('userName');
        localStorage.removeItem('userRole');
        authToken = null;
        userName = null;
        userRole = 'izleyici';
        updateAuthUI(false);
        window.location.href = '/';
    }
}

// Expose functions globally for inline onclick handlers
window.showLoginModal = showLoginModal;
window.showRegisterModal = showRegisterModal;
window.hideModals = hideModals;
window.toggleMenu = toggleMenu;
window.logout = logout;

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

function renderFilms(films, totalCount = null) {
    const container = document.getElementById('popularFilms');
    const badge = document.getElementById('filmCountBadge');

    if (!container) {
        return;
    }

    if (badge) {
        const count = totalCount ?? films.length;
        badge.textContent = `${count} Film`;
    }

    if (!films || films.length === 0) {
        container.innerHTML = `
            <div class="col-span-full py-12 text-center text-gray-400">
                Aradığınız kriterlere uygun film bulunamadı.
            </div>
        `;
        return;
    }

    container.innerHTML = films.map(film => {
        const translations = film.translations || {};
        const localizedTitle = escapeHtml(translations[currentLang]?.title || film.title);
        const originalTitle = escapeHtml(film.original_title || '');
        const slug = encodeURIComponent(film.slug || '');
        const year = escapeHtml(film.year || '');
        const genre = escapeHtml(film.genre || '');
        const director = escapeHtml(film.director || '');
        const avgRating = film.reviews_avg_rating ? Number(film.reviews_avg_rating).toFixed(1) : null;
        const posterUrl = escapeHtml(film.poster_url || `https://placehold.co/300x450/1e293b/f1f5f9?text=${encodeURIComponent(film.title || 'Film')}`);

        // Build compact multi-language title preview badges
        const altTitles = ['tr', 'en', 'fr', 'de', 'es', 'ja']
            .filter(code => code !== currentLang && translations[code]?.title && translations[code].title !== (translations[currentLang]?.title || film.title))
            .slice(0, 2)
            .map(code => `<span class="text-[10px] bg-gray-900/90 text-gray-300 px-1.5 py-0.5 rounded border border-gray-700/70 truncate max-w-full" title="${code.toUpperCase()}: ${escapeHtml(translations[code].title)}"><strong class="text-primary uppercase">${code}:</strong> ${escapeHtml(translations[code].title)}</span>`)
            .join('');

        return `
            <a href="/films/${slug}" class="group bg-gray-800/60 border border-gray-700/60 hover:border-primary/50 rounded-xl overflow-hidden hover:-translate-y-1 transition-all duration-200 flex flex-col">
                <div class="aspect-[2/3] bg-gray-900 overflow-hidden relative">
                    <img 
                        src="${posterUrl}" 
                        alt="${localizedTitle}"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='https://placehold.co/300x450/1e293b/f1f5f9?text=Cinema';"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                    ${year ? `<span class="absolute top-2 right-2 bg-black/75 text-xs px-2 py-0.5 rounded text-gray-200">${year}</span>` : ''}
                    <span class="absolute top-2 left-2 bg-primary/85 text-[10px] font-bold uppercase px-1.5 py-0.5 rounded text-white">${escapeHtml(currentLang)}</span>
                    ${avgRating ? `<span class="absolute bottom-2 right-2 bg-black/85 border border-secondary/50 text-secondary text-[11px] font-bold px-2 py-0.5 rounded-md shadow">★ ${avgRating}</span>` : ''}
                </div>
                <div class="p-3 flex-1 flex flex-col justify-between gap-1.5">
                    <div>
                        <h3 class="font-semibold text-sm text-white group-hover:text-primary transition-colors line-clamp-1" title="${localizedTitle}">${localizedTitle}</h3>
                        ${originalTitle && originalTitle !== localizedTitle ? `<p class="text-[11px] text-gray-400 italic line-clamp-1">${originalTitle}</p>` : ''}
                        <p class="text-primary text-xs mt-1 line-clamp-1">${genre}</p>
                    </div>
                    ${altTitles ? `<div class="flex flex-col gap-1 pt-1">${altTitles}</div>` : ''}
                    ${director ? `<p class="text-gray-400 text-xs mt-1 truncate">🎬 ${director}</p>` : ''}
                </div>
            </a>
        `;
    }).join('');
}

function renderPagination(meta) {
    const pagContainer = document.getElementById('filmsPagination');
    if (!pagContainer) return;

    if (!meta || !meta.last_page || meta.last_page <= 1) {
        pagContainer.innerHTML = meta?.total
            ? `<span class="text-xs text-gray-400">Toplam <strong>${meta.total}</strong> film listeleniyor</span>`
            : '';
        return;
    }

    const current = meta.current_page || 1;
    const last = meta.last_page || 1;
    const total = meta.total || 0;

    let pagesHtml = '';
    for (let p = 1; p <= last; p++) {
        const activeClass = p === current
            ? 'bg-primary text-white border-primary'
            : 'bg-gray-900 text-gray-300 border-gray-800 hover:border-primary/50 hover:text-white';
        pagesHtml += `
            <button
                type="button"
                onclick="window.goToFilmsPage(${p})"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all cursor-pointer ${activeClass}"
            >${p}</button>
        `;
    }

    pagContainer.innerHTML = `
        <span class="text-xs text-gray-400">
            Toplam <strong class="text-white">${total}</strong> film • Sayfa <strong class="text-white">${current} / ${last}</strong>
        </span>
        <div class="flex items-center gap-1.5">
            <button
                type="button"
                onclick="window.goToFilmsPage(${current - 1})"
                ${current <= 1 ? 'disabled' : ''}
                class="px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-800 bg-gray-900 text-gray-300 hover:border-primary/50 disabled:opacity-40 disabled:pointer-events-none cursor-pointer"
            >← Önceki</button>
            ${pagesHtml}
            <button
                type="button"
                onclick="window.goToFilmsPage(${current + 1})"
                ${current >= last ? 'disabled' : ''}
                class="px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-800 bg-gray-900 text-gray-300 hover:border-primary/50 disabled:opacity-40 disabled:pointer-events-none cursor-pointer"
            >Sonraki →</button>
        </div>
    `;
}

function updateDecadeButtonsUI() {
    document.querySelectorAll('[data-decade-btn]').forEach(btn => {
        const isSelected = btn.getAttribute('data-decade-btn') === currentDecade;
        btn.classList.toggle('bg-primary', isSelected);
        btn.classList.toggle('bg-gray-900', !isSelected);
        btn.classList.toggle('text-white', isSelected);
        btn.classList.toggle('text-gray-300', !isSelected);
        btn.classList.toggle('border-primary', isSelected);
        btn.classList.toggle('border-gray-800', !isSelected);
    });
}

window.setFilmDecade = function (decade) {
    currentDecade = decade;
    currentPage = 1;
    updateDecadeButtonsUI();
    fetchFilms(currentSearch);
};

window.setFilmSort = function (sort) {
    currentSort = sort || 'latest';
    currentPage = 1;
    fetchFilms(currentSearch);
};

window.goToFilmsPage = function (page) {
    if (page < 1 || (cachedPagination && page > cachedPagination.last_page)) {
        return;
    }
    currentPage = page;
    fetchFilms(currentSearch);
    document.getElementById('filmsSectionTitle')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
};

async function fetchFilms(searchQuery = '') {
    const container = document.getElementById('popularFilms');
    if (!container) {
        return;
    }

    currentSearch = searchQuery;

    try {
        const params = new URLSearchParams(window.location.search);
        if (searchQuery) {
            params.set('search', searchQuery);
        }
        if (currentDecade) {
            params.set('decade', currentDecade);
        }
        if (currentSort && currentSort !== 'latest') {
            params.set('sort', currentSort);
        }
        if (currentPage > 1) {
            params.set('page', String(currentPage));
        }

        const queryString = params.toString();
        const url = queryString ? `/api/films?${queryString}` : '/api/films';

        // Update active filter badge if URL has filters
        const filterContainer = document.getElementById('activeFilterContainer');
        const filterLabel = document.getElementById('activeFilterLabel');
        if (filterContainer && filterLabel) {
            const labels = [];
            if (params.get('director')) labels.push(`Yönetmen: ${params.get('director')}`);
            if (params.get('actor')) labels.push(`Oyuncu: ${params.get('actor')}`);
            if (params.get('genre')) labels.push(`Tür: ${params.get('genre')}`);
            if (params.get('year')) labels.push(`Yıl: ${params.get('year')}`);
            if (params.get('decade')) labels.push(`Dönem: ${params.get('decade')}'ler`);
            if (params.get('country')) labels.push(`Ülke: ${params.get('country')}`);

            if (labels.length > 0) {
                filterLabel.textContent = labels.join(' • ');
                filterContainer.classList.remove('hidden');
                filterContainer.classList.add('inline-flex');
            } else {
                filterContainer.classList.add('hidden');
                filterContainer.classList.remove('inline-flex');
            }
        }

        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();

        if (result.success && result.data) {
            const films = Array.isArray(result.data)
                ? result.data
                : (result.data.data || []);
            const total = result.data.total ?? films.length;
            cachedFilms = films;
            cachedFilmsTotal = total;
            cachedPagination = {
                current_page: result.data.current_page || 1,
                last_page: result.data.last_page || 1,
                total: total,
            };
            renderFilms(films, total);
            renderPagination(cachedPagination);
        }
    } catch (error) {
        console.error('Film çekme hatası:', error);
    }
}

function renderLatestReviews(reviews) {
    const container = document.getElementById('latestReviews');
    if (!container) {
        return;
    }

    if (!reviews || reviews.length === 0) {
        container.innerHTML = `<p class="text-sm text-gray-400">Henüz eleştiri eklenmemiş.</p>`;
        return;
    }

    container.innerHTML = reviews.slice(0, 6).map(review => {
        const filmTranslations = review.film?.translations || {};
        const filmTitle = escapeHtml(filmTranslations[currentLang]?.title || review.film?.title || 'Film');
        const filmSlug = review.film?.slug ? `/films/${encodeURIComponent(review.film.slug)}` : '#';
        const authorName = escapeHtml(review.user?.name || 'Anonim');
        const origLang = review.language || 'tr';
        const origLangBadge = LANG_LABELS[origLang] || origLang.toUpperCase();
        const reviewTranslations = review.translations || {};
        const displayedContent = escapeHtml(reviewTranslations[currentLang] || review.content || '');
        const originalContent = escapeHtml(review.content || '');
        const rating = escapeHtml(review.rating ?? '-');

        return `
            <div class="bg-gray-800/60 border border-gray-700/60 rounded-xl p-4 space-y-2">
                <div class="flex items-center justify-between gap-2">
                    <a href="${filmSlug}" class="font-medium text-sm text-white hover:text-primary transition-colors line-clamp-1">${filmTitle}</a>
                    <span class="bg-secondary/20 text-secondary text-xs font-semibold px-2 py-0.5 rounded shrink-0">★ ${rating}/10</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <span class="bg-gray-900 border border-gray-700 text-gray-300 px-2 py-0.5 rounded">${origLangBadge}</span>
                    ${origLang !== currentLang && reviewTranslations[currentLang] ? `<span class="text-primary">→ ${currentLang.toUpperCase()} çevirisi</span>` : ''}
                </div>
                <p class="text-gray-200 text-sm line-clamp-3" id="home-review-text-${review.id}">${displayedContent}</p>
                ${origLang !== currentLang && originalContent !== displayedContent ? `
                    <p class="text-gray-400 text-xs italic line-clamp-2 border-l-2 border-gray-700 pl-2" title="Orijinal (${origLang.toUpperCase()})">${originalContent}</p>
                ` : ''}
                <p class="text-gray-400 text-xs pt-1">— ${authorName}</p>
            </div>
        `;
    }).join('');
}

async function fetchLatestReviews() {
    const container = document.getElementById('latestReviews');
    if (!container) {
        return;
    }

    try {
        const response = await fetch('/api/reviews', {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();

        if (result.success && result.data) {
            const reviews = Array.isArray(result.data)
                ? result.data
                : (result.data.data || []);
            cachedReviews = reviews;
            renderLatestReviews(reviews);
        }
    } catch (error) {
        console.error('Eleştiri çekme hatası:', error);
        container.innerHTML = `<p class="text-sm text-gray-400">Eleştiriler yüklenemedi.</p>`;
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    updateLangButtonsUI();

    const urlParams = new URLSearchParams(window.location.search);

    if (window.SERVER_AUTH?.loggedIn && window.SERVER_AUTH.user) {
        userName = window.SERVER_AUTH.user.name;
        userRole = window.SERVER_AUTH.user.role || 'izleyici';
        localStorage.setItem('userName', userName);
        localStorage.setItem('userRole', userRole);
        updateAuthUI(true);
    } else if (authToken) {
        // Verify token against MySQL database and sync web session cookie
        try {
            const res = await fetch('/api/user', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`,
                },
                credentials: 'same-origin',
            });
            if (res.ok) {
                const payload = await res.json();
                const user = payload.data || payload;
                userName = user.name || userName;
                userRole = user.role || 'izleyici';
                localStorage.setItem('userName', userName);
                localStorage.setItem('userRole', userRole);
                updateAuthUI(true);
                if (urlParams.get('login') === '1') {
                    window.location.href = '/dashboard';
                    return;
                }
            } else {
                // Stale token (e.g. from previous SQLite database) -> clear it
                localStorage.removeItem('authToken');
                localStorage.removeItem('userName');
                localStorage.removeItem('userRole');
                authToken = null;
                userName = null;
                updateAuthUI(false);
                if (urlParams.get('login') === '1') {
                    showLoginModal();
                }
            }
        } catch (e) {
            updateAuthUI(false);
        }
    } else {
        updateAuthUI(false);
        if (urlParams.get('login') === '1') {
            showLoginModal();
        }
    }

    fetchFilms();
    fetchLatestReviews();

    // Search input listener
    const searchInput = document.getElementById('searchInput');
    let searchTimeout = null;
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (query.length === 0 || query.length >= 2) {
                    currentPage = 1;
                    fetchFilms(query);
                }
            }, 250);
        });
    }

    // Login form submission
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const email = formData.get('email');
            const password = formData.get('password');

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    authToken = data.token || null;
                    userName = data.data?.name || email;
                    userRole = data.data?.role || 'izleyici';
                    if (authToken) {
                        localStorage.setItem('authToken', authToken);
                    }
                    localStorage.setItem('userName', userName);
                    localStorage.setItem('userRole', userRole);
                    updateAuthUI(true);
                    hideModals();
                    const redirectAfterLogin = new URLSearchParams(window.location.search).get('login') === '1'
                        ? '/dashboard'
                        : window.location.pathname;
                    window.location.href = redirectAfterLogin;
                } else {
                    alert(data.message || 'Giriş başarısız');
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('Bir hata oluştu');
            }
        });
    }

    // Register form submission
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const name = formData.get('name');
            const email = formData.get('email');
            const password = formData.get('password');
            const password_confirmation = formData.get('password_confirmation');

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ name, email, password, password_confirmation })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    authToken = data.token || null;
                    userName = data.data?.name || name || email;
                    userRole = data.data?.role || 'izleyici';
                    if (authToken) {
                        localStorage.setItem('authToken', authToken);
                    }
                    localStorage.setItem('userName', userName);
                    localStorage.setItem('userRole', userRole);
                    updateAuthUI(true);
                    hideModals();
                    window.location.href = '/dashboard';
                } else {
                    alert(data.message || 'Kayıt başarısız');
                }
            } catch (error) {
                console.error('Register error:', error);
                alert('Bir hata oluştu');
            }
        });
    }
});
