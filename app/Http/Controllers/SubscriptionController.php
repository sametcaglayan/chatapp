<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscription;

/**
 * @OA\Tag(
 *     name="Subscription",
 *     description="Abonelik Kontrol"
 * )
 */
class SubscriptionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/subscription",
     *     summary="Aboneliği satın alımını kontrol eder",
     *     tags={"Subscription"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"productId", "receiptToken"},
     *             @OA\Property(property="productId", type="string", example="product_1234"),
     *             @OA\Property(property="receiptToken", type="string", example="uuid-token-5678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Abonelik durumu",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="positive")
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|string',
            'receiptToken' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $subscription = Subscription::where('receipt_token', $request->input('receiptToken'))->first();

        return response()->json([
            'status' => $subscription ? 'positive' : 'negative'
        ]);
    }
}
