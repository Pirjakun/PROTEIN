
// Buitenworks mini-ecommerce front-end logic
// Single-root localStorage DB, user auth, cart, wishlist, orders, loader, catalog sorting

(function () {
  const STORAGE_KEY = 'buiten_db_v1';

  const PRODUCTS = [
    {
      id: 1,
      title: "BUITENWORKS Vol.3 Nullism Long-sleeve T-Shirt",
      price: 145000,
      type: "longsleeve",
      featured: true,
      img: "assets/prod1.png",
      shopee: "https://shopee.co.id/BUITENWORKS-Vol.3-Nullism-Long-sleeve-T-Shirt-i.1450606504.42816990879?extraParams=%7B%22display_model_id%22%3A301402424922%7D"
    },
    {
      id: 2,
      title: "BUITENWORKS Vol.3 Nullism Long-sleeve T-Shirt (Variant)",
      price: 150000,
      type: "longsleeve",
      featured: false,
      img: "assets/prod2.png",
      shopee: "https://shopee.co.id/BUITENWORKS-Vol.3-Nullism-Long-sleeve-T-Shirt-i.1450606504.40166988322?extraParams=%7B%22display_model_id%22%3A261403389524%7D"
    },
    {
      id: 3,
      title: "BUITENWORKS Vol.1 Stargaze Oversized Boxy T-Shirt",
      price: 150000,
      type: "boxy",
      featured: true,
      img: "assets/prod3.png",
      shopee: "https://shopee.co.id/BUITENWORKS-Vol.1-Stargaze-Oversized-Boxy-T-Shirt-i.1450606504.26874922331?extraParams=%7B%22display_model_id%22%3A242569419158%7D"
    },
    {
      id: 4,
      title: "BUITENWORKS Vol.2 Liquera WHITE Boxy T-Shirt",
      price: 170000,
      type: "boxy",
      featured: false,
      img: "assets/prod4.png",
      shopee: "https://shopee.co.id/BUITENWORKS-Vol.-2-Liquera-WHITE-Boxy-T-Shirt-i.1450606504.26681640067?extraParams=%7B%22display_model_id%22%3A248054076794%7D"
    },
    {
      id: 5,
      title: "BUITENWORKS Vol.2 Liquera BLACK Boxy T-Shirt",
      price: 170000,
      type: "boxy",
      featured: false,
      img: "assets/prod5.png",
      shopee: "https://shopee.co.id/BUITENWORKS-Vol.-2-Liquera-BLACK-Boxy-T-Shirt-i.1450606504.22190736060?extraParams=%7B%22display_model_id%22%3A198375737907%7D"
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
      cart: [],
      wishlist: [],
      orders: []
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
        // Server says Guest, so we force Guest mode locally too
        // Optional: clear local session to keep it clean
        if (db.session) {
          db.session = null;
          saveDB(db);
        }
        return null;
      }

      // Ensure this user exists in local DB for cart persistence
      const existing = db.users.find(u => u.email === window.AUTH_USER.email);
      if (!existing) {
        const newUser = {
          id: 'USR-SERVER-' + window.AUTH_USER.id,
          name: window.AUTH_USER.name,
          email: window.AUTH_USER.email,
          passwordHash: 'SERVER_AUTH',
          cart: [],
          wishlist: [],
          orders: []
        };
        db.users.push(newUser);
        db.session = { loggedInUserId: newUser.id };
        saveDB(db);
        return newUser;
      }
      // Sync session if needed
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
          title: serverProd.name, // Map 'name' to 'title'
          price: parseInt(serverProd.price), // Ensure integer for clean format
          img: '/assets/' + serverProd.image, // Absolute path prefix
          featured: false
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
      // Fallback: redirect to login page if modal doesn't exist
      window.location.href = '/login';
      return;
    }
    m.setAttribute('aria-hidden', 'false');
    document.body.classList.add('no-scroll');
    switchAuthMode(mode || 'login');
  }

  // ... (rest of auth functions)

  function renderCartUI(db) {
    const user = getCurrentUser(db);
    const cart = user ? getUserCart(user) : [];
    const listEl = document.getElementById('cartItems');
    const emptyEl = document.getElementById('cartEmpty');
    const totalEl = document.getElementById('cartTotal');
    const footerEl = document.querySelector('.cart-footer');

    if (!listEl || !emptyEl || !totalEl) return;

    listEl.innerHTML = '';

    if (!cart.length) {
      emptyEl.style.display = 'block';
      if (footerEl) footerEl.style.display = 'none';

      // Custom Empty State
      if (!user) {
        emptyEl.innerHTML = `
            <div class="text-center py-5">
              <h5 class="fw-bold mb-3">Your cart is empty</h5>
              <p class="text-muted mb-4 small px-4">Discover products or log in to pick up where you left off.</p>
              <div class="d-flex flex-column gap-2 px-4">
                 <button class="btn btn-outline-dark w-100 rounded-pill close-drawer-btn">Continue Shopping</button>
                 <a href="/login" class="btn btn-dark w-100 rounded-pill">Login</a>
              </div>
            </div>
         `;
      } else {
        emptyEl.innerHTML = `
            <div class="text-center py-5">
              <h5 class="fw-bold mb-3">Your cart is empty</h5>
              <p class="text-muted mb-4 small">Looks like you haven't added anything to your cart yet.</p>
              <button class="btn btn-outline-dark w-100 rounded-pill close-drawer-btn">Continue Shopping</button>
            </div>
         `;
      }

      // Attach event listeners to new buttons
      const closeBtns = emptyEl.querySelectorAll('.close-drawer-btn');
      closeBtns.forEach(btn => {
        btn.addEventListener('click', function () {
          const drawer = document.getElementById('cartDrawer');
          if (drawer) {
            drawer.classList.remove('open');
            drawer.setAttribute('aria-hidden', 'true');
          }
        });
      });

      totalEl.textContent = formatRupiah(0);
      return;
    }

    emptyEl.style.display = 'none';
    if (footerEl) footerEl.style.display = 'block';

    let total = 0;
    cart.forEach(item => {
      const p = getProductById(item.productId);
      if (!p) return;
      const lineTotal = p.price * item.qty;
      total += lineTotal;
      const sizeLabel = item.size ? `<div class="small text-muted" style="font-size:11px">Size: ${item.size}</div>` : '';
      const row = document.createElement('div');
      row.className = 'cart-row';
      row.innerHTML = `
        <div class="cart-row-main">
          <img src="${p.img}" alt="${p.title}">
          <div class="cart-row-info">
            <div class="cart-row-title">${p.title}</div>
            ${sizeLabel}
            <div class="cart-row-price">${formatRupiah(p.price)}</div>
          </div>
        </div>
        <div class="cart-row-actions">
          <input type="number" min="1" value="${item.qty}" data-id="${p.id}" data-size="${item.size || ''}" class="cart-qty-input" />
          <button class="cart-remove-btn" data-id="${p.id}" data-size="${item.size || ''}">✕</button>
        </div>
      `;
      listEl.appendChild(row);
    });
    totalEl.textContent = formatRupiah(total);
    totalEl.textContent = formatRupiah(total);
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
      row.innerHTML = `
        <div class="cart-row-main">
          <img src="${p.img}" alt="${p.title}">
          <div class="cart-row-info">
            <div class="cart-row-title">${p.title}</div>
            <div class="cart-row-price">${formatRupiah(p.price)}</div>
            <a href="${p.shopee || '#'}" target="_blank" class="small text-muted" style="font-size:11px; text-decoration:underline;">View on Shopee</a>
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
        // Also update catalog hearts if visible
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

    // orders
    const ordersWrap = document.getElementById('ordersList');
    if (ordersWrap) {
      ordersWrap.innerHTML = '';
      if (!user || !user.orders || !user.orders.length) {
        ordersWrap.innerHTML = '<div class="empty">No orders yet.</div>';
      } else {
        user.orders.forEach(o => {
          const div = document.createElement('div');
          div.className = 'order-card';
          const itemCount = o.items ? o.items.reduce((sum, x) => sum + x.qty, 0) : 0;
          div.innerHTML = `
            <div class="order-head">
              <div class="order-id">${o.orderId}</div>
              <div class="order-status">${o.status}</div>
            </div>
            <div class="order-meta">
              <span>${new Date(o.date).toLocaleString()}</span>
              <span>${itemCount} item(s)</span>
              <span>${formatRupiah(o.total)}</span>
            </div>
            <div class="order-source">Source: ${o.source || 'Shopee'}</div>
          `;
          ordersWrap.appendChild(div);
        });
      }
    }

    // wishlist
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
          card.innerHTML = `
            <img src="${p.img}" alt="${p.title}">
            <div class="wishlist-info">
              <div class="wishlist-title">${p.title}</div>
              <div class="wishlist-price">${formatRupiah(p.price)}</div>
              <a href="${p.shopee}" target="_blank" rel="noopener noreferrer" class="wishlist-link">View on Shopee</a>
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

  function renderCatalog(db) {
    const grid = document.getElementById('products');
    if (!grid) return;

    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const typeChips = document.querySelectorAll('.chip[data-type]');
    let state = {
      search: '',
      sort: 'featured',
      type: 'all'
    };

    function apply() {
      let list = PRODUCTS.slice();
      if (state.type !== 'all') {
        list = list.filter(p => p.type === state.type);
      }
      if (state.search) {
        const q = state.search.toLowerCase();
        list = list.filter(p => p.title.toLowerCase().includes(q));
      }
      if (state.sort === 'price-asc') {
        list.sort((a, b) => a.price - b.price);
      } else if (state.sort === 'price-desc') {
        list.sort((a, b) => b.price - a.price);
      } else {
        list.sort((a, b) => {
          if (a.featured && !b.featured) return -1;
          if (!a.featured && b.featured) return 1;
          return a.id - b.id;
        });
      }

      const user = getCurrentUser(db);
      const wishlist = user ? getUserWishlist(user) : [];

      grid.innerHTML = '';
      list.forEach(p => {
        const wishActive = wishlist.includes(p.id);
        const card = document.createElement('article');
        card.className = 'product-card';
        card.setAttribute('data-id', p.id);
        card.innerHTML = `
          <a class="product-link" href="${p.shopee}" target="_blank" rel="noopener noreferrer">
            <div class="product-thumb">
              <img src="${p.img}" alt="${p.title}">
            </div>
          </a>
          <div class="product-info">
            <div class="product-title">${p.title}</div>
            <div class="product-meta-row">
              <span class="product-price">${formatRupiah(p.price)}</span>
              <span class="product-pill">${p.type === 'longsleeve' ? 'Longsleeve' : 'Boxy Tee'}</span>
            </div>
            <div class="product-actions-row">
              <button class="btn add-cart-btn" data-id="${p.id}">Add to Cart</button>
              <button class="wishlist-btn ${wishActive ? 'active' : ''}" data-id="${p.id}" aria-label="wishlist">
                <span class="heart-icon">${wishActive ? '♥' : '♡'}</span>
              </button>
            </div>
          </div>
        `;
        grid.appendChild(card);
      });
    }

    if (searchInput) {
      searchInput.addEventListener('input', function () {
        state.search = this.value || '';
        apply();
      });
    }
    if (sortSelect) {
      sortSelect.addEventListener('change', function () {
        state.sort = this.value;
        apply();
      });
    }
    typeChips.forEach(chip => {
      chip.addEventListener('click', function () {
        typeChips.forEach(c => c.classList.remove('chip-active'));
        this.classList.add('chip-active');
        state.type = this.dataset.type;
        apply();
      });
    });

    apply();

    // event delegation for add to cart and wishlist
    grid.addEventListener('click', function (e) {
      const target = e.target.closest('button');
      if (!target) return;
      const pid = parseInt(target.dataset.id, 10);
      if (target.classList.contains('add-cart-btn')) {
        const dbNow = loadDB();
        const res = addToCart(dbNow, pid);
        if (res.error === 'LOGIN_REQUIRED') {
          openAuth('login');
        } else {
          renderCartUI(dbNow);
        }
      } else if (target.classList.contains('wishlist-btn')) {
        const dbNow = loadDB();
        const res = toggleWishlist(dbNow, pid);
        if (res.error === 'LOGIN_REQUIRED') {
          openAuth('login');
        } else {
          // re-render catalog to refresh hearts
          renderCatalog(dbNow);
        }
      }
    });
  }

  // ---------- Cart & Wishlist Logic (Hoisted) ----------
  function getUserCart(user) { return user.cart || []; }
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

  function addToCart(db, productId, qty = 1, size = null) {
    const user = getCurrentUser(db);
    if (!user) return { error: 'LOGIN_REQUIRED' };
    if (!user.cart) user.cart = [];

    // Find item with same ID AND same Size
    const item = user.cart.find(c => c.productId === productId && c.size === size);
    if (item) {
      item.qty += qty;
    } else {
      user.cart.push({ productId, qty, size });
    }
    saveDB(db);
    return { ok: true, cart: user.cart.slice() };
  }

  function updateCartQty(db, productId, qty, size = null) {
    const user = getCurrentUser(db);
    if (!user || !user.cart) return;

    const item = user.cart.find(c => c.productId === productId && c.size === size);
    if (item) {
      item.qty = qty;
      if (item.qty <= 0) {
        user.cart = user.cart.filter(c => !(c.productId === productId && c.size === size));
      }
    }
    saveDB(db);
  }

  function clearCart(db) {
    const user = getCurrentUser(db);
    if (!user) return;
    user.cart = [];
    saveDB(db);
  }

  function buildOrderFromCart(user) {
    if (!user || !user.cart || !user.cart.length) return null;
    const id = 'ORD-' + Date.now();
    let total = 0;
    const items = user.cart.map(c => {
      const p = getProductById(c.productId);
      if (!p) return null;
      const lineTotal = p.price * c.qty;
      total += lineTotal;
      return {
        productId: p.id,
        title: p.title,
        qty: c.qty,
        size: c.size,
        price: p.price,
        lineTotal
      };
    }).filter(Boolean);
    const order = {
      orderId: id,
      items,
      total,
      date: new Date().toISOString(),
      status: 'Saved',
      source: 'Shopee'
    };
    return order;
  }

  // ---------- main init ----------
  // Expose helpers to window for Blade interaction
  window.loadDB = loadDB;
  window.saveDB = saveDB;
  window.getCurrentUser = getCurrentUser;
  window.getUserWishlist = getUserWishlist;
  window.toggleWishlist = toggleWishlist;
  window.addToCart = addToCart;
  window.renderCartUI = renderCartUI;
  window.renderWishlistUI = renderWishlistUI;

  // ---------- main init ----------
  document.addEventListener('DOMContentLoaded', function () {
    let db = loadDB();

    // cinematic loader on first load
    // open auth from standalone login page
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

    // nav login button
    const loginBtn = document.getElementById('loginBtn');
    if (loginBtn) {
      loginBtn.addEventListener('click', function (e) {
        e.preventDefault();
        db = loadDB();
        const user = getCurrentUser(db);
        if (user) {
          // go to account page
          window.location.href = 'account.html';
        } else {
          openAuth('login');
        }
      });
    }

    // drawer helper
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
        // only hide backdrop if NO other drawer is open (for now we assume one at a time)
        if (backdrop) {
          backdrop.classList.remove('open');
          backdrop.setAttribute('aria-hidden', 'true');
        }
        document.body.classList.remove('no-scroll');
      }
    }

    // cart button (nav)
    if (cartBtn) {
      cartBtn.addEventListener('click', function (e) {
        e.preventDefault();
        toggleDrawer('cartDrawer', true);
        db = loadDB();
        renderCartUI(db);
      });
    }

    // wishlist button (nav)
    const wishlistBtn = document.getElementById('wishlistBtn');
    if (wishlistBtn) {
      wishlistBtn.addEventListener('click', function (e) {
        e.preventDefault();
        toggleDrawer('wishlistDrawer', true);
        db = loadDB();
        renderWishlistUI(db);
      });
    }

    // close cart
    const closeCartBtn = document.getElementById('closeCart');
    if (closeCartBtn) {
      closeCartBtn.addEventListener('click', function (e) {
        e.preventDefault();
        toggleDrawer('cartDrawer', false);
      });
    }

    const closeWishlistBtn = document.getElementById('closeWishlist');
    if (closeWishlistBtn) {
      closeWishlistBtn.addEventListener('click', function (e) {
        e.preventDefault();
        toggleDrawer('wishlistDrawer', false);
      });
    }

    // backdrop click
    const backdrop = document.getElementById('drawerBackdrop');
    if (backdrop) {
      backdrop.addEventListener('click', function () {
        toggleDrawer('cartDrawer', false);
        toggleDrawer('wishlistDrawer', false);
      });
    }

    // cart events (qty & remove)
    const cartItems = document.getElementById('cartItems');
    if (cartItems) {
      cartItems.addEventListener('input', function (e) {
        if (e.target.classList.contains('cart-qty-input')) {
          const id = parseInt(e.target.getAttribute('data-id'), 10);
          const size = e.target.getAttribute('data-size') || null;
          let qty = parseInt(e.target.value, 10) || 1;
          if (qty < 1) qty = 1;
          db = loadDB();
          updateCartQty(db, id, qty, size);
          renderCartUI(db);
        }
      });
      cartItems.addEventListener('click', function (e) {
        if (e.target.classList.contains('cart-remove-btn')) {
          const id = parseInt(e.target.getAttribute('data-id'), 10);
          const size = e.target.getAttribute('data-size') || null;
          db = loadDB();
          updateCartQty(db, id, 0, size);
          renderCartUI(db);
        }
      });
    }

    // clear cart
    const clearCartBtn = document.getElementById('clearCartBtn');
    if (clearCartBtn) {
      clearCartBtn.addEventListener('click', function (e) {
        e.preventDefault();
        db = loadDB();
        clearCart(db);
        renderCartUI(db);
      });
    }

    // checkout
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
      checkoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        db = loadDB();
        const user = getCurrentUser(db);
        if (!user) {
          openAuth('login');
          return;
        }
        const checkoutModal = document.getElementById('checkoutModal');
        if (checkoutModal) {
          checkoutModal.setAttribute('aria-hidden', 'false');
          document.body.classList.add('no-scroll');
        }
      });
    }

    // checkout modal buttons
    const checkoutClose = document.getElementById('closeCheckout');
    if (checkoutClose) {
      checkoutClose.addEventListener('click', function (e) {
        e.preventDefault();
        const m = document.getElementById('checkoutModal');
        if (m) {
          m.setAttribute('aria-hidden', 'true');
          document.body.classList.remove('no-scroll');
        }
      });
    }

    const saveOrderBtn = document.getElementById('saveOrderBtn');
    const saveAndGoBtn = document.getElementById('saveAndGoBtn');
    if (saveOrderBtn) {
      saveOrderBtn.addEventListener('click', function (e) {
        e.preventDefault();
        db = loadDB();
        const res = saveOrderFromCart(db);
        if (!res.error) {
          clearCart(db);
          renderCartUI(db);
          renderAccountPage(db);
          alert('Order saved to your history.');
        }
      });
    }
    if (saveAndGoBtn) {
      saveAndGoBtn.addEventListener('click', function (e) {
        e.preventDefault();
        db = loadDB();
        const user = getCurrentUser(db);
        const res = saveOrderFromCart(db);
        if (res.error) {
          return;
        }
        // redirect to first product's Shopee link
        if (user && user.cart && user.cart.length) {
          const first = user.cart[0];
          const p = getProductById(first.productId);
          clearCart(db);
          renderCartUI(db);
          if (p && p.shopee) {
            window.open(p.shopee, '_blank');
          }
        }
      });
    }

    // auth modal events
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

    // login form submit
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

    // sign up form submit
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

    // logout buttons
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

    // profile form (account settings)
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

    // delete account
    const deleteAccountBtn = document.getElementById('deleteAccountBtn');
    if (deleteAccountBtn) {
      deleteAccountBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const ok = confirm('Are you sure you want to permanently delete your account?');
        if (!ok) return;
        db = loadDB();
        const user = getCurrentUser(db);
        if (!user) return;
        db.users = db.users.filter(u => u.id !== user.id);
        db.session = null;
        saveDB(db);
        alert('Account deleted.');
        window.location.href = 'index.html';
      });
    }

    // init catalog & account page & nav user
    renderNavUser(db);
    renderCatalog(db);
    renderAccountPage(db);
    renderCartUI(db);
    // Init Logic
  });

  // Expose functions to global scope (Now safe because they are defined in IIFE scope)
  window.loadDB = loadDB;
  window.addToCart = addToCart;
  window.renderCartUI = renderCartUI;
  window.toggleWishlist = toggleWishlist;
  window.formatRupiah = formatRupiah;

})();
