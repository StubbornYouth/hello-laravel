<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    //创建用户页面
    function create(){
        return view('user.create');
    }
    //查看某个用户信息
    function show(User $user){
        //compact将数据转为关联数组
        return view('user.show',compact('user'));
    }
    //post提交添加注册
    function store(Request $request){
        //表单验证
        $this->validate($request,[
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6|max:20'
        ]);

        //提交用户信息
        //$request中存放用户提交信息
        $user=User::create(
            array(
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            )
        );
        //Laravel 中，如果要让一个已认证通过的用户实例进行登录，可以使用以下方法：
        Auth::login($user);
        //session会话 存入缓存数据 flash()方法让会话只在下一次请求有效
        session()->flash('success',"恭喜你，注册成功！");
        //重定向 这里的参数传的是新创建的用户id
        return redirect()->route('users.show',[$user]);
    }

}
