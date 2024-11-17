<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Models\User;
use App\Models\Media;



class AuthRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];

        $user->save();

        if($data['role']=='admin'){
            $user->assignRole('admin');
        }
        else{
            $user->assignRole('user');
        }


        return $user;
    }

    public function findByEmail($email)
    {
        return $this->user->where('email', $email)->first();
    }

    public function revokeUserTokens(User $user)
    {
        // Revoke all tokens for the user
        $user->tokens()->delete();
    }







}
