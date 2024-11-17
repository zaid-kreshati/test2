<?php

namespace App\Repositories;

use App\Models\Description;
use Illuminate\Support\Facades\Auth;
use App\Models\Media;
use App\Models\User;

class ProfileRepository
{
    public function getDescriptions()
    {
        $userID=Auth::id();
        return Description::where('user_id', $userID)->get();
    }

    public function getProfileImage()
    {
        $userID=Auth::id();
        $profile_image=Media::where('mediable_id', $userID)->where('type','user_profile_image')->first();
        return $profile_image;
    }

    public function getCoverImage()
    {
        $userID=Auth::id();
        $cover_image=Media::where('mediable_id', $userID)->where('mediable_type',User::class)->where('type','user_cover_image')->first();
        return $cover_image;
    }

    public function deleteOldCoverImage()
    {
        $userID=Auth::id();
        $cover_image=Media::where('mediable_id', $userID)->where('type','user_cover_image')->first();
        if($cover_image){
            $cover_image->delete();
        }
    }

    public function deleteOldProfileImage()
    {
        $userID=Auth::id();
        $profile_image=Media::where('mediable_id', $userID)->where('type','user_profile_image')->first();
        if($profile_image){
            $profile_image->delete();
        }
    }

    public function addDescription($request)
    {
        $userID=Auth::id();
        return Description::create([
            'user_id' => $userID,
            'text' => $request->text
        ]);
    }

    public function updateDescription($request, $id)
    {
        return Description::find($id)->update($request->all());
    }

    public function deleteDescription($id)
    {
        return Description::find($id)->delete();
    }

    public function saveDescriptions($request)
    {
        return Description::where('user_id', Auth::id())->update(['text' => $request->text]);
    }

}
