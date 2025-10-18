
<x-admin::layouts>
    <x-slot:title>
        {{ __('deeplink::app.edit_aff_link') }}
    </x-slot:title>

    <x-admin::form
        :action="route('admin.product-url.update', $productUrl->id)"
        method="POST"
    >
        @csrf
        @method('PUT')

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                {{ __('deeplink::app.edit_aff_link') }}
            </h1>

            <div class="flex gap-x-2.5">
                <a href="{{ route('admin.product-url.index') }}" class="secondary-button">
                    @lang('deeplink::app.back-btn')
                </a>

                <button type="submit" class="primary-button">
                    @lang('deeplink::app.submit')
                </button>
            </div>
        </div>

        <div class="mt-3 flex flex-col gap-4">
            {{-- Product ID --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('deeplink::app.product_id')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="product_id"
                    :value="$productUrl->product_id"
                    disabled
                />
            </x-admin::form.control-group>

            {{-- Link Source --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('deeplink::app.link_source')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="link_source"
                    :value="$productUrl->link_source"
                    
                />
            </x-admin::form.control-group>

            {{-- Link Aff --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('deeplink::app.link_aff')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="link_aff"
                    :value="old('link_aff', $productUrl->link_aff)"
                    rules="url"
                    placeholder="https://example.com/deeplink?..."
                />

                <x-admin::form.control-group.error control-name="link_aff" />
            </x-admin::form.control-group>
        </div>
    </x-admin::form>
</x-admin::layouts>
