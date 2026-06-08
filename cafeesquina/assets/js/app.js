/**
 * CAFEESQUINA — Frontend 2026 (Vanilla JS)
 */
const CE = {
    init() {
        this.theme.init();
        this.loader.init();
        this.nav.init();
        this.carousel.init();
        this.whatsapp.init();
        this.forms.init();
        this.catalog.init();
        this.cart.init();
        this.modals.init();
        this.deleteConfirm.init();
    },

    theme: {
        key: 'ce-theme',
        init() {
            const saved = localStorage.getItem(this.key);
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            this.set(theme);
            document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
                    this.set(next);
                });
            });
        },
        set(theme) {
            document.documentElement.dataset.theme = theme;
            localStorage.setItem(this.key, theme);
            document.querySelectorAll('[data-theme-icon]').forEach((el) => {
                el.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            });
        },
    },

    loader: {
        init() {
            const el = document.getElementById('page-loader');
            if (!el) return;
            window.addEventListener('load', () => {
                setTimeout(() => el.classList.add('hide'), 350);
                setTimeout(() => el.remove(), 900);
            });
        },
    },

    nav: {
        init() {
            const nav = document.querySelector('[data-site-nav]');
            const toggle = document.querySelector('[data-nav-toggle]');
            const panel = document.querySelector('[data-nav-panel]');
            if (toggle && panel) {
                toggle.addEventListener('click', () => {
                    const open = panel.classList.toggle('is-open');
                    toggle.setAttribute('aria-expanded', open);
                });
            }
            if (nav) {
                const onScroll = () => nav.classList.toggle('is-scrolled', window.scrollY > 8);
                window.addEventListener('scroll', onScroll, { passive: true });
                onScroll();
            }
        },
    },

    carousel: {
        init() {
            document.querySelectorAll('[data-carousel]').forEach((root) => {
                const track = root.querySelector('.carousel-track');
                const slides = root.querySelectorAll('.carousel-slide');
                if (!track || !slides.length) return;
                let i = 0;
                setInterval(() => {
                    i = (i + 1) % slides.length;
                    track.style.transform = `translateX(-${i * 100}%)`;
                }, 5000);
            });
        },
    },

    whatsapp: {
        init() {
            document.querySelectorAll('[data-whatsapp-order]').forEach((btn) => {
                btn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const loginUrl = btn.dataset.loginUrl || '/login?compra=1';
                    if (btn.dataset.requireLogin === '1') {
                        window.location.href = loginUrl;
                        return;
                    }
                    const id = btn.dataset.productId;
                    const url = btn.dataset.waUrl;
                    if (id && btn.dataset.logUrl) {
                        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                        const headers = { 'Content-Type': 'application/json' };
                        if (csrf) headers['X-CSRF-TOKEN'] = csrf;
                        try {
                            const res = await fetch(btn.dataset.logUrl, {
                                method: 'POST',
                                headers,
                                credentials: 'same-origin',
                                body: JSON.stringify({ product_id: parseInt(id, 10) }),
                            });
                            if (res.status === 401) {
                                const data = await res.json().catch(() => ({}));
                                window.location.href = data.redirect || loginUrl;
                                return;
                            }
                        } catch (_) { /* noop */ }
                    }
                    if (url) window.open(url, '_blank', 'noopener');
                });
            });
        },
    },

    forms: {
        init() {
            document.querySelectorAll('[data-password-toggle]').forEach((btn) => {
                const input = document.getElementById(btn.dataset.passwordToggle);
                if (!input) return;
                btn.addEventListener('click', () => {
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.querySelector('i').className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
                    btn.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
                });
            });

            const pwd = document.querySelector('[data-password-strength]');
            if (pwd) {
                const bar = document.querySelector('[data-pwd-bar]');
                const label = document.querySelector('[data-pwd-label]');
                pwd.addEventListener('input', () => {
                    const s = this.passwordStrength(pwd.value);
                    if (bar) {
                        bar.style.width = `${s.percent}%`;
                        bar.style.background = s.color;
                    }
                    if (label) label.textContent = s.text;
                });
            }

            document.querySelectorAll('[data-validate-form]').forEach((form) => {
                form.addEventListener('submit', (e) => {
                    if (!this.validateForm(form)) e.preventDefault();
                });
                form.querySelectorAll('.input-field').forEach((input) => {
                    input.addEventListener('blur', () => this.validateField(input));
                    input.addEventListener('input', () => {
                        if (input.classList.contains('is-invalid')) this.validateField(input);
                    });
                });
            });
        },

        passwordStrength(value) {
            let score = 0;
            if (value.length >= 8) score++;
            if (/[A-Z]/.test(value)) score++;
            if (/[0-9]/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;
            const map = [
                { percent: 15, color: '#dc2626', text: 'Muy débil' },
                { percent: 35, color: '#ea580c', text: 'Débil' },
                { percent: 55, color: '#ca8a04', text: 'Regular' },
                { percent: 75, color: '#65a30d', text: 'Buena' },
                { percent: 100, color: '#16a34a', text: 'Excelente' },
            ];
            return map[Math.min(score, 4)];
        },

        validateForm(form) {
            let ok = true;
            form.querySelectorAll('[data-validate]').forEach((input) => {
                if (!this.validateField(input)) ok = false;
            });
            return ok;
        },

        validateField(input) {
            const rules = (input.dataset.validate || '').split('|');
            let valid = true;
            let msg = '';
            const v = input.value.trim();
            const confirm = input.dataset.confirm;

            rules.forEach((rule) => {
                if (!valid) return;
                if (rule === 'required' && !v) { valid = false; msg = 'Este campo es obligatorio.'; }
                if (rule === 'email' && v && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) { valid = false; msg = 'Correo no válido.'; }
                if (rule === 'min:8' && v.length < 8) { valid = false; msg = 'Mínimo 8 caracteres.'; }
                if (rule === 'match' && confirm) {
                    const other = document.querySelector(`[name="${confirm}"]`);
                    if (other && v !== other.value) { valid = false; msg = 'Las contraseñas no coinciden.'; }
                }
            });

            input.classList.toggle('is-invalid', !valid);
            input.classList.toggle('is-valid', valid && v.length > 0);
            input.setAttribute('aria-invalid', !valid);
            let err = input.parentElement.querySelector('.input-error');
            if (!valid) {
                if (!err) {
                    err = document.createElement('p');
                    err.className = 'input-error';
                    err.setAttribute('role', 'alert');
                    input.parentElement.appendChild(err);
                }
                err.textContent = msg;
            } else if (err) err.remove();
            return valid;
        },
    },

    catalog: {
        setVisible(el, visible) {
            el.hidden = !visible;
            el.classList.toggle('hidden', !visible);
        },
        init() {
            const grid = document.querySelector('[data-catalog-grid]');
            const skeleton = document.querySelector('[data-catalog-skeleton]');
            const search = document.querySelector('[data-instant-search]');
            if (skeleton && grid) {
                this.setVisible(skeleton, true);
                this.setVisible(grid, false);
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        this.setVisible(skeleton, false);
                        this.setVisible(grid, true);
                    }, 400);
                });
            }
            if (search) {
                const cards = document.querySelectorAll('[data-product-card]');
                search.addEventListener('input', () => {
                    const q = search.value.toLowerCase();
                    cards.forEach((card) => {
                        const text = card.dataset.searchText || '';
                        card.hidden = q.length > 0 && !text.includes(q);
                    });
                });
            }
        },
    },

    modals: {
        init() {
            window.openModal = (id) => {
                const el = document.getElementById(id);
                if (el) {
                    el.classList.add('is-open');
                    el.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }
            };
            window.closeModal = (id) => {
                const el = document.getElementById(id);
                if (el) {
                    el.classList.remove('is-open');
                    el.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }
            };
            document.querySelectorAll('[data-modal-close]').forEach((btn) => {
                btn.addEventListener('click', () => closeModal(btn.dataset.modalClose));
            });
            document.querySelectorAll('.modal-backdrop').forEach((backdrop) => {
                backdrop.addEventListener('click', (e) => {
                    if (e.target === backdrop) closeModal(backdrop.id);
                });
            });
        },
    },

    cart: {
        key: 'ce-cart',

        getItems() {
            try {
                const raw = localStorage.getItem(this.key);
                const items = raw ? JSON.parse(raw) : [];
                return Array.isArray(items) ? items : [];
            } catch (_) {
                return [];
            }
        },

        saveItems(items) {
            localStorage.setItem(this.key, JSON.stringify(items));
            this.updateBadges();
            if (document.querySelector('[data-cart-page]')) {
                this.renderPage();
            }
        },

        count() {
            return this.getItems().reduce((sum, item) => sum + (item.qty || 0), 0);
        },

        add(product) {
            const id = parseInt(product.id, 10);
            if (!id || !product.name) return;
            const items = this.getItems();
            const existing = items.find((i) => i.id === id);
            if (existing) {
                existing.qty = Math.min(99, (existing.qty || 0) + 1);
            } else {
                items.push({
                    id,
                    name: String(product.name),
                    price: parseFloat(product.price) || 0,
                    image: product.image || '',
                    qty: 1,
                });
            }
            this.saveItems(items);
            showToast('success', 'Añadido al carrito');
        },

        updateQty(id, qty) {
            const items = this.getItems().map((item) => {
                if (item.id !== id) return item;
                return { ...item, qty: Math.max(1, Math.min(99, qty)) };
            });
            this.saveItems(items);
        },

        remove(id) {
            this.saveItems(this.getItems().filter((item) => item.id !== id));
        },

        clear() {
            this.saveItems([]);
        },

        total() {
            return this.getItems().reduce((sum, item) => sum + (item.price || 0) * (item.qty || 0), 0);
        },

        updateBadges() {
            const count = this.count();
            document.querySelectorAll('[data-cart-count]').forEach((el) => {
                el.textContent = String(count);
                el.hidden = count === 0;
                el.classList.toggle('hidden', count === 0);
            });
        },

        formatMoney(value) {
            return '$' + (parseFloat(value) || 0).toFixed(2);
        },

        renderPage() {
            const page = document.querySelector('[data-cart-page]');
            if (!page) return;
            const list = page.querySelector('[data-cart-list]');
            const empty = page.querySelector('[data-cart-empty]');
            const summary = page.querySelector('[data-cart-summary]');
            const totalEl = page.querySelector('[data-cart-total]');
            const items = this.getItems();
            if (!list) return;

            list.innerHTML = '';
            if (items.length === 0) {
                if (empty) empty.hidden = false;
                if (summary) summary.hidden = true;
                return;
            }
            if (empty) empty.hidden = true;
            if (summary) summary.hidden = false;
            if (totalEl) totalEl.textContent = this.formatMoney(this.total());

            items.forEach((item) => {
                const row = document.createElement('article');
                row.className = 'cart-item';
                row.dataset.cartItem = String(item.id);

                const imgWrap = document.createElement('div');
                imgWrap.className = 'cart-item__img';
                const img = document.createElement('img');
                img.src = item.image || 'https://images.unsplash.com/photo-1514432324607-09f969782a96?w=200';
                img.alt = item.name;
                img.loading = 'lazy';
                imgWrap.appendChild(img);

                const body = document.createElement('div');
                body.className = 'cart-item__body';
                const name = document.createElement('h3');
                name.className = 'cart-item__name';
                name.textContent = item.name;
                const price = document.createElement('p');
                price.className = 'cart-item__price';
                price.textContent = this.formatMoney(item.price);

                const qtyRow = document.createElement('div');
                qtyRow.className = 'cart-item__qty';
                const minus = document.createElement('button');
                minus.type = 'button';
                minus.className = 'cart-qty-btn';
                minus.dataset.cartQty = 'dec';
                minus.dataset.productId = String(item.id);
                minus.setAttribute('aria-label', 'Disminuir cantidad');
                minus.innerHTML = '<i class="fas fa-minus"></i>';
                const qtyVal = document.createElement('span');
                qtyVal.className = 'cart-qty-val';
                qtyVal.textContent = String(item.qty);
                const plus = document.createElement('button');
                plus.type = 'button';
                plus.className = 'cart-qty-btn';
                plus.dataset.cartQty = 'inc';
                plus.dataset.productId = String(item.id);
                plus.setAttribute('aria-label', 'Aumentar cantidad');
                plus.innerHTML = '<i class="fas fa-plus"></i>';

                qtyRow.append(minus, qtyVal, plus);

                const sub = document.createElement('p');
                sub.className = 'cart-item__subtotal';
                sub.textContent = 'Subtotal: ' + this.formatMoney(item.price * item.qty);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'cart-item__remove';
                removeBtn.dataset.cartRemove = String(item.id);
                removeBtn.textContent = 'Quitar';

                body.append(name, price, qtyRow, sub, removeBtn);
                row.append(imgWrap, body);
                list.appendChild(row);
            });
        },

        async checkout(btn) {
            const items = this.getItems();
            if (items.length === 0) {
                showToast('error', 'Tu carrito está vacío');
                return;
            }
            const url = btn.dataset.checkoutUrl;
            if (!url) return;
            btn.disabled = true;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const headers = { 'Content-Type': 'application/json' };
            if (csrf) headers['X-CSRF-TOKEN'] = csrf;
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers,
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        items: items.map((i) => ({ product_id: i.id, qty: i.qty })),
                    }),
                });
                const data = await res.json();
                if (!data.ok) {
                    showToast('error', 'No se pudo procesar el pedido');
                    return;
                }
                this.clear();
                if (data.wa_url) window.open(data.wa_url, '_blank', 'noopener');
                showToast('success', 'Pedido registrado. Abriendo WhatsApp…');
            } catch (_) {
                showToast('error', 'Error de conexión');
            } finally {
                btn.disabled = false;
            }
        },

        init() {
            document.querySelectorAll('[data-add-to-cart]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    this.add({
                        id: btn.dataset.productId,
                        name: btn.dataset.productName,
                        price: btn.dataset.productPrice,
                        image: btn.dataset.productImage,
                    });
                });
            });

            const page = document.querySelector('[data-cart-page]');
            if (page) {
                this.renderPage();
                page.addEventListener('click', (e) => {
                    const t = e.target.closest('[data-cart-qty], [data-cart-remove], [data-cart-clear], [data-cart-checkout]');
                    if (!t) return;
                    if (t.matches('[data-cart-checkout]')) {
                        e.preventDefault();
                        this.checkout(t);
                        return;
                    }
                    if (t.matches('[data-cart-clear]')) {
                        this.clear();
                        showToast('success', 'Carrito vaciado');
                        return;
                    }
                    if (t.dataset.cartRemove) {
                        this.remove(parseInt(t.dataset.cartRemove, 10));
                        return;
                    }
                    const id = parseInt(t.dataset.productId, 10);
                    const item = this.getItems().find((i) => i.id === id);
                    if (!item || !t.dataset.cartQty) return;
                    const delta = t.dataset.cartQty === 'inc' ? 1 : -1;
                    const next = item.qty + delta;
                    if (next < 1) {
                        this.remove(id);
                    } else {
                        this.updateQty(id, next);
                    }
                });
            }

            this.updateBadges();
            window.addEventListener('storage', () => {
                this.updateBadges();
                if (document.querySelector('[data-cart-page]')) this.renderPage();
            });
        },
    },

    deleteConfirm: {
        init() {
            document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    if (typeof Swal === 'undefined') {
                        if (confirm(form.dataset.confirmText || '¿Eliminar?')) form.submit();
                        return;
                    }
                    Swal.fire({
                        title: '¿Eliminar?',
                        text: form.dataset.confirmText || 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3E2723',
                        cancelButtonColor: '#8D6E63',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                    }).then((r) => r.isConfirmed && form.submit());
                });
            });
        },
    },
};

function showToast(type, message) {
    if (typeof Swal === 'undefined') return;
    Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
    }).fire({ icon: type, title: message });
}

document.addEventListener('DOMContentLoaded', () => CE.init());

/* Admin: product/category/promo/user editors */
function editProduct(p) {
    document.getElementById('e-id').value = p.id;
    document.getElementById('e-name').value = p.name;
    document.getElementById('e-cat').value = p.category_id;
    document.getElementById('e-desc').value = p.description;
    document.getElementById('e-price').value = p.price;
    document.getElementById('e-img').value = p.image || '';
    document.getElementById('e-status').value = p.status;
    document.getElementById('e-feat').checked = p.featured == 1;
    openModal('m-edit');
}
function editCat(c) {
    document.getElementById('cat-title').textContent = 'Editar categoría';
    const form = document.getElementById('cat-form');
    form.action = form.dataset.updateUrl || form.action;
    document.getElementById('c-id').value = c.id;
    document.getElementById('c-name').value = c.name;
    document.getElementById('c-desc').value = c.description || '';
    openModal('m-cat');
}
function editPromo(p) {
    const form = document.getElementById('promo-form');
    form.action = form.dataset.updateUrl;
    document.getElementById('p-id').value = p.id;
    document.getElementById('p-title').value = p.title;
    document.getElementById('p-desc').value = p.description;
    document.getElementById('p-start').value = p.start_date;
    document.getElementById('p-end').value = p.end_date;
    document.getElementById('p-img').value = p.image || '';
    document.getElementById('p-active').checked = p.active == 1;
    openModal('m-promo');
}
function editUser(u) {
    document.getElementById('u-id').value = u.id;
    document.getElementById('u-user').value = u.username;
    document.getElementById('u-email').value = u.email;
    document.getElementById('u-name').value = u.full_name || '';
    document.getElementById('u-phone').value = u.phone || '';
    document.getElementById('u-role').value = u.role;
    openModal('m-user');
}
