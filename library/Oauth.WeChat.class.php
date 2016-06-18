<?php
require(__DIR__ . '/URL.class.php');
class Oauth
{
	const VERSION = "2.0";
	const GET_AUTH_CODE_URL = "https://open.weixin.qq.com/connect/qrconnect";
	const GET_ACCESS_TOKEN_URL = "https://api.weixin.qq.com/sns/oauth2/access_token";
	const GET_OPENID_URL = "https://api.weixin.qq.com/sns/oauth2/refresh_token";
	const GET_USER_INFO_URL = "https://api.weixin.qq.com/sns/userinfo";
	
	private $AppKey;
	public $AccessToken = null;
	public $OpenID = null;
	public $NickName = null;
	public $AvatarURL = null;
	
	function __construct($AppKey)
	{
		$this->AppKey = $AppKey;
		//$this->GetAccessToken();
		//$this->GetOpenID();
	}
	
	
	public static function AuthorizeURL($WebsitePath, $AppID, $AppKey, $SendState)
	{
		// https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419316505&token=&lang=zh_CN
		$RequestParameter = array(
			'response_type' => 'code',
			'appid' => $AppKey,
			'redirect_uri' => $WebsitePath . '/oauth-' . $AppID,
			'state' => $SendState,
			'scope' => 'snsapi_login'
		);
		return self::GET_AUTH_CODE_URL . '?' . http_build_query($RequestParameter);
	}
	
	
	public function GetAccessToken($WebsitePath, $AppID, $AppSecret, $Code)
	{
		
		// 请求参数列表
		$RequestParameter = array(
			"grant_type" => "authorization_code",
			"appid" => $this->AppKey,
			"secret" => $AppSecret,
			"code" => $Code
		);
		// 构造请求access_token的url
		$Response         = URL::Get(self::GET_ACCESS_TOKEN_URL . '?' . http_build_query($RequestParameter));
		$Params           = json_decode($Response, true);
		// 检测错误是否发生
		if ($Params === false || empty($Params['access_token'])) {
			//记录错误，这里没写Error Log模块
			$this->AccessToken = null;
			$this->OpenID = null;
			return false;
		} else {
			$this->AccessToken = $Params["access_token"];
			$this->OpenID = $Params["openid"];
			return true;
		}
	}
	
	
	public function GetOpenID()
	{
		// 此方法画蛇添足，实际上在获取access_token时已经得到了OpenID
		// 这里为了封装一致性再请求一次
		// 本质是微信API设计有缺陷
		return $this->OpenID;
	}
	
	
	public function GetUserInfo()
	{
		// 请求参数列表
		$RequestParameter = array(
			"access_token" => $this->AccessToken,
			"openid" => $this->OpenID,
			"lang" => "zh-CN"
		);
		
		$GraphURL = self::GET_USER_INFO_URL . '?' . http_build_query($RequestParameter);
		$Response = URL::Get($GraphURL);
		
		// http://wiki.connect.qq.com/get_user_info
		$UserInfo = json_decode($Response, true);
		if ($UserInfo === false || empty($UserInfo['openid'])) {
			//记录错误，这里没写Error Log模块
			return false;
		} else {
			// 储存昵称
			$this->NickName  = $UserInfo['nickname'];
			// 储存头像
			$this->AvatarURL = $UserInfo['headimgurl'];
			return true;
		}
	}
}