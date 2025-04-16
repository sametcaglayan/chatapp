<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Device;

/**
 * @OA\Tag(
 *     name="Chat",
 *     description="Mesajlaşma işlemleri"
 * )
 */
class ChatController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/chat",
     *     summary="Sohbet mesajı gönderir ve yanıt alır",
     *     tags={"Chat"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"chatId", "message"},
     *             @OA\Property(property="chatId", type="string", example="session_1234"),
     *             @OA\Property(property="message", type="string", example="Hello")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Başarılı mesajlaşma",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="response", type="string", example="I am fine, thank you!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Yetersiz kredi veya chatId uyuşmazlığı",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Insufficient chat credit.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cihaz bulunamadı",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Device not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Doğrulama hatası",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function talk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chatId' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $device = Device::where('uuid', auth()->user()->uuid)->first();
        if (!$device) {
            return response()->json([
                'status' => 'error',
                'message' => 'Device not found.'
            ], 404);
        }

        if ($device->chat_credit <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient chat credit.'
            ], 403);
        }

        $chat_seeder = ChatSeeder::where('user_message', 'like', '%' . $request->input('message') . '%')->first();
        if (!$chat_seeder) {
            $chat_message = 'Sorry, I do not understand your message.';
        }

        $get_chat = Chat::where('chat_id', $request->input('chatId'))->first();
        if ($get_chat && $get_chat->device_id !== auth()->user()->uuid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chat ID does not match the device.'
            ], 403);
        }

        Chat::create([
            'device_id' =>  auth()->user()->uuid,
            'chat_id' => $request->input('chatId'),
            'message' =>  $request->input('message'),
            'response' => $chat_seeder->bot_response ?? $chat_message
        ]);

        Device::where('uuid', auth()->user()->uuid)->decrement('chat_credit');

        return response()->json([
            'status' => 'success',
            'response' => $chat_seeder->bot_response ?? $chat_message
        ]);
    }
}
