<x-admin::layouts>
    <x-slot:title>
        @lang('deeplink::app.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-col">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('deeplink::app.title')
        </p>

        <div class="flex items-center gap-x-1">
            <a href="{{ route('admin.deeplink.create') }}" class="primary-button">
                @lang('deeplink::app.create')
            </a>
        </div>
    </div>
    <x-admin::datagrid src="{{route('admin.deeplink.index') }}"></x-admin::datagrid>

</x-admin::layouts>

