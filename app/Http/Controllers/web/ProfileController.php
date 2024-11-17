<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use App\Models\Description;
use App\Services\MediaService;
use App\Services\ProfileService;
use App\Services\PostService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;


use App\Traits\JsonResponseTrait;
class ProfileController extends Controller
{
    protected $mediaService;
    protected $profileService;
    protected $postService;
    protected $categoryService;
    use JsonResponseTrait;

    public function __construct(MediaService $mediaService, ProfileService $profileService, PostService $postService, CategoryService $categoryService)
    {
        $this->mediaService = $mediaService;
        $this->profileService = $profileService;
        $this->postService = $postService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $status='published';
        $profile_image=null;
        $cover_image=null;
        $descriptions=$this->profileService->getDescriptions();
        $Categories=$this->categoryService->getParentNullCategorieswithoutpagination();
        $post_list=$this->postService->postList($status,1);
        $profile_image=$this->profileService->getProfileImage();
        $cover_image=$this->profileService->getCoverImage();
        $Users=User::all();
        $home=false;
        $name=Auth::user()->name;
        $user_id=Auth::id();
        //return $post_list;
        return view('profile', compact('Categories', 'post_list', 'profile_image', 'cover_image', 'descriptions', 'home', 'name', 'Users', 'status', 'user_id'));
    }

    public function upload_profile_image(Request $request)
    {

        $request['type']='user_profile_image';
        $this->profileService->deleteOldProfileImage();

        $photo_path=$this->mediaService->uploadPhoto($request);


        $user_id=Auth::id();
        $status = 'published';

        // Get posts based on the status
        $post_list=$this->postService->postList($status,1);
        $home=false;
        $html = view('partials.posts', compact('post_list', 'home', 'status','user_id'))->render();


        $data=[
            'html'=>$html,
            'photo_path'=>$photo_path
        ];

        return $this->successResponse($data,'Profile image uploaded successfully');



    }

    public function upload_background_image(Request $request)
    {

        $request['type']='user_cover_image';
        $this->profileService->deleteOldCoverImage();
        $photo_path=$this->mediaService->uploadPhoto($request);

        return $this->successResponse($photo_path,'Cover image uploaded successfully');


    }

    public function addDescription(Request $request)
    {
        Log::info($request->all());
        $description=$this->profileService->addDescription($request);
        return response()->json(
            [
                'success' => true,
                'message' => 'Description added successfully',
                'description' => $description
            ]
        );
    }

    public function updateDescription(Request $request, $id)
    {
        $this->profileService->updateDescription($request, $id);
        return response()->json(
            [
                'success' => true,
                'message' => 'Description updated successfully',
            ]
        );
    }

    public function deleteDescription($id)
    {
        $this->profileService->deleteDescription($id);
        return response()->json(
            [
                'success' => true,
                'message' => 'Description deleted successfully',
            ]
        );
    }

    public function saveDescriptions(Request $request)
    {

        Log::info($request->all());
        try {
            DB::beginTransaction();

            $changes = $request->input('changes');
            $userId = auth()->id();


            foreach ($changes as $change) {
                Log::info($change);
                switch ($change['action']) {
                    case 'add':
                        Description::create([
                            'user_id' => $userId,
                            'text' => $change['text']
                        ]);
                        break;

                    case 'edit':
                        if (!str_starts_with($change['id'], 'temp_')) {
                            Description::where('id', $change['id'])
                                      ->where('user_id', $userId)
                                      ->update(['text' => $change['text']]);
                        }
                        break;

                    case 'delete':
                        if (!str_starts_with($change['id'], 'temp_')) {
                            Description::where('id', $change['id'])
                                      ->where('user_id', $userId)
                                      ->delete();
                        }
                        break;
                }
            }

            DB::commit();

            // Return updated descriptions
            $descriptions = Description::where('user_id', $userId)->get();
            $home=false;
            $html = view('partials.Descriptions', compact('descriptions', 'home'))->render();
            return response()->json([
                'success' => true,
                'message' => 'Descriptions saved successfully',
                'html' => $html

            ]);

        } catch (\Exception $e) {
            Log::info($e->getMessage());

            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving descriptions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeProfileImage()
    {
        $this->profileService->deleteOldProfileImage();
        return $this->successResponse(null,'Profile image removed successfully');
    }

    public function removeCoverImage()
    {
        $this->profileService->deleteOldCoverImage();
        return $this->successResponse(null,'Cover image removed successfully');
    }
    




}
