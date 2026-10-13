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
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@thepatchee.com'],
            [
                'name' => 'Patchee Admin',
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
            'site_name' => 'The Patchee Bangladesh',
            'site_tagline' => 'Stylish Tote Bags & Side Bags for Ladies | Patchee BD',
            'logo' => 'https://thepatchee.com/cdn/shop/files/The_Patchee-Logo-05.png?v=1734523690&width=180',
            'phone' => '+880 1711-223344',
            'email' => 'support@thepatchee.com',
            'address' => 'Banani, Road 11, Block D, Dhaka, Bangladesh',
            'announcement_1' => 'No Tricks, Just BOO-tiful Deals 👻 | Up to 75% Off',
            'announcement_2' => 'Free Delivery on prepaid orders over ৳2,500',
            'announcement_3' => 'Cash On Delivery Available All Over Bangladesh 🇧🇩',
            'delivery_inside_dhaka' => '60',
            'delivery_outside_dhaka' => '120',
            'free_delivery_threshold' => '2500',
            'facebook_url' => 'https://facebook.com/thepatchee',
            'instagram_url' => 'https://instagram.com/thepatchee',
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Hero Banners
        Banner::truncate();
        Banner::create([
            'title' => 'TIMELESS. BOLD. UNSTOPPABLE.',
            'subtitle' => 'Explore the exclusive 2026 Collection of Luxury Ladies Bags & Accessories',
            'badge' => 'NEW SEASON ARRIVALS',
            'button_text' => 'EXPLORE COLLECTION',
            'button_url' => '/collections/women',
            'image_url' => 'https://thepatchee.com/cdn/shop/files/DESKTOP_SIZE.webp?v=1791810459&width=1800',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'BAG THE VIBE',
            'subtitle' => 'Statement Shoulder Bags, Totes & Wallets Crafted For Everyday Perfection',
            'badge' => 'UP TO 70% OFF',
            'button_text' => 'SHOP POPULAR PICKS',
            'button_url' => '/collections/shoulder-bags',
            'image_url' => 'https://thepatchee.com/cdn/shop/files/Desktop_size_8.png?v=1790332863&width=1800',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 4. Complete List of Target Site Collections
        $allCategories = [
            ['name' => 'Women', 'slug' => 'women', 'image' => 'https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=600', 'desc' => 'Chic, premium tote bags, crossbody, shoulder bags, and luxury accessories for modern women.'],
            ['name' => 'Tote Bags', 'slug' => 'tote-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/5084834f-5845-43de-8fad-100416886850.jpg?v=1776148689&width=600', 'desc' => 'Spacious, elegant tote bags engineered for work, college, and effortless daily travel.'],
            ['name' => 'Clutch Bags', 'slug' => 'clutch-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Glamorous evening clutches for weddings, parties, and formal galas.'],
            ['name' => 'Shoulder Bags', 'slug' => 'shoulder-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/Untitled_20design_20_2875_29_ojw3jt.png?v=1777968337&width=600', 'desc' => 'Modern aesthetics meet timeless elegance with versatile shoulder bags.'],
            ['name' => 'Mini Bags', 'slug' => 'mini-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Compact yet stunning statement pieces for parties, dates, and evenings out.'],
            ['name' => 'Bucket Bags', 'slug' => 'bucket-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/c2ee0999-fc06-4942-aa71-6cd41e71d473.png?v=1782728863&width=600', 'desc' => 'Drawstring cylindrical silhouettes with relaxed charm and high capacity.'],
            ['name' => 'Backpacks', 'slug' => 'backpacks', 'image' => 'https://thepatchee.com/cdn/shop/files/6d8a6d9b-495a-4d68-ba90-1f87aa300435.jpg?v=1755610521&width=600', 'desc' => 'Lightweight, durable, multi-compartment backpacks for city commuting and travel.'],
            ['name' => 'Women Backpacks', 'slug' => 'women-backpack-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/6d8a6d9b-495a-4d68-ba90-1f87aa300435.jpg?v=1755610521&width=600', 'desc' => 'Fashion-forward anti-theft backpacks tailored for ladies.'],
            ['name' => 'Crossbody Bags', 'slug' => 'crossbody-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/c2ee0999-fc06-4942-aa71-6cd41e71d473.png?v=1782728863&width=600', 'desc' => 'Hands-free convenience without compromising on style. Perfect side bags for ladies.'],
            ['name' => 'Women Crossbody Bags', 'slug' => 'women-crossbody-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/c2ee0999-fc06-4942-aa71-6cd41e71d473.png?v=1782728863&width=600', 'desc' => 'Signature side bags for ladies in Bangladesh.'],
            ['name' => 'Handbags', 'slug' => 'handbags', 'image' => 'https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=600', 'desc' => 'Classy structured ladies top-handle handbags.'],
            ['name' => 'Sync Set Bags', 'slug' => 'sync-set-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/5084834f-5845-43de-8fad-100416886850.jpg?v=1776148689&width=600', 'desc' => 'Coordinated 3-in-1 matching sets (Tote + Crossbody + Wallet).'],
            ['name' => 'Men', 'slug' => 'men', 'image' => 'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=600', 'desc' => 'Sleek RFID wallets, commuter backpacks, and minimalist travel gear for men.'],
            ['name' => 'Men Backpacks', 'slug' => 'men-backpack-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/6d8a6d9b-495a-4d68-ba90-1f87aa300435.jpg?v=1755610521&width=600', 'desc' => 'Waterproof tech commuting backpacks with laptop compartments.'],
            ['name' => 'Men Crossbody', 'slug' => 'men-crossbody-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=600', 'desc' => 'Compact chest bags and sling crossbody bags for men.'],
            ['name' => 'Unisex', 'slug' => 'unisex', 'image' => 'https://thepatchee.com/cdn/shop/files/625df22d-ff10-4b67-a4fc-3c43388fcd51.png?v=1776663753&width=600', 'desc' => 'Gender-neutral travel duffles, gym bags, and card cases.'],
            ['name' => 'Cosmetic Bags & Cases', 'slug' => 'cosmetic-bags-cases', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Multi-layer waterproof makeup organizers and cosmetic pouches.'],
            ['name' => 'Duffle Bags', 'slug' => 'duffle-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/625df22d-ff10-4b67-a4fc-3c43388fcd51.png?v=1776663753&width=600', 'desc' => 'Gym, weekend trips, and flight carry-on duffles built to last.'],
            ['name' => 'Travel Backpack', 'slug' => 'travel-backpack', 'image' => 'https://thepatchee.com/cdn/shop/files/6d8a6d9b-495a-4d68-ba90-1f87aa300435.jpg?v=1755610521&width=600', 'desc' => 'Large capacity flight approval travel packs.'],
            ['name' => 'Wallets & Card Holders', 'slug' => 'wallets-card-holders', 'image' => 'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=600', 'desc' => 'Smart RFID pop-up wallets, slim card holders, and luxury leather clutches.'],
            ['name' => 'Laptop Bags', 'slug' => 'laptop-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/6d8a6d9b-495a-4d68-ba90-1f87aa300435.jpg?v=1755610521&width=600', 'desc' => 'Shockproof padded luxury briefcases and sleeves.'],
            ['name' => 'Watches', 'slug' => 'watches', 'image' => 'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=600', 'desc' => 'Minimalist luxury wristwatches to complement your style.'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Premium fashion accessories, key chains, and luxury accents.'],
            ['name' => 'Bag Charms', 'slug' => 'bag-charms', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Custom charms, pom poms, and metallic tassels to customize your bags.'],
            ['name' => 'The Prime Drop', 'slug' => 'the-prime-drop', 'image' => 'https://thepatchee.com/cdn/shop/files/Untitled_20design_20_2875_29_ojw3jt.png?v=1777968337&width=600', 'desc' => 'Ultra-exclusive limited releases crafted with rare materials.'],
            ['name' => 'New Arrivals', 'slug' => 'new-arrivals', 'image' => 'https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=600', 'desc' => 'Fresh designs added this week.'],
            ['name' => 'Clearance Sale', 'slug' => 'clearance-sale', 'image' => 'https://thepatchee.com/cdn/shop/files/5084834f-5845-43de-8fad-100416886850.jpg?v=1776148689&width=600', 'desc' => 'End of season clearance with discounts up to 80% off.'],
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

        // 5. Products Data
        $productsData = [
            [
                'title' => 'New Style Large-Capacity Shoulder Bag',
                'slug' => 'new-style-large-capacity-shoulder-bag-1',
                'category_slug' => 'shoulder-bags',
                'sku' => 'PTC-SH-001',
                'price' => 1673.00,
                'compare_at_price' => 2883.00,
                'stock' => 35,
                'primary_image' => 'https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=1000',
                'short_description' => 'Premium textured vegan leather with reinforced straps and spacious main compartment. Fits iPad, notebooks, cosmetics, and phone with ease.',
                'description' => '<p>Designed for women who refuse to compromise between fashion and utility. Crafted with handpicked premium scratch-resistant vegan leather, high-grade golden hardware, and an ergonomic shoulder strap for all-day comfort.</p>
                <ul>
                    <li>Dimensions: 32cm x 28cm x 12cm</li>
                    <li>Material: High-Grade Premium PU Leather</li>
                    <li>Closure: Smooth Metallic Zipper + Magnetic Snap</li>
                    <li>Interior: Multi-layer organizers and zippered safety pocket</li>
                </ul>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=1000',
                    'https://thepatchee.com/cdn/shop/files/93a85f8d-9359-4dd5-bdd5-b59e518db3c9.jpg?v=1776151583&width=800',
                    'https://thepatchee.com/cdn/shop/files/5236d1d1-1674-48c4-a4db-2e6d9995241c.jpg?v=1776151594&width=800',
                    'https://thepatchee.com/cdn/shop/files/eb2cd0b8-7e5c-4015-bbd6-e31ed33a5efa.jpg?v=1776151600&width=800',
                ],
                'variants' => [
                    ['name' => 'Classic Black', 'color_code' => '#1c1c1c'],
                    ['name' => 'Cream Beige', 'color_code' => '#e8dfd3'],
                    ['name' => 'Mocha Brown', 'color_code' => '#5c3a21'],
                ],
            ],
            [
                'title' => 'Croissant Chic Crossbody Bag',
                'slug' => 'croissant-chic-crossbody-bag',
                'category_slug' => 'crossbody-bags',
                'sku' => 'PTC-CB-002',
                'price' => 1669.00,
                'compare_at_price' => 2750.00,
                'stock' => 42,
                'primary_image' => 'https://thepatchee.com/cdn/shop/files/c2ee0999-fc06-4942-aa71-6cd41e71d473.png?v=1782728863&width=800',
                'short_description' => 'The viral curved crescent dumpling silhouette. Ultra-lightweight, waterproof soft leather with wide comfortable crossbody strap.',
                'description' => '<p>The iconic Croissant silhouette is a fashion-forward favorite. Light as a feather, spacious enough for your wallet, makeup, keys, and phone.</p>
                <ul>
                    <li>Dimensions: 30cm x 17cm x 10cm</li>
                    <li>Strap: Adjustable wide nylon & leather blend</li>
                    <li>Weight: Only 280 grams</li>
                    <li>Style: Casual Chic / Streetwear / Daily Commute</li>
                </ul>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'https://thepatchee.com/cdn/shop/files/c2ee0999-fc06-4942-aa71-6cd41e71d473.png?v=1782728863&width=800',
                    'https://thepatchee.com/cdn/shop/files/3_8MaPFd.png?v=1782728867&width=800',
                    'https://thepatchee.com/cdn/shop/files/O1CN01YqEumd1HjF4E1TrX8__2047230793-0-cib.jpg?v=1782728887&width=800',
                    'https://thepatchee.com/cdn/shop/files/O1CN01YNgqYf1HjF4E1TKFu__2047230793-0-cib.jpg?v=1782728884&width=800',
                ],
                'variants' => [
                    ['name' => 'Matte Black', 'color_code' => '#181818'],
                    ['name' => 'Warm Khaki', 'color_code' => '#c2a884'],
                    ['name' => 'Off White', 'color_code' => '#f5f3ed'],
                ],
            ],
            [
                'title' => 'High-End Retro Commuter Shoulder Bag',
                'slug' => 'high-end-retro-commuter-shoulder-bag',
                'category_slug' => 'shoulder-bags',
                'sku' => 'PTC-SH-003',
                'price' => 2688.00,
                'compare_at_price' => 3990.00,
                'stock' => 20,
                'primary_image' => 'https://thepatchee.com/cdn/shop/files/Untitled_20design_20_2875_29_ojw3jt.png?v=1777968337&width=800',
                'short_description' => 'Vintage Italian-inspired structured shoulder bag with custom metallic buckle and velvet lined interior.',
                'description' => '<p>An aristocratic blend of retro sophistication and contemporary utility. Perfect for corporate meetings, brunch dates, and formal events.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => false,
                'images' => [
                    'https://thepatchee.com/cdn/shop/files/Untitled_20design_20_2875_29_ojw3jt.png?v=1777968337&width=800',
                ],
                'variants' => [
                    ['name' => 'Vintage Mahogany', 'color_code' => '#422416'],
                    ['name' => 'Onyx Black', 'color_code' => '#111111'],
                ],
            ],
            [
                'title' => 'New Wave Square Shoulder Bag',
                'slug' => 'new-wave-square-shoulder-bag',
                'category_slug' => 'tote-bags',
                'sku' => 'PTC-TT-004',
                'price' => 3531.00,
                'compare_at_price' => 4800.00,
                'stock' => 18,
                'primary_image' => 'https://thepatchee.com/cdn/shop/files/5084834f-5845-43de-8fad-100416886850.jpg?v=1776148689&width=1000',
                'short_description' => 'Geometric clean-line square tote with reinforced base and magnetic lock. The definition of modern luxury.',
                'description' => '<p>Minimalist lines with maximum presence. Tailored for power dressers who adore structured bags.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'https://thepatchee.com/cdn/shop/files/5084834f-5845-43de-8fad-100416886850.jpg?v=1776148689&width=1000',
                ],
                'variants' => [
                    ['name' => 'Caramel Tan', 'color_code' => '#a3683a'],
                    ['name' => 'Jet Black', 'color_code' => '#1e1e1e'],
                ],
            ],
            [
                'title' => 'Executive RFID Card Holder & Pop-up Wallet',
                'slug' => 'mens-rfid-smart-pop-up-wallet',
                'category_slug' => 'wallets-card-holders',
                'sku' => 'PTC-WL-005',
                'price' => 1272.00,
                'compare_at_price' => 1950.00,
                'stock' => 60,
                'primary_image' => 'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=800',
                'short_description' => 'Aluminum quick-eject pop-up card mechanism with anti-skimming RFID protection and genuine leather casing.',
                'description' => '<p>Instant one-click trigger pops up all your cards in a neat staircase order. Protects against digital theft and RFID scanning.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => false,
                'images' => [
                    'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=800',
                    'https://thepatchee.com/cdn/shop/files/dc64c41d-ae95-4a7f-912f-1c2a664d35f1.jpg?v=1781362693&width=800',
                    'https://thepatchee.com/cdn/shop/files/15_1Knw4N.jpg?v=1781362711&width=800',
                    'https://thepatchee.com/cdn/shop/files/18_2rgYCr.jpg?v=1781362709&width=800',
                ],
                'variants' => [
                    ['name' => 'Carbon Fiber Black', 'color_code' => '#212529'],
                    ['name' => 'Espresso Brown', 'color_code' => '#4a3525'],
                ],
            ],
            [
                'title' => 'Light-Weight Fitness Training Gym Duffle Bag',
                'slug' => 'light-weight-fitness-training-gym-duffle-bag',
                'category_slug' => 'duffle-bags',
                'sku' => 'PTC-DF-006',
                'price' => 3293.00,
                'compare_at_price' => 4500.00,
                'stock' => 25,
                'primary_image' => 'https://thepatchee.com/cdn/shop/files/625df22d-ff10-4b67-a4fc-3c43388fcd51.png?v=1776663753&width=800',
                'short_description' => 'Dedicated ventilated shoe compartment, dry/wet separation pocket, and water-repellent oxford fabric.',
                'description' => '<p>Your ultimate workout and overnight travel companion. Easily accommodates gym attire, protein shaker, sneakers, and toiletries.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => false,
                'images' => [
                    'https://thepatchee.com/cdn/shop/files/625df22d-ff10-4b67-a4fc-3c43388fcd51.png?v=1776663753&width=800',
                ],
                'variants' => [
                    ['name' => 'Stealth Charcoal', 'color_code' => '#343a40'],
                    ['name' => 'Dusty Rose', 'color_code' => '#d4a59a'],
                ],
            ],
            [
                'title' => 'Butterfly Design Luxury Shoulder Bag',
                'slug' => 'butterfly-design-shoulder-bag',
                'category_slug' => 'shoulder-bags',
                'sku' => 'PTC-SH-007',
                'price' => 2413.00,
                'compare_at_price' => 3600.00,
                'stock' => 30,
                'primary_image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=800',
                'short_description' => 'Embossed butterfly motif with detachable charm and dual interchangeable handles (chain + leather).',
                'description' => '<p>A whimsical yet ultra-chic piece that turns heads wherever you step.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=800',
                ],
                'variants' => [
                    ['name' => 'Soft Vanilla', 'color_code' => '#fbf3d5'],
                    ['name' => 'Midnight Noir', 'color_code' => '#141414'],
                ],
            ],
            [
                'title' => 'Trend Flex Everyday Waterproof Backpack',
                'slug' => 'trend-flex-backpack',
                'category_slug' => 'backpacks',
                'sku' => 'PTC-BP-008',
                'price' => 3321.00,
                'compare_at_price' => 4950.00,
                'stock' => 28,
                'primary_image' => 'https://thepatchee.com/cdn/shop/files/6d8a6d9b-495a-4d68-ba90-1f87aa300435.jpg?v=1755610521&width=1000',
                'short_description' => 'Padded 15.6" laptop sleeve, water-resistant nylon, hidden anti-theft back pocket, and breathable airflow back padding.',
                'description' => '<p>Crafted for young professionals, college students, and commuters seeking sleek protection for tech gears and everyday essentials.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'https://thepatchee.com/cdn/shop/files/6d8a6d9b-495a-4d68-ba90-1f87aa300435.jpg?v=1755610521&width=1000',
                ],
                'variants' => [
                    ['name' => 'Matte Black', 'color_code' => '#1c1c1c'],
                    ['name' => 'Military Green', 'color_code' => '#4b5320'],
                    ['name' => 'Navy Blue', 'color_code' => '#1f2937'],
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
            ['order_number' => 'PTC-88210'],
            [
                'customer_name' => 'Tanzina Islam',
                'customer_phone' => '01712345678',
                'customer_email' => 'tanzina@gmail.com',
                'delivery_address' => 'House 42, Road 11, Dhanmondi, Dhaka',
                'city_zone' => 'inside_dhaka',
                'delivery_charge' => 60.00,
                'subtotal' => 1673.00,
                'total' => 1733.00,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'order_status' => 'shipped',
                'notes' => 'Please call before delivery.',
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order1->id, 'product_name' => 'New Style Large-Capacity Shoulder Bag'],
            [
                'price' => 1673.00,
                'quantity' => 1,
                'variant_name' => 'Classic Black',
                'total' => 1673.00,
                'image_url' => 'https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=200',
            ]
        );
    }
}
