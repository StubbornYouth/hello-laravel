<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //允许用户提交更新的字段
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //对敏感信息进行隐藏
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function gravatar($size = '100')
    {
        $hash =md5(strtolower(trim($this->attributes['email'])));
        return  "http://www.gravatar.com/avatar/$hash?s=$size";
    }
}
