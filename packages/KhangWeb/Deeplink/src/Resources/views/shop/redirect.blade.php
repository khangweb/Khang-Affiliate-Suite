<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('deeplink::app.shop-view.title')
    </x-slot>

    <div class="flex items-center justify-center h-screen px-4 text-center">
        <div class="max-w-xl w-full space-y-6">
            <!-- Product Image -->
            <div class="flex justify-center">
                <img 
                    src="{{ $imageUrl ?? asset('images/default-product.png') }}" 
                    alt="Product" 
                    class="w-48 h-48 object-contain rounded-lg shadow"
                >
            </div>

            <!-- Heading -->
            <h1 class="text-2xl font-semibold">
               @lang('deeplink::app.shop-view.redirecting')
            </h1>

            <!-- Countdown -->
            <p class="text-lg text-zinc-500">
                @lang('deeplink::app.shop-view.waitting') <span id="countdown">5</span> @lang('deeplink::app.shop-view.second')...
            </p>

            <!-- Manual Redirect Link -->
            <a 
                href="{{ $targetUrl }}"
                class="inline-block mt-4 rounded-full bg-navyBlue px-6 py-3 text-white text-sm font-medium shadow hover:bg-blue-800 transition"
            >
                @lang('deeplink::app.shop-view.not-redirect')
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let countdown = 5;
            const countdownEl = document.getElementById('countdown');

            const timer = setInterval(() => {
                countdown--;
                countdownEl.textContent = countdown;

                if (countdown <= 0) {
                    clearInterval(timer);
                    window.location.href = "{{ $targetUrl }}";
                }
            }, 1000);
        });
    </script>
    
</x-shop::layouts>
