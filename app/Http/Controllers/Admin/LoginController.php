<?php
/*
 * @Author: 落叶
 * @Date: 2020-01-11 09:45:54
 * @LastEditTime : 2020-01-11 10:35:53
 * @LastEditors  : Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \app\app\Http\Controllers\Admin\LoginController.php
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Session;


class LoginController extends Controller
{
    // 登陆执行
    public function login_do(Request $request){
        $user_name=$request->user_name;
        $user_pwd=$request->user_pwd;

        $info=UserModel::where('user_name',$user_name)->first();
        if (!$info){
            echo json_encode(['code'=>300001,'msg'=>'The account does not exist']);
            exit;
        }

        # 用户名密码错误3次锁定账号
        if (!empty($info['locktime']) && time()<$info['locktime']){
            $time=$info['locktime']-time();
            if (date('i'.$time)<0){
                $msg='Account locked.Please log in in '.date('s',$time).' seconds';
            }else{
                $msg='Account locked.Please log in in '.date('i',$time).' minute '.date('s',$time).' seconds';
            }
            echo json_encode(['code'=>300004,'msg'=>$msg]);
            exit;
        }

        if ($user_pwd!=$info['user_pwd']){
            $locknum=$info['locknum']+1;
            UserModel::where('user_id',$info['user_id'])->update(['locknum'=>$locknum]);
            $residueNum=3-$locknum;
            if ($residueNum ==0){
                $locktime=time()+600;
                UserModel::where('user_id',$info['user_id'])->update(['locktime'=>$locktime,'locknum'=>$residueNum]);
                echo json_encode(['code'=>300003,'msg'=>'Account locked.Unlock time '.date('Y-m-d H:i:s',$locktime)]);
                exit;
            }
            echo json_encode(['code'=>300002,'msg'=>'wrong password.The remaining '.$residueNum.' time']);
            exit;
        }

        # 同一账号不同地点登录互踢
        $session_id = Session::getId();
        if ($session_id!=$info['session_id'] && $info['expire_time']>time()){
            $json= 'A long-distance login';
        }else{
            $json='login successfully';
        }
        $expire_time=time()+1200;
        UserModel::where('user_id',$info['user_id'])->update(['expire_time'=>$expire_time,'session_id'=>$session_id]);
        $request->session()->forget($session_id);
        echo json_encode(['code'=>200,'msg'=>$json]);
        exit;
    }
}
