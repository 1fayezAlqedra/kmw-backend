<?php

namespace App\Services\V1;

use App\Models\Video;

class VideoService
{
    public function createVideo(array $data): Video
    {
        return Video::create([
            'title_en'       => $data['title'],
            'title_ar'       => $data['title'],
            'youtube_url'    => $data['url'],
            'description_en' => $data['description'] ?? null,
            'description_ar' => $data['description'] ?? null,
        ]);
    }

    public function updateVideo(Video $video, array $data): Video
    {
        $video->update([
            'title_en'       => $data['title'],
            'title_ar'       => $data['title'],
            'youtube_url'    => $data['url'],
            'description_en' => $data['description'] ?? null,
            'description_ar' => $data['description'] ?? null,
        ]);

        return $video;
    }
}
