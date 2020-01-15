<?php
namespace App\Tools;
use App\Tools\Curl;
use App\Models\AccessTokenModel;
class Wechat{
    const appId='wxa886af038c9c8365';
    const appSecret='7d730deabba69dd7c0254f30bd6a2bdf';
    /**
     * 回复文本消息
     */
    public static function returnText($text,$xmlObj){
        echo "<xml>
                   <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                   <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                   <CreateTime>".time()."</CreateTime>
                   <MsgType><![CDATA[text]]></MsgType>
                   <Content><![CDATA[".$text."]]></Content>
              </xml>";
    }

    /**
     * 返回图片
     */
    public static function returnImage($media_id,$xmlObj){
        echo "<xml>
                   <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                   <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                   <CreateTime>".time()."</CreateTime>
                   <MsgType><![CDATA[image]]></MsgType>
                   <Image>
                     <MediaId><![CDATA[$media_id]]></MediaId>
                   </Image>
              </xml>";
    }

    /**
     * 获取access_token
     */
    public static function getAccessToken(){
//        $access_token=cache('access_token');
        $info=AccessTokenModel::where('id','1')->first();
        $time=time();
        $access_token=$info['access_token'];
        if(empty($access_token)){
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appId."&secret=".self::appSecret."";
            $data=file_get_contents($url);
            $data=json_decode($data,true);
            $access_token=$data['access_token'];
            // 令牌 存储俩小时
//            cache('access_token',$access_token,7200);
            AccessTokenModel::create(['access_token'=>$access_token,'time'=>$time]);
            $info=AccessTokenModel::where('id','1')->first();
            $access_token=$info['access_token'];
        }elseif(($time-$info['time'])>300){
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appId."&secret=".self::appSecret."";
            $data=file_get_contents($url);
            $data=json_decode($data,true);
            $access_token=$data['access_token'];
            // 令牌 存储俩小时
//            cache('access_token',$access_token,7200);
            AccessTokenModel::where('id','1')->update(['access_token'=>$access_token,'time'=>$time]);
            $info=AccessTokenModel::where('id','1')->first();
            $access_token=$info['access_token'];
        }
        return $access_token;
    }

    /**
     * 获取用户信息
     */
    public static function getUserInfo($open_id){
        $access_token=Wechat::getAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        return $data;
    }

    /**
     *获取天气
     */
    public static function getWeather($city){
        $url="http://api.k780.com/?app=weather.future&weaid={$city}&&appkey=46454&sign=144be998802fe8d58a578a086aeab470&format=json";
        // 读取url
        $data=file_get_contents($url);
        // 转化数组
        $data=json_decode($data,true);
        $text='';
        foreach ($data['result'] as $key => $v) {
            // 时间 地点 星期 温度 天气
            $text.=$v['days'].' '.$v['citynm'].' '.$v['week'].' '.$v['temperature'].' '.$v['weather']."\r\n";
        }
        return $text;
    }

    /**
     * 上传素材
     */
    public static function uploadMaterial($path,$data){
        $access_token=self::getAccessToken();
        if ($data['media_type']=='1'){
            $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$data['media_format']}";
        }else{
            $url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type={$data['media_format']}";
        }
        $postData['media']=new \CURLFile($path);
        $media=Curl::curlPost($url,$postData);
        $media=json_decode($media,true);
        return $media;
    }

    /**
     * 生成二维码Ticket
     */
    public static function getTicket($channel_code){
        $access_token=self::getAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";

        $postData='{"expire_seconds": 30, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$channel_code.'"}}}';
        $res=Curl::curlPost($url,$postData);
        $ticket=json_decode($res,true);
        return $ticket;
    }

    /**
     * 网页授权获取用户openid
     * @return [type] [description]
     */
    public static function getOpenid()
    {
        //先去session里取openid
        $openid = session('openid');
        //var_dump($openid);die;
        if(!empty($openid)){
            return $openid;
        }
        //微信授权成功后 跳转咱们配置的地址 （回调地址）带一个code参数
        $code = request()->input('code');
        if(empty($code)){
            //没有授权 跳转到微信服务器进行授权
            $host = $_SERVER['HTTP_HOST'];  //域名
            $uri = $_SERVER['REQUEST_URI']; //路由参数
            $redirect_uri = urlencode("http://".$host.$uri);  // ?code=xx
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appId."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
            header("location:".$url);die;
        }else{
            //通过code换取网页授权access_token
            $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appId."&secret=".self::appSecret."&code={$code}&grant_type=authorization_code";
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $openid = $data['openid'];
            //获取到openid之后  存储到session当中
            session(['openid'=>$openid]);
            return $openid;
            //如果是非静默授权 再通过openid  access_token获取用户信息
        }
    }

    /**
     * 网页授权获取用户基本信息
     * @return [type] [description]
     */
    public static function getOpenidByUserInfo()
    {
        //先去session里取openid
        $userInfo = session('userInfo');
        //var_dump($openid);die;
        if(!empty($userInfo)){
            return $userInfo;
        }
        //微信授权成功后 跳转咱们配置的地址 （回调地址）带一个code参数
        $code = request()->input('code');
        if(empty($code)){
            //没有授权 跳转到微信服务器进行授权
            $host = $_SERVER['HTTP_HOST'];  //域名
            $uri = $_SERVER['REQUEST_URI']; //路由参数
            $redirect_uri = urlencode("http://".$host.$uri);  // ?code=xx
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appId."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            header("location:".$url);die;
        }else{
            //通过code换取网页授权access_token
            $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appId."&secret=".self::appSecret."&code={$code}&grant_type=authorization_code";
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $openid = $data['openid'];
            $access_token = $data['access_token'];
            //获取到openid之后  存储到session当中
            //session(['openid'=>$openid]);
            //return $openid;
            //如果是非静默授权 再通过openid  access_token获取用户信息
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
            $userInfo = file_get_contents($url);
            $userInfo = json_decode($userInfo,true);
            //返回用户信息
            session(['userInfo'=>$userInfo]);
            return $userInfo;
        }
    }

    public static function getToken(){
        $access_token=cache('access_token');
        $time=time();
        if(empty($access_token)){
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appId."&secret=".self::appSecret."";
            $data=file_get_contents($url);
            $data=json_decode($data,true);
            $access_token=$data['access_token'];
            // 令牌 存储俩小时
            cache('access_token',$access_token,7200);
        }else{
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appId."&secret=".self::appSecret."";
            $data=file_get_contents($url);
            $data=json_decode($data,true);
            $access_token=$data['access_token'];
            // 令牌 存储俩小时
            cache('access_token',$access_token,7200);
        }
        return $access_token;
    }
    //生成扫码登陆二维码
    public static function loginCode($status){
        $access_token = Wechat::getToken();
        //创建参数二维码接口
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";
        //请求数据
        $postData = [
            'expire_seconds'=>60,
            'action_name'=>'QR_STR_SCENE',
            'action_info'=>[
                'scene'=>[
                    'scene_str'=>$status
                ],
            ],
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $data = Curl::curlPost($url,$postData);
        $data = json_decode($data,true);
        if(isset($data['ticket'])){
            $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$data['ticket'];
            return $url;
        }
        return false;
    }
}