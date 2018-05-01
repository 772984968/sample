<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }
    public function statuses(){
        return $this->hasMany(Status::class);
    }
    public function feed(){
        return $this->statuses()->orderByDesc('created_at');
    }
    //粉丝-联系
    public  function  followers(){
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }
    //关注-联系
    public function followings()
    {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }
    //关注
    public function follow($user_ids){
        if (!is_array($user_ids)){
            $user_ids=compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }
    public function unfollow($user_ids){
        if (!is_array($user_ids)){
            $user_ids=compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }
    //是否包含
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }




















}
