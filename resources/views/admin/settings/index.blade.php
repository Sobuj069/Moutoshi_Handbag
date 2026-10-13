@extends('admin.layouts.admin')

@section('title', 'Store Settings')
@section('header_title', 'Store & Delivery Settings')

@section('content')

    <div class="max-w-4xl bg-white border border-neutral-200 rounded-xs shadow-2xs p-4 sm:p-6 lg:p-8">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- 1. Store Identity -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                    1. STORE INFORMATION
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Store Name</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Moutoshi Handbag' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Store Tagline / Title</label>
                        <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? 'Handbag Haven | Discover your style with the perfect accessory for every occasion' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Customer Care Phone</label>
                        <input type="text" name="phone" value="{{ $settings['phone'] ?? '+880 1711-000000' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Support Email</label>
                        <input type="email" name="email" value="{{ $settings['email'] ?? 'support@moutoshihandbag.shop' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Physical Store / Office Address</label>
                        <input type="text" name="address" value="{{ $settings['address'] ?? 'Dhaka, Bangladesh' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>
                </div>
            </div>

            <!-- 2. Delivery Rates -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                    2. SHIPPING CHARGES & THRESHOLDS (BANGLADESH)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Inside Dhaka Rate (৳)</label>
                        <input type="number" step="1" name="delivery_inside_dhaka" value="{{ $settings['delivery_inside_dhaka'] ?? 80 }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Outside Dhaka Rate (৳)</label>
                        <input type="number" step="1" name="delivery_outside_dhaka" value="{{ $settings['delivery_outside_dhaka'] ?? 130 }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Free Delivery Min Spend (৳) [0 = Disable]</label>
                        <input type="number" step="1" name="free_delivery_threshold" value="{{ $settings['free_delivery_threshold'] ?? 0 }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>
                </div>
            </div>

            <!-- 3. Announcement Bar -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                    3. TOP ANNOUNCEMENT BAR MESSAGES
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Announcement Message 1</label>
                        <input type="text" name="announcement_1" value="{{ $settings['announcement_1'] ?? 'No Tricks, Just BOO-tiful Deals 👻 | Up to 75% Off' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Announcement Message 2</label>
                        <input type="text" name="announcement_2" value="{{ $settings['announcement_2'] ?? 'Free Delivery on prepaid orders over ৳2,500' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Announcement Message 3</label>
                        <input type="text" name="announcement_3" value="{{ $settings['announcement_3'] ?? 'Cash On Delivery Available All Over Bangladesh 🇧🇩' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>
                </div>
            </div>

            <!-- 4. Social Links -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans pb-3 border-b border-neutral-200 mb-4">
                    4. SOCIAL MEDIA
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Facebook URL</label>
                        <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? 'https://www.facebook.com/profile.php?id=61559952171891' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Instagram URL</label>
                        <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? 'https://instagram.com/moutoshihandbag' }}"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 focus:outline-none focus:border-black">
                    </div>
                </div>
            </div>

            <!-- 5. Pathao Courier API Settings -->
            <div id="pathao-section" class="bg-neutral-50 border border-neutral-300 p-6 rounded-xs">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-3 border-b border-neutral-200 mb-4 gap-2">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-900 font-sans flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span>
                            5. PATHAO COURIER API CONFIGURATION (পাঠাও ডেলিভারি ইন্টিগ্রেশন)
                        </h3>
                        <p class="text-[11px] text-neutral-500 mt-0.5">
                            Connect your Pathao Merchant API to dispatch orders directly to Pathao Courier with 1-click.
                        </p>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-xs {{ !empty($settings['pathao_client_id']) ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                        {{ !empty($settings['pathao_client_id']) ? '✓ Configured' : '⚠ Not Configured' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">
                            Environment (পরিবেশ) *
                        </label>
                        <select name="pathao_environment" id="pathao_env_select" class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 bg-white focus:outline-none focus:border-black">
                            <option value="sandbox" {{ ($settings['pathao_environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>
                                Sandbox / Testing (courier-api-sandbox.pathao.com)
                            </option>
                            <option value="production" {{ ($settings['pathao_environment'] ?? '') === 'production' ? 'selected' : '' }}>
                                Production / Live (api-hermes.pathao.com)
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">
                            Store ID (পাঠাও স্টোর আইডি)
                        </label>
                        <input type="number" name="pathao_store_id" id="pathao_store_input" value="{{ $settings['pathao_store_id'] ?? '' }}"
                               placeholder="e.g. 12345"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 bg-white focus:outline-none focus:border-black">
                        <span class="text-[10px] text-neutral-400 mt-1 block">Your default pickup store ID from Pathao merchant panel.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">
                            Client ID *
                        </label>
                        <input type="text" name="pathao_client_id" id="pathao_client_id_input" value="{{ $settings['pathao_client_id'] ?? '' }}"
                               placeholder="Your Pathao Developer Client ID"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 bg-white focus:outline-none focus:border-black font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">
                            Client Secret / Key *
                        </label>
                        <input type="password" name="pathao_client_secret" id="pathao_client_secret_input" value="{{ $settings['pathao_client_secret'] ?? '' }}"
                               placeholder="Your Pathao Client Secret"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 bg-white focus:outline-none focus:border-black font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">
                            Pathao Merchant Email / Username *
                        </label>
                        <input type="text" name="pathao_client_email" id="pathao_email_input" value="{{ $settings['pathao_client_email'] ?? '' }}"
                               placeholder="e.g. merchant@moutoshihandbag.shop"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 bg-white focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">
                            Pathao Account Password *
                        </label>
                        <input type="password" name="pathao_client_password" id="pathao_password_input" value="{{ $settings['pathao_client_password'] ?? '' }}"
                               placeholder="Your Pathao account password"
                               class="w-full text-xs px-3.5 py-2.5 border border-neutral-300 bg-white focus:outline-none focus:border-black">
                    </div>
                </div>

                <!-- Test Connection Trigger & Result Box -->
                <div class="mt-5 pt-4 border-t border-neutral-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <button type="button" id="btnTestPathao"
                            class="px-4 py-2 bg-neutral-800 text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition rounded-xs flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Test Connection & Fetch Stores
                    </button>
                    <span id="testPathaoStatus" class="text-xs font-medium text-neutral-600"></span>
                </div>

                <div id="testPathaoResult" class="hidden mt-3 p-3 text-xs rounded-xs border"></div>
            </div>

            <div class="pt-6 border-t border-neutral-200">
                <button type="submit" class="px-8 py-3.5 bg-[#1c1c1c] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition rounded-xs shadow-sm">
                    Save Store Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Script for Pathao Live Test Connection -->
    <script>
        document.getElementById('btnTestPathao')?.addEventListener('click', function() {
            const btn = this;
            const statusText = document.getElementById('testPathaoStatus');
            const resultBox = document.getElementById('testPathaoResult');

            btn.disabled = true;
            btn.innerHTML = 'Testing Connection...';
            statusText.innerText = 'Connecting to Pathao API...';
            resultBox.classList.add('hidden');

            const payload = {
                _token: '{{ csrf_token() }}',
                pathao_environment: document.getElementById('pathao_env_select').value,
                pathao_client_id: document.getElementById('pathao_client_id_input').value,
                pathao_client_secret: document.getElementById('pathao_client_secret_input').value,
                pathao_client_email: document.getElementById('pathao_email_input').value,
                pathao_client_password: document.getElementById('pathao_password_input').value,
            };

            fetch('{{ route("admin.settings.test-pathao") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Test Connection & Fetch Stores';
                resultBox.classList.remove('hidden');

                if (data.success) {
                    statusText.innerText = '✓ Success!';
                    resultBox.className = 'mt-3 p-3 text-xs rounded-xs border bg-green-50 border-green-200 text-green-800';
                    let html = `<p class="font-bold">${data.message}</p>`;
                    if (data.stores && data.stores.length > 0) {
                        html += `<div class="mt-2"><span class="font-bold block">Available Stores:</span><ul class="list-disc list-inside mt-1 space-y-1">`;
                        data.stores.forEach(store => {
                            html += `<li><strong>ID ${store.store_id}</strong>: ${store.store_name} (${store.store_address || 'No address'}) <button type="button" class="ml-2 text-[10px] underline text-blue-700" onclick="document.getElementById('pathao_store_input').value = '${store.store_id}';">Use this Store ID</button></li>`;
                        });
                        html += `</ul></div>`;
                    }
                    resultBox.innerHTML = html;
                } else {
                    statusText.innerText = '✕ Connection Failed';
                    resultBox.className = 'mt-3 p-3 text-xs rounded-xs border bg-red-50 border-red-200 text-red-800';
                    resultBox.innerHTML = `<p class="font-bold">Error:</p><p>${data.message}</p>`;
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = 'Test Connection & Fetch Stores';
                statusText.innerText = '✕ Network Error';
                resultBox.classList.remove('hidden');
                resultBox.className = 'mt-3 p-3 text-xs rounded-xs border bg-red-50 border-red-200 text-red-800';
                resultBox.innerText = 'Network error or invalid server response: ' + err.message;
            });
        });
    </script>

@endsection
