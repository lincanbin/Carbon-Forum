<?php
/**
 * Created by PhpStorm.
 * User: lincanbin
 * Date: 2017/12/7
 * Time: 9:56
 */

$_SERVER['REQUEST_URI'] = '';
$_SERVER['REMOTE_ADDR'] = '0.0.0.0';
$_SERVER['HTTP_HOST']   = 'www.94cb.com';

require(__DIR__ . '/../../common.php');

if (php_sapi_name() !== "cli") {
	exit(401);
}

require(LibraryPath . 'WebSocket.php');
require(LibraryPath . 'WebSocketUser.php');

function CheckPortBindable($host, $port)
{
	$errno = null;
	$errstr = null;
	$socket = stream_socket_server("tcp://$host:$port", $errno, $errstr);
	if (!$socket) {
		echo "$errstr ($errno)\n";
		return false;
	}
	return true;
}

class pushServer extends WebSocketServer
{
	//protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
	private $userId2NotificationNumberMap = [];

	protected function process($user, $message)
	{
		$this->send($user, $message);
	}

	protected function connected($user)
	{
		global $Config;
		// Do nothing: This is just an echo server, there's no need to track the user.
		// However, if we did care about the users, we would probably have a cookie to
		// parse at this step, would be looking them up in permanent storage, etc.
		$CurUserID             = intval($user->headers['cookie'][$Config['CookiePrefix'] . 'UserID']);
		$CurUserExpirationTime = intval($user->headers['cookie'][$Config['CookiePrefix'] . 'UserExpirationTime']);
		$CurUserCode           = $user->headers['cookie'][$Config['CookiePrefix'] . 'UserCode'];
		$CurUserInfo           = null; //当前用户信息，Array，以后判断是否登录使用if($CurUserID)
		$CurUserRole           = 0;
		$CurUserName           = '';
		CheckCookie($CurUserID, $CurUserExpirationTime, $CurUserCode, $CurUserInfo, $CurUserRole, $CurUserName);
		if (!empty($CurUserInfo)) {
			$this->stdout($CurUserID . ' ' . $CurUserName);
			$user->userId = $CurUserID;
			if (empty($this->userId2IdMap[$CurUserID])) {
				$this->userId2IdMap[$CurUserID] = [];
			}
			$this->userId2IdMap[$CurUserID][$user->id] = $user->id;
		}
		if (!empty($user->headers['x-forwarded-for'])) {
			$this->stdout($user->headers['x-forwarded-for']);
		}
		if (!empty($user->headers['user-agent'])) {
			$this->stdout($user->headers['user-agent']);
		}

		$this->stdout(date("Y-m-d H:i:s") . "\n\n");
		//$this->send($user, var_export($user->headers['cookie'], true));
		//$this->send($user, var_export($CurUserInfo, true));
	}

	protected function push()
	{
		//var_dump($this->userId2IdMap);
		$userIds = array_keys($this->userId2IdMap);
		foreach ($userIds as $userId) {
			$CurUserInfo = GetUserInfo($userId);
			if ($CurUserInfo && $CurUserInfo['NewNotification'] == 0) {
				unset($this->userId2NotificationNumberMap[$userId]);
			}
			if (
				$CurUserInfo && $CurUserInfo['NewNotification'] > 0
				&& (
					!isset($this->userId2NotificationNumberMap[$userId])
					|| $this->userId2NotificationNumberMap[$userId] != $CurUserInfo['NewNotification']
				)
			) {
				$notificationArray = [
					'Status'          => 1,
					'NewNotification' => $CurUserInfo['NewNotification'],
					'NewReply'        => $CurUserInfo['NewReply'],
					'NewMention'      => $CurUserInfo['NewMention'],
					'NewMessage'      => $CurUserInfo['NewMessage']
				];
				foreach ($this->userId2IdMap[$userId] as $userUniqueId){
					if (isset($this->users[$userUniqueId])) {
						$this->send($this->users[$userUniqueId], json_encode($notificationArray));
					} else {
						unset($this->userId2IdMap[$userId][$userUniqueId]);
					}
				}
				$this->userId2NotificationNumberMap[$userId] = $CurUserInfo['NewNotification'];
			}
		}
		//var_dump(time());
		//var_dump($this->userId2NotificationNumberMap);
	}

	protected function closed($user)
	{
		if (!empty($user->userId)) {
			unset($this->userId2IdMap[$user->userId][$user->id]);
			if (count($this->userId2IdMap[$user->userId]) === 0) {
				unset($this->userId2IdMap[$user->userId]);
			}
		}
		// Do nothing: This is where cleanup would go, in case the user had any sort of
		// open files or other objects associated with them.  This runs after the socket
		// has been closed, so there is no need to clean up the socket itself here.
	}
}

if (checkPortBindable("0.0.0.0", WebSocketBackendPort) === false) {
	echo "port " . WebSocketBackendPort . " has been used.";
	exit(1);
}
$echo = new pushServer("0.0.0.0", WebSocketBackendPort);
try {
	$echo->run();
} catch (Exception $e) {
	$echo->stdout($e->getMessage());
	exit(1);
}
