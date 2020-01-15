<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Models\UserModel;

class LoginVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $session_id = Session::getId();

        $info=UserModel::where('session_id',$session_id)->first();

        if (!$info){
            return redirect("login/login")->withErrors(["请先登陆！"]);
        }

        if ($session_id!=$info['session_id']){
            return redirect("login/login")->withErrors(["账号已在其他地方登陆！"]);
        }

        # 超过20分钟不操作重新登录
        if (time()>$info['expire_time']){
            $request->session()->forget($info['session_id']);
            return redirect("login/login")->withErrors(["登陆超时！"]);
        }
        $expire_time=time()+1200;
        UserModel::where('user_id',$info['user_id'])->update(['expire_time'=>$expire_time]);
        echo 1;
        return $next($request);
    }
}
