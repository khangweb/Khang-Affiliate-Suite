<x-shop::layouts>

    @push('meta')
        <meta name="description" content="{{ $post->meta_description }}">
        <meta name="keywords" content="{{ $post->meta_keywords }}">
    @endpush

    {{-- Title of the page --}}
    <x-slot:title>
        {{ $post->meta_title ?? $post->title }}
    </x-slot:title>

    <div class="main">
        <div class="container py-8">
   
        
            @if($post->featured_image)
                <img src="{{ $post->featured_image }}" 
                     alt="{{ $post->title }}" 
                     class="img-fluid mb-4">
            @endif
        
            <div class="content">
                {!! $post->content !!}
            </div>
        </div>
    </div>
    
</x-shop::layouts>
