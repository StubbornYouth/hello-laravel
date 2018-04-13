<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //times 和 make 方法是由 FactoryBuilder 类 提供的 API。times 接受一个参数用于指定要创建的模型数量，make 方法调用后将为模型创建一个 集合。
        $users = factory(User::class)->times(50)->make();
        //makeVisible 方法临时显示 User 模型里指定的隐藏属性 $hidden，接着我们使用了 insert 方法来将生成假用户列表数据批量插入到数据库中
        User::insert($users->makeVisible(['password','remember_token'])->toArray());

        //修改第一个插入的用户
        $user=User::find(6);
        $user->update([
            'name' => 'Aufree',
            'email' => 'Aufree@yousails.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'activated' => true,
        ]);

    }
}
