<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;

class LinkController extends Controller
{
    public function index(Request $request){
        $echoStr=$request->echostr;

        if (!empty($echoStr)){
            echo $echoStr;exit;
        }

        $postStr = file_get_contents("php://input");
        file_put_contents('1.text',$postStr);
        $postObj=simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);

        $openid=$postObj->FromUserName;
        $openid=(string)$openid;
        $logo=$postObj->EventKey;
        $logo=(string)$logo;

        if ($postObj->MsgType=='event'){
            // 判断是关注事件
            if($postObj->Event=='subscribe'){
                logoCache($logo,$openid,$postObj);
            }

            // 判断是已关注事件
            if($postObj->Event=='SCAN'){
                logoCache($logo,$openid,$postObj);
            }
        }
    }
}
function logoCache($logo,$openid,$postObj){
    if ($logo){
        $logo=ltrim($logo,'qrscene_');
        cache([$logo=>$openid],60);
        Wechat::returnText('正在登陆中！',$postObj);
        exit;
    }
}
