<x-admin::layouts>
    <x-slot:title>
        @lang('scraper::app.scraping_templates.index_title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-col">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('scraper::app.scraping_templates.index_title')
        </p>

        <div class="flex items-center gap-x-1">
            
            <a href="{{ route('admin.scraper.scraping-templates.create') }}" class="primary-button">
                @lang('scraper::app.scraping_templates.add-btn')
            </a>
        </div>
    </div>
    <x-admin::datagrid src="{{route('admin.scraper.scraping-templates.index') }}"></x-admin::datagrid>

</x-admin::layouts>

