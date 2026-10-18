<?php

namespace App\Services\V1;

use App\Models\Video;

class VideoService
{
    public function createVideo(array $data): Video
    {
        return Video::create([
            'title_en'       => $data['title_en'],
            'title_ar'       => $data['title_ar'],
            'youtube_url'    => $data['youtube_url'] ?? $data['video_url'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'description_ar' => $data['description_ar'] ?? null,
        ]);
    }

    public function updateVideo(Video $video, array $data): Video
    {
        $video->update([
            'title_en'       => $data['title_en'] ?? $video->title_en,
            'title_ar'       => $data['title_ar'] ?? $video->title_ar,
            'youtube_url'    => $data['youtube_url'] ?? $data['video_url'] ?? $video->youtube_url,
            'description_en' => $data['description_en'] ?? $video->description_en,
            'description_ar' => $data['description_ar'] ?? $video->description_ar,
        ]);

        return $video->fresh();
    }
}
