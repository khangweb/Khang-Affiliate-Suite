<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        Import Settings
    </x-slot:title>



    <x-admin::form :action="route('admin.scraper.import.save')">

        <div class="flex justify-between items-center mb-4">

            <h1 class="text-2xl font-bold">Import Settings</h1>

            <div class="flex gap-x-2.5">
                <button type="submit" class="primary-button">
                    @lang('scraper::app.scraped_products.edit.save-btn')
                </button>
            </div>
        </div>


        <div class="row">
            {{-- LEFT COLUMN --}}
            <div class="col-lg-6">
                {{-- GENERAL SETTINGS --}}
                <div class="mb-5">
                    <h3 class="mb-3"><strong>General Settings</strong></h3>

                    {{-- Channel --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Channel
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control type="select" name="channel_code" rules="required"
                            :value="old('channel_code', $setting->channel_code ?? '')" :label="trans('Channel')">
                            @foreach($channels as $channel)
                            <option value="{{ $channel->code }}">{{ $channel->name }}</option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="channel_code" />
                    </x-admin::form.control-group>

                    {{-- Locale --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Locale
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control type="select" name="locale_code" rules="required"
                            :value="old('locale_code', $setting->locale_code ?? '')" :label="trans('Locale')">
                            @foreach(core()->getAllLocales() as $locale)
                            <option value="{{ $locale->code }}">{{ $locale->name }}</option>
                            @endforeach
                           
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="locale_code" />
                    </x-admin::form.control-group>

                    {{-- Currency --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Currency
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control type="select" name="currency_code" rules="required"
                            :value="old('currency_code', $setting->currency_code ?? core()->getCurrentCurrencyCode())"
                            :label="trans('Currency')">
                            @foreach(core()->getAllCurrencies() as $currency)
                            <option value="{{ $currency->code }}">
                                {{ $currency->name }} ({{ $currency->code }})
                            </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="currency_code" />
                    </x-admin::form.control-group>

                    {{-- Default Category --}}

                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <!-- Parent category -->
                        <label class="mb-2.5 block text-xs font-medium leading-6 text-gray-800 dark:text-white">
                            @lang('admin::app.catalog.categories.create.parent-category')
                        </label>

                        <!-- Radio select button -->
                        <div class="flex flex-col gap-3">
                            <x-admin::tree.view
                                input-type="radio"
                                id-field="id"
                                name-field="default_category_ids[]"
                                value-field="id"
                                :value="$setting->default_category_ids[0] ?? 1"  
                                :items="json_encode($categories)"
                                :fallback-locale="config('app.fallback_locale')"
                            />
                        </div>
                </div>

                </div>

                {{-- MEDIA SETTINGS --}}
                <div class="mb-5">
                    <h3 class="mb-3"><strong>Media Import Settings</strong></h3>
                    <p class="form-text">Choose how images and videos should be handled during import.</p>

                    {{-- Image Source --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Image Source
                        </x-admin::form.control-group.label>

                        <div class="form-group d-flex gap-20">
                            @foreach(['url' => 'Use URL', 'download' => 'Download'] as $value => $label)
                            <div class="form-check d-inline-flex align-items-center">
                                <input type="radio" name="image_source" value="{{ $value }}"
                                    id="image_source_{{ $value }}" class="form-check-input"
                                    {{ (old('image_source', $setting->image_source ?? '') === $value) ? 'checked' : '' }}>
                                <label class="form-check-label ms-1"
                                    for="image_source_{{ $value }}">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>

                        <x-admin::form.control-group.error control-name="image_source" />
                    </x-admin::form.control-group>

                    {{-- Video Source --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Video Source
                        </x-admin::form.control-group.label>

                        <div class="form-group d-flex gap-20">
                            @foreach(['url' => 'Use URL', 'download' => 'Download'] as $value => $label)
                            <div class="form-check d-inline-flex align-items-center">
                                <input type="radio" name="video_source" value="{{ $value }}"
                                    id="video_source_{{ $value }}" class="form-check-input"
                                    {{ (old('video_source', $setting->video_source ?? '') === $value) ? 'checked' : '' }}>
                                <label class="form-check-label ms-1"
                                    for="video_source_{{ $value }}">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>

                        <x-admin::form.control-group.error control-name="video_source" />
                    </x-admin::form.control-group>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-6">
                <div class="mb-5">
                    <h3 class="mb-3"><strong>Meta Tag Templates</strong></h3>
                    <p class="form-text">
                        Use placeholders like <code>{product_name}</code>, <code>{short_description}</code>, etc. to
                        auto-generate SEO-friendly meta content.
                    </p>

                    {{-- Meta Title --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Meta Title Template
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control type="text" name="meta_title_template"
                            :value="old('meta_title_template', $setting->meta_title_template ?? '')" />

                        <x-admin::form.control-group.error control-name="meta_title_template" />
                    </x-admin::form.control-group>

                    {{-- Meta Description --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Meta Description Template
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control type="textarea" name="meta_description_template"
                            :value="old('meta_description_template', $setting->meta_description_template ?? '')" />

                        <x-admin::form.control-group.error control-name="meta_description_template" />
                    </x-admin::form.control-group>

                    {{-- Meta Keywords --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Meta Keywords Template
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control type="textarea" name="meta_keywords_template"
                            :value="old('meta_keywords_template', $setting->meta_keywords_template ?? '')" />

                        <x-admin::form.control-group.error control-name="meta_keywords_template" />
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>

    </x-admin::form>
</x-admin::layouts>