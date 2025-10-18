<x-admin::layouts>
    <x-slot:title>
        @lang('scraper::app.scraped_products.edit.title')
    </x-slot:title>

    <x-admin::form :action="isset($scrapedProduct) ? route('scraper.admin.scraped_products.update', $scrapedProduct->id) : route('scraper.admin.scraped_products.store')" method="POST">
        @csrf

        @if (isset($scrapedProduct))
            @method('PUT')
        @endif

        <div class="flex justify-between items-center mb-4">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('scraper::app.scraped_products.edit.title')
            </p>

            <div class="flex gap-x-2.5">
                <a href="{{ route('scraper.admin.scraped_products.index') }}" class="secondary-button">
                    @lang('scraper::app.scraped_products.edit.back-btn')
                </a>

                <button type="submit" class="primary-button">
                    @lang('scraper::app.scraped_products.edit.save-btn')
                </button>
            </div>
        </div>

        <div class="mt-3 flex gap-4 max-xl:flex-wrap">
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    {{-- Name --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('scraper::app.scraped_products.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            :value="old('name', $scrapedProduct->name ?? '')"
                            rules="required"
                            :label="trans('scraper::app.scraped_products.name')"
                            placeholder="@lang('scraper::app.scraped_products.name-placeholder')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    {{-- URL --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('scraper::app.scraped_products.url')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="url"
                            :value="old('url', $scrapedProduct->url ?? '')"
                            rules="required|url"
                            :label="trans('scraper::app.scraped_products.url')"
                            placeholder="https://example.com/product-url"
                        />

                        <x-admin::form.control-group.error control-name="url" />
                    </x-admin::form.control-group>

                    {{-- Status --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('scraper::app.scraped_products.status')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="status"
                            :value="old('status', $scrapedProduct->status ?? '')"
                            rules="required"
                            :label="trans('scraper::app.scraped_products.status')"
                        >
                            <option value="pending" {{ old('status', $scrapedProduct->status ?? '') == 'pending' ? 'selected' : '' }}>
                                @lang('scraper::app.scraped_products.status-pending')
                            </option>
                            <option value="imported" {{ old('status', $scrapedProduct->status ?? '') == 'imported' ? 'selected' : '' }}>
                                @lang('scraper::app.scraped_products.status-imported')
                            </option>
                            <option value="failed" {{ old('status', $scrapedProduct->status ?? '') == 'failed' ? 'selected' : '' }}>
                                @lang('scraper::app.scraped_products.status-failed')
                            </option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="status" />
                    </x-admin::form.control-group>

                    {{-- Scraping Template --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('scraper::app.scraped_products.scraping_templates_id')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="scraping_templates_id"
                            :value="old('scraping_templates_id', $scrapedProduct->scraping_templates_id ?? '')"
                            :label="trans('scraper::app.scraped_products.scraping_templates_id')"
                        >
                            <option value="">@lang('scraper::app.scraped_products.select-template')</option>
                            @foreach($scrapingTemplates as $template)
                                <option value="{{ $template->id }}"
                                    {{ old('scraping_templates_id', $scrapedProduct->scraping_templates_id ?? '') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="scraping_templates_id" />
                    </x-admin::form.control-group>

                    {{-- IP --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('scraper::app.scraped_products.ip')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="ip"
                            :value="old('ip', $scrapedProduct->ip ?? '')"
                            :label="trans('scraper::app.scraped_products.ip')"
                            placeholder="192.168.1.1"
                        />

                        <x-admin::form.control-group.error control-name="ip" />
                    </x-admin::form.control-group>

                    {{-- Error Message --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('scraper::app.scraped_products.error_message')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="error_message"
                            :value="old('error_message', $scrapedProduct->error_message ?? '')"
                            :label="trans('scraper::app.scraped_products.error_message')"
                            rows="3"
                        />

                        <x-admin::form.control-group.error control-name="error_message" />
                    </x-admin::form.control-group>

                    {{-- Raw Data --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('scraper::app.scraped_products.raw_data')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="raw_data"
                            :value="old('raw_data', isset($scrapedProduct->raw_data) ? json_encode($scrapedProduct->raw_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '')"
                            :label="trans('scraper::app.scraped_products.raw_data')"
                            rows="10"
                            rules="required"
                        />

                        <x-admin::form.control-group.error control-name="raw_data" />
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
