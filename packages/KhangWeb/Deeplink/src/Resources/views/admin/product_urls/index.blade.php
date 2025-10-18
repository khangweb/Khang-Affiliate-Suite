
<x-admin::layouts>
    <x-slot:title>
        @lang('deeplink::app.product-url.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-col">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('deeplink::app.product-url.title')
        </p>

    </div>
    <x-admin::datagrid src="{{route('admin.product-url.index') }}"></x-admin::datagrid>

</x-admin::layouts>


