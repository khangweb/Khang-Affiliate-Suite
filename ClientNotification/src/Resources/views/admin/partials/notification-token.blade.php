@php
    $apiResult = session('api_result');
    $checkedEmail = session('checked_email');
    $hasCustomerId = !empty($domain_tokens?->customer_id) || ($apiResult['exists'] ?? false);
@endphp

<div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
        @lang('client_notification::app.token.title')
    </h3>

    {{-- Thông báo lỗi --}}
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
            {{ implode(', ', $errors->all()) }}
        </div>
    @endif

    @if($domain_tokens)

        <div class="space-y-4">
            <div class="mb-4">
                <label for="access_token" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    @lang('client_notification::app.token.access-token')
                </label>
                <div class="flex items-center gap-2">
                     <textarea id="access_token" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" readonly rows="2">{{ $domain_tokens->access_token }}</textarea>
                    <button type="button" onclick="copyAccessToken()" 
                            class="px-3 py-2 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        @lang('client_notification::app.token.copy')
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    @lang('client_notification::app.token.expire-at')
                </label>
                <p class="text-sm text-gray-800 dark:text-gray-200">
                    {{ $domain_tokens->token_expires_at->format('d-m-Y H:i:s') }}
                </p>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-md text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                <p>@lang('client_notification::app.token.content.text_1')</p>
</br>
                <p>@lang('client_notification::app.token.content.text_2')</p>
            </div>
        </div>

    @else

        {{-- Nếu đã có customer_id thì bỏ qua check-email --}}
        @if ($hasCustomerId)
            <form action="{{ route('admin.client-notification.get-token') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="customer_id"
                    value="{{ $domain_tokens->customer_id ?? $apiResult['customer_id'] }}">
                
                <div  class='mb-4'>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('client_notification::app.token.email')
                    </label>
                    <input type="email" id="email" name="email"
                        value="{{ $checkedEmail ?? ($apiResult['email'] ?? '') }}"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                        readonly>
                </div>

                <div class='mb-4'>
                    <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('client_notification::app.token.domain')
                    </label>
                    <input type="text" id="domain" name="domain" value="{{ request()->getHost() }}"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                        readonly>
                </div>

                <button type="submit" class="primary-button w-full">
                    @lang('client_notification::app.token.get-token')
                </button>
            </form>
        @else
            {{-- Nếu chưa có customer_id thì hiển thị form check-email --}}
            <form action="{{ route('admin.client-notification.check') }}" method="POST" class="space-y-4">
                @csrf
                <div class='mb-4'>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('client_notification::app.token.email')
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                        placeholder="@lang('client_notification::app.token.email-placeholder')" required>
                </div>

                <button type="submit" class="primary-button w-full">
                    @lang('client_notification::app.token.check-email')
                </button>
            </form>
        @endif

    @endif

</div>
