<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    /**
     * Public: جلب جميع الفيديوهات للزوار
     */
    public function index(): JsonResponse
    {
        $videos = Video::latest()->get();
        return response()->json(['success' => true, 'data' => $videos], 200);
    }

    /**
     * Admin CMS: إضافة فيديو جديد
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'url'      => 'required|url', // للتأكد من أنه رابط يوتيوب صحيح
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $video = Video::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Video added successfully.',
            'data'    => $video
        ], 201);
    }

    /**
     * Admin CMS: تعديل فيديو حالي
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $video = Video::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'url'      => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $video->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Video updated successfully.',
            'data'    => $video
        ], 200);
    }

    /**
     * Admin CMS: حذف فيديو
     */
    public function destroy(string $id): JsonResponse
    {
        $video = Video::findOrFail($id);
        $video->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video deleted successfully.'
        ], 200);
    }
}
