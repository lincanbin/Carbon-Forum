<?php
// http://php.net/manual/en/book.memcache.php
class MemcacheMod
{
	private $MemcacheInstance;
	public function __construct($MemCacheHost, $MemCachePort)
	{
		$this->MemcacheInstance = new Memcache();
		$this->MemcacheInstance->pconnect($MemCacheHost, $MemCachePort);
		return $this->MemcacheInstance;
	}

	public function set($key, $value, $expire = 86400)
	{
		if (!$key) {
			return false;
		}
		return $this->MemcacheInstance->set($key, $value, 0, $expire);
	}

	public function get($key)
	{
		if (!$key) {
			return false;
		}
		return $this->MemcacheInstance->get($key);
	}

	public function delete($key)
	{
		if (!$key) {
			return false;
		}
		return $this->MemcacheInstance->delete($key);
	}

	public function flush()
	{
		return $this->MemcacheInstance->flush();
	}
}