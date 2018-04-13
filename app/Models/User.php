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
    //creating 用于监听模型被创建之前的事件，created 用于监听模型被创建之后的事件。
    //事件是 Laravel 提供一种简单的监听器实现，我们可以对事件进行监听和订阅，从而在事件被触发时接收到响应并执行一些指定操作。
    public static function boot(){
        //boot 方法会在用户模型类完成初始化之后进行加载，因此我们对事件的监听需要放在该方法中。
        parent::boot();
        //static 相当于self
        static::creating(function($user){
            //生成令牌
            $user->activation_token=str_random(30);
        });
    }
}
