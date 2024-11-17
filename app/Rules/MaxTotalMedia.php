<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Media;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class MaxTotalMedia implements Rule
{
    protected $maxTotal;
    protected $postId;

    public function __construct( $maxTotal = 5, $postId = null)
    {
        $this->maxTotal = $maxTotal;
        $this->postId = $postId;
    }

    public function passes($attribute, $value)
    {
        $totalCount=0;

        // Count existing media (excluding current post's media if updating)
        $query = Media::where('mediable_type', 'App\Models\Post');

        if ($this->postId) {
            $query->where('mediable_id', '!=', $this->postId);
        }

        $existingCount = $query->count();

        // Count new files
        $newPhotosCount = is_array(request()->file('photos')) ? count(request()->file('photos')) : 0;
        $newVideosCount = is_array(request()->file('videos')) ? count(request()->file('videos')) : 0;

        // Total count
        $totalCount = $existingCount + $newPhotosCount + $newVideosCount;

        Log::info('maxi');
        Log::info($totalCount);
        return $totalCount <= $this->maxTotal;
    }

    public function message()
    {
        return 'You cannot upload more than ' . $this->maxTotal . ' media files in total.';
    }
}
