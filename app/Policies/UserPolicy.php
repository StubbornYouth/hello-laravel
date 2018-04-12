<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //用户更新时的权限验证
    //第一个指代当前登录的用户实例，第二个指代所要更新的实例
    public function update(User $currentUser,User $user){
        //判断二者是否相同
        return $currentUser->id === $user->id;
    }
}
