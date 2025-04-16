<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Buy;
use App\Models\Subscription;
use App\Models\Device;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Purchase",
 *     description="Abonelik satın alma işlemleri"
 * )
 */
class BuyController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/buy",
     *     summary="Yeni bir satın alma işlemi oluşturur",
     *     tags={"Purchase"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"productId"},
     *             @OA\Property(property="productId", type="string", example="premium")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Satın alma başarılı",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Purchase created successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="device_uuid", type="string", example="123e4567-e89b-12d3-a456-426614174000"),
     *                 @OA\Property(property="productId", type="string", example="premium"),
     *                 @OA\Property(property="receiptToken", type="string", example="a4bc23c5-f634-4b8e-9227-c8cd4b13e611")
     *             )
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
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|string|exists:products,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $uuid4 = \Ramsey\Uuid\Guid\Guid::uuid4();
        $receiptToken = $uuid4->toString();

        Buy::create([
            'device_id' => auth()->user()->uuid,
            'productId' => $request->input('productId'),
            'receiptToken' =>  $receiptToken
        ]);

        $subscription = Subscription::where('device_id', auth()->user()->uuid)->first();
        if ($subscription) {
            $subscription->product_id = $request->input('productId');
            $subscription->receipt_token =  $receiptToken;
            $subscription->save();
        } else {
            Subscription::create([
                'device_id' => auth()->user()->uuid,
                'product_id' => $request->input('productId'),
                'receipt_token' => $receiptToken
            ]);
        }

        $product = Product::where('name', $request->input('productId'))->first();
        $device = Device::where('uuid', auth()->user()->uuid)->first();

        if ($device) {
            $device->chat_credit += $product->chat_credit;
            $device->save();
        } else {
            $device = Device::firstOrCreate(
                ['uuid' => auth()->user()->uuid],
                ['name' => $request->input('productId')],
                ['chat_credit' => $product->chat_credit]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Purchase created successfully.',
            'data' => [
                'device_uuid' => auth()->user()->uuid,
                'productId' => $request->input('productId'),
                'receiptToken' => $receiptToken
            ]
        ]);
    }
}
