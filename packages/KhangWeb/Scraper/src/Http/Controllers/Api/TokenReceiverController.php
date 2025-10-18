<?php

namespace KhangWeb\Scraper\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use KhangWeb\Scraper\Models\DomainToken;

class TokenReceiverController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'access_token'     => 'required|string',
            'token_expires_at' => 'required|date',
        ]);

        try {
            // Chỉ lưu 1 token duy nhất, xóa cũ nếu có
            DomainToken::truncate();

            DomainToken::create([
                'access_token'     => $request->input('access_token'),
                'token_expires_at' => $request->input('token_expires_at'),
            ]);

            return response()->json(['success' => true, 'message' => 'Token stored successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to store token.'], 500);
        }
    }
}
