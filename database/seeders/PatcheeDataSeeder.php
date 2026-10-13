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
            'delivery_inside_dhaka' => '60',
            'delivery_outside_dhaka' => '120',
            'free_delivery_threshold' => '2500',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61559952171891',
            'instagram_url' => 'https://instagram.com/moutoshihandbag',
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Hero Banners
        Banner::truncate();
        Banner::create([
            'title' => 'ELEVATE YOUR STYLE WITH MOUTOSHI',
            'subtitle' => 'Handbag Haven 👜 | Discover premium canvas totes, varsity bags & daily essentials',
            'badge' => 'NEW SEASON ARRIVALS',
            'button_text' => 'SHOP CANVAS TOTES',
            'button_url' => '/collections/tote-bags',
            'image_url' => 'assets/images/bags/canvas-tote-black.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'CAMPUS & CASUAL PERFECTION',
            'subtitle' => 'Spacious, washable & stylish bags designed for everyday comfort',
            'badge' => 'BESTSELLER COLLECTION',
            'button_text' => 'EXPLORE COLLECTION',
            'button_url' => '/collections/women',
            'image_url' => 'assets/images/bags/canvas-college-varsity.jpg',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 4. Collections / Categories
        $allCategories = [
            ['name' => 'Women', 'slug' => 'women', 'image' => 'assets/images/bags/canvas-tote-black.jpg', 'desc' => 'Chic, premium tote bags, crossbody, shoulder bags, and accessories for modern women.'],
            ['name' => 'Tote Bags', 'slug' => 'tote-bags', 'image' => 'assets/images/bags/canvas-tote-beige.jpg', 'desc' => 'Spacious, washable canvas & leather tote bags engineered for work, university, and daily travel.'],
            ['name' => 'Clutch Bags', 'slug' => 'clutch-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Glamorous evening clutches for weddings, parties, and formal galas.'],
            ['name' => 'Shoulder Bags', 'slug' => 'shoulder-bags', 'image' => 'assets/images/bags/minimalist-shoulder-bag.jpg', 'desc' => 'Modern aesthetics meet timeless elegance with versatile shoulder bags.'],
            ['name' => 'Mini Bags', 'slug' => 'mini-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Compact statement pieces for outings, dates, and evenings.'],
            ['name' => 'Bucket Bags', 'slug' => 'bucket-bags', 'image' => 'assets/images/bags/croissant-dumpling-bag.jpg', 'desc' => 'Drawstring cylindrical silhouettes with relaxed charm and high capacity.'],
            ['name' => 'Backpacks', 'slug' => 'backpacks', 'image' => 'assets/images/bags/olive-green-tote.jpg', 'desc' => 'Lightweight, durable, multi-compartment backpacks for city commuting and travel.'],
            ['name' => 'Women Backpacks', 'slug' => 'women-backpack-bags', 'image' => 'assets/images/bags/olive-green-tote.jpg', 'desc' => 'Fashion-forward backpacks tailored for ladies.'],
            ['name' => 'Crossbody Bags', 'slug' => 'crossbody-bags', 'image' => 'assets/images/bags/croissant-dumpling-bag.jpg', 'desc' => 'Hands-free convenience without compromising on style. Perfect side bags.'],
            ['name' => 'Women Crossbody Bags', 'slug' => 'women-crossbody-bags', 'image' => 'assets/images/bags/croissant-dumpling-bag.jpg', 'desc' => 'Signature side bags for ladies in Bangladesh.'],
            ['name' => 'Handbags', 'slug' => 'handbags', 'image' => 'assets/images/bags/classic-leather-handbag.jpg', 'desc' => 'Classy structured ladies top-handle handbags.'],
            ['name' => 'Sync Set Bags', 'slug' => 'sync-set-bags', 'image' => 'assets/images/bags/aesthetic-linen-shopper.jpg', 'desc' => 'Coordinated matching sets (Tote + Crossbody + Wallet).'],
            ['name' => 'Men', 'slug' => 'men', 'image' => 'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=600', 'desc' => 'Sleek RFID wallets, commuter backpacks, and minimalist travel gear.'],
            ['name' => 'Men Backpacks', 'slug' => 'men-backpack-bags', 'image' => 'assets/images/bags/olive-green-tote.jpg', 'desc' => 'Waterproof tech commuting backpacks with laptop compartments.'],
            ['name' => 'Men Crossbody', 'slug' => 'men-crossbody-bags', 'image' => 'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=600', 'desc' => 'Compact chest bags and sling crossbody bags for men.'],
            ['name' => 'Unisex', 'slug' => 'unisex', 'image' => 'assets/images/bags/canvas-tote-black.jpg', 'desc' => 'Gender-neutral travel duffles, gym bags, and canvas totes.'],
            ['name' => 'Cosmetic Bags & Cases', 'slug' => 'cosmetic-bags-cases', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Multi-layer waterproof makeup organizers and pouches.'],
            ['name' => 'Duffle Bags', 'slug' => 'duffle-bags', 'image' => 'assets/images/bags/canvas-college-varsity.jpg', 'desc' => 'Gym, weekend trips, and flight carry-on duffles built to last.'],
            ['name' => 'Travel Backpack', 'slug' => 'travel-backpack', 'image' => 'assets/images/bags/olive-green-tote.jpg', 'desc' => 'Large capacity flight approval travel packs.'],
            ['name' => 'Wallets & Card Holders', 'slug' => 'wallets-card-holders', 'image' => 'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=600', 'desc' => 'Smart RFID pop-up wallets, slim card holders, and luxury clutches.'],
            ['name' => 'Laptop Bags', 'slug' => 'laptop-bags', 'image' => 'assets/images/bags/canvas-tote-black.jpg', 'desc' => 'Shockproof padded briefcases, totes and sleeves.'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Premium fashion accessories, key chains, and luxury accents.'],
            ['name' => 'Bag Charms', 'slug' => 'bag-charms', 'image' => 'https://thepatchee.com/cdn/shop/files/f0d03c76-a64f-43b2-8907-66252bba24e6.png?v=1776663601&width=600', 'desc' => 'Custom charms and metallic tassels to customize your bags.'],
            ['name' => 'New Arrivals', 'slug' => 'new-arrivals', 'image' => 'assets/images/bags/canvas-college-varsity.jpg', 'desc' => 'Fresh designs added this week to Handbag Haven.'],
            ['name' => 'Clearance Sale', 'slug' => 'clearance-sale', 'image' => 'assets/images/bags/canvas-tote-beige.jpg', 'desc' => 'Special discount deals on top-selling bags.'],
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

        // 5. Products Data (featuring Moutoshi Handbag collection with local assets)
        $productsData = [
            [
                'title' => 'Moutoshi Signature Canvas Tote Bag (Black)',
                'slug' => 'moutoshi-signature-canvas-tote-black',
                'category_slug' => 'tote-bags',
                'sku' => 'MH-TT-001',
                'price' => 850.00,
                'compare_at_price' => 1250.00,
                'stock' => 50,
                'primary_image' => 'assets/images/bags/canvas-tote-black.jpg',
                'short_description' => 'Heavy-duty 16oz washable canvas tote bag with reinforced handles, inner zip pouch, and spacious compartment.',
                'description' => '<p>Discover your style at Handbag Haven! The Moutoshi Signature Canvas Tote is crafted with ultra-durable heavy cotton canvas, engineered for university, office, shopping, and everyday errands.</p>
                <ul>
                    <li>Heavy-duty 16oz washable cotton canvas</li>
                    <li>Interior zippered pocket for phone, keys & cards</li>
                    <li>Reinforced shoulder straps with cross-stitching</li>
                    <li>Comfortably fits 15" laptop, notebooks, water bottle & umbrella</li>
                </ul>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/canvas-tote-black.jpg',
                    'assets/images/bags/canvas-tote-beige.jpg',
                ],
                'variants' => [
                    ['name' => 'Classic Black', 'color_code' => '#1c1c1c'],
                    ['name' => 'Natural Beige', 'color_code' => '#f2ebd9'],
                ],
            ],
            [
                'title' => 'Moutoshi Aesthetic Canvas Tote Bag (Natural Beige)',
                'slug' => 'moutoshi-aesthetic-canvas-tote-beige',
                'category_slug' => 'tote-bags',
                'sku' => 'MH-TT-002',
                'price' => 850.00,
                'compare_at_price' => 1250.00,
                'stock' => 45,
                'primary_image' => 'assets/images/bags/canvas-tote-beige.jpg',
                'short_description' => 'Minimalist beige canvas tote bag with inner security pocket. Effortlessly chic for campus and casual hangouts.',
                'description' => '<p>A timeless neutral piece from Moutoshi Handbag. Made from eco-friendly unbleached premium canvas that complements any outfit.</p>
                <ul>
                    <li>Dimensions: 38cm x 35cm x 10cm</li>
                    <li>Inner zippered pocket and magnetic snap closure</li>
                    <li>Lightweight yet capable of carrying heavy loads</li>
                </ul>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/canvas-tote-beige.jpg',
                    'assets/images/bags/canvas-tote-black.jpg',
                ],
                'variants' => [
                    ['name' => 'Natural Beige', 'color_code' => '#f2ebd9'],
                    ['name' => 'Classic Black', 'color_code' => '#1c1c1c'],
                ],
            ],
            [
                'title' => 'College Varsity Multi-Pocket Canvas Shoulder Bag',
                'slug' => 'moutoshi-college-varsity-canvas-bag',
                'category_slug' => 'shoulder-bags',
                'sku' => 'MH-SH-003',
                'price' => 950.00,
                'compare_at_price' => 1400.00,
                'stock' => 38,
                'primary_image' => 'assets/images/bags/canvas-college-varsity.jpg',
                'short_description' => 'Trendy campus-ready shoulder bag with front utility pockets, dual handle straps, and smooth zipper closure.',
                'description' => '<p>The ultimate university and college bag by Moutoshi Handbag. Features multiple quick-access front pockets for pens, sanitizer, phone, and chargers.</p>
                <ul>
                    <li>Multiple outer front pockets</li>
                    <li>Secure top zipper closure</li>
                    <li>Dual carrying straps: hand-carry & shoulder</li>
                </ul>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/canvas-college-varsity.jpg',
                    'assets/images/bags/canvas-tote-black.jpg',
                ],
                'variants' => [
                    ['name' => 'Varsity White & Black', 'color_code' => '#2b2b2b'],
                    ['name' => 'Navy Blue', 'color_code' => '#1f2937'],
                ],
            ],
            [
                'title' => 'Minimalist Structured Ladies Shoulder Bag',
                'slug' => 'moutoshi-minimalist-structured-shoulder-bag',
                'category_slug' => 'shoulder-bags',
                'sku' => 'MH-SH-004',
                'price' => 1450.00,
                'compare_at_price' => 2200.00,
                'stock' => 25,
                'primary_image' => 'assets/images/bags/minimalist-shoulder-bag.jpg',
                'short_description' => 'Sleek geometric silhouette crafted with premium vegan leather and comfortable wide strap.',
                'description' => '<p>Clean lines and contemporary elegance. Tailored for meetings, dinners, and everyday sophisticated styling.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => false,
                'images' => [
                    'assets/images/bags/minimalist-shoulder-bag.jpg',
                ],
                'variants' => [
                    ['name' => 'Ivory Cream', 'color_code' => '#f3eee3'],
                    ['name' => 'Midnight Black', 'color_code' => '#111111'],
                ],
            ],
            [
                'title' => 'Olive Green Daily Utility Canvas Tote',
                'slug' => 'moutoshi-olive-green-daily-utility-tote',
                'category_slug' => 'tote-bags',
                'sku' => 'MH-TT-005',
                'price' => 890.00,
                'compare_at_price' => 1300.00,
                'stock' => 30,
                'primary_image' => 'assets/images/bags/olive-green-tote.jpg',
                'short_description' => 'Earth-toned army olive canvas tote with reinforced bottom base and spacious interior.',
                'description' => '<p>A stunning earthy olive tone that stands out. Made from washable canvas with durable stitching.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/olive-green-tote.jpg',
                ],
                'variants' => [
                    ['name' => 'Olive Green', 'color_code' => '#556b2f'],
                    ['name' => 'Khaki Tan', 'color_code' => '#c2b280'],
                ],
            ],
            [
                'title' => 'Aesthetic French Linen Everyday Shopper',
                'slug' => 'moutoshi-aesthetic-linen-shopper',
                'category_slug' => 'tote-bags',
                'sku' => 'MH-TT-006',
                'price' => 820.00,
                'compare_at_price' => 1190.00,
                'stock' => 35,
                'primary_image' => 'assets/images/bags/aesthetic-linen-shopper.jpg',
                'short_description' => 'Breathable textured linen blend shopper bag. Effortless, lightweight, and eco-friendly.',
                'description' => '<p>Crafted for weekend farmers markets, relaxed brunch, or beach days. Soft texture with high durability.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => false,
                'images' => [
                    'assets/images/bags/aesthetic-linen-shopper.jpg',
                ],
                'variants' => [
                    ['name' => 'Linen Oatmeal', 'color_code' => '#e3dac9'],
                    ['name' => 'Pure Black', 'color_code' => '#1a1a1a'],
                ],
            ],
            [
                'title' => 'Croissant Dumpling Soft Crossbody Bag',
                'slug' => 'moutoshi-croissant-dumpling-crossbody-bag',
                'category_slug' => 'crossbody-bags',
                'sku' => 'MH-CB-007',
                'price' => 1250.00,
                'compare_at_price' => 1850.00,
                'stock' => 40,
                'primary_image' => 'assets/images/bags/croissant-dumpling-bag.jpg',
                'short_description' => 'Viral crescent moon dumpling bag. Ultra-soft waterproof material with wide adjustable crossbody strap.',
                'description' => '<p>Lightweight yet roomy enough for your wallet, makeup, sunglasses, and phone. A modern streetwear essential.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'images' => [
                    'assets/images/bags/croissant-dumpling-bag.jpg',
                ],
                'variants' => [
                    ['name' => 'Matte Black', 'color_code' => '#181818'],
                    ['name' => 'Warm Beige', 'color_code' => '#e8dfd3'],
                ],
            ],
            [
                'title' => 'Classic Structured Top-Handle Leather Handbag',
                'slug' => 'moutoshi-classic-structured-leather-handbag',
                'category_slug' => 'handbags',
                'sku' => 'MH-HB-008',
                'price' => 1850.00,
                'compare_at_price' => 2650.00,
                'stock' => 22,
                'primary_image' => 'assets/images/bags/classic-leather-handbag.jpg',
                'short_description' => 'Sophisticated structured handbag with gold-plated hardware, protective base studs, and detachable sling.',
                'description' => '<p>Pure elegance for formal events, parties, and executive workdays. Built with premium scratch-resistant leather.</p>',
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => false,
                'images' => [
                    'assets/images/bags/classic-leather-handbag.jpg',
                ],
                'variants' => [
                    ['name' => 'Classic Black', 'color_code' => '#141414'],
                    ['name' => 'Rich Cognac', 'color_code' => '#9a5327'],
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
                'subtotal' => 850.00,
                'total' => 910.00,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'order_status' => 'shipped',
                'notes' => 'Please call before delivery.',
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order1->id, 'product_name' => 'Moutoshi Signature Canvas Tote Bag (Black)'],
            [
                'price' => 850.00,
                'quantity' => 1,
                'variant_name' => 'Classic Black',
                'total' => 850.00,
                'image_url' => 'assets/images/bags/canvas-tote-black.jpg',
            ]
        );
    }
}
