<!-- resources/themes/default/views/notifications/index.blade.php -->
<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('clientmessage::app.contact.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-col">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('clientmessage::app.contact.title')
        </p>

    </div>

    {{-- Main content area with two columns, now always 1/2 - 1/2 split by default --}}
    <div class="mt-8 grid grid-cols-2 gap-6">
        {{-- Column 1: Notifications (Takes 1/2 width by default) --}}
   
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                 @lang('clientmessage::app.contact.message')
            </h3>

                    {{-- Updated form action and added CSRF token --}}
            <form action="{{ route('admin.client-messages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf {{-- CSRF token for Laravel forms --}}

                {{-- Display success/error messages --}}
                @if (session()->has('success'))
                    <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('clientmessage::app.contact.contact-person')
                    </label>
                    <input type="text" id="contact_person" name="contact_person"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="@lang('clientmessage::app.contact.contact-person-placeholder')"
                            value="{{ old('contact_person') }}" {{-- Retain old input --}}
                            required>
                    @error('contact_person') {{-- Display validation error --}}
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('clientmessage::app.contact.email')
                    </label>
                    <input type="email" id="email" name="email"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="@lang('clientmessage::app.contact.email-placeholder')"
                            value="{{ old('email') }}" {{-- Retain old input --}}
                            required>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('clientmessage::app.contact.gender')
                    </label>
                    <select id="gender" name="gender"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="">@lang('clientmessage::app.contact.select-gender')</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>@lang('clientmessage::app.contact.gender-male')</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>@lang('clientmessage::app.contact.gender-female')</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>@lang('clientmessage::app.contact.gender-other')</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4"> {{-- New field for Subject --}}
                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('clientmessage::app.contact.subject')
                    </label>
                    <input type="text" id="subject" name="subject"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="@lang('clientmessage::app.contact.subject-placeholder')"
                            value="{{ old('subject') }}" {{-- Retain old input --}}
                            required>
                    @error('subject')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4"> {{-- Field for image uploads --}}
                    <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('clientmessage::app.contact.upload-images')
                    </label>
                    <input type="file" id="images" name="images[]" multiple
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            accept="image/*"
                            placeholder="@lang('clientmessage::app.contact.upload-images-placeholder')">
                    
                    @error('images.*') {{-- Error for individual image files --}}
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>


                
                <div class="mb-4"> {{-- Field for video uploads --}}
                    <label for="videos" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('clientmessage::app.contact.upload-videos')
                    </label>
                    <input type="file" id="videos" name="videos[]" multiple
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            accept="video/*"
                            placeholder="@lang('clientmessage::app.contact.upload-videos-placeholder')">
                    @error('videos.*') {{-- Error for individual video files --}}
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('clientmessage::app.contact.message')
                    </label>
                    <textarea id="message" name="message" rows="4"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="@lang('clientmessage::app.contact.message-placeholder')"
                                required>{{ old('message') }}</textarea> {{-- Retain old input --}}
                    @error('message')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="primary-button w-full">
                    @lang('clientmessage::app.contact.submit')
                </button>
            </form>
    
        </div>

        {{-- Column 2: Contact Form (Takes 1/2 width by default) --}}
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                   <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                    @lang('clientmessage::app.contact.list')
                </h3>

                {!! view_render_event('khangweb.admin.client_messages.list.before') !!}

                <x-admin::datagrid src="{{ route('admin.client_messages.index') }}"></x-admin::datagrid>

                {!! view_render_event('khangweb.admin.client_messages.list.after') !!}
        </div>
    </div>
</x-admin::layouts>
