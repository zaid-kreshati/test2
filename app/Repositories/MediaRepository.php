<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Support\Facades\Log;

class MediaRepository
{
    public function create(array $data)
    {
        Log::info($data);
        $media = new Media();
        $media->URL = $data['URL']; // Save the photo path
        $media->mediable_type = $data['mediable_type']; // Set the polymorphic type
        $media->mediable_id = $data['mediable_id']; // Associate with the post ID
        $media->type = $data['type']; // Set the type
        $media->save(); // Save the photo instance
    }
}
