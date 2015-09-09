<?php
require(__DIR__ . '/URL.class.php');
// https://developer.github.com/v3/oauth/
class Oauth
{
	const VERSION = "2.0";
	const GET_AUTH_CODE_URL = "https://github.com/login/oauth/authorize";
	const GET_ACCESS_TOKEN_URL = "https://github.com/login/oauth/access_token";
	const GET_OPENID_URL = "https://api.github.com/user";
	const GET_USER_INFO_URL = "https://api.github.com/user";
	
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
		// http://open.weibo.com/wiki/Oauth2/authorize
		$RequestParameter = array(
			'client_id' => $AppKey,
			'redirect_uri' => $WebsitePath . '/oauth-' . $AppID,
			'state' => $SendState,
			'scope' => 'user'
		);
		return self::GET_AUTH_CODE_URL . '?' . http_build_query($RequestParameter);
	}
	
	
	public function GetAccessToken($WebsitePath, $AppID, $AppSecret, $Code)
	{
		// 请求参数列表
		$RequestParameter = array(
			"grant_type" => "authorization_code",
			"client_id" => $this->AppKey,
			"redirect_uri" => $WebsitePath . '/oauth-' . $AppID,
			"client_secret" => $AppSecret,
			"code" => $Code
			// ,"state" => 
		);
		// 构造请求access_token的url
		$Response         = URL::Post(self::GET_ACCESS_TOKEN_URL, $RequestParameter);
		parse_str($Response, $Params);
		//var_dump($Params);
		// 检测错误是否发生
		if (empty($Params['access_token'])) {
			//记录错误，这里没写Error Log模块
			$this->AccessToken = null;
			return false;
		} else {
			$this->AccessToken = $Params["access_token"];
			return true;
		}
	}
	
	
	public function GetOpenID()
	{
		// 请求参数列表
		$RequestParameter = array(
			"access_token" => $this->AccessToken
		);
		$Response         = URL::Get(self::GET_OPENID_URL . '?' . http_build_query($RequestParameter));
		//var_dump(self::GET_OPENID_URL . '?' . http_build_query($RequestParameter));
		//var_dump($Response);
		// 检测错误是否发生
		$UserInfo         = json_decode($Response, true);
		if ($UserInfo === false || empty($UserInfo['id'])) {
			//记录错误，这里没写Error Log模块
			// 记录openid
			$this->OpenID = null;
			return null;
		} else {
			// 记录openid
			$this->OpenID = $UserInfo['id'];
			return $UserInfo['id'];
		}
	}
	
	
	public function GetUserInfo()
	{
		// 请求参数列表
		$RequestParameter = array(
			"access_token" => $this->AccessToken
		);
		$Response         = URL::Get(self::GET_USER_INFO_URL . '?' . http_build_query($RequestParameter));
		$UserInfo = json_decode($Response, true);
		if ($UserInfo === false || empty($UserInfo['id'])) {
			//记录错误，这里没写Error Log模块
			return false;
		} else {
			// 储存昵称
			$this->NickName  = $UserInfo['login'];
			// 储存头像
			$this->AvatarURL = $UserInfo['avatar_url'];
			return true;
		}
	}
}