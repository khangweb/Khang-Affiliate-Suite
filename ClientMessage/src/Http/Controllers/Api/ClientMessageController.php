<?php 
namespace KhangWeb\ClientMessage\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use KhangWeb\ClientMessage\Models\ClientMessage;

class ClientMessageController extends Controller
{
    /**
     * Lấy danh sách client messages (tùy chọn lọc theo email hoặc status)
     */
    public function index(Request $request)
    {
        // Lọc theo email hoặc status nếu có
        $data = ClientMessage::all();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Xem chi tiết 1 message
     */
    public function show(Request $request, int $id)
    {
        $token = $request->header('X-Client-Token');// dùng header: Authorization: Bearer {token}
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token is required.'
            ], 400);
        }

        $message = ClientMessage::where('id', $id)->where('token', $token)->first();

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid ID or token.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    /**
     * Cập nhật message: ví dụ trả lời lại hoặc đánh dấu đã xử lý
     */
    public function update(Request $request, $id)
    {
        
           $token = $request->header('X-Client-Token');// dùng header: Authorization: Bearer {token}

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is required in Authorization header.'
                ], 400);
            }

            $message = ClientMessage::where('id', $id)
                ->where('token', $token)
                ->first();

            if (!$message) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ID or token.'
                ], 404);
            }

            $data = $request->only(['status']);

            if (isset($data['status'])) {
                $message->status = $data['status'];
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing or invalid `status` field.'
                ], 422);
            }

            $message->save();
            return response()->json([
                'success' => true,
                'message' => 'Message updated successfully.',
            ]);
        
    }
}
