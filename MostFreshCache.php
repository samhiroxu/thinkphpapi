<?php
class MostFreshCache { 
	private $_cache = null;
	
	private $_expire = 0;
	private $_flag = 0;

	private $_aliyun = false;
	function __construct($host, $port, $user, $password) {
		// Aliyun ACE DEFAULT
		if(class_exists('Alibaba')) {
			$config = array(
			    'host'  => $host,
			    'port'  => $port,
			    'username' => $user,
			    'password' => $password,
			);
			$this->_cache = \Alibaba::Cache($config);
			$this->_aliyun = true;
		}
		// Local
    	else $this->_cache = memcache_connect($host, $port);
   	}

   	public function setDefault($flag, $expire) {
   		$this->_expire = $expire;
   		$this->_flag = $flag;
   	}

	public function set($key, $value, $expire, $flag) {
		if($flag == null) $flag = $this->_flag;
		if($expire == null) $expire = $this->_expire;
		if($this->_aliyun)
			return $this->_cache->set($key, $value, $expire, $flag);
		else
			return $this->_cache->set($key, $value, $flag, $expire);
	}

	public function get($key) {
		return $this->_cache->get($key);
	}

	public function flush() {
		$cache = $this->_cache;
		if(method_exists($cache, 'flush'))
			$cache->flush();
	}
	public function delete($key) {
		$cache = $this->_cache;
		if(method_exists($cache, 'flush'))
			$r=$cache->delete($key);
		return $r;
	}
}