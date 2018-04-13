<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    //Laravel 中间件 (Middleware) 为我们提供了一种非常棒的过滤机制来过滤进入应用的 HTTP 请求，第一个参数是中间件名称，第二个为要进行过滤的动作
    //except是除了指定的，其它都需要登录后才能访问,类似黑名单过滤机制
    //相反，还有白名单 only
    public function __construct(){
        $this->middleware('auth',[
            'except' => ['show','create','store','index']
        ]);

        //只允许未登录用户访问注册页面
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }
    //所有用户列表
    function index(){
        //该方法用来指定每页生成的数据数量为10条
        $users=User::paginate(10);
        return view('user.index',compact('users'));
    }
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
    //转到修改界面
    public function edit(User $user){
        //授权策略定义完成并与当前模型联系起来后，就可以使用authorize方法来验证用户的授权策略
        //该方法是父类之中的，两个参数，一个是授权策略定义的方法名称，另一个是验证数据
        $this->authorize('update',$user);
        return view('user.edit',compact('user'));
    }

    //提交修改
    public function update(User $user,Request $request){
        $this->validate($request,[
            'name' => 'required|min:3|max:50',
            'password' => 'nullable|confirmed|min:6|max:20'
        ]);
        $data=['name'=>$request->name];
        if($request->password){
            $data['password']=bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success','修改信息成功！');
        return redirect()->route('users.show',$user->id );
    }
    //删除用户
    public function destroy(User $user){
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','删除用户成功!');
        return redirect()->back();
    }
}
