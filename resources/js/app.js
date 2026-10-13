import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global Cart Store
Alpine.store('cart', {
    drawerOpen: false,
    quickViewOpen: false,
    quickViewProduct: null,
    buyNowModalOpen: false,
    buyNowProduct: null,
    items: [],
    count: 0,
    subtotal: 0,
    freeShippingThreshold: 2500,

    init() {
        this.fetchCart();
    },

    toggleDrawer() {
        this.drawerOpen = !this.drawerOpen;
    },

    openDrawer() {
        this.drawerOpen = true;
    },

    closeDrawer() {
        this.drawerOpen = false;
    },

    async fetchCart() {
        try {
            const res = await fetch('/cart/data');
            const data = await res.json();
            this.items = data.items || [];
            this.count = data.count || 0;
            this.subtotal = data.subtotal || 0;
            this.freeShippingThreshold = data.freeShippingThreshold || 2500;
        } catch (err) {
            console.error('Failed to fetch cart:', err);
        }
    },

    async addToCart(productId, quantity = 1, variant = '') {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    variant: variant
                })
            });
            const data = await res.json();
            if (data.success) {
                this.items = data.items;
                this.count = data.count;
                this.subtotal = data.subtotal;
                this.openDrawer();
            }
        } catch (err) {
            console.error('Error adding to cart:', err);
        }
    },

    async updateQuantity(cartKey, quantity) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    cart_key: cartKey,
                    quantity: quantity
                })
            });
            const data = await res.json();
            if (data.success) {
                this.items = data.items;
                this.count = data.count;
                this.subtotal = data.subtotal;
            }
        } catch (err) {
            console.error('Error updating cart:', err);
        }
    },

    async removeItem(cartKey) {
        await this.updateQuantity(cartKey, 0);
    },

    openBuyNow(product, selectedVariant = '') {
        this.buyNowProduct = {
            ...product,
            selectedVariant: selectedVariant || (product.variants?.[0]?.name || '')
        };
        this.buyNowModalOpen = true;
    },

    closeBuyNow() {
        this.buyNowModalOpen = false;
        this.buyNowProduct = null;
    }
});

Alpine.start();
