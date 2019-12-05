<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;
    protected $appends = ['avatar'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'login', 'desc', 'verification_code', 'image', 'city'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function sendEmailVerificationNotification(){
        $this->verification_code = md5(Str::random(60));
        $this->save();
        Mail::to($this->email)->send(new VerificationEmail($this));
    }
    public function places(){
        return $this->hasMany('App\Place')->with('tags');
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute(){
        if($this->image){
            return $this->image;
        }else{
            return 'default/avatar.jpg';
        }
    }
}
