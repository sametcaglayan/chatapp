<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Device;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function device()
    {
        $data = Device::select('uuid', 'name', 'chat_credit')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'ok' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $data
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function chat($device_id = null)
    {

        if ($device_id) {
            $data = Chat::select('chat_id', 'device_id', DB::raw('MAX(created_at) as latest_message_time'))
                ->where('device_id', $device_id)
                ->groupBy('chat_id', 'device_id')
                ->orderByDesc('latest_message_time')
                ->get();
        } else {
            $data = Chat::select('chat_id', 'device_id', DB::raw('MAX(created_at) as latest_message_time'))
                ->groupBy('chat_id', 'device_id')
                ->orderByDesc('latest_message_time')
                ->get();
        }
        return response()->json([
            'ok' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $data
        ]);
    }

    public function getchat($chat_id = null)
    {
        if ($chat_id) {
            $data = Chat::select('device_id', 'chat_id', 'message', 'response', 'created_at')
                ->where('chat_id', $chat_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            return response()->json([
                'ok' => false,
                'message' => 'Chat ID is required.',
            ]);
        }
        foreach ($data as $key => $value) {
            $dateTime = new DateTime($value->created_at);
            $dateTime->setTimezone(new DateTimeZone('Asia/Calcutta'));
            $data[$key]->created_at = $dateTime->format('Y-m-d H:i:s');
        }

        return response()->json([
            'ok' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $data
        ]);
    }
}
