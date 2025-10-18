
<x-admin::layouts>
    <x-slot:title>
        @lang('scraper::app.scraped_products.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-col">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('scraper::app.scraped_products.title')
        </p>

        <div class="flex items-center gap-x-1">
           <a href="{{ route('scraper.admin.scraped_products.create') }}" class="primary-button">
                @lang('scraper::app.scraped_products.add-btn')
            </a>
        </div>
    </div>
    <x-admin::datagrid src="{{ route('scraper.admin.scraped_products.index') }}"></x-admin::datagrid>

</x-admin::layouts>
