<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
    /**
     * جلب جميع فيديوهات أعمال الشركة (اليوتيوب)
     */
    public function index(): JsonResponse
    {
        $videos = Video::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $videos
        ], 200);
    }
}
