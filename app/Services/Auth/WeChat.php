<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 10:38
 * Function:
 */

namespace App\Services\Auth;


use App\Contracts\JwtAuthContract;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserAuthWechat;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Tanmo\Wechat\Facades\MiniProgram;
use Ixudra\Curl\Facades\Curl;
use ServerAPI;
class WeChat implements JwtAuthContract
{
    use Respond;

    /**
     * @var UserAuthWechat
     */
    protected $userAuthWechat;

    /**
     * WeChat constructor.
     * @param UserAuthWechat $userAuthWechat
     */
    public function __construct(UserAuthWechat $userAuthWechat)
    {
        $this->userAuthWechat = $userAuthWechat;
    }

    /**
     * @return Response
     */
    public function register(Request $request)
    {
       // mlog('text',$request->all());

        $auth = $this->userAuthWechat->getByOpenId($request->get('openid'));

        if (!$auth) {
            /// 未注册
            $user = new User();
            $user->avatarUrl = $request->get('headimgurl');
            $user->nickname = $request->get('nickname');
            $user->gender = $request->get('sex');
            $user->country = $request->get('country');
            $user->province = $request->get('province');
            $user->city = $request->get('city');
            $user->save();

            ///
            $authWechat = new UserAuthWechat(['open_id' => $request->get('openid')]);
            $user->authWechat()->save($authWechat);
        }
        else {
            $user = $auth->user;
            User::where('id', $user->id)
                ->update(['avatarUrl' => $request->get('headimgurl')]);
        }

        $token = auth('api')->login($user);
        return api()->item($user, UserResource::class)->setMeta($this->respondWithToken($token));
        //获取access_token
        //https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
//        $param =[
//            'appid' => config('wechatlogin.wechatoff.appid'),
//            'secret' => config('wechatlogin.wechatoff.secret'),
//            'code' => $request['code'],
//            'grant_type' => 'authorization_code'
//        ];
//        $response = Curl::to(config('wechatlogin.wechataccess'))->withData($param)->get();
//        $response = json_decode($response, true);
//        if(isset($response['errcode'])){
//            return '登录失败'.$response['errmsg'];
//        }
        //获取用户信息
        //https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
//        $param=[
//            'access_token' =>$response['access_token'],
//            'openid' =>$response['openid'],
//            'lang' => 'zh_CN'
//        ];
//        $userinfo = Curl::to(config('wechatlogin.wechatuserinfo'))->withData($param)->get();
//        dd($userinfo);
//        $userinfo = json_decode($userinfo, true);
//        if(isset($userinfo['errcode'])){
//            return '登录失败'.$userinfo['errmsg'];
//        }
//        return   $userinfo['nickname'];
    }
    public function login(): Response
    {

        $code = request()->get('code');
        $encryptedData = request()->get('encrypted_data');
        $iv = request()->get('iv');




        ///
        $wechatUser = MiniProgram::app()->auth()->code($code)->encryptedData($encryptedData)->iv($iv)->user();

        ///
        $auth = $this->userAuthWechat->getByOpenId($wechatUser['openId']);
        if (!$auth) {
            /// 未注册
            $user = new User();
            $user->avatarUrl = $wechatUser['avatarUrl'];
            $user->nickname = $wechatUser['nickName'];
            $user->gender = $wechatUser['gender'];
            $user->country = $wechatUser['country'];
            $user->province = $wechatUser['province'];
            $user->city = $wechatUser['city'];
            $user->save();

            ///
            $authWechat = new UserAuthWechat(['open_id' => $wechatUser['openId']]);
            $user->authWechat()->save($authWechat);
        }
        else {
            $user = $auth->user;
            User::where('id', $user->id)
                ->update(['avatarUrl' => $wechatUser['avatarUrl']]);
        }

        $token = auth('api')->login($user);
        return api()->item($user, UserResource::class)->setMeta($this->respondWithToken($token));
    }

    /**
     * @return Response
     */
    public function refresh(): Response
    {
        $user = auth('api')->user();
        return api()->item($user, UserResource::class)->setMeta($this->respondWithToken(auth()->refresh()));
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        auth()->logout();
        return api()->accepted();
    }

    public function checktoken(Request $request) :Response
    {

        //服务器绑定认证token
        $timestamp = $request->get('timestamp');
        $nonce = $request->get('nonce');
        //和微信公众号后台的token一样
        $token = 'weixin';
        $signature = $request->get('signature');
        $array = array($timestamp,$nonce,$token);
        sort($array);
        $tmpstr = implode('',$array);
        $tmpstr = sha1($tmpstr);
        if($tmpstr == $signature) {
            echo $request->get('echostr');
            exit;
        }
//        https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxea5e102bc1ce56e1&redirect_uri=http://k7t9ee.natappfree.cc/auth/register&response_type=code&scope=snsapi_userinfo#wechat_redirect
//下面的链接貌似没有用户提示
//https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxea5e102bc1ce56e1&redirect_uri=http://82h53e.natappfree.cc/login&response_type=code&scope=snsapi_base#wechat_redirect
    }
    public function mobile(Request $request)
    {
        $mobile = $request->get('mobile');
        $AppKey = '650b06e26162620263827f935eeb11fc';
//网易云信分配的账号，请替换你在管理后台应用下申请的appSecret
        $AppSecret = '124cf3e62149';
        $p = new ServerAPI($AppKey,$AppSecret,'fsockopen');     //fsockopen伪造请求

//发送短信验证码
        print_r( $p->sendSmsCode('3943558','13358324335','','5') );

//发送模板短信
       // print_r( $p->sendSMSTemplate('6272',array('13888888888','13666666666'),array('xxxx','xxxx' )));
    }
}