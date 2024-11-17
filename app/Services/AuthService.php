<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    protected $AuthRepository;

    public function __construct(AuthRepository $AuthRepository)
    {
        $this->AuthRepository = $AuthRepository;
    }

    public function register(array $data)
    {
        Log::info($data);
        // Hash the password before saving
        $data['password'] = Hash::make($data['password']);


           // Upload photo and store path
           $photoPath = null;
           if (isset($data['photo']) && $data['photo']->isValid()) {
               $photoPath = $data['photo']->store('photos', 'public'); // Store in the 'public/photos' directory
           }

           // Call repository to create post
           $user= $this->AuthRepository->create([
               'name' => $data['name'],
               'email' => $data['email'],
               'password' => $data['password'],
               'photo_path' => $photoPath, // Store the path here
               'role' => $data['role'],
           ]);



        // Generate a token for the user
        $token = $user->createToken('MyApp')->accessToken;

        // Return the user and token information
        return [
            'status' => 'success',
            'user' => $user,
            'access_token' => $token,
        ];
    }

    public function login(array $credentials)
    {
        // Attempt to authenticate the user with the given credentials
        if (Auth::attempt($credentials)) {
            // Get the authenticated user
            $user = Auth::user();

            // Generate a new token for the authenticated user
            $token = $user->createToken('Token Name')->accessToken;


            // Return the user and token information
            return [
                'status' => 'success',
                'user' => $user,
                'access_token' => $token,
            ];
        } else {
            // Return an error response if authentication fails
            return [
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ];
        }
    }



}
