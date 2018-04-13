<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class Sessioncontroller extends Controller
{
    //
    public function __construct(){
        //使用Auth中间件的guest来设置只让未登录用户对登录页的访问
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    public function create()
    {

        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials=$this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        //Laravel 提供的 Auth 的 attempt 方法可以让我们很方便的完成用户的身份认证操作
        //参数是一个数组，即数据库索引和对应的值，这里直接传$credentials,第二个参数是记住我的布尔值
        if(Auth::attempt($credentials,$request->has('remember'))){
            //数据库中email和密码都匹配
            session()->flash('success','欢迎回来'.Auth::user()->name);
            //Laravel 提供的 Auth::user() 方法来获取 当前登录用户 的信息，并将数据传送给路由。
           // return redirect()->route('users.show',[Auth::user()]);

            //友好的跳转 redirect()提供了一个intended方法可以跳转到用户上一次请求的页面地址，也接收一个默认地址参数，当上一次请求为空时，跳转到默认地址
            return redirect()->intended(route('users.show',[Auth::user()]));
        }
        else{
            session()->flash('danger','登录失败，邮箱与密码不匹配！');
            //后退
            return redirect()->back();
        }
    }
    //用户退出注销
    function destroy(){
        //注销方法
        Auth::logout();
        session()->flash('success','您已成功退出!');
        //重定向
        return redirect('login');
    }
}
