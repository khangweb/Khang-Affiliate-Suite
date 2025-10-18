<?php

namespace KhangWeb\ClearCache\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;

class ClearCacheController extends Controller
{
    public function clear()
    {

        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('optimize:clear');

        return redirect()->back()->with('success', '🎉 Cache has been cleared completely!');
    }


}
