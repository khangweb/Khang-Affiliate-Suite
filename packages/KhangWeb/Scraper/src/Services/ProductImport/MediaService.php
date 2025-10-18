<?php

namespace KhangWeb\Scraper\Services\ProductImport;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Product\Models\ProductVideo;
use Webkul\Product\Models\ProductImage; // Import ProductImage model để có thể tương tác trực tiếp nếu cần, dù product->images() đã đủ

class MediaService
{
    public function __construct()
    {
    }

    /**
     * Tải và gắn hình ảnh vào sản phẩm dựa trên tùy chọn lưu.
     *
     * @param \Webkul\Product\Models\Product $product
     * @param array $imagesData (Mảng các URL hình ảnh)
     * @param string $image_source ('url' hoặc 'download')
     * @return array
     */
    public function processImages($product, array $imagesData, string $image_source)
    {
       
        if (empty($imagesData)) {
            return [];
        }

        $imagesToCreate = [];

        foreach ($imagesData as $imageData) {
            $imageUrl = $imageData ?? null;

            if (!$imageUrl || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                continue;
            }

            try {
                if ($image_source === 'download') {
                    // Tải ảnh về server
                    $response = Http::timeout(30)->get($imageUrl);

                    if (!$response->successful()) {
                        continue;
                    }

                    $filename = uniqid('product_') . '.' . (pathinfo($imageUrl, PATHINFO_EXTENSION) ?: 'jpg');
                    $relativePath = 'product/' . $product->id . '/' . $filename;
                    Storage::disk('public')->put($relativePath, $response->body());

                    $imagesToCreate[] = [
                        'path' => $relativePath,
                    ];
                } else { // $image_source === 'url'
                    // Sử dụng URL trực tiếp
                    $imagesToCreate[] = [
                        'path' => $imageUrl,
                    ];
                }
            } catch (\Exception $e) {
            }
        }

        if (!empty($imagesToCreate)) {
            $product->images()->createMany($imagesToCreate);
        }

        return $imagesToCreate;
    }

    /**
     * Tải và gắn video vào sản phẩm dựa trên tùy chọn lưu.
     *
     * @param \Webkul\Product\Models\Product $product
     * @param string $videoUrl
     * @param string $video_source ('url' hoặc 'download')
     * @return string|null Đường dẫn của video (relative path nếu download, hoặc URL nếu là url)
     */
    public function processVideo($product, $videoUrl, string $video_source)
    {
        if (empty($videoUrl)) {
            return null;
        }

        try {
            if (!filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                return null;
            }

            $videoPath = null;

            if ($video_source === 'download') {
                // Tải video về server
                $response = Http::timeout(30)->get($videoUrl);

                if (!$response->successful()) {
                    return null;
                }

                $extension = pathinfo($videoUrl, PATHINFO_EXTENSION) ?: 'mp4'; // Cố gắng lấy extension hoặc mặc định mp4
                $filename = uniqid('product_video_') . '.' . $extension;
                $relativePath = 'product/' . $product->id . '/videos/' . $filename; // Thêm thư mục 'videos'
                Storage::disk('public')->put($relativePath, $response->body());
                $videoPath = $relativePath;
            } else { // $video_source === 'url'
                // Sử dụng URL trực tiếp
                $videoPath = $videoUrl;
            }

            if ($videoPath) {
                ProductVideo::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        // Có thể thêm 'type' nếu cần phân biệt rõ ràng các loại video
                    ],
                    [
                        'path' => $videoPath,
                        'type' => 'videos', // Hoặc một type phù hợp với dữ liệu của bạn
                    ]
                );
            }
            return $videoPath;
        } catch (\Exception $e) {
            return null;
        }
    }
}