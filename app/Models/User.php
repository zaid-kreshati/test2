<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    public function desctiption()
    {
        return $this->hasMany(Description::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function tag()
    {
        return $this->hasMany(Tag::class);
    }

    public function post()
    {
        return $this->hasMany(Post::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
        'two_factor_expires_at',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_expires_at' => 'datetime',
    ];



    public function generateTwoFactorCode()
{
    $this->timestamps = false;
    $this->two_factor_code = rand(100000, 999999); // 6-digit code
    $this->two_factor_expires_at = now()->addMinutes(10); // 10-minute expiration
    $this->save();
}

public function resetTwoFactorCode()
{
    $this->timestamps = false;
    $this->two_factor_code = null;
    $this->two_factor_expires_at = null;
    $this->save();
}





}
