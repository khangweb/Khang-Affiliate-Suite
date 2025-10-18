<?php

namespace Webkul\Product;

use Illuminate\Support\Facades\Storage;

class ProductVideo
{
    /**
     * Retrieve collection of videos
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getVideos($product)
    {
        if (! $product) {
            return [];
        }

        $videos = [];

        foreach ($product->videos as $video) {
            // if (! Storage::has($video->path)) {
            //     continue;
            // }
        $src = '' ;
        if (preg_match('/^https?:\/\//', $video->path)) {
            $src = $video->path;
        }else{
            $src = Storage::url($video->path);
        }

            $videos[] = [
                'type'      => $video->type,
                'video_url' => $src,
            ];
        }

        return $videos;
    }
}
