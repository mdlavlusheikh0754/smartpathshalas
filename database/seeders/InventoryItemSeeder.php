<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    public function run()
    {
        $inventoryItems = [
            // Uniform Items
            [
                'item_code' => 'UNI001',
                'name' => 'স্কুল শার্ট (ছেলেদের)',
                'category' => 'uniform',
                'price' => 450,
                'stock' => 50,
                'min_stock' => 10,
                'unit' => 'piece',
                'description' => 'সাদা রঙের স্কুল শার্ট',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'UNI002',
                'name' => 'স্কুল প্যান্ট (ছেলেদের)',
                'category' => 'uniform',
                'price' => 550,
                'stock' => 40,
                'min_stock' => 8,
                'unit' => 'piece',
                'description' => 'নেভি ব্লু রঙের স্কুল প্যান্ট',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'UNI003',
                'name' => 'স্কুল কামিজ (মেয়েদের)',
                'category' => 'uniform',
                'price' => 480,
                'stock' => 45,
                'min_stock' => 10,
                'unit' => 'piece',
                'description' => 'সাদা রঙের স্কুল কামিজ',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'UNI004',
                'name' => 'স্কুল সালোয়ার (মেয়েদের)',
                'category' => 'uniform',
                'price' => 420,
                'stock' => 35,
                'min_stock' => 8,
                'unit' => 'piece',
                'description' => 'নেভি ব্লু রঙের স্কুল সালোয়ার',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'UNI005',
                'name' => 'স্কুল টাই',
                'category' => 'uniform',
                'price' => 150,
                'stock' => 60,
                'min_stock' => 15,
                'unit' => 'piece',
                'description' => 'স্কুলের অফিসিয়াল টাই',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'UNI006',
                'name' => 'স্কুল বেল্ট',
                'category' => 'uniform',
                'price' => 200,
                'stock' => 30,
                'min_stock' => 8,
                'unit' => 'piece',
                'description' => 'কালো রঙের স্কুল বেল্ট',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'UNI007',
                'name' => 'স্কুল জুতা',
                'category' => 'uniform',
                'price' => 800,
                'stock' => 25,
                'min_stock' => 5,
                'unit' => 'pair',
                'description' => 'কালো রঙের স্কুল জুতা',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'UNI008',
                'name' => 'স্কুল মোজা',
                'category' => 'uniform',
                'price' => 80,
                'stock' => 100,
                'min_stock' => 20,
                'unit' => 'pair',
                'description' => 'সাদা রঙের স্কুল মোজা',
                'status' => 'in_stock'
            ],

            // Books
            [
                'item_code' => 'BOK001',
                'name' => 'বাংলা বই (১ম শ্রেণী)',
                'category' => 'books',
                'price' => 120,
                'stock' => 80,
                'min_stock' => 15,
                'unit' => 'piece',
                'description' => 'প্রথম শ্রেণীর বাংলা পাঠ্যবই',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'BOK002',
                'name' => 'ইংরেজি বই (১ম শ্রেণী)',
                'category' => 'books',
                'price' => 110,
                'stock' => 75,
                'min_stock' => 15,
                'unit' => 'piece',
                'description' => 'প্রথম শ্রেণীর ইংরেজি পাঠ্যবই',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'BOK003',
                'name' => 'গণিত বই (১ম শ্রেণী)',
                'category' => 'books',
                'price' => 100,
                'stock' => 70,
                'min_stock' => 15,
                'unit' => 'piece',
                'description' => 'প্রথম শ্রেণীর গণিত পাঠ্যবই',
                'status' => 'in_stock'
            ],

            // Stationery
            [
                'item_code' => 'STA001',
                'name' => 'খাতা (১০০ পাতা)',
                'category' => 'stationery',
                'price' => 35,
                'stock' => 200,
                'min_stock' => 50,
                'unit' => 'piece',
                'description' => 'লাইন টানা খাতা',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'STA002',
                'name' => 'পেন্সিল',
                'category' => 'stationery',
                'price' => 8,
                'stock' => 300,
                'min_stock' => 100,
                'unit' => 'piece',
                'description' => 'HB পেন্সিল',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'STA003',
                'name' => 'রাবার',
                'category' => 'stationery',
                'price' => 5,
                'stock' => 150,
                'min_stock' => 50,
                'unit' => 'piece',
                'description' => 'সাদা রাবার',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'STA004',
                'name' => 'কলম (নীল)',
                'category' => 'stationery',
                'price' => 12,
                'stock' => 180,
                'min_stock' => 60,
                'unit' => 'piece',
                'description' => 'নীল কালির কলম',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'STA005',
                'name' => 'স্কেল (৩০ সেমি)',
                'category' => 'stationery',
                'price' => 15,
                'stock' => 120,
                'min_stock' => 30,
                'unit' => 'piece',
                'description' => 'প্লাস্টিকের স্কেল',
                'status' => 'in_stock'
            ],

            // Accessories
            [
                'item_code' => 'ACC001',
                'name' => 'স্কুল ব্যাগ',
                'category' => 'accessories',
                'price' => 650,
                'stock' => 40,
                'min_stock' => 10,
                'unit' => 'piece',
                'description' => 'স্কুলের লোগো সহ ব্যাগ',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'ACC002',
                'name' => 'পানির বোতল',
                'category' => 'accessories',
                'price' => 180,
                'stock' => 60,
                'min_stock' => 15,
                'unit' => 'piece',
                'description' => 'প্লাস্টিকের পানির বোতল',
                'status' => 'in_stock'
            ],
            [
                'item_code' => 'ACC003',
                'name' => 'টিফিন বক্স',
                'category' => 'accessories',
                'price' => 220,
                'stock' => 45,
                'min_stock' => 12,
                'unit' => 'piece',
                'description' => 'স্টেইনলেস স্টিলের টিফিন বক্স',
                'status' => 'in_stock'
            ]
        ];

        foreach ($inventoryItems as $item) {
            InventoryItem::create($item);
        }
    }
}