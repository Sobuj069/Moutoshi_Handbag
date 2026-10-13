# Moutoshi_Handbag

A modern luxury e-commerce platform and handbag store inspired by **The Patchee BD** prestige theme, built with **Laravel 12**, **Tailwind CSS 4**, and **Alpine.js**.

---

## 🌟 Key Features

### Storefront & Aesthetics
- **Shopify Prestige Theme Luxury Design**: Jost typography, charcoal `#1c1c1c` palette, gold `#c9a66b` accents.
- **Announcement Bar**: Sticky carousel top bar with rotating promotional notices.
- **Sticky Prestige Header**: Desktop mega-menu, mobile navigation drawer, interactive search modal, and cart badge.
- **Hero Slider & Marquee**: High-res editorial photo slides, infinite animated luxury ticker ribbon.
- **Curated Collections**: Just Landed, Best Sellers, 27 Category Collections (Tote, Shoulder, Crossbody, Backpacks, Wallets).
- **Interactive Product Details**: Multi-angle gallery zoom, variant swatches, tab accordions, related items.
- **Slide-out Cart Drawer**: Real-time quantity adjustments, free delivery threshold progress meter.
- **1-Click Cash on Delivery Modal**: High-converting instant checkout without leaving the product page.
- **Full Checkout**: Fast 1-page checkout with live Inside Dhaka (৳60) / Outside Dhaka (৳120) rates, COD and bKash options.
- **Real-Time Order Tracking**: 4-step progress bar (*Order Placed -> Processing -> Shipped -> Delivered*).

### Complete Editorial & Policy Sub-Pages
- **About Us**: *"Where It All Began"* & brand heritage story.
- **Contact Us**: Contact form, Banani Showroom address, hotline, operating hours.
- **Exchange & Return Policy**: 3-step return guide & 7-day policy.
- **Help & FAQs**: Categorized interactive accordions.
- **Privacy Policy & Terms of Service**: Compliance and store policies.
- **B2B & Corporate Queries**: Corporate gifting and wholesale inquiry form.
- **Customer Complaint Portal**: Formal escalation desk for parcel and order issues.
- **Meet Our Founder & CEO**: Executive leadership message and story.
- **Bag Talks & Style Stories (Blog)**: Fashion guides and style journal.
- **Customer Authentication & Account**: Sign in, register, and full personal order history.

### Administrative Management Panel (`/admin`)
- **Dashboard**: Live analytics (Sales turnover, orders count, pending orders, catalog items).
- **Product Management**: Full CRUD, variants, pricing, compare price, badges, images.
- **Order Management**: Status workflow (*Pending -> Processing -> Shipped -> Delivered -> Cancelled*), printable packing slips & invoices.
- **Category & Banner Management**: Hero sliders, collection directories.
- **Store Settings**: Delivery fees, hotline, address, free delivery minimum.

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2 or 8.3
- Composer
- Node.js & npm
- MySQL

### Setup Instructions
1. Clone the repository:
   ```bash
   git clone https://github.com/Sobuj069/Moutoshi_Handbag.git
   cd Moutoshi_Handbag
   ```

2. Install PHP and JS dependencies:
   ```bash
   composer install
   npm install
   ```

3. Setup environment and database:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations and database seeder:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. Build frontend assets:
   ```bash
   npm run build
   ```

6. Link storage and start local server:
   ```bash
   php artisan storage:link
   php artisan serve
   ```

---

## 🔑 Default Credentials

- **Admin Portal**: `http://localhost:8000/admin/login`
  - **Email**: `admin@thepatchee.com`
  - **Password**: `password`
- **Customer Portal**: `http://localhost:8000/customer/login`
  - **Email**: `customer@thepatchee.com`
  - **Password**: `password`

---

## 📄 License
This project is open-source and created for e-commerce demonstrations.
