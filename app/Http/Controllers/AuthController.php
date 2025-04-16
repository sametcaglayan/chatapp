<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Device;


/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Cihaz doğrulama ve token alma"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth",
     *     summary="Cihaz doğrulaması yapar ve token döner",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"device_uuid", "device_name"},
     *             @OA\Property(property="device_uuid", type="string", example="123e4567-e89b-12d3-a456-426614174000"),
     *             @OA\Property(property="device_name", type="string", example="iPhone 14")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doğrulama başarılı, token döner",
     *         @OA\JsonContent(
     *             @OA\Property(property="subscription", type="string", example="free"),
     *             @OA\Property(property="chat_credit", type="integer", example=10),
     *             @OA\Property(property="token", type="string", example="1|a2B3c4D5e6F7g8H9iJ0K")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Geçersiz parametreler",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_uuid' => 'required|string',
            'device_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $device = Device::where('uuid', $request->input('device_uuid'))->first();
        if (!$device) {
            $device = Device::create([
                'uuid' => $request->input('device_uuid'),
                'name' => $request->input('device_name')
            ]);
        } else {
            $device->name = $request->input('device_name');
            $device->save();
        }

        $token = $device->createToken('Device Token')->plainTextToken;

        return response()->json([
            'subscription' => $device->subscription_status,
            'chat_credit' => $device->chat_credit ?? 10,
            'token' => $token,
        ]);
    }
}
