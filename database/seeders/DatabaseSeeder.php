<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\MenuItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori Utama
        $jajanan = Category::create(['slug' => 'jajanan', 'name' => 'Jajanan & Snack', 'icon' => '🍿']);
        $makanan = Category::create(['slug' => 'makanan', 'name' => 'Makanan Berat', 'icon' => '🍚']);
        $minuman = Category::create(['slug' => 'minuman', 'name' => 'Minuman Segar', 'icon' => '🧋']);

        // 2. Data Menu Kantin (27 Items)
        $items = [
            // --- JAJANAN & SNACK (11 Items) ---
            ['category_id' => $jajanan->id, 'name' => 'Seblak Komplit Pedas', 'price' => 10000, 'image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Batagor Bandung Crispy', 'price' => 8000, 'image' => 'https://images.unsplash.com/photo-1541544741938-0af808871cc0?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Risol Mayo Smoked Beef (3 pcs)', 'price' => 9000, 'image' => 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Dimsum Ayam Mix (4 pcs)', 'price' => 12000, 'image' => 'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Cilor & Cilok Bumbu Tabur', 'price' => 5000, 'image' => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Tahu Walik Crispy (5 pcs)', 'price' => 7000, 'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Sotang Mozzarella Jumbo', 'price' => 10000, 'image' => 'https://images.unsplash.com/photo-1562967914-608f82629710?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Piscok Lumer Melted (4 pcs)', 'price' => 8000, 'image' => 'https://images.unsplash.com/photo-1587314168485-3236d6710814?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Sempol Ayam Crispy (5 pcs)', 'price' => 7000, 'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Jasuke Keju Melimpah', 'price' => 8000, 'image' => 'https://images.unsplash.com/photo-1551782450-a2132b4ba21d?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $jajanan->id, 'name' => 'Corndog Mozzarella Full', 'price' => 12000, 'image' => 'https://images.unsplash.com/photo-1628294895950-9805252327bc?w=400&auto=format&fit=crop&q=80'],

            // --- MAKANAN BERAT (8 Items) ---
            ['category_id' => $makanan->id, 'name' => 'Nasi Ayam Geprek Sambal Korek', 'price' => 13000, 'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $makanan->id, 'name' => 'Nasi Goreng Spesial Kantin', 'price' => 12000, 'image' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $makanan->id, 'name' => 'Mie Goreng Dok-Dok Pedas', 'price' => 10000, 'image' => 'https://images.unsplash.com/photo-1612927601601-6638404737ce?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $makanan->id, 'name' => 'Nasi Bebek Goreng Bumbu Hitam', 'price' => 18000, 'image' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $makanan->id, 'name' => 'Kwetiau Goreng Sapi Pedas', 'price' => 15000, 'image' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $makanan->id, 'name' => 'Rice Bowl Ayam Sambal Matah', 'price' => 14000, 'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $makanan->id, 'name' => 'Nasi Ayam Bakar Madu', 'price' => 16000, 'image' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $makanan->id, 'name' => 'Soto Ayam Lamongan Komplit', 'price' => 12000, 'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=400&auto=format&fit=crop&q=80'],

            // --- MINUMAN SEGAR (8 Items) ---
            ['category_id' => $minuman->id, 'name' => 'Es Teh Manis Jumbo', 'price' => 4000, 'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'name' => 'Kopi Susu Gula Aren', 'price' => 8000, 'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'name' => 'Es Jeruk Peras Segar', 'price' => 5000, 'image' => 'https://images.unsplash.com/photo-1613478223719-2ab802602423?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'name' => 'Boba Brown Sugar Fresh Milk', 'price' => 12000, 'image' => 'https://images.unsplash.com/photo-1558857563-b371033873b8?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'name' => 'Matcha Latte Ice', 'price' => 10000, 'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'name' => 'Thai Tea Original Jumbo', 'price' => 7000, 'image' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'name' => 'Alpukat Kocok Keju', 'price' => 10000, 'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'name' => 'Ice Lemon Tea Segar', 'price' => 6000, 'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=400&auto=format&fit=crop&q=80'],
        ];

        foreach ($items as $item) {
            MenuItem::create($item);
        }
    }
}