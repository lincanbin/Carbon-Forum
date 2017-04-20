<?php
class PDOLog
{
	private $path = '/logs/';
	public function __construct()
	{
		$this->path = __DIR__ . $this->path;
	}
	
	public function write($message, $fileSalt)
	{
		$date = new DateTime();
		$log  = $this->path . $date->format('Y-m-d') . "-" . md5($date->format('Y-m-d') . $fileSalt) . ".txt";
		if (is_dir($this->path)) {
			if (!file_exists($log)) {
				$fh = fopen($log, 'a+') or die("Fatal Error !");
				$logcontent = "Time : " . $date->format('H:i:s') . "\r\n" . $message . "\r\n";
				fwrite($fh, $logcontent);
				fclose($fh);
			} else {
				$this->edit($log, $date, $message);
			}
		} else {
			if (mkdir($this->path, 0777) === true) {
				$this->write($message, $fileSalt);
			}
		}
	}
	private function edit($log, $date, $message)
	{
		$logcontent = "Time : " . $date->format('H:i:s') . "\r\n" . $message . "\r\n\r\n";
		$logcontent = $logcontent . file_get_contents($log);
		file_put_contents($log, $logcontent);
	}
}