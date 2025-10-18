<?php 

namespace KhangWeb\Scraper\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function verify(Request $request)
    {
        return response()->json([
            'status' => 'valid',
            'message' => 'API token is valid.',
            'extension_version' => '1.0.0',
        ]);
    }
}
