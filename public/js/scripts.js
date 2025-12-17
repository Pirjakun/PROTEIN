// Buitenworks mini-ecommerce front-end logic
// Single-root localStorage DB, user auth, wishlist, loader, catalog sorting

(function () {
  console.log(">>> SCRIPTS.JS LOADED <<<");

  const STORAGE_KEY = 'buiten_db_v1';

  // Fallback products (Legacy)
  const PRODUCTS = [
    {
      id: 1,
      title: "BUITENWORKS - Vol.3 Nullism BLACK Long sleeve T-Shirt",
      price: 145000,
      type: "longsleeve",
      featured: true,
      img: "assets/products/prod1.png",
      shopee_link: "https://shopee.co.id/BUITENWORKS-Vol.3-Nullism-Long-sleeve-T-Shirt-i.1450606504.42816990879?extraParams=%7B%22display_model_id%22%3A301402424922%7D"
    },
    {
      id: 2,
      title: "BUITENWORKS - Vol.3 Nullism Grey Long sleeve T-Shirt",
      price: 150000,
      type: "longsleeve",
      featured: false,
      img: "assets/products/prod2.png",
      shopee_link: "https://shopee.co.id/BUITENWORKS-Vol.3-Nullism-Long-sleeve-T-Shirt-i.1450606504.40166988322?extraParams=%7B%22display_model_id%22%3A261403389524%7D"
    },
    {
      id: 3,
      title: "BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt",
      price: 150000,
      type: "boxy",
      featured: true,
      img: "assets/products/prod3.png",
      shopee_link: "https://shopee.co.id/BUITENWORKS-Vol.1-Stargaze-Oversized-Boxy-T-Shirt-i.1450606504.26874922331?extraParams=%7B%22display_model_id%22%3A242569419158%7D"
    },
    {
      id: 4,
      title: "BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt",
      price: 170000,
      type: "boxy",
      featured: false,
      img: "assets/products/prod4.png",
      shopee_link: "https://shopee.co.id/BUITENWORKS-Vol.-2-Liquera-WHITE-Boxy-T-Shirt-i.1450606504.26681640067?extraParams=%7B%22display_model_id%22%3A248054076794%7D"
    },
    {
      id: 5,
      title: "BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt",
      price: 170000,
      type: "boxy",
      featured: false,
      img: "assets/products/prod5.png",
      shopee_link: "https://shopee.co.id/BUITENWORKS-Vol.-2-Liquera-BLACK-Boxy-T-Shirt-i.1450606504.22190736060?extraParams=%7B%22display_model_id%22%3A198375737907%7D"
    }
  ];

  // ---------- helpers ----------
  function loadDB() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return { users: [], session: null };
      const parsed = JSON.parse(raw);
      if (!parsed.users) parsed.users = [];
      return parsed;
    } catch (e) {
      console.error('Failed to parse DB', e);
      return { users: [], session: null };
    }
  }

  function saveDB(db) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(db));
  }

  function hashPassword(pw) {
    try {
      return btoa(unescape(encodeURIComponent(pw)));
    } catch (e) {
      return pw;
    }
  }

  function findUserByEmail(db, email) {
    email = (email || '').trim().toLowerCase();
    return db.users.find(u => u.email === email) || null;
  }

  function createUser(db, payload) {
    const id = 'USR-' + Date.now();
    const user = {
      id,
      name: payload.name || '',
      email: (payload.email || '').trim().toLowerCase(),
      passwordHash: hashPassword(payload.password || ''),
      birthdate: payload.birthdate || '',
      wishlist: []
    };
    db.users.push(user);
    db.session = { loggedInUserId: id };
    saveDB(db);
    return user;
  }

  function getCurrentUser(db) {
    // Priority 1: Server Side User (injected via Blade)
    if (typeof window.AUTH_USER !== 'undefined') {
      if (window.AUTH_USER === null) {
        if (db.session) {
          db.session = null;
          saveDB(db);
        }
        return null;
      }

      const existing = db.users.find(u => u.email === window.AUTH_USER.email);
      if (!existing) {
        const newUser = {
          id: 'USR-SERVER-' + window.AUTH_USER.id,
          name: window.AUTH_USER.name,
          email: window.AUTH_USER.email,
          passwordHash: 'SERVER_AUTH',
          wishlist: []
        };
        db.users.push(newUser);
        db.session = { loggedInUserId: newUser.id };
        saveDB(db);
        return newUser;
      }
      if (!db.session || db.session.loggedInUserId !== existing.id) {
        db.session = { loggedInUserId: existing.id };
        saveDB(db);
      }
      return existing;
    }

    // Priority 2: Client Side User (Legacy / Dev)
    if (!db.session || !db.session.loggedInUserId) return null;
    return db.users.find(u => u.id === db.session.loggedInUserId) || null;
  }

  function setSession(db, user) {
    if (user) {
      db.session = { loggedInUserId: user.id };
    } else {
      db.session = null;
    }
    saveDB(db);
  }

  function formatRupiah(v) {
    if (typeof v !== 'number') return v;
    return 'Rp ' + v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  function getProductById(id) {
    // 1. Check Server Products (injected via Blade)
    if (typeof window.SERVER_PRODUCTS !== 'undefined' && Array.isArray(window.SERVER_PRODUCTS)) {
      const serverProd = window.SERVER_PRODUCTS.find(p => p.id == id);
      if (serverProd) {
        return {
          id: serverProd.id,
          title: serverProd.name,
          price: parseInt(serverProd.price),
          img: '/assets/products/' + serverProd.image,
          featured: false,
          shopee_link: serverProd.shopee_link
        };
      }
    }
    // 2. Fallback to hardcoded (Legacy)
    return PRODUCTS.find(p => p.id == id) || null;
  }

  // ---------- loader ----------
  function showLoader(mode) {
    const el = document.getElementById('pageLoader');
    if (!el) return;
    el.setAttribute('data-mode', mode || 'full');
    el.setAttribute('aria-hidden', 'false');
    document.body.classList.add('no-scroll');
    const fill = el.querySelector('.loader-bar-fill');
    if (fill) {
      fill.style.width = '0%';
      setTimeout(() => { fill.style.width = '100%'; }, 20);
    }
  }
  function hideLoader() {
    const el = document.getElementById('pageLoader');
    if (!el) return;
    el.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('no-scroll');
  }

  // ---------- auth modal or redirect ----------
  function openAuth(mode) {
    const m = document.getElementById('authModal');
    if (!m) {
      window.location.href = '/login';
      return;
    }
    m.setAttribute('aria-hidden', 'false');
    document.body.classList.add('no-scroll');
    switchAuthMode(mode || 'login');
  }

  function closeAuth() {
    const m = document.getElementById('authModal');
    if (m) {
      m.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('no-scroll');
    }
  }

  function switchAuthMode(mode) {
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const tabs = document.querySelectorAll('.auth-tab');

    tabs.forEach(t => {
      if (t.dataset.mode === mode) t.classList.add('active');
      else t.classList.remove('active');
    });

    if (mode === 'login') {
      if (loginForm) loginForm.style.display = 'block';
      if (signupForm) signupForm.style.display = 'none';
    } else {
      if (loginForm) loginForm.style.display = 'none';
      if (signupForm) signupForm.style.display = 'block';
    }
  }

  function renderNavUser(db) {
    // Handled by server-side blade usually, but for client-side legacy
  }

  function renderWishlistUI(db) {
    const user = getCurrentUser(db);
    const wishlist = user ? getUserWishlist(user) : [];
    const listEl = document.getElementById('wishlistItems');
    const emptyEl = document.getElementById('wishlistEmpty');

    if (!listEl || !emptyEl) return;

    listEl.innerHTML = '';

    if (!wishlist.length) {
      emptyEl.style.display = 'block';
      return;
    }

    emptyEl.style.display = 'none';

    wishlist.forEach(pid => {
      const p = getProductById(pid);
      if (!p) return;

      const row = document.createElement('div');
      row.className = 'cart-row';
      const link = p.shopee_link || '#';
      row.innerHTML = `
  <div class="cart-row-main">
          <img src="${p.img}" alt="${p.title}">
          <div class="cart-row-info">
            <div class="cart-row-title">${p.title}</div>
            <div class="cart-row-price">${formatRupiah(p.price)}</div>
            <a href="${link}" target="_blank" class="small text-muted" style="font-size:11px; text-decoration:underline;">View on Shopee</a>
          </div>
        </div>
        <div class="cart-row-actions">
           <button class="cart-remove-btn wishlist-remove-btn" data-id="${p.id}">✕</button>
        </div>
`;
      listEl.appendChild(row);
    });

    // Event delegation for remove buttons
    const removeBtns = listEl.querySelectorAll('.wishlist-remove-btn');
    removeBtns.forEach(btn => {
      btn.addEventListener('click', function () {
        const pid = parseInt(this.getAttribute('data-id'), 10);
        const dbNow = loadDB();
        toggleWishlist(dbNow, pid);
        renderWishlistUI(dbNow);
        renderCatalog(dbNow);
      });
    });
  }

  function renderAccountPage(db) {
    const root = document.querySelector('.account-page');
    if (!root) return;
    const user = getCurrentUser(db);
    const nameLine = document.getElementById('accountNameLine');
    const emailLine = document.getElementById('accountEmailLine');
    if (user) {
      if (nameLine) nameLine.textContent = 'Hi, ' + (user.name || user.email);
      if (emailLine) emailLine.textContent = user.email;
    } else {
      if (nameLine) nameLine.textContent = 'You are not logged in';
      if (emailLine) emailLine.textContent = 'Please login to see your account details.';
    }

    // wishlist in account page
    const wishlistWrap = document.getElementById('wishlistList');
    if (wishlistWrap) {
      wishlistWrap.innerHTML = '';
      if (!user || !user.wishlist || !user.wishlist.length) {
        wishlistWrap.innerHTML = '<div class="empty">No wishlist yet.</div>';
      } else {
        user.wishlist.forEach(pid => {
          const p = getProductById(pid);
          if (!p) return;
          const card = document.createElement('article');
          card.className = 'wishlist-card';
          const link = p.shopee_link || '#';
          card.innerHTML = `
   <img src = "${p.img}" alt = "${p.title}" >
    <div class="wishlist-info">
      <div class="wishlist-title">${p.title}</div>
      <div class="wishlist-price">${formatRupiah(p.price)}</div>
      <a href="${link}" target="_blank" rel="noopener noreferrer" class="wishlist-link">View on Shopee</a>
    </div>
`;
          wishlistWrap.appendChild(card);
        });
      }
    }

    // settings/profile form
    const profileForm = document.getElementById('profileForm');
    if (profileForm && user) {
      profileForm.elements['name'].value = user.name || '';
      profileForm.elements['email'].value = user.email || '';
      profileForm.elements['birthdate'].value = user.birthdate || '';
    }
  }

  // Catalog State
  let catalogState = {
    search: '',
    sort: 'featured',
    filter: 'all'
  };

  // 1. Get Source Data (Server Products preferred)
  function getSourceData() {
    if (typeof window.SERVER_PRODUCTS !== 'undefined' && Array.isArray(window.SERVER_PRODUCTS)) {
      return window.SERVER_PRODUCTS.map(sp => ({
        id: sp.id,
        title: sp.name,
        price: Number(sp.price), // Ensure number
        type: sp.category,
        featured: false,
        img: '/assets/products/' + sp.image, // Ensure absolute path with leading slash if needed, or relative 'assets/'
        shopee_link: sp.shopee_link
      }));
    } else {
      return PRODUCTS.slice();
    }
  }

  function renderCatalog(db) {
    const grid = document.getElementById('products');
    if (!grid) return;

    let list = getSourceData();

    // Helper to get volume
    const getVol = (title) => {
      const match = title.match(/Vol\.?\s*(\d+)/i);
      return match ? parseInt(match[1], 10) : 0;
    };

    // Filter
    if (catalogState.filter === 'featured') {
      // Featured: Vol.3 Collection + Vol.1 Stargaze
      list = list.filter(p => {
        const t = p.title.toLowerCase();
        return t.includes('vol.3') || t.includes('vol.1 stargaze');
      });
    } else if (catalogState.filter !== 'all') {
      list = list.filter(p => (p.type || '').toLowerCase() === (catalogState.filter || '').toLowerCase());
    }

    // Search
    if (catalogState.search) {
      const q = catalogState.search.toLowerCase();
      list = list.filter(p => p.title.toLowerCase().includes(q));
    }

    // Sort
    console.log('Sorting by:', catalogState.sort);
    if (catalogState.sort === 'price-asc') {
      list.sort((a, b) => {
        return Number(a.price) - Number(b.price);
      });
    } else if (catalogState.sort === 'price-desc') {
      list.sort((a, b) => {
        return Number(b.price) - Number(a.price);
      });
    } else {
      // Default / Featured: Sort by Volume (Newest/Highest Vol first)
      list.sort((a, b) => {
        const getVol = (title) => {
          const match = title.match(/Vol\.?\s*(\d+)/i);
          return match ? parseInt(match[1], 10) : 0;
        };
        const volA = getVol(a.title);
        const volB = getVol(b.title);

        // If volumes differ, higher volume comes first
        if (volB !== volA) return volB - volA;

        // If volumes are same or not found, fallback to ID (newer ID first usually implies newer addition)
        return b.id - a.id;
      });
    }

    const user = getCurrentUser(db);
    const wishlist = user ? getUserWishlist(user) : [];

    grid.innerHTML = '';
    if (list.length === 0) {
      grid.innerHTML = '<div class="col-12 text-center text-muted py-5"><p>No products found.</p></div>';
      return;
    }

    list.forEach(p => {
      const wishActive = wishlist.includes(p.id);
      const cardWrapper = document.createElement('div');
      cardWrapper.className = 'col-6 col-md-4 col-lg-4';

      const shopeeLink = p.shopee_link || '#';

      cardWrapper.innerHTML = `
  <div class="d-flex flex-column h-100 product-card-hover rounded p-2">
            <a class="text-decoration-none text-dark mb-2" href="products/${p.id}">
                <div class="position-relative bg-white rounded overflow-hidden ratio ratio-1x1 mb-3">
                    <img src="${p.img}" class="img-fluid object-fit-cover p-0 w-100 h-100" alt="${p.title}">
                </div>
                <div class="text-start px-2">
                    <div class="fw-bold text-uppercase small mb-1" style="font-family: 'Oswald', sans-serif;">
                        ${p.title}
                    </div>
                    <div class="fw-semibold small">${formatRupiah(p.price)}</div>
                </div>
            </a>
            <div class="px-2 mt-auto d-flex gap-2">
                 <a href="${shopeeLink}" target="_blank" class="btn btn-dark btn-sm flex-grow-1 shopee-btn" style="font-size: 12px;">Buy on Shopee</a>
                 <button class="wishlist-btn ${wishActive ? 'active' : ''} btn btn-link p-0 text-dark border-0" data-id="${p.id}">
                    ${wishActive
          ? `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart-fill text-danger" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/></svg>`
          : `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16"><path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/></svg>`
        }
                 </button>
            </div>
        </div >
  `;
      grid.appendChild(cardWrapper);
    });
  }

  function initCatalogListeners() {
    console.log(">>> initCatalogListeners executing... <<<");
    const grid = document.getElementById('products');
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');

    console.log("Elements found:", {
      grid: !!grid,
      searchInput: !!searchInput,
      sortSelect: !!sortSelect
    });

    const filterRadios = document.querySelectorAll('.filter-check');

    // Listeners
    if (searchInput) {
      searchInput.value = catalogState.search;
      searchInput.addEventListener('input', function () {
        catalogState.search = this.value || '';
        renderCatalog(loadDB());
      });
    }
    if (sortSelect) {
      sortSelect.value = catalogState.sort;
      sortSelect.addEventListener('change', function () {
        console.log('Sort changed to:', this.value);
        catalogState.sort = this.value;
        renderCatalog(loadDB());
      });
    }

    // Sort logic inside renderCatalog is already using catalogState.sort
    // But let's verify renderCatalog reads it correctly.
    console.log('Catalog logic initialized. State:', catalogState);

    filterRadios.forEach(radio => {
      if (radio.value === catalogState.filter) radio.checked = true; // Sync view
      radio.addEventListener('change', function () {
        if (this.checked) {
          catalogState.filter = this.value;
          renderCatalog(loadDB());
        }
      });
    });

    // Initial render
    renderCatalog(loadDB());

    // Grid Listener for Wishlist
    if (grid) {
      grid.addEventListener('click', function (e) {
        const target = e.target.closest('button');
        if (!target) return;
        const pid = parseInt(target.dataset.id, 10);
        if (target.classList.contains('wishlist-btn')) {
          const dbNow = loadDB();
          const res = toggleWishlist(dbNow, pid);
          if (res.error === 'LOGIN_REQUIRED') {
            openAuth('login');
          } else {
            renderCatalog(dbNow);
          }
        }
      });
    }
  }

  // ---------- Wishlist Logic ----------
  function getUserWishlist(user) { return user.wishlist || []; }

  function toggleWishlist(db, productId) {
    const user = getCurrentUser(db);
    if (!user) return { error: 'LOGIN_REQUIRED' };
    if (!user.wishlist) user.wishlist = [];
    const idx = user.wishlist.indexOf(productId);
    if (idx >= 0) user.wishlist.splice(idx, 1);
    else user.wishlist.push(productId);
    saveDB(db);
    return { ok: true, wishlist: user.wishlist.slice() };
  }

  // ---------- main init ----------
  window.loadDB = loadDB;
  window.saveDB = saveDB;
  window.getCurrentUser = getCurrentUser;
  window.getUserWishlist = getUserWishlist;
  window.toggleWishlist = toggleWishlist;
  window.renderWishlistUI = renderWishlistUI;

  document.addEventListener('DOMContentLoaded', function () {
    let db = loadDB();

    const openAuthBtnPage = document.getElementById('openAuthFromPage');
    if (openAuthBtnPage) {
      openAuthBtnPage.addEventListener('click', function (e) {
        e.preventDefault();
        openAuth('login');
      });
    }

    const already = sessionStorage.getItem('buiten_seen_loader');
    if (!already) {
      sessionStorage.setItem('buiten_seen_loader', '1');
      showLoader('full');
      setTimeout(hideLoader, 2300);
    }

    const loginBtn = document.getElementById('loginBtn');
    if (loginBtn) {
      loginBtn.addEventListener('click', function (e) {
        e.preventDefault();
        db = loadDB();
        const user = getCurrentUser(db);
        if (user) {
          window.location.href = 'account.html';
        } else {
          openAuth('login');
        }
      });
    }

    function toggleDrawer(drawerId, open) {
      const drawer = document.getElementById(drawerId);
      const backdrop = document.getElementById('drawerBackdrop');
      if (!drawer) return;

      if (open) {
        drawer.classList.add('open');
        drawer.setAttribute('aria-hidden', 'false');
        if (backdrop) {
          backdrop.classList.add('open');
          backdrop.setAttribute('aria-hidden', 'false');
        }
        document.body.classList.add('no-scroll');
      } else {
        drawer.classList.remove('open');
        drawer.setAttribute('aria-hidden', 'true');
        if (backdrop) {
          backdrop.classList.remove('open');
          backdrop.setAttribute('aria-hidden', 'true');
        }
        document.body.classList.remove('no-scroll');
      }
    }

    // Note: Cart button logic removed.

    // Updated to handle both desktop and mobile wishlist buttons
    const wishlistTriggerSelectors = ['#wishlistBtn', '#wishlistBtnMobile'];
    wishlistTriggerSelectors.forEach(selector => {
      const btn = document.querySelector(selector);
      if (btn) {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          toggleDrawer('wishlistDrawer', true);
          db = loadDB();
          renderWishlistUI(db);
        });
      }
    });

    const closeWishlistBtn = document.getElementById('closeWishlist');
    if (closeWishlistBtn) {
      closeWishlistBtn.addEventListener('click', function (e) {
        e.preventDefault();
        toggleDrawer('wishlistDrawer', false);
      });
    }

    const backdrop = document.getElementById('drawerBackdrop');
    if (backdrop) {
      backdrop.addEventListener('click', function () {
        toggleDrawer('wishlistDrawer', false);
      });
    }

    const authTabs = document.querySelectorAll('.auth-tab');
    authTabs.forEach(tab => {
      tab.addEventListener('click', function () {
        switchAuthMode(this.dataset.mode);
      });
    });
    const closeAuthBtn = document.getElementById('closeAuth');
    if (closeAuthBtn) {
      closeAuthBtn.addEventListener('click', function (e) {
        e.preventDefault();
        closeAuth();
      });
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
      loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const email = this.elements['email'].value;
        const password = this.elements['password'].value;
        db = loadDB();
        const user = findUserByEmail(db, email);
        const err = document.getElementById('authError');
        if (!user || user.passwordHash !== hashPassword(password)) {
          if (err) err.textContent = 'Invalid email or password.';
          return;
        }
        setSession(db, user);
        renderNavUser(db);
        renderAccountPage(db);
        closeAuth();
        if (window.location.pathname.indexOf('account.html') === -1) {
          window.location.href = 'account.html';
        }
      });
    }

    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
      signupForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const name = this.elements['name'].value;
        const email = this.elements['email'].value;
        const password = this.elements['password'].value;
        const birthdate = this.elements['birthdate'].value;
        const err = document.getElementById('authError');
        db = loadDB();
        if (findUserByEmail(db, email)) {
          if (err) err.textContent = 'Email already registered.';
          return;
        }
        const user = createUser(db, { name, email, password, birthdate });
        renderNavUser(db);
        renderAccountPage(db);
        closeAuth();
        window.location.href = 'account.html';
      });
    }

    document.querySelectorAll('.logout-btn').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        db = loadDB();
        setSession(db, null);
        renderNavUser(db);
        if (window.location.pathname.indexOf('account.html') !== -1) {
          window.location.href = 'index.html';
        }
      });
    });

    // init catalog listeners
    renderNavUser(db);
    initCatalogListeners();

    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
      profileForm.addEventListener('submit', function (e) {
        e.preventDefault();
        db = loadDB();
        const user = getCurrentUser(db);
        if (!user) return;
        user.name = this.elements['name'].value;
        user.email = this.elements['email'].value;
        user.birthdate = this.elements['birthdate'].value;
        saveDB(db);
        renderNavUser(db);
        renderAccountPage(db);
        alert('Profile updated.');
      });
    }
  });

})();
