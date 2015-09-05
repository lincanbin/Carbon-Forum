<?php
// http://xcache.lighttpd.net/wiki/XcacheApi
class XCache
{
	public function __construct()
	{
		return extension_loaded('xcache');
	}

	public function set($key, $value, $expire = 86400)
	{
		if (!$key) {
			return false;
		}
		return xcache_set($key, $value, $expire);
	}

	public function get($key)
	{
		if (!$key) {
			return false;
		}
		return xcache_isset($key) ? xcache_get($key) : false;
	}

	public function delete($key)
	{
		if (!$key) {
			return false;
		}
		return xcache_unset($key);
	}

	public function flush()
	{
		return xcache_clear_cache(XC_TYPE_VAR, 0);
	}
}