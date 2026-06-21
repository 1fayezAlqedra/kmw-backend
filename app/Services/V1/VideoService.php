<?php

namespace App\Services\V1;

use App\Models\Video;

class VideoService
{
    public function createVideo(array $data): Video
    {
        return Video::create($data);
    }

    public function updateVideo(Video $video, array $data): Video
    {
        $video->update($data);
        return $video;
    }
}
