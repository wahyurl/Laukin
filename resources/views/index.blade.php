<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LAUKIN - Pesan Makanan & Jajanan Kantin Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        @keyframes cartBounce { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.12) rotate(-2deg); } }
        @keyframes badgePulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.85; transform: scale(1.03); } }
        @keyframes modalSlideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes toastIn { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-cart-bounce { animation: cartBounce 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .animate-badge-pulse { animation: badgePulse 2s infinite ease-in-out; }
        .animate-modal-slide { animation: modalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-toast { animation: toastIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .menu-card { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .menu-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px -8px rgba(16, 185, 129, 0.25); }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-200 font-sans min-h-screen text-slate-800 antialiased selection:bg-emerald-500 selection:text-white flex justify-center items-start">

    <div class="w-full max-w-md bg-slate-50 min-h-screen shadow-2xl relative flex flex-col pb-28 border-x border-slate-200">

        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-xs space-y-2 pointer-events-none px-4"></div>

        <!-- Sticky Header Navigation -->
        <header class="bg-emerald-600 text-white sticky top-0 z-40 shadow-md backdrop-blur-md bg-emerald-600/95 transition-all">
            <div class="p-3.5 flex justify-between items-center gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-yellow-400 text-slate-900 rounded-xl flex items-center justify-center shadow-inner font-black text-lg shrink-0">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold tracking-wide leading-none flex items-center gap-1.5">
                            LAUKIN
                        </h1>
                        <p class="text-[10px] text-emerald-100 font-medium mt-0.5">Kantin Digital Mahasiswa</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-4 space-y-4 flex-1">

            <!-- Status Order Floating Banner -->
            <div id="active-order-banner" class="hidden bg-slate-900 text-white p-3.5 rounded-2xl shadow-xl border border-slate-700/80 animate-badge-pulse space-y-1.5">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-black text-emerald-400" id="banner-order-id">NO. ---</span>
                    </div>
                    <span class="text-[10px] bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider" id="banner-status">Diproses</span>
                </div>
                <p class="text-xs font-medium text-slate-200" id="banner-message">Pesanan Anda sedang disiapkan pedagang...</p>
            </div>

            <!-- Banner Promo -->
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 via-teal-600 to-emerald-700 text-white p-4 rounded-2xl shadow-md space-y-1.5 border border-emerald-400/20">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <span class="inline-block bg-yellow-400 text-slate-900 font-extrabold text-[9px] uppercase px-2.5 py-0.5 rounded-full shadow-sm">
                    🔥 {{ $menuItems->count() }}+ Pilihan Kuliner Kampus
                </span>
                <h2 class="text-base font-extrabold tracking-tight">Lapar Pas Kuliah?</h2>
                <p class="text-xs text-emerald-100 font-medium leading-relaxed">Pesan langsung tanpa antre, bayar instan pakai QRIS & tinggal ambil pas matang!</p>
            </div>

            <!-- Category Filter Slider -->
            <section class="space-y-2">
                <div class="flex justify-between items-center">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori Menu</h2>
                    <span id="menu-count-badge" class="text-[10px] bg-slate-200 text-slate-600 font-bold px-2 py-0.5 rounded-full">{{ $menuItems->count() }} Menu</span>
                </div>
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none text-xs font-bold">
                    <button onclick="filterCategory('all')" class="category-btn active px-3.5 py-2 bg-emerald-600 text-white rounded-xl shadow-md transition-all whitespace-nowrap active:scale-95" data-cat="all">🔥 Semua Menu</button>
                    @foreach($categories as $cat)
                        <button onclick="filterCategory('{{ $cat->slug }}')" class="category-btn px-3.5 py-2 bg-white text-slate-700 border border-slate-200 rounded-xl whitespace-nowrap hover:bg-slate-50 transition-all active:scale-95 shadow-sm" data-cat="{{ $cat->slug }}">
                            {{ $cat->icon }} {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </section>

            <!-- Menu Grid Section -->
            <section class="space-y-3">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilihan Menu Kantin</h2>
                <div id="menu-grid" class="grid grid-cols-2 gap-3">
                    @foreach($menuItems as $item)
                        <div class="menu-card bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between group" data-category="{{ $item->category->slug }}">
                            <div class="relative overflow-hidden">
                                <img src="{{ $item->image }}" alt="{{ $item->name }}" 
                                     onerror="this.src='https://placehold.co/400x300/10b981/ffffff?text={{ urlencode($item->name) }}'"
                                     class="w-full h-28 object-cover group-hover:scale-105 transition-transform duration-500">
                                <span class="absolute top-2 left-2 bg-slate-900/80 text-white text-[9px] px-2 py-0.5 rounded-full font-bold backdrop-blur-md shadow-sm">
                                    {{ $item->category->icon }} {{ $item->category->name }}
                                </span>
                            </div>
                            <div class="p-2.5 space-y-2 flex-1 flex flex-col justify-between bg-white">
                                <div>
                                    <h3 class="font-bold text-xs text-slate-800 line-clamp-2 leading-snug group-hover:text-emerald-700 transition-colors">{{ $item->name }}</h3>
                                    <p class="text-xs font-black text-emerald-600 mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>

                                <div class="pt-2 border-t border-slate-100 flex justify-between items-center" id="action-container-{{ $item->id }}">
                                    <button onclick="updateQty({{ $item->id }}, 1)" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold py-1.5 px-3 rounded-xl text-xs flex items-center justify-center gap-1 shadow-sm transition-all">
                                        <i class="fa-solid fa-plus text-[10px]"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

        </main>

        <!-- Floating Cart Footer -->
        <div id="floating-cart" class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md p-3.5 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-2xl z-30 hidden transition-all">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div id="cart-icon-wrapper" class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center font-bold text-base relative">
                        <i class="fa-solid fa-basket-shopping"></i>
                        <span id="cart-badge-count" class="absolute -top-1.5 -right-1.5 bg-emerald-600 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-black border-2 border-white">0</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Total Pembayaran</p>
                        <p id="cart-total-price" class="text-base font-black text-emerald-600 leading-none">Rp 0</p>
                    </div>
                </div>
                <button onclick="openCheckoutModal()" class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-600/30 flex items-center gap-2 transition-all">
                    <span>Lanjut Checkout</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div id="checkout-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-end justify-center hidden transition-opacity">
            <div class="bg-white w-full max-w-md rounded-t-3xl p-5 space-y-4 max-h-[90vh] overflow-y-auto animate-modal-slide shadow-2xl border-t border-slate-100">
                
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-black text-base text-slate-800">Konfirmasi & Pembayaran</h3>
                        <p class="text-[11px] text-slate-500 font-medium">Isi identitas dan scan QRIS untuk memesan</p>
                    </div>
                    <button onclick="closeCheckoutModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:text-slate-800 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama / NIM Mahasiswa <span class="text-red-500">*</span></label>
                        <input type="text" id="customer-name" placeholder="Contoh: Budi Utama (220101002)" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-slate-800 font-medium bg-slate-50/50 transition">
                    </div>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80 space-y-2 text-xs">
                    <p class="font-bold text-slate-700 border-b border-slate-200/60 pb-1.5 flex justify-between">
                        <span>Rincian Menu:</span>
                        <span id="summary-items-count" class="text-emerald-600 font-extrabold">0 Item</span>
                    </p>
                    <div id="modal-order-summary" class="space-y-1.5 text-slate-600 max-h-36 overflow-y-auto pr-1"></div>
                    <div class="flex justify-between font-black text-sm text-emerald-600 border-t border-slate-200/60 pt-2.5">
                        <span>Total Tagihan:</span>
                        <span id="modal-total-price">Rp 0</span>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200/80 p-4 rounded-2xl text-center flex flex-col items-center justify-center space-y-2">
                    <p class="text-xs font-bold text-slate-700 pb-1 border-b border-slate-200 w-full text-center flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-qrcode text-emerald-600"></i> Scan QRIS Non-Tunai
                    </p>
                    
                    <div class="bg-white p-2.5 border border-slate-200 rounded-xl shadow-sm flex items-center justify-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=LAUKIN-PAYMENT-MOCKUP" 
                             alt="QRIS QR Code" 
                             class="w-36 h-36 object-contain mx-auto block">
                    </div>

                    <p class="text-[10px] text-slate-500 font-medium text-center">
                        M-Banking / GoPay / OVO / ShopeePay / Dana / LinkAja
                    </p>
                </div>

                <button onclick="submitOrder()" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white font-black py-3.5 rounded-xl text-xs shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition-all">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Selesaikan & Kirim Pesanan</span>
                </button>
            </div>
        </div>

    </div>

    <script>
        const menuItemsData = @json($menuItems);
        let cart = {};
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-slate-900 text-white border-emerald-500' : 'bg-red-900 text-white border-red-500';
            const icon = type === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation text-red-400';

            toast.className = `${bgColor} border p-3 rounded-2xl shadow-2xl text-xs font-bold flex items-center gap-2.5 animate-toast pointer-events-auto`;
            toast.innerHTML = `<i class="fa-solid ${icon} text-base"></i><span class="flex-1">${message}</span>`;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function filterCategory(slug) {
            document.querySelectorAll('.category-btn').forEach(btn => {
                if (btn.dataset.cat === slug) {
                    btn.className = 'category-btn active px-3.5 py-2 bg-emerald-600 text-white rounded-xl shadow-md whitespace-nowrap transition-all active:scale-95';
                } else {
                    btn.className = 'category-btn px-3.5 py-2 bg-white text-slate-700 border border-slate-200 rounded-xl whitespace-nowrap hover:bg-slate-50 transition-all active:scale-95 shadow-sm';
                }
            });

            const cards = document.querySelectorAll('.menu-card');
            let visibleCount = 0;
            cards.forEach(card => {
                if (slug === 'all' || card.dataset.category === slug) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            document.getElementById('menu-count-badge').innerText = `${visibleCount} Menu`;
        }

        function updateQty(id, change) {
            const current = cart[id] || 0;
            const newQty = current + change;
            if (newQty <= 0) {
                delete cart[id];
            } else {
                cart[id] = newQty;
            }

            const actionContainer = document.getElementById(`action-container-${id}`);
            const qty = cart[id] || 0;

            if (actionContainer) {
                if (qty > 0) {
                    actionContainer.innerHTML = `
                        <div class="flex items-center gap-2 bg-emerald-50 p-1 rounded-xl w-full justify-between border border-emerald-200">
                            <button onclick="updateQty(${id}, -1)" class="w-6 h-6 bg-emerald-600 active:scale-90 text-white rounded-lg font-bold flex items-center justify-center text-xs transition-transform">-</button>
                            <span class="text-xs font-black text-emerald-800">${qty}</span>
                            <button onclick="updateQty(${id}, 1)" class="w-6 h-6 bg-emerald-600 active:scale-90 text-white rounded-lg font-bold flex items-center justify-center text-xs transition-transform">+</button>
                        </div>
                    `;
                } else {
                    actionContainer.innerHTML = `
                        <button onclick="updateQty(${id}, 1)" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold py-1.5 px-3 rounded-xl text-xs flex items-center justify-center gap-1 shadow-sm transition-all">
                            <i class="fa-solid fa-plus text-[10px]"></i> Tambah
                        </button>
                    `;
                }
            }

            const cartIcon = document.getElementById('cart-icon-wrapper');
            if(cartIcon) {
                cartIcon.classList.remove('animate-cart-bounce');
                void cartIcon.offsetWidth;
                cartIcon.classList.add('animate-cart-bounce');
            }

            updateCartSummary();
        }

        function updateCartSummary() {
            let total = 0;
            let count = 0;

            Object.keys(cart).forEach(id => {
                const item = menuItemsData.find(m => m.id == id);
                if (item) {
                    total += item.price * cart[id];
                    count += cart[id];
                }
            });

            const floatingCart = document.getElementById('floating-cart');
            if (count > 0) {
                floatingCart.classList.remove('hidden');
                document.getElementById('cart-total-price').innerText = `Rp ${total.toLocaleString('id-ID')}`;
                document.getElementById('cart-badge-count').innerText = count;
            } else {
                floatingCart.classList.add('hidden');
            }
        }

        function openCheckoutModal() {
            const summaryContainer = document.getElementById('modal-order-summary');
            summaryContainer.innerHTML = '';
            let total = 0;
            let count = 0;

            Object.keys(cart).forEach(id => {
                const item = menuItemsData.find(m => m.id == id);
                if (item) {
                    const subtotal = item.price * cart[id];
                    total += subtotal;
                    count += cart[id];

                    summaryContainer.innerHTML += `
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-medium text-slate-700">${item.name} <strong class="text-emerald-700">x${cart[id]}</strong></span>
                            <span class="font-bold text-slate-800">Rp ${subtotal.toLocaleString('id-ID')}</span>
                        </div>
                    `;
                }
            });

            document.getElementById('summary-items-count').innerText = `${count} Item`;
            document.getElementById('modal-total-price').innerText = `Rp ${total.toLocaleString('id-ID')}`;
            document.getElementById('checkout-modal').classList.remove('hidden');
        }

        function closeCheckoutModal() {
            document.getElementById('checkout-modal').classList.add('hidden');
        }

        function submitOrder() {
            const nameInput = document.getElementById('customer-name').value.trim();
            if (!nameInput) {
                showToast('Mohon isi Nama & NIM Mahasiswa terlebih dahulu!', 'error');
                document.getElementById('customer-name').focus();
                return;
            }

            const cartPayload = Object.keys(cart).map(id => ({
                id: parseInt(id),
                quantity: cart[id]
            }));

            fetch("{{ route('buyer.checkout') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    customer_name: nameInput,
                    cart: cartPayload
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem('laukin_active_order', data.order.orderId);
                    cart = {};
                    closeCheckoutModal();
                    updateCartSummary();
                    showToast(`Pesanan ${data.order.orderId} Berhasil Dibuat!`);
                    checkActiveOrder();
                    // Reset menu qty buttons
                    menuItemsData.forEach(item => {
                        const container = document.getElementById(`action-container-${item.id}`);
                        if(container) {
                            container.innerHTML = `
                                <button onclick="updateQty(${item.id}, 1)" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold py-1.5 px-3 rounded-xl text-xs flex items-center justify-center gap-1 shadow-sm transition-all">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Tambah
                                </button>
                            `;
                        }
                    });
                }
            })
            .catch(err => showToast('Terjadi kesalahan koneksi server.', 'error'));
        }

        function checkActiveOrder() {
            const activeOrderId = localStorage.getItem('laukin_active_order');
            const banner = document.getElementById('active-order-banner');

            if (activeOrderId) {
                fetch(`/order-status/${activeOrderId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.found) {
                        banner.classList.remove('hidden');
                        document.getElementById('banner-order-id').innerText = data.orderId;
                        if (data.status === 'Ready') {
                            document.getElementById('banner-status').className = "text-[10px] bg-emerald-500 text-white px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider";
                            document.getElementById('banner-status').innerText = "SIAP DIAMBIL!";
                            document.getElementById('banner-message').innerText = "🎉 Pesanan Anda sudah matang! Silakan ambil di stand pedagang.";
                        } else if (data.status === 'Selesai') {
                            banner.classList.add('hidden');
                            localStorage.removeItem('laukin_active_order');
                        } else {
                            document.getElementById('banner-status').className = "text-[10px] bg-yellow-400 text-slate-900 px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider";
                            document.getElementById('banner-status').innerText = "Diproses";
                            document.getElementById('banner-message').innerText = "⏳ Pesanan sedang disiapkan oleh pedagang...";
                        }
                    }
                });
            }
        }

        setInterval(checkActiveOrder, 3000);
        checkActiveOrder();
    </script>
</body>
</html>