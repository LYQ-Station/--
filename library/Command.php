<?php

/**
 * 命令行封装类
 *
 * @author Steven
 * @date 2010-12-19
 */
class Command
{
	/**
	 * 服务器sudo默认路径
	 * @var unknown_type
	 */
	static protected $sudo			= '/usr/local/bin/sudo';
	
	/**
	 * 限制的命令,可以为数组或是','连接的字符串
	 * @var mixed
	 */
	static protected $limit_cmd = array();

	static public function exec_ex ($cmd, array &$ret = null, &$status = null)
	{
		self::filter($cmd);
		$sudo = self::$sudo;

		$cmd = escapeshellcmd("$sudo $cmd");
		return exec($cmd, $ret, $status);
	}

	static protected function escape ($cmd)
	{
		return escapeshellcmd($cmd);
	}

	/**
	 * 
	 * 命令过滤器
	 * 
	 * @param string $cmd
	 * @return bool
	 */
	static protected function filter ($cmd)
	{
		return self::escape($cmd);
		
		$fileter = self::$limit_cmd;
		
		if (is_array($fileter))
		{
			//$filter为array
			if (!in_array($cmd, $fileter))
			{
				return false;
			}
		}
		else
		{
			//$filter为string
			$cmd = "," . $cmd . ",";
			$cmd = self::escape($cmd);
			$fileter = "," . $fileter . ",";
			if (false === strpos($fileter, $cmd))
			{
				return false;
			}
		}

		return self::escape($cmd);
	}

	//-------------------------------------------- 预置命令 --------------------------------------------

	static public function ifconfig (&$ret = null, &$status = null)
	{
		$cache = Zend_Registry::get('cache');

		if (!$ifconfig_cache = $cache->load('cmd_ifconfig'))
		{
			self::exec_ex('ifconfig', $ret_arr, $status);

			if ($status)
			{
				throw new Exception('执行ifconfig失败');
			}

			$ret = join("\n", $ret_arr);
			$cache->save($ret, 'cmd_ifconfig');
		}
		else
		{
			$ret = $ifconfig_cache;
			$status = 0;
		}
	}

}