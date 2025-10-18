<?php

namespace KhangWeb\SharedPost\Http\Controllers\Shop;

use KhangWeb\SharedPost\Models\SharedPost;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SharedPostController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('sharedpost::shop.index');
    }
    
      public function show(string $slug)
    {
        $post = SharedPost::where('slug', $slug)->firstOrFail();
 
        // Render ra view trong theme hoặc gói package SharedPost
        return view('sharedpost::shop.show', compact('post'));
    }
    
}
