<!DOCTYPE html>
<!-- saved from url=(0048)https://demo.mycodes.net/houtai/yonghumingsaoma/ -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <link rel="stylesheet" href="/Document_files/style.css">
        <script src="/static/js/jquery-3.2.1.min.js"></script>
    <style>
        .popupAlert{ position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5)}
        .popupAlert .contentBox{ position: absolute; width: 460px; height: 200px; background: #FFF; left: 50%; margin-left: -230px; top: 100px; overflow: hidden; border-radius: 6px}
        .popupAlert .titBox{ width: 100%; height: 40px; background: #17c2a4; padding: 0 20px; box-sizing: border-box; font-size: 18px; color: #FFF; line-height: 40px;}
        .popupAlert .textBox{ box-sizing: border-box; width: 100%; height: 100px; padding: 31px 20px; font-size: 16px; color: #333; line-height: 24px}
        .popupAlert .backBtn{ float: right; margin: 10px 20px 0 0; display: block; width: 100px; height: 40px; background: #17c2a4; border-radius: 6px; text-align: center; line-height: 40px; text-decoration: none; font-size: 16px; color: #FFF}

    </style>
    <style id="tsbrowser_video_independent_player_style" type="text/css">
      [tsbrowser_force_max_size] {
        width: 100% !important;
        height: 100% !important;
        left: 0px !important;
        top: 0px !important;
        margin: 0px !important;
        padding: 0px !important;
        transform: none !important;
      }
      [tsbrowser_force_fixed] {
        position: fixed !important;
        z-index: 9999 !important;
        background: black !important;
      }
      [tsbrowser_force_hidden] {
        opacity: 0 !important;
        z-index: 0 !important;
      }
      [tsbrowser_hide_scrollbar] {
        overflow: hidden !important;
      }
      [tsbrowser_display_none] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
      }
      [tsbrowser_force_show] {
        display: black !important;
        visibility: visible !important;
        opacity: 0;
      }
        #dl{

          background-color: #703EFF;
          width: 120px;
            height: 44px;
          border-radius: 50px;
          border-bottom: none;
          cursor: pointer;
          font-size: 16px;
          color: #fff;
          text-align: center;
          font-weight: bold;

        }
    </style>
</head>
    <body><div id="BAIDU_DUP_fp_wrapper" style="position: absolute; left: -1px; bottom: -1px; z-index: 0; width: 0px; height: 0px; overflow: hidden; visibility: hidden; display: none;">
        <iframe id="BAIDU_DUP_fp_iframe" src="" style="width: 0px; height: 0px; visibility: hidden; display: none;"></iframe></div>
        <header>
            <nav class="b_clear">
                <div class="nav_logo l_float">
                    <img src="/Document_files/logo.svg" alt="">
                </div>
                <div class="nav_link r_float">
                    <ul>
                        <li><a href="">返回首页</a></li>
                        <li><a href="">关于我们</a></li>
                        <li><a href="">联系我们</a></li>

                    </ul>
                </div>
            </nav>
        </header>

        <div class="container">



            <div class="login_body l_clear">
                <div class="login_form l_float">
                    <div class="login_top">
                        <img src="/Document_files/logo_z.svg" alt="" class="">
                        <span style="color:red">@if(!empty($errors->first())) {{$errors->first()}} @endif</span>
                        <div class="login_tags b_clear">
                            <span class="top_tag l_float active" onclick="PwdLogin()">密码登录</span>
                            <span class="top_tag r_float saoma" onclick="QrcodeLogin()">扫码登录</span>
                        </div>
                    </div>
                    <div class="login_con">
                        <form action="{{url('login/login_do')}}" method="POST">
                            <div>
                                <label for="user_name">用户名</label>
                                <input type="text" name="user_name" id="user_name" placeholder="账号/手机号/邮箱">
                                <img src="/Document_files/user.svg">
                                <p class="tips hidden">请检查您的账号</p>
                            </div>
                            <div>
                                <label for="user_pwd">密码</label>
                                <input type="password" name="user_pwd" id="user_pwd" placeholder="请输入账户密码">
                                <img src="/Document_files/lock.svg">
                                <p class="tips hidden">请检查您的密码</p>
                            </div>
                            <div class="b_clear">
                                <label for="auth_code" class="b_clear">验证码</label>
                                <input type="text" name="" id="auth_code" placeholder="" class="l_float" maxlength="6">

                                <button class="auth_code l_float">获取验证码</button>
                                <img src="/Document_files/auth_code.svg">
                                <p class="tips hidden">验证码错误</p>

                            </div>
                            <div class="">
                                
                                <button type="button" id="dl">登&nbsp;&nbsp;录</button>
                                <a href="https://demo.mycodes.net/houtai/yonghumingsaoma/#" class="r_float">忘记密码？</a>
                                <p class="tips hidden">登录失败，请检查您的账户与密码</p>
                            </div>
                        </form>   
                    </div>
                    <div class="login_con hidden">
                        <div class="qr_code">
                                <img id="erweima" src="/Document_files/qr.png" alt="" style="width: 140px;height: 140px">
                                <p>请使用微信扫码登录<br>支持已绑定微信的账户进行快速登录</p>
                        </div>
                        
                    </div>
                    <div class="login_otherAccount">
                        <span>第三方登录</span>
                        <a href="http:"><img src="/Document_files/wechat.svg" alt="微信登录"></a>
                        <a href="http:"><img src="/Document_files/weibo.svg" alt="微博登录"></a>
                        <a href="https://demo.mycodes.net/houtai/yonghumingsaoma/"><img src="/Document_files/qq.svg" alt="QQ登录"></a>
                    </div>
                    
                </div>
                <div class="login_ad l_float">
                    <img src="/Document_files/40cc608c8e78b5c36e64dad7a5c2bbd4.jpg">
                </div>
            </div>
            <div class="footer">
                <p>L-Y © 2019-2022  <a href="https://demo.mycodes.net/houtai/yonghumingsaoma/#">落叶</a></p>
                <!-- <a href="http://www.beian.gov.cn/" target="_blank"><img src="imgs/icons/national_emblem.svg" alt="公安部备案">蒙公网安备15020302000160号</a> -->
                <a href="http//www.beian.miit.gov.cn" target="_blank" style="text-decoration:none">冀ICP备19033202号</a><a href="http://www.mycodes.net/" target="_blank">源码之家</a>
            </div>
        </div>

        <script type="text/javascript" async="" src="/Document_files/ocdm"></script><script type="text/javascript" async="" src="/Document_files/auto_dup"></script><script src="/Document_files/login.js.下载"></script>

<script type="text/javascript">
</script>
<div style="" id="_itghtai4t8s"></div><script type="text/javascript" src="/Document_files/c.js.下载" async="async" defer="defer"></script>


</body></html>
<script>
    $(function () {
        $('.saoma').click(function () {
            huanha();
        })
        $('#erweima').click(function () {
            huanha();
        })
        $('#dl').click(function () {
            var user_name=$('[name="user_name"]').val();
            var user_pwd=$('[name="user_pwd"]').val();
            if (user_name=='' || user_pwd==''){
                alert('用户名密码必填！');
                return ;
            }
            $.ajax({
                method: "POST",
                url: "{{url('login/login_do')}}",
                data: { user_name:user_name, user_pwd:user_pwd },
                dataType:'json'
            }).done(function( msg ) {
                alert(msg.msg)
                if (msg.code==200){
                    location.href='{{url('index/index')}}';
                }
            });

        })
    })
    function huanha() {
        $.ajax({
            method: "get",
            url: "{{url('login/logincode')}}",
            dataType:'json'
        }).done(function( msg ) {
            $('#erweima').attr('src',msg.data.img_url);
            setTimer(msg.data.key);
        });
    }
    function setTimer (logo) {
        console.log(logo);
        var t=setInterval(function (){is_scan(logo)}, 1000);
        function is_scan(logo) {
            $.ajax({
                method: "get",
                url: "{{url('login/is_scan')}}",
                dataType:'json',
                data:{logo:logo}
            }).done(function(msg) {
                if (msg.code==200) {
                    clearInterval(t);
                    location.href="{{url('login/login_do')}}"+'?logo='+logo;
                }
            });
        }
    }

</script>