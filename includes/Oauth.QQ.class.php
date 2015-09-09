<?php
require(__DIR__ . '/URL.class.php');
class Oauth
{
	const VERSION = "2.0";
	const GET_AUTH_CODE_URL = "https://graph.qq.com/oauth2.0/authorize";
	const GET_ACCESS_TOKEN_URL = "https://graph.qq.com/oauth2.0/token";
	const GET_OPENID_URL = "https://graph.qq.com/oauth2.0/me";
	const GET_USER_INFO_URL = "https://graph.qq.com/user/get_user_info";
	
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
		//http://wiki.connect.qq.com/%E4%BD%BF%E7%94%A8authorization_code%E8%8E%B7%E5%8F%96access_token
		$RequestParameter = array(
			'response_type' => 'code',
			'client_id' => $AppKey,
			'redirect_uri' => $WebsitePath . '/oauth-' . $AppID,
			'state' => $SendState,
			'scope' => 'get_user_info,get_info'
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
		);
		// 构造请求access_token的url
		$TokenURL         = self::GET_ACCESS_TOKEN_URL . '?' . http_build_query($RequestParameter);
		$Response         = URL::Get($TokenURL);
		// Example:
		// access_token=FE04************************CCE2&expires_in=7776000&refresh_token=88E4************************BE14
		// 检测错误是否发生
		if (strpos($Response, "callback") !== false) {
			$LeftBracketPosition  = strpos($Response, "(");
			$RightBracketPosition = strrpos($Response, ")");
			$Response             = substr($Response, $LeftBracketPosition + 1, $RightBracketPosition - $LeftBracketPosition - 1);
			$Msg                  = json_decode($Response, true);
			//记录错误，这里没写Error Log模块
			$this->AccessToken    = null;
			return false;
		} else {
			$Params = array();
			parse_str($Response, $Params);
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
		
		$GraphURL = self::GET_OPENID_URL . '?' . http_build_query($RequestParameter);
		$Response = URL::Get($GraphURL);
		// 检测错误是否发生
		if (strpos($Response, "callback") !== false) {
			$LeftBracketPosition  = strpos($Response, "(");
			$RightBracketPosition = strrpos($Response, ")");
			$Response             = substr($Response, $LeftBracketPosition + 1, $RightBracketPosition - $LeftBracketPosition - 1);
		}
		// http://wiki.connect.qq.com/%E8%8E%B7%E5%8F%96%E7%94%A8%E6%88%B7openid_oauth2-0
		// Example:
		// callback( {"client_id":"YOUR_APPID","openid":"YOUR_OPENID"} );
		$UserInfo = json_decode($Response, true);
		if (isset($UserInfo['error'])) {
			//记录错误，这里没写Error Log模块
			// 记录openid
			$this->OpenID = null;
			return null;
		} else {
			// 记录openid
			$this->OpenID = $UserInfo['openid'];
			return $UserInfo['openid'];
		}
	}
	
	
	public function GetUserInfo()
	{
		// 请求参数列表
		$RequestParameter = array(
			"access_token" => $this->AccessToken,
			"oauth_consumer_key" => $this->AppKey,
			"openid" => $this->OpenID,
			"format" => "json"
		);
		
		$GraphURL = self::GET_USER_INFO_URL . '?' . http_build_query($RequestParameter);
		$Response = URL::Get($GraphURL);
		
		// http://wiki.connect.qq.com/get_user_info
		$UserInfo = json_decode($Response, true);
		if ($UserInfo['ret'] != 0) {
			//记录错误，这里没写Error Log模块
			return false;
		} else {
			// 储存昵称
			$this->NickName  = $UserInfo['nickname'];
			// 储存头像
			$this->AvatarURL = $UserInfo['figureurl_qq_2'];
			return true;
		}
	}
}