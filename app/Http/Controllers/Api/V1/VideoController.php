<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreVideoRequest;
use App\Http\Requests\Api\V1\UpdateVideoRequest;
use App\Services\V1\VideoService;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
    protected $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => Video::latest()->get()], 200);
    }

    /**
     * Display the specified video resource.
     */
    public function show(string $id): JsonResponse
    {
        $video = Video::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $video
        ], 200);
    }

    public function store(StoreVideoRequest $request): JsonResponse
    {
        $video = $this->videoService->createVideo($request->validated());
        return response()->json(['success' => true, 'data' => $video], 201);
    }

    public function update(UpdateVideoRequest $request, string $id): JsonResponse
    {
        $video = Video::findOrFail($id);
        $updated = $this->videoService->updateVideo($video, $request->validated());
        return response()->json(['success' => true, 'data' => $updated], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $video = Video::findOrFail($id);
        $video->delete();
        return response()->json(['success' => true, 'message' => 'Video deleted successfully.'], 200);
    }
}
