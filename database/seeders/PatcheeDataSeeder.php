<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Banner;
use App\Models\Setting;
use App\Models\Order;
use App\Models\OrderItem;

class PatcheeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin Users
        User::updateOrCreate(
            ['email' => 'admin@moutoshihandbag.shop'],
            [
                'name' => 'Moutoshi Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'phone' => '01700000000',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@thepatchee.com'],
            [
                'name' => 'Moutoshi Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'phone' => '01700000000',
            ]
        );

        // Demo Customer
        User::updateOrCreate(
            ['email' => 'customer@thepatchee.com'],
            [
                'name' => 'Tanzina Islam',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'phone' => '01712345678',
            ]
        );

        // 2. Settings
        $settings = [
            'site_name' => 'Moutoshi Handbag',
            'site_tagline' => 'Handbag Haven | Discover your style with the perfect accessory for every occasion',
            'logo' => 'assets/images/logo.png',
            'phone' => '+880 1711-000000',
            'email' => 'support@moutoshihandbag.shop',
            'address' => 'Dhaka, Bangladesh',
            'announcement_1' => '🌟 Handbag Haven 🌟 | Discover your style with Moutoshi Handbag',
            'announcement_2' => 'Free Delivery on prepaid orders over ৳2,500',
            'announcement_3' => 'Cash On Delivery Available All Over Bangladesh 🇧🇩',
            'delivery_inside_dhaka' => '80',
            'delivery_outside_dhaka' => '130',
            'free_delivery_threshold' => '0',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61559952171891',
            'instagram_url' => 'https://instagram.com/moutoshihandbag',
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Hero Banners with Real Moutoshi Handbag Images
        Banner::truncate();
        Banner::create([
            'title' => 'HANDCRAFTED CANVAS ESSENTIALS',
            'subtitle' => 'Handbag Haven 👜 | Discover delicate wildflower embroidery & multi-pocket utility totes',
            'badge' => 'EXCLUSIVE 2026 COLLECTION',
            'button_text' => 'EXPLORE CANVAS TOTES',
            'button_url' => '/collections/tote-bags',
            'image_url' => 'assets/images/bags/moutoshi-bag-02.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'FUNCTION MEETS AESTHETICS',
            'subtitle' => 'Spacious, washable, multi-pocket totes designed for campus life & daily outings',
            'badge' => 'BESTSELLER DROP',
            'button_text' => 'SHOP POPULAR PICKS',
            'button_url' => '/collections/women',
            'image_url' => 'assets/images/bags/moutoshi-bag-01.jpg',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 4. Collections / Categories with Real Images
        $allCategories = [
            ['name' => 'Women', 'slug' => 'women', 'image' => 'assets/images/bags/moutoshi-bag-02.jpg', 'desc' => 'Chic, handcrafted canvas tote bags, crossbody, shoulder bags, and accessories for women.'],
            ['name' => 'Tote Bags', 'slug' => 'tote-bags', 'image' => 'assets/images/bags/moutoshi-bag-03.jpg', 'desc' => 'Spacious, heavy-duty washable canvas tote bags engineered for university, work, and travel.'],
            ['name' => 'Shoulder Bags', 'slug' => 'shoulder-bags', 'image' => 'assets/images/bags/moutoshi-bag-05.jpg', 'desc' => 'Modern aesthetics meet daily functionality with versatile shoulder canvas bags.'],
            ['name' => 'Crossbody Bags', 'slug' => 'crossbody-bags', 'image' => 'assets/images/bags/moutoshi-bag-06.jpg', 'desc' => 'Hands-free convenience without compromising on style. Perfect daily side bags.'],
            ['name' => 'Handbags', 'slug' => 'handbags', 'image' => 'assets/images/bags/moutoshi-bag-04.jpg', 'desc' => 'Classy structured ladies top-handle bags and hand-carry canvas shoppers.'],
            ['name' => 'Sync Set Bags', 'slug' => 'sync-set-bags', 'image' => 'assets/images/bags/moutoshi-bag-01.jpg', 'desc' => 'Matching duo sets for casual dates and campus duos.'],
            ['name' => 'Backpacks', 'slug' => 'backpacks', 'image' => 'assets/images/bags/moutoshi-bag-08.jpg', 'desc' => 'Multi-compartment bags for city commuting and travel.'],
            ['name' => 'Women Backpacks', 'slug' => 'women-backpack-bags', 'image' => 'assets/images/bags/moutoshi-bag-08.jpg', 'desc' => 'Fashion-forward backpacks tailored for ladies.'],
            ['name' => 'Women Crossbody Bags', 'slug' => 'women-crossbody-bags', 'image' => 'assets/images/bags/moutoshi-bag-06.jpg', 'desc' => 'Signature side bags for ladies in Bangladesh.'],
            ['name' => 'Clutch Bags', 'slug' => 'clutch-bags', 'image' => 'assets/images/bags/moutoshi-bag-07.jpg', 'desc' => 'Compact clutches and wristlet organizers.'],
            ['name' => 'Mini Bags', 'slug' => 'mini-bags', 'image' => 'assets/images/bags/moutoshi-bag-09.jpg', 'desc' => 'Compact statement pieces for outings, dates, and evenings.'],
            ['name' => 'Bucket Bags', 'slug' => 'bucket-bags', 'image' => 'assets/images/bags/moutoshi-bag-10.jpg', 'desc' => 'Relaxed charm and high storage capacity.'],
            ['name' => 'Unisex', 'slug' => 'unisex', 'image' => 'assets/images/bags/moutoshi-bag-10.jpg', 'desc' => 'Gender-neutral durable canvas shoppers and utility bags.'],
            ['name' => 'Cosmetic Bags & Cases', 'slug' => 'cosmetic-bags-cases', 'image' => 'assets/images/bags/moutoshi-bag-11.jpg', 'desc' => 'Multi-layer pouches for cosmetics and makeup.'],
            ['name' => 'Duffle Bags', 'slug' => 'duffle-bags', 'image' => 'assets/images/bags/moutoshi-bag-13.jpg', 'desc' => 'Weekend trips and travel carry-on duffles built to last.'],
            ['name' => 'Travel Backpack', 'slug' => 'travel-backpack', 'image' => 'assets/images/bags/moutoshi-bag-14.jpg', 'desc' => 'Large capacity travel packs.'],
            ['name' => 'Wallets & Card Holders', 'slug' => 'wallets-card-holders', 'image' => 'assets/images/bags/moutoshi-bag-15.jpg', 'desc' => 'Pouches, slim card holders, and clutches.'],
            ['name' => 'Laptop Bags', 'slug' => 'laptop-bags', 'image' => 'assets/images/bags/moutoshi-bag-16.jpg', 'desc' => 'Padded canvas totes accommodating 14" and 15" laptops.'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'image' => 'assets/images/bags/moutoshi-bag-17.jpg', 'desc' => 'Premium fashion accessories and key accents.'],
            ['name' => 'Bag Charms', 'slug' => 'bag-charms', 'image' => 'assets/images/bags/moutoshi-bag-18.jpg', 'desc' => 'Custom charms to accessorize your bags.'],
            ['name' => 'Men', 'slug' => 'men', 'image' => 'assets/images/bags/moutoshi-bag-10.jpg', 'desc' => 'Commuter bags and minimalist carry gear.'],
            ['name' => 'Men Backpacks', 'slug' => 'men-backpack-bags', 'image' => 'assets/images/bags/moutoshi-bag-08.jpg', 'desc' => 'Commuting backpacks with laptop compartments.'],
            ['name' => 'Men Crossbody', 'slug' => 'men-crossbody-bags', 'image' => 'assets/images/bags/moutoshi-bag-05.jpg', 'desc' => 'Compact utility crossbody bags.'],
            ['name' => 'New Arrivals', 'slug' => 'new-arrivals', 'image' => 'assets/images/bags/moutoshi-bag-12.jpg', 'desc' => 'Fresh designs added this week to Handbag Haven.'],
            ['name' => 'Clearance Sale', 'slug' => 'clearance-sale', 'image' => 'assets/images/bags/moutoshi-bag-15.jpg', 'desc' => 'Special discount deals on top-selling bags.'],
        ];

        $categories = [];
        foreach ($allCategories as $idx => $c) {
            $categories[$c['slug']] = Category::updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'name' => $c['name'],
                    'image' => $c['image'],
                    'description' => $c['desc'],
                    'is_featured' => ($idx < 8),
                    'sort_order' => $idx + 1,
                ]
            );
        }

        // 5. Products Data (Featuring Moutoshi Handbag REAL Catalog)
        $productsData = [
            [
                'title' => 'Moutoshi Hand-Embroidered Wildflower Canvas Tote',
                'slug' => 'moutoshi-hand-embroidered-wildflower-canvas-tote',
                'category_slug' => 'tote-bags',
                'sku' => 'MH-EMB-001',
                'price' => 750.00,
                'compare_at_price' => 1150.00,
                'stock' => 50,
                'primary_image' => 'assets/images/bags/moutoshi-bag-02.jpg',
                'short_description' => 'Beautiful hand-embroidered floral design on thick, durable 16oz cotton canvas. Features reinforced shoulder straps and inner safety pocket.',
                'description' => '<p>Discover your style at Handbag Haven! This signature hand-embroidered tote bag is crafted from pure unbleached cotton canvas with exquisite botanical threadwork. Designed for campus classes, casual outings, library study sessions, and everyday errands.</p>
                <ul>
                    <li>Material: 100% Heavy-Duty Washable Cotton Canvas (16oz)</li>
                    <li>Design: Handcrafted delicate floral embroidery</li>
                    <li>Compartment: Roomy main chamber with interior zippered pocket</li>
                    <li>Capacity: Fits 15-inch laptop, textbooks, water bottle & makeup pouch</li>
                    <li>Handles: Reinforced cross-stitched ergonomic shoulder straps</li>
                </ul>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/moutoshi-bag-02.jpg',
                    'assets/images/bags/moutoshi-bag-03.jpg',
                    'assets/images/bags/moutoshi-bag-07.jpg',
                    'assets/images/bags/moutoshi-bag-14.jpg',
                    'assets/images/bags/moutoshi-bag-01.jpg',
                ],
                'variants' => [
                    ['name' => 'Daisy White', 'color_code' => '#fbf8f2'],
                    ['name' => 'Wildflower Cream', 'color_code' => '#f5eee1'],
                ],
            ],
            [
                'title' => 'Moutoshi Multi-Pocket Campus Utility Canvas Tote',
                'slug' => 'moutoshi-multi-pocket-campus-utility-canvas-tote',
                'category_slug' => 'tote-bags',
                'sku' => 'MH-UTL-002',
                'price' => 850.00,
                'compare_at_price' => 1250.00,
                'stock' => 45,
                'primary_image' => 'assets/images/bags/moutoshi-bag-05.jpg',
                'short_description' => 'Multi-compartment canvas varsity bag with front utility organizer pockets, strong dual handles, and high-capacity storage.',
                'description' => '<p>Engineered for students and busy professionals. Front pockets provide effortless quick access to smartphone, keys, cards, sanitizer, and pens, while the roomy interior accommodates books and electronics.</p>
                <ul>
                    <li>Outer: 2 large front utility organizer slip pockets</li>
                    <li>Inner: Deep lined compartment with zip closure</li>
                    <li>Carrying: Dual handles for both hand-carry & shoulder wear</li>
                    <li>Eco-Friendly: Machine washable heavy-duty natural fabric</li>
                </ul>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/moutoshi-bag-05.jpg',
                    'assets/images/bags/moutoshi-bag-08.jpg',
                    'assets/images/bags/moutoshi-bag-11.jpg',
                    'assets/images/bags/moutoshi-bag-13.jpg',
                ],
                'variants' => [
                    ['name' => 'Off White', 'color_code' => '#f8f6f0'],
                    ['name' => 'Natural Beige', 'color_code' => '#eee8dc'],
                ],
            ],
            [
                'title' => 'Moutoshi Botanical Blue Blossom Canvas Tote',
                'slug' => 'moutoshi-botanical-blue-blossom-canvas-tote',
                'category_slug' => 'shoulder-bags',
                'sku' => 'MH-BLU-003',
                'price' => 790.00,
                'compare_at_price' => 1200.00,
                'stock' => 38,
                'primary_image' => 'assets/images/bags/moutoshi-bag-06.jpg',
                'short_description' => 'Contrasting botanical floral motif on premium textured canvas. A chic artistic statement piece.',
                'description' => '<p>Artistic and refreshing. Handcrafted with precision embroidery, making it a standout accessory for brunch, university, and evening hangouts.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/moutoshi-bag-06.jpg',
                    'assets/images/bags/moutoshi-bag-09.jpg',
                    'assets/images/bags/moutoshi-bag-12.jpg',
                ],
                'variants' => [
                    ['name' => 'Blue Blossom White', 'color_code' => '#2b4c7e'],
                    ['name' => 'Ivory Cream', 'color_code' => '#f5f3ed'],
                ],
            ],
            [
                'title' => 'Moutoshi Floral Aesthetic Duo Canvas Set (2-in-1)',
                'slug' => 'moutoshi-floral-aesthetic-duo-canvas-set',
                'category_slug' => 'sync-set-bags',
                'sku' => 'MH-DUO-004',
                'price' => 1350.00,
                'compare_at_price' => 1950.00,
                'stock' => 25,
                'primary_image' => 'assets/images/bags/moutoshi-bag-01.jpg',
                'short_description' => 'Matching 2-in-1 set: Large work tote plus compact day bag. Coordinated floral aesthetics in one combo.',
                'description' => '<p>Double the elegance! Get both the large high-capacity tote and the matching day bag at a special combo discount price.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => false,
                'images' => [
                    'assets/images/bags/moutoshi-bag-01.jpg',
                    'assets/images/bags/moutoshi-bag-04.jpg',
                    'assets/images/bags/moutoshi-bag-15.jpg',
                ],
                'variants' => [
                    ['name' => 'Duo Natural Cream', 'color_code' => '#f2ede4'],
                ],
            ],
            [
                'title' => 'Moutoshi Outdoor Lifestyle Shoulder Tote',
                'slug' => 'moutoshi-outdoor-lifestyle-shoulder-tote',
                'category_slug' => 'women',
                'sku' => 'MH-OUT-005',
                'price' => 780.00,
                'compare_at_price' => 1180.00,
                'stock' => 30,
                'primary_image' => 'assets/images/bags/moutoshi-bag-16.jpg',
                'short_description' => 'Lightweight, sunny day canvas bag with wide comfortable straps and reinforced bottom.',
                'description' => '<p>Crafted for sunny weekend walks, travel trips, and everyday campus commutes.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/moutoshi-bag-16.jpg',
                    'assets/images/bags/moutoshi-bag-17.jpg',
                    'assets/images/bags/moutoshi-bag-18.jpg',
                ],
                'variants' => [
                    ['name' => 'Garden White', 'color_code' => '#faf8f5'],
                ],
            ],
            [
                'title' => 'Moutoshi Minimalist Pure Canvas Daily Shopper',
                'slug' => 'moutoshi-minimalist-pure-canvas-shopper',
                'category_slug' => 'tote-bags',
                'sku' => 'MH-MIN-006',
                'price' => 690.00,
                'compare_at_price' => 990.00,
                'stock' => 35,
                'primary_image' => 'assets/images/bags/moutoshi-bag-10.jpg',
                'short_description' => 'Clean aesthetic minimalist canvas shopper. Ultra-durable, washable, and eco-friendly.',
                'description' => '<p>The ultimate clean-aesthetic daily shopper. Washable heavy cotton canvas designed to last for years.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => false,
                'images' => [
                    'assets/images/bags/moutoshi-bag-10.jpg',
                    'assets/images/bags/moutoshi-bag-19.jpg',
                    'assets/images/bags/moutoshi-bag-20.jpg',
                ],
                'variants' => [
                    ['name' => 'Pure Natural', 'color_code' => '#e8e2d5'],
                ],
            ],
            [
                'title' => 'Moutoshi Embroidered Garden Crossbody Tote',
                'slug' => 'moutoshi-embroidered-garden-crossbody-tote',
                'category_slug' => 'crossbody-bags',
                'sku' => 'MH-CRS-007',
                'price' => 820.00,
                'compare_at_price' => 1200.00,
                'stock' => 40,
                'primary_image' => 'assets/images/bags/moutoshi-bag-21.jpg',
                'short_description' => 'Crossbody canvas bag with adjustable strap and delicate botanical needlework.',
                'description' => '<p>Comfortable across your body with effortless access to all your essentials.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/moutoshi-bag-21.jpg',
                    'assets/images/bags/moutoshi-bag-22.jpg',
                    'assets/images/bags/moutoshi-bag-23.jpg',
                ],
                'variants' => [
                    ['name' => 'Garden Floral', 'color_code' => '#efebe3'],
                ],
            ],
            [
                'title' => 'Moutoshi Handcrafted Heritage Canvas Handbag',
                'slug' => 'moutoshi-handcrafted-heritage-handbag',
                'category_slug' => 'handbags',
                'sku' => 'MH-HND-008',
                'price' => 890.00,
                'compare_at_price' => 1350.00,
                'stock' => 22,
                'primary_image' => 'assets/images/bags/moutoshi-bag-24.jpg',
                'short_description' => 'Structured hand-carry canvas handbag with top handles and protective bottom gusset.',
                'description' => '<p>Heritage craftsmanship meets contemporary chic. Made with handpicked canvas and detailed stitching.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => false,
                'images' => [
                    'assets/images/bags/moutoshi-bag-24.jpg',
                    'assets/images/bags/moutoshi-bag-25.jpg',
                    'assets/images/bags/moutoshi-bag-26.jpg',
                ],
                'variants' => [
                    ['name' => 'Warm Cream', 'color_code' => '#f3ede0'],
                ],
            ],
        ];

        foreach ($productsData as $p) {
            $cat = $categories[$p['category_slug']] ?? null;
            $product = Product::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'category_id' => $cat ? $cat->id : null,
                    'title' => $p['title'],
                    'sku' => $p['sku'],
                    'price' => $p['price'],
                    'compare_at_price' => $p['compare_at_price'],
                    'stock' => $p['stock'],
                    'primary_image' => $p['primary_image'],
                    'short_description' => $p['short_description'],
                    'description' => $p['description'],
                    'is_featured' => $p['is_featured'],
                    'is_sale' => $p['is_sale'],
                    'is_new' => $p['is_new'],
                    'is_active' => true,
                ]
            );

            // Add images
            ProductImage::where('product_id', $product->id)->delete();
            foreach ($p['images'] as $idx => $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $img,
                    'is_primary' => ($idx === 0),
                    'sort_order' => $idx + 1,
                ]);
            }

            // Add variants
            ProductVariant::where('product_id', $product->id)->delete();
            foreach ($p['variants'] as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v['name'],
                    'color_code' => $v['color_code'],
                    'stock' => 15,
                ]);
            }
        }

        // 6. Demo Orders
        $order1 = Order::updateOrCreate(
            ['order_number' => 'MH-88210'],
            [
                'customer_name' => 'Tanzina Islam',
                'customer_phone' => '01712345678',
                'customer_email' => 'tanzina@gmail.com',
                'delivery_address' => 'House 42, Road 11, Dhanmondi, Dhaka',
                'city_zone' => 'inside_dhaka',
                'delivery_charge' => 60.00,
                'subtotal' => 750.00,
                'total' => 810.00,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'order_status' => 'shipped',
                'notes' => 'Please call before delivery.',
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order1->id, 'product_name' => 'Moutoshi Hand-Embroidered Wildflower Canvas Tote'],
            [
                'price' => 750.00,
                'quantity' => 1,
                'variant_name' => 'Daisy White',
                'total' => 750.00,
                'image_url' => 'assets/images/bags/moutoshi-bag-02.jpg',
            ]
        );
    }
}
