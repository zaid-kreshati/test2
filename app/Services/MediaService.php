<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\MediaRepository;
use App\Models\User;
use App\Traits\CompressesMedia;
class MediaService
{
    use CompressesMedia;
    protected $MediaRepository;

    public function __construct(MediaRepository $MediaRepository)
    {
        $this->MediaRepository = $MediaRepository;
    }


    public function uploadPhoto(Request $request)
    {


        $userID=auth()->user()->id;
        $photoPath = null;
        if (isset($request['profile_image']) && $request['profile_image']->isValid()) {
            $compressedPhoto = $this->compressImage($request['profile_image']);
            $Path = $request['profile_image']->store('photos', 'public'); // Store in the 'public/photos' directory
            $photoPath = basename($Path); // Get only the file name
         $this->MediaRepository->create([
            'URL'=>$photoPath,
            'type'=>'user_profile_image',
            'mediable_id'=>$userID,
            'mediable_type'=>User::class,
            ]);
        }

        if (isset($request['cover_image']) && $request['cover_image']->isValid()) {
            $compressedPhoto = $this->compressImage($request['cover_image']);
            $Path = $request['cover_image']->store('photos', 'public'); // Store in the 'public/photos' directory
            $photoPath = basename($Path); // Get only the file name

         $this->MediaRepository->create([
            'URL'=>$photoPath,
            'type'=>'user_cover_image',
            'mediable_id'=>$userID,
            'mediable_type'=>User::class,
            ]);
        }

        return $photoPath;

    }

}
