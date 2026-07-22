<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LAUKIN - Dashboard Pedagang Kantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        @keyframes toastIn { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-toast { animation: toastIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-slate-200 font-sans min-h-screen text-slate-800 antialiased flex justify-center items-start">

    <div class="w-full max-w-md bg-slate-50 min-h-screen shadow-2xl relative flex flex-col pb-12 border-x border-slate-200">

        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-xs space-y-2 pointer-events-none px-4"></div>

        <!-- Sticky Header Navigation -->
        <header class="bg-slate-900 text-white sticky top-0 z-40 shadow-md backdrop-blur-md bg-slate-900/95 transition-all">
            <div class="p-3.5 flex justify-between items-center gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-emerald-500 text-slate-900 rounded-xl flex items-center justify-center shadow-inner font-black text-lg shrink-0">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold tracking-wide leading-none flex items-center gap-1.5 text-white">
                            PEDAGANG
                            <span class="text-[9px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded-full font-bold border border-emerald-500/30">MODE PENJUAL</span>
                        </h1>
                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">Dapur & Manajemen Pesanan</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 space-y-4 flex-1">

            <!-- Ringkasan Statistik -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
                    <div class="flex justify-between items-center text-slate-400 text-xs font-bold">
                        <span>Total Omzet</span>
                        <i class="fa-solid fa-wallet text-emerald-500"></i>
                    </div>
                    <p class="text-base font-black text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-slate-400 font-medium">Hari Ini</p>
                </div>
                <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
                    <div class="flex justify-between items-center text-slate-400 text-xs font-bold">
                        <span>Pesanan Masuk</span>
                        <i class="fa-solid fa-bell text-amber-500"></i>
                    </div>
                    <p class="text-base font-black text-amber-600">{{ $activeOrders->count() }} Pesanan</p>
                    <p class="text-[10px] text-slate-400 font-medium">Perlu Diproses</p>
                </div>
            </div>

            <!-- Tab Filter Menu -->
            <div class="flex bg-slate-200/80 p-1 rounded-xl font-bold text-xs">
                <button onclick="switchTab('orders')" id="tab-btn-orders" class="flex-1 py-2 rounded-lg bg-white text-slate-800 shadow-sm transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-list-check"></i> Pesanan Masuk (<span>{{ $activeOrders->count() }}</span>)
                </button>
                <button onclick="switchTab('completed')" id="tab-btn-completed" class="flex-1 py-2 rounded-lg text-slate-500 transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-circle-check"></i> Selesai
                </button>
            </div>

            <!-- Content Area: Daftar Pesanan Aktif -->
            <div id="section-orders" class="space-y-3">
                <div class="flex justify-between items-center">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Antrean Pesanan Realtime</h2>
                    <button onclick="clearAllOrders()" class="text-[10px] text-red-500 hover:text-red-700 font-bold underline flex items-center gap-1">
                        <i class="fa-solid fa-trash-can"></i> Reset Data Pesanan
                    </button>
                </div>

                <div class="space-y-3">
                    @forelse($activeOrders as $order)
                        @php $isReady = $order->status === 'Ready'; @endphp
                        <div class="bg-white p-4 rounded-2xl border {{ $isReady ? 'border-emerald-300 bg-emerald-50/20' : 'border-slate-200/80' }} shadow-sm space-y-3">
                            <div class="flex justify-between items-center border-b border-slate-100 pb-2.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-black text-emerald-700">{{ $order->order_number }}</span>
                                        <span class="text-[10px] {{ $isReady ? 'bg-emerald-600 text-white' : 'bg-amber-400 text-slate-900' }} font-bold px-2 py-0.5 rounded-full">
                                            {{ $isReady ? 'SIAP DIAMBIL' : 'DIPROSES' }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-extrabold text-slate-800 mt-0.5">
                                        <i class="fa-solid fa-user text-slate-400 mr-1 text-[10px]"></i> {{ $order->customer_name }}
                                    </p>
                                </div>
                                <span class="text-xs font-black text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 space-y-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Item Pesanan:</p>
                                <ul class="text-xs text-slate-700 font-medium space-y-1">
                                    @foreach($order->orderItems as $item)
                                        <li class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-dot text-[8px] text-emerald-500"></i>
                                            {{ $item->menu_name }} ({{ $item->quantity }}x)
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="flex gap-2 pt-1">
                                @if(!$isReady)
                                    <button onclick="updateOrderStatus({{ $order->id }}, 'Ready')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold py-2.5 px-3 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md shadow-emerald-600/20 transition-all">
                                        <i class="fa-solid fa-bell"></i> Tandai Siap Diambil
                                    </button>
                                @else
                                    <button onclick="updateOrderStatus({{ $order->id }}, 'Selesai')" class="flex-1 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white font-bold py-2.5 px-3 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md transition-all">
                                        <i class="fa-solid fa-check"></i> Selesaikan Pesanan
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-slate-200 p-6 space-y-2">
                            <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto text-xl">
                                <i class="fa-solid fa-utensils"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-600">Belum ada pesanan aktif</p>
                            <p class="text-[11px] text-slate-400">Pesanan baru dari mahasiswa akan muncul di sini secara otomatis.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Content Area: Pesanan Selesai -->
            <div id="section-completed" class="space-y-3 hidden">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Riwayat Pesanan Selesai Hari Ini</h2>
                <div class="space-y-3">
                    @forelse($completedOrders as $order)
                        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm opacity-80 space-y-2">
                            <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                                <div>
                                    <span class="text-xs font-black text-slate-800">{{ $order->order_number }}</span>
                                    <p class="text-[11px] font-bold text-slate-600">{{ $order->customer_name }}</p>
                                </div>
                                <span class="text-[10px] bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded-full">SELESAI</span>
                            </div>
                            <ul class="text-xs text-slate-600 list-disc list-inside space-y-0.5">
                                @foreach($order->orderItems as $item)
                                    <li>{{ $item->menu_name }} ({{ $item->quantity }}x)</li>
                                @endforeach
                            </ul>
                            <div class="pt-1.5 border-t border-slate-100 flex justify-between items-center text-xs">
                                <span class="text-slate-400 font-medium">Total Tagihan:</span>
                                <span class="font-black text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 bg-white rounded-2xl border border-dashed border-slate-200 p-4">
                            <p class="text-xs text-slate-400 font-medium">Belum ada pesanan yang diselesaikan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </main>
    </div>

    <script>
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

        function switchTab(tab) {
            const btnOrders = document.getElementById('tab-btn-orders');
            const btnCompleted = document.getElementById('tab-btn-completed');
            const secOrders = document.getElementById('section-orders');
            const secCompleted = document.getElementById('section-completed');

            if (tab === 'orders') {
                btnOrders.className = "flex-1 py-2 rounded-lg bg-white text-slate-800 shadow-sm transition-all flex items-center justify-center gap-1.5";
                btnCompleted.className = "flex-1 py-2 rounded-lg text-slate-500 transition-all flex items-center justify-center gap-1.5";
                secOrders.classList.remove('hidden');
                secCompleted.classList.add('hidden');
            } else {
                btnCompleted.className = "flex-1 py-2 rounded-lg bg-white text-slate-800 shadow-sm transition-all flex items-center justify-center gap-1.5";
                btnOrders.className = "flex-1 py-2 rounded-lg text-slate-500 transition-all flex items-center justify-center gap-1.5";
                secCompleted.classList.remove('hidden');
                secOrders.classList.add('hidden');
            }
        }

        function updateOrderStatus(id, newStatus) {
            fetch(`/pedagang/order-status/${id}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    setTimeout(() => location.reload(), 500);
                }
            });
        }

        function clearAllOrders() {
            if (confirm('Apakah Anda yakin ingin mereset seluruh data pesanan?')) {
                fetch("/pedagang/reset", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    showToast(data.message);
                    setTimeout(() => location.reload(), 500);
                });
            }
        }

        // Auto Refresh tiap 5 detik untuk memperbarui antrean pesanan masuk
        setInterval(() => location.reload(), 5000);
    </script>
</body>
</html>