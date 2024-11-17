<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\ProfileController;
use App\Http\Controllers\web\PostController;
use App\Http\Middleware\TwoFactor;
use App\Http\Controllers\web\AdminController;
use App\Http\Controllers\web\TwoAuthController;
use App\Http\Controllers\web\CategoryController;
use App\Http\Controllers\web\CommentController;
use App\Http\Controllers\web\SearchController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    return view('DashBoard.welcome');
})->name('admin');


Route::get('/test', function () {
    return view('test');
});


Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'showRegisterForm')->name('register.form');
   // Route::post('/register/user', 'register_user')->name('register.user');

});


Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

//profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/upload-profile-image', [ProfileController::class, 'upload_profile_image'])->name('profile.upload-profile-image');
    Route::put('/profile/upload-background-image', [ProfileController::class, 'upload_background_image'])->name('profile.upload-background-image');
    Route::post('profile/description/add', [ProfileController::class, 'addDescription'])->name('profile.add-description');
    Route::put('profile/description/update/{id}', [ProfileController::class, 'updateDescription']);
    Route::delete('profile/description/delete/{id}', [ProfileController::class, 'deleteDescription']);
    Route::post('/profile/save-descriptions', [ProfileController::class, 'saveDescriptions'])
    ->name('profile.save-descriptions');
    Route::delete('/profile/remove-profile-image', [ProfileController::class, 'removeProfileImage'])->name('profile.remove-profile-image');
    Route::delete('/profile/remove-cover-image', [ProfileController::class, 'removeCoverImage'])->name('profile.remove-cover-image');

});


//post
Route::middleware('auth')->group(function () {
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
    Route::put('/posts/{id}/update', [PostController::class, 'update'])->name('post.update');
    Route::patch('/posts/{id}/archive', [PostController::class, 'archive'])->name('post.archive');
    Route::post('/posts/filter', [PostController::class, 'filterPosts'])->name('posts.filter');
    Route::delete('/media/{id}/delete', [PostController::class, 'deleteMedia'])->name('media.delete');
    Route::delete('/posts/{id}/delete', [PostController::class, 'deletePost'])->name('post.delete');
    Route::post('/posts/{id}/publish', [PostController::class, 'publishPost'])->name('post.publish');
    Route::get('/posts/list', [PostController::class, 'postList'])->name('posts.list');

    Route::get('/posts/load-more', [PostController::class, 'loadMorePosts'])->name('posts.load-more');

});

//two factor
Route::get('/verify-two-factor', [TwoAuthController::class, 'showVerifyForm'])->name('verify.two.factor');
Route::post('/verify-two-factor', [TwoAuthController::class, 'initiateRegistration'])->name('verify.two.factor.code');
Route::get('/resend-two-factor-code', [TwoAuthController::class, 'resendTwoFactorCode'])->name('resend.2fa');
Route::post('/register/initiate', [TwoAuthController::class, 'initiateRegistration'])->name('register.initiate');
Route::post('/register/verify', [TwoAuthController::class, 'verifyRegistration'])->name('register.verify');




Route::controller(AdminController::class)->prefix('admin')->group(function () {
    Route::get('/login', 'showLoginForm')->name('login.form.admin');
    Route::post('/login', 'login')->name('login.admin');
    Route::post('/logout', 'logout')->name('DashBoard.logout')->middleware('checkAdminAuth');
    Route::get('/register', 'showRegisterForm')->name('register.form.admin');
    Route::post('/register', 'register')->middleware('auth:api')->name('register.admin')->middleware('checkAdmin');
    Route::get('/home', 'index')->name('DashBoard.home')->middleware('checkAdminAuth');
});


Route::controller(CategoryController::class)->prefix('admin')->group(function () {
    Route::get('/categories', 'index')->name('categories.index');
    Route::get('/categories/create', 'create')->name('categories.create');
    Route::post('/categories', 'store')->name('categories.store');
    Route::get('/categories/{id}/edit', 'edit')->name('categories.edit');
    Route::put('/categories/{id}', 'update')->name('categories.update');
    Route::delete('/categories/{id}', 'destroy')->name('categories.destroy');
    Route::get('/categories/{id}/nested', 'getNestedCategories')->name('nestedCategories');
    Route::post('/categories/search', 'search')->name('categories.search');
    Route::post('/categories/paginate', 'paginate')->name('categories.paginate');
    //Route::get('/categories/{id}/children', 'getChildren')->name('categories.children');
    Route::get('/categories/{category}/children', 'getChildren')->name('categories.children');



    Route::get('/categories/index',  'index2')->name('categories.index2');
    Route::get('/categories/{parentId}/children', 'getChildren')->name('categories.children');

    Route::get('/categories',  'index')->name('categories.index');
    Route::get('/categories/{category}/children',  'getChildren')->name('categories.children');

});

Route::controller(CommentController::class)->prefix('comment')->group(function () {
    Route::post('/store', 'store')->name('comment.store');
    Route::get('/{postId}', 'index')->name('comment.index');
    Route::delete('/{id}', 'destroy')->name('comment.destroy');
    Route::put('/{id}', 'update')->name('comment.update');
    Route::post('/store/nested', 'storeNested')->name('comment.store.nested');
    Route::get('/get/nested', 'getNestedComments')->name('comment.get.nested');
});

Route::controller(SearchController::class)->prefix('search')->group(function () {
    Route::get('/all', 'searchAll')->name('search.all');
    Route::get('/posts/with/photo', 'searchPostswithphoto')->name('search.posts.with.photo');
    Route::get('/posts/with/video', 'searchPostswithvideo')->name('search.posts.with.video');
    Route::get('/all/posts', 'searchAllPosts')->name('search.all.posts');
    Route::get('/users', 'searchUsers')->name('search.users');
});
