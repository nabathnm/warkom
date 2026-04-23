<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // CPU
            ['name' => 'Intel Core i5-12400F', 'description' => 'Prosesor generasi ke-12 Intel, 6 Cores 12 Threads.', 'category' => 'cpu', 'price' => 2500000, 'stock' => 15],
            ['name' => 'AMD Ryzen 5 5600X', 'description' => 'Prosesor AMD Ryzen 5, sangat cocok untuk gaming.', 'category' => 'cpu', 'price' => 2800000, 'stock' => 10],
            ['name' => 'Intel Core i7-13700K', 'description' => 'Prosesor high-end Intel generasi ke-13, 16 Cores.', 'category' => 'cpu', 'price' => 7000000, 'stock' => 5],
            
            // Motherboard
            ['name' => 'MSI B550 TOMAHAWK', 'description' => 'Motherboard tangguh untuk prosesor AMD Ryzen.', 'category' => 'motherboard', 'price' => 2900000, 'stock' => 8],
            ['name' => 'ASUS ROG Strix Z690-A', 'description' => 'Motherboard premium untuk prosesor Intel gen 12/13.', 'category' => 'motherboard', 'price' => 5500000, 'stock' => 4],
            
            // VGA
            ['name' => 'NVIDIA GeForce RTX 3060 12GB', 'description' => 'Kartu grafis mainstream untuk gaming 1080p.', 'category' => 'vga', 'price' => 5200000, 'stock' => 12],
            ['name' => 'AMD Radeon RX 6700 XT 12GB', 'description' => 'Pesaing tangguh untuk kelas menengah dengan VRAM besar.', 'category' => 'vga', 'price' => 6000000, 'stock' => 7],
            ['name' => 'ASUS TUF Gaming RTX 4070', 'description' => 'Performa grafis generasi terbaru dari seri 40 NVIDIA.', 'category' => 'vga', 'price' => 12500000, 'stock' => 3],
            
            // RAM
            ['name' => 'Corsair Vengeance RGB Pro 16GB (2x8GB) 3200MHz', 'description' => 'Memori RAM dengan RGB yang memukau.', 'category' => 'ram', 'price' => 1200000, 'stock' => 20],
            ['name' => 'G.Skill Trident Z Neo 32GB (2x16GB) 3600MHz', 'description' => 'RAM berkecepatan tinggi cocok untuk sistem Ryzen.', 'category' => 'ram', 'price' => 2500000, 'stock' => 10],
            
            // Storage
            ['name' => 'Samsung 980 PRO 1TB NVMe M.2 SSD', 'description' => 'Penyimpanan SSD PCIe Gen4 super cepat.', 'category' => 'storage', 'price' => 2100000, 'stock' => 15],
            ['name' => 'Seagate Barracuda 2TB HDD', 'description' => 'Hard disk besar untuk menyimpan semua file Anda.', 'category' => 'storage', 'price' => 950000, 'stock' => 25],
            ['name' => 'Western Digital WD Blue SN570 500GB', 'description' => 'NVMe terjangkau untuk sistem ringan.', 'category' => 'storage', 'price' => 700000, 'stock' => 18],
            
            // PSU
            ['name' => 'Corsair RM750x 80+ Gold Fully Modular', 'description' => 'Power supply efisien dengan kabel modular.', 'category' => 'psu', 'price' => 2200000, 'stock' => 8],
            ['name' => 'Seasonic Focus GX-650', 'description' => 'PSU handal 650W untuk rig gaming menengah.', 'category' => 'psu', 'price' => 1700000, 'stock' => 12],
            
            // Casing
            ['name' => 'NZXT H510 Flow', 'description' => 'Casing minimalis dengan sirkulasi udara (airflow) mantap.', 'category' => 'casing', 'price' => 1400000, 'stock' => 6],
            ['name' => 'Lian Li PC-O11 Dynamic', 'description' => 'Casing sultan untuk menampung banyak radiator pendingin.', 'category' => 'casing', 'price' => 2600000, 'stock' => 5],
            
            // Cooling
            ['name' => 'Cooler Master Hyper 212 Black Edition', 'description' => 'Pendingin udara legendaris yang handal.', 'category' => 'cooling', 'price' => 650000, 'stock' => 22],
            ['name' => 'NZXT Kraken X63 280mm AIO', 'description' => 'Liquid cooler AIO dengan layar infinity mirror RGB.', 'category' => 'cooling', 'price' => 2800000, 'stock' => 4],
            
            // Aksesoris
            ['name' => 'Logitech G Pro X Superlight', 'description' => 'Mouse gaming wireless super ringan.', 'category' => 'aksesoris', 'price' => 2100000, 'stock' => 15],
            ['name' => 'Keychron K8 Wireless Mechanical Keyboard', 'description' => 'Keyboard mekanikal untuk kerja dan gaming.', 'category' => 'aksesoris', 'price' => 1600000, 'stock' => 9],
        ];

        $imageMap = [
            'aksesoris' => 'products/aksesoris_mockup_1776924788244.png',
            'casing' => 'products/casing_mockup_1776924760034.png',
            'cooling' => 'products/cooling_mockup_1776924773988.png',
            'cpu' => 'products/cpu_mockup_1776924837593.png',
            'motherboard' => 'products/motherboard_mockup_1776924680968.png',
            'psu' => 'products/psu_mockup_1776924745047.png',
            'ram' => 'products/ram_mockup_1776924714779.png',
            'storage' => 'products/storage_mockup_1776924730300.png',
            'vga' => 'products/vga_mockup_1776924699579.png'
        ];

        foreach ($products as $product) {
            if (isset($imageMap[$product['category']])) {
                // Menambahkan gambar dalam bentuk array (mendukung banyak gambar)
                $product['images'] = [$imageMap[$product['category']]];
            }
            Product::create($product);
        }
    }
}
