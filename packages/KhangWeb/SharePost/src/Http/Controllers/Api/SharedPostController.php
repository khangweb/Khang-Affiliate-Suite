<?php

namespace KhangWeb\SharedPost\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use KhangWeb\SharedPost\Models\SharedPost;

class SharedPostController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Host gửi nội dung bài viết cập nhật sang client
     */
    public function updateFromHost(Request $request)
    {
        $request->validate([
            'slug' => 'required|string',
            'title' => 'required|string',
            'content' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'featured_image' => 'nullable|string', // host gửi link ảnh
        ]);
        
        try {
            $post = SharedPost::updateOrCreate(
                ['slug' => $request->slug],
                [
                    'title'             => $request->title,
                    'content'           => $request->content,
                    'meta_title'        => $request->meta_title,
                    'meta_description'  => $request->meta_description,
                    'meta_keywords'     => $request->meta_keywords,
                    'featured_image'    => $request->featured_image,
                ]
            );
    
            return response()->json([
                'success' => true,
                'post_id' => $post->id,
            ]);
        } catch (\Throwable $e) {
  
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
        
    }

    /**
     * Host lấy bài viết hiện tại từ client (để load vào form edit)
     */
    public function getPost($slug)
    {
        $post = SharedPost::where('slug', $slug)->first();

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }
}
