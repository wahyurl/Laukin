<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class LaukinController extends Controller
{

    /**
     * Tampilan Halaman Utama / Pembeli
     */
    public function index()
    {
        $categories = Category::all();
        $menuItems = MenuItem::with('category')->where('is_available', true)->get();

        return view('index', compact('categories', 'menuItems'));
    }

    /**
     * Proses Pembuatan Pesanan Baru dari Pembeli
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:menu_items,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated) {
            // Hitung total urutan nomor order hari ini
            $countToday = Order::whereDate('created_at', now()->today())->count();
            $orderNumber = 'NO. ' . str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);

            $totalPrice = 0;
            $itemsToCreate = [];

            foreach ($validated['cart'] as $cartItem) {
                $menu = MenuItem::findOrFail($cartItem['id']);
                $subtotal = $menu->price * $cartItem['quantity'];
                $totalPrice += $subtotal;

                $itemsToCreate[] = [
                    'menu_item_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'quantity' => $cartItem['quantity'],
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                ];
            }

            // Simpan Order Utama
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'total_price' => $totalPrice,
                'status' => 'Diproses',
            ]);

            // Simpan Detail Order Items
            foreach ($itemsToCreate as $item) {
                $order->orderItems()->create($item);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'order' => [
                    'orderId' => $order->order_number,
                    'customer' => $order->customer_name,
                    'total' => $order->total_price,
                    'status' => $order->status,
                ]
            ]);
        });
    }

    /**
     * API Cek Status Pesanan Terbaru untuk Floating Banner
     */
    public function checkStatus($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'orderId' => $order->order_number,
            'customer' => $order->customer_name,
            'status' => $order->status,
        ]);
    }

    /**
     * Tampilan Dashboard Pedagang / Dapur
     */
    public function pedagang()
    {
        $orders = Order::with('orderItems')->latest()->get();

        $activeOrders = $orders->where('status', '!=', 'Selesai');
        $completedOrders = $orders->where('status', '===', 'Selesai');

        $totalRevenue = $orders->sum('total_price');

        return view('pedagang', compact('activeOrders', 'completedOrders', 'totalRevenue'));
    }

    /**
     * API Update Status Pesanan (Ready / Selesai)
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Diproses,Ready,Selesai',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => "Status pesanan {$order->order_number} berhasil diubah ke {$order->status}",
            'order' => $order
        ]);
    }

    /**
     * Reset Semua Pesanan (Demo Purpose)
     */
    public function resetOrders()
    {
        Order::query()->delete();
        return response()->json(['success' => true, 'message' => 'Semua data pesanan berhasil direset!']);
    }
}