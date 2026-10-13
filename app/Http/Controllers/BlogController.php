<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected array $articles = [
        [
            'title' => 'How to Style Structured Tote Bags for Corporate & Everyday Looks',
            'slug' => 'how-to-style-structured-tote-bags',
            'category' => 'STYLE GUIDE',
            'date' => 'October 10, 2026',
            'read_time' => '4 min read',
            'image' => 'https://thepatchee.com/cdn/shop/files/d0783770-4668-4b3e-a3b5-2bbc89c2869f.jpg?v=1776151580&width=800',
            'excerpt' => 'From corporate boardroom meetings to relaxed weekend café dates, discover how to pair luxury tote bags with tailored blazers and monochrome dresses.',
            'content' => '<p class="lead">A luxury tote bag isn’t just an accessory — it’s a statement of ambition and poise. In 2026, fashion in Dhaka has shifted towards refined minimalism, where effortless utility meets Italian-inspired structure.</p>
            <h3>1. The Monochromatic Corporate Look</h3>
            <p>Pairing a jet-black or mahogany structured tote with crisp ivory trousers and a neutral tailored blazer elevates your posture and confidence. Make sure your hardware — like gold metallic locks — complements your watch and jewelry.</p>
            <h3>2. Off-Duty Weekend Chic</h3>
            <p>Swap the blazer for an oversized cotton trench or linen button-down. A spacious tote accommodates your iPad, daily planner, and cosmetics without looking bulky.</p>
            <h3>3. Caring for Vegan Leather in Bangladesh’s Climate</h3>
            <p>Keep your bag away from damp closets during monsoon season. A gentle wipe with a dry microfiber cloth maintains the subtle luster of high-grade vegan leather for years.</p>'
        ],
        [
            'title' => 'The Rise of the Crescent Dumpling Bag: Why Everyone Loves It',
            'slug' => 'rise-of-crescent-dumpling-bag',
            'category' => 'TREND REPORT',
            'date' => 'September 28, 2026',
            'read_time' => '3 min read',
            'image' => 'https://thepatchee.com/cdn/shop/files/c2ee0999-fc06-4942-aa71-6cd41e71d473.png?v=1782728863&width=800',
            'excerpt' => 'Exploring the ergonomics and streetwear versatility of curved croissant bags that took international runways and Dhaka fashion by storm.',
            'content' => '<p class="lead">Curved silhouettes have officially displaced rigid boxy clutches in streetwear. Why? Comfort.</p>
            <p>The crescent croissant bag molds effortlessly to your body contour, distributing weight across your torso while keeping both hands completely free for city commuting and transit.</p>
            <p>Our soft-grain waterproof edition is specifically engineered to endure the unexpected rain showers of Bangladesh while keeping all your tech gadgets perfectly dry.</p>'
        ],
        [
            'title' => 'Why RFID Pop-Up Wallets Are an Essential Upgrade for Modern Men',
            'slug' => 'why-rfid-wallets-are-essential',
            'category' => 'TECH & GEAR',
            'date' => 'September 15, 2026',
            'read_time' => '5 min read',
            'image' => 'https://thepatchee.com/cdn/shop/files/654a7e83-c512-4ec1-8ebf-af44c488e776.png?v=1781362688&width=800',
            'excerpt' => 'Digital payment cards are susceptible to wireless skimming scanners. Learn how aircraft-grade aluminum casings keep your cards safe and organized.',
            'content' => '<p class="lead">Bulky bi-fold leather wallets are a relic of the past. Modern urbanites carry smart contactless debit/credit cards that require active RFID blocking protection.</p>
            <p>With a single click of our quick-release mechanism, all your cards pop out in an organized stair-step fan, ready for instant contactless payment at restaurants and shopping malls.</p>'
        ]
    ];

    public function index()
    {
        $articles = $this->articles;
        return view('pages.blog-index', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = collect($this->articles)->firstWhere('slug', $slug);
        if (!$article) {
            abort(404);
        }

        $related = collect($this->articles)->where('slug', '!=', $slug)->take(2);
        return view('pages.blog-show', compact('article', 'related'));
    }
}
