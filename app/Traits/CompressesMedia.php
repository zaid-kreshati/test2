<?php

namespace App\Traits;

use Intervention\Image\Facades\Image as Image;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\Dimension;
use Illuminate\Http\UploadedFile;

trait CompressesMedia
{
    protected function compressImage(UploadedFile $file, $quality = 60)
    {
        try {
            // Create Image instance
            $image = Image::make($file);

            // Resize if width is greater than 1920px
            if ($image->width() > 1920) {
                $image->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Create temporary file
            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Save compressed image
            $image->save($tempPath, $quality);

            // Create new UploadedFile instance
            return new UploadedFile(
                $tempPath,
                $file->getClientOriginalName(),
                $file->getClientMimeType(),
                null,
                true
            );

        } catch (\Exception $e) {
            \Log::error('Image compression failed: ' . $e->getMessage());
            return $file; // Return original file if compression fails
        }
    }

    protected function compressVideo(UploadedFile $file)
    {
        try {
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg', // Update path as needed
                'ffprobe.binaries' => '/usr/bin/ffprobe', // Update path as needed
                'timeout'          => 3600,
                'ffmpeg.threads'   => 12,
            ]);

            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.mp4';

            $video = $ffmpeg->open($file->getPathname());

            $video->filters()
                ->resize(new Dimension(1280, 720))
                ->synchronize();

            $video->save('h264', $tempPath);

            return new UploadedFile(
                $tempPath,
                $file->getClientOriginalName(),
                $file->getClientMimeType(),
                null,
                true
            );

        } catch (\Exception $e) {
            \Log::error('Video compression failed: ' . $e->getMessage());
            return $file; // Return original file if compression fails
        }
    }
}
