<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Traits\CompressesMedia;
use Intervention\Image\Facades\Image;
use App\Models\Post;

class PostService
{
    protected $postRepository;
    protected $mediaRepository;
    use CompressesMedia;
    public function __construct(PostRepository $postRepository,MediaRepository $mediaRepository)
    {
        $this->postRepository = $postRepository;
        $this->mediaRepository = $mediaRepository;
    }

    public function getUserPosts()
    {
        return $this->postRepository->getUserPosts();
    }

    public function createPost($data)
    {

        //Log::info($data['user_ids']);
        $post=$this->postRepository->createPost($data->only('description','category_id','status', 'user_ids'));

        // Handle Photos
        if ($data->hasFile('photos')) {
            foreach ($data['photos'] as $photo) {
                // Create unique filename
                $compressedPhoto = $this->compressImage($photo);
                $filename = time() . '_' . $photo->getClientOriginalName();
                Storage::putFileAs('public/photos', $compressedPhoto, $filename);

                $mediaData=[
                    'URL'=>$filename,
                    'mediable_type'=>Post::class,
                    'mediable_id'=>$post->id,
                    'type'=>'post_image'
                ];
                $this->mediaRepository->create($mediaData);

            }
        }

        // Handle Videos (store as is)
        if ($data->hasFile('videos')) {
            foreach ($data['videos'] as $video) {

                $compressedVideo = $this->compressVideo($video);
                $filename = time() . '_' . $video->getClientOriginalName();
                Storage::putFileAs('public/videos', $compressedVideo, $filename);

                $mediaData=[
                    'URL'=>$filename,
                    'mediable_type'=>Post::class,
                    'mediable_id'=>$post->id,
                    'type'=>'post_video'
                ];
                $this->mediaRepository->create($mediaData);

            }
        }

        // Create post with paths
        return $post;
    }


    public function getUserName()
    {
        return $this->postRepository->getUserName();
    }


    public function updatePost($id,  $data)
    {

        $post=$this->postRepository->update($id,$data->only('description','category_id','status'));
            if ($data->hasFile('photos')) {
                foreach ($data['photos'] as $photo) {

                    $compressedPhoto = $this->compressImage($photo);
                    $filename = time() . '_' . $photo->getClientOriginalName();
                    Storage::putFileAs('public/photos', $compressedPhoto, $filename);

                    $mediaData=[
                        'URL'=>$filename,
                        'mediable_type'=>Post::class,
                        'mediable_id'=>$post->id,
                        'type'=>'post_image'
                    ];
                    $this->mediaRepository->create($mediaData);

                }
        }

        if ($data->hasFile('videos')) {
            foreach ($data['videos'] as $video) {

                $compressedVideo = $this->compressVideo($video);
                $filename = time() . '_' . $video->getClientOriginalName();
                Storage::putFileAs('public/videos', $compressedVideo, $filename);

                $mediaData=[
                    'URL'=>$filename,
                    'mediable_type'=>Post::class,
                    'mediable_id'=>$post->id,
                    'type'=>'post_video'
                ];
                $this->mediaRepository->create($mediaData);

            }
        }
        return $post;
    }

    public function deletePost($id)
    {
        return $this->postRepository->destroy($id);
    }


    public function filterPosts($status)
    {
        return $this->postRepository->filterPosts($status);
    }


    public function getPersonalPhoto()
    {
        return $this->postRepository->getPersonalPhoto();
    }

    public function deleteMedia($id)
    {
        $data=$this->postRepository->deleteMedia($id);
        return $data;
    }

    public function publishPost($id)
    {
        return $this->postRepository->publishPost($id);
    }

    public function postList($status, $page=1)
    {
        return $this->postRepository->postList($status, $page);
    }

    public function archive($id)
    {
        return $this->postRepository->archive($id);
    }

    public function index()
    {
        return $this->postRepository->index();
    }

}
