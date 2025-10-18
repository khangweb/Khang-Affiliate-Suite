@php 
    $is_edit = isset($template);
@endphp

<x-admin::layouts>
    <x-slot:title>
        {{ $is_edit ? __('deeplink::app.edit') : __('deeplink::app.create') }}
    </x-slot:title>

    <x-admin::form
        :action="$is_edit ? route('admin.deeplink.update', $template->id) : route('admin.deeplink.store')"
        method="POST"
    >
        @csrf

        @if ($is_edit)
            @method('PUT')
        @endif

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                {{ $is_edit ? __('deeplink::app.edit') : __('deeplink::app.create') }}
            </h1>

            <div class="flex gap-x-2.5">
                <a href="{{ route('admin.deeplink.index') }}" class="secondary-button">
                    @lang('deeplink::app.back-btn')
                </a>

                <button type="submit" class="primary-button">
                    @lang('deeplink::app.submit')
                </button>
            </div>
        </div>

        <div class="mt-3 flex flex-col gap-4">
            {{-- Name --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    @lang('deeplink::app.name')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="name"
                    :value="old('name', $is_edit ? $template->name : '')"
                    rules="required"
                    placeholder="Accesstrade, MasOffer..."
                />

                <x-admin::form.control-group.error control-name="name" />
            </x-admin::form.control-group>

            {{-- Base URL --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('deeplink::app.base_url')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="base_url"
                    :value="old('base_url', $is_edit ? $template->base_url : '')"
                    rules="url"
                    placeholder="https://go.isclix.com/deep_link/..."
                />

                <x-admin::form.control-group.error control-name="base_url" />
            </x-admin::form.control-group>

            {{-- Query Template --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    @lang('deeplink::app.query_template')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="textarea"
                    name="query_template"
                    :value="old('query_template', $is_edit ? $template->query_template : '')"
                    rules="required"
                    placeholder="?url={product_url}&ref={ref_id}"
                    rows="3"
                />

                <x-admin::form.control-group.error control-name="query_template" />
            </x-admin::form.control-group>

            {{-- Should Encode --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    @lang('deeplink::app.should_encode_url')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    name="should_encode_url"
                    :value="old('should_encode_url', $is_edit ? (int)$template->should_encode_url : 1)"
                    rules="required"
                >
                    <option value="1">@lang('deeplink::app.yes')</option>
                    <option value="0">@lang('deeplink::app.no')</option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error control-name="should_encode_url" />
            </x-admin::form.control-group>

            {{-- Apply Directly To Product URL --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('deeplink::app.apply_directly_to_product_url')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    name="apply_directly_to_product_url"
                    :value="old('apply_directly_to_product_url', $is_edit ? (int)$template->apply_directly_to_product_url : 0)"
                >
                    <option value="1">@lang('deeplink::app.yes')</option>
                    <option value="0">@lang('deeplink::app.no')</option>
                </x-admin::form.control-group.control>
                <small class="text-gray-500">
                    @lang('deeplink::app.example_apply_directly_to_product_url')
                </small>
                <x-admin::form.control-group.error control-name="apply_directly_to_product_url" />
            </x-admin::form.control-group>

            {{-- Accepted Domains --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('deeplink::app.accepted_domains')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="accepted_domains"
                    :value="old('accepted_domains', $is_edit ? implode(',', $template->accepted_domains ?? []) : '')"
                    placeholder="shopee.vn,lazada.vn"
                />

                <x-admin::form.control-group.error control-name="accepted_domains" />
            </x-admin::form.control-group>

            {{-- Instructions --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('deeplink::app.instructions')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="textarea"
                    name="instructions"
                    :value="old('instructions', $is_edit ? $template->instructions : '')"
                    placeholder=""
                    rows="4"
                />

                <x-admin::form.control-group.error control-name="instructions" />
            </x-admin::form.control-group>

            {{-- Status --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    @lang('deeplink::app.status')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    name="status"
                    :value="old('status', $is_edit ? (int)$template->status : 1)"
                    rules="required"
                >
                    <option value="1">@lang('deeplink::app.active')</option>
                    <option value="0">@lang('deeplink::app.inactive')</option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error control-name="status" />
            </x-admin::form.control-group>
        </div>
    </x-admin::form>
</x-admin::layouts>
