
<x-admin::layouts>
    <x-slot:title>
        {{ isset($template) ? __('scraper::app.scraping_templates.edit.title') : __('scraper::app.scraping_templates.create.title') }}
    </x-slot:title>

    <x-admin::form 
        :action="isset($template) 
            ? route('admin.scraper.scraping-templates.update', $template->id) 
            : route('admin.scraper.scraping-templates.store')" 
        method="POST"
    >
        @csrf
        @if (isset($template))
            @method('PUT')
        @endif

        <div class="flex justify-between items-center mb-4">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                {{ isset($template) 
                    ? __('scraper::app.scraping_templates.edit.title') 
                    : __('scraper::app.scraping_templates.create.title') }}
            </p>

            <div class="flex gap-x-2.5">
                <a href="{{ route('admin.scraper.scraping-templates.index') }}" class="secondary-button">
                    @lang('scraper::app.scraping_templates.back-btn')
                </a>

                <button type="submit" class="primary-button">
                    {{ isset($template) 
                        ? __('scraper::app.scraping_templates.update-btn') 
                        : __('scraper::app.scraping_templates.save-btn') }}
                </button>
            </div>
        </div>

        <div class="mt-3 flex gap-4 max-xl:flex-wrap">
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    {{-- Template Name --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('scraper::app.scraping_templates.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            :value="old('name', $template->name ?? '')"
                            rules="required"
                            :label="trans('scraper::app.scraping_templates.name')"
                            placeholder="@lang('scraper::app.scraping_templates.name-placeholder')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    {{-- Fields JSON --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('scraper::app.scraping_templates.fields')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="fields"
                            rows="10"
                            rules="required"
                            :value="old('fields', isset($template->fields) ? json_encode($template->fields, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '')"
                            :label="trans('scraper::app.scraping_templates.fields')"
                        >

                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="fields" />
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
