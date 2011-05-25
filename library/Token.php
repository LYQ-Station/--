<?php

/**
 * 令牌异常类
 * 此类仅供Token类使用（只能在本文件中使用）
 *
 */
class TokenException extends Exception
{
	const NO_INIT		= 1001;
	const NO_INFO		= 2001;
	const EXPIRE		= 2002;
}

/**
 * 用户令牌
 * 记录登录用户的基本信息和权限信息，与Token表中信息对应
 *
 * @author Steven
 * @date 2010-07-15
 */
class Token
{
	const TOKEN_TABLE = 'egt_acl_token';

	/**
	 * 单实例
	 *
	 * @var Token
	 */
	static protected $instance;
	protected $db;
	protected $sn;
	protected $data;

	private function __construct ($sn)
	{
		$this->db = Zend_Registry::get('db');

		$this->sn = $sn;
	}

	/**
	 * 创建令牌
	 *
	 * @param string $sn
	 * @return Token
	 */
	static public function create ($sn)
	{
		if (!self::$instance)
		{
			$c = __CLASS__;
			self::$instance = new $c($sn);
		}

		self::$instance->fetch();

		return self::$instance;
	}

	/**
	 * 获取token中变量值
	 *
	 * @param string
	 * @return mix
	 */
	public function __get ($var)
	{
		if (isset($this->data[$var]))
			return $this->data[$var];

		if (isset($this->$var))
			return $this->$var;

		return null;
	}

	/**
	 * 获取当前登录者的令牌实例
	 *
	 * @return Token
	 */
	static public function get_instance ()
	{
		if (self::$instance)
		{
			return self::$instance;
		}

		throw new TokenException('令牌未初始化', TokenException::NO_INIT);
	}

	/**
	 * 取登录信息（把Token实例与Token表中记录对应起来）
	 *
	 * @return bool
	 */
	private function fetch ()
	{
		$select = $this->db->select()->from(self::TOKEN_TABLE)->where('sn = ?', $this->sn)->limit(1);
		$profile = $this->db->fetchRow($select);

		if (!$profile)
		{
			return false;
		}

		if (time() - $profile['sync_time'] > 30 * 60)
		{
			return false;
		}

		$this->data['uid'] = $profile['uid'];
		$this->data['usn'] = $profile['usn'];
		$this->data['uname'] = $profile['uname'];
		$this->data['login_time'] = $profile['login_time'];
		$this->data['sync_time'] = $profile['sync_time'];
		$this->data['login_ip'] = $profile['login'];

		return true;
	}

	private function _getinfo ()
	{
		$select = $this->db->select();
		$select->from(self::TOKEN_TABLE)->where('sn = ?', $this->sn)->limit(1);
		$profile = $this->db->fetchRow($select);
		$this->info = unserialize($profile['info']);
		return $this->info;
	}

	/**
	 * 注册登录信息
	 *
	 * @param array $fields 包含登录信息的数组
	 */
	public function register (array $fields = null)
	{
		$now = time();

		$db_fields = array(
			'sn' => $this->sn,
			'sync_time' => $now,
		);

		if (null == $fields)
		{
			$fields = array();
		}

		$db_fields = array_merge($db_fields, $fields);

		$select = $this->db->select();
		$select->from(self::TOKEN_TABLE, 'sn')->where('sn = ?', $this->sn)->limit(1);
		$profile = $this->db->fetchRow($select);

		if (!empty($profile))
		{
			$where = $this->db->quoteInto('sn=?', $this->sn);
			$this->db->update(self::TOKEN_TABLE, $db_fields, $where);
		}
		else
		{
			$db_fields['login_time'] = $now;
			$db_fields['login_ip'] = NetUtils::get_client_ip_long();

			$this->db->insert(self::TOKEN_TABLE, $db_fields);
		}
	}

	/**
	 * 增加TOKEN--info信息
	 *
	 * @param mix $mix
	 * @param str $value
	 * 
	 */
	public function setinfo ($mix, $value="")
	{
		$list = $row = array();
		$list = $this->_getinfo();
		if (is_array($mix))
		{
			foreach ($mix as $key => $val)
			{
				$list[$key] = $val;
			}
		}
		elseif (is_string($mix))
		{
			$list[$mix] = $value;
		}
		$set = serialize($list);
		$row = array('info' => $set);
		$where = $this->db->quoteInto('sn = ?', $this->sn);
		$this->db->update(self::TOKEN_TABLE, $row, $where);
	}

	/**
	 * 获得TOKEN--info信息
	 * @param <string or array> $mix
	 * @return <string or array>
	 */
	public function getinfo ($mix)
	{
		$list = array();
		$list = $this->_getinfo();
		if (is_array($mix))
		{
			foreach ($mix as $key => $val)
			{
				$row[$key] = $list[$key];
			}
			return $row;
		}
		elseif (is_string($mix))
		{
			if (!empty($list[$mix]))
			{
				return $list[$mix];
			}
		}
	}

	/**
	 * 删除token
	 * @param <array or string> $mix
	 */
	public function deleteinfo ($mix)
	{
		$list = array();
		$list = $this->_getinfo();
		if (is_array($mix))
		{
			foreach ($mix as $key => $val)
			{
				unset($list[$key]);
			}
		}
		if (is_string($mix) && !empty($mix))
		{
			unset($list[$mix]);
		}

		$set = serialize($list);
		$row = array('info' => $set);
		$where = $this->db->quoteInto('sn = ?', $this->sn);
		$this->db->update(self::TOKEN_TABLE, $row, $where);
	}

	/**
	 * 清除登录信息
	 *
	 */
	public function destroy ()
	{
		$where = $this->db->quoteInto('sn = ?', $this->sn);
		$this->db->delete(self::TOKEN_TABLE, $where);
		$this->sn = null;
		$this->data = null;
	}

	/**
	 * 是否登录状态
	 *
	 * @return bool
	 */
	public function is_logined ()
	{
		return '' != $this->usn;
	}

	/**
	 * 是否过期
	 *
	 * @return bool
	 */
	public function is_expire ()
	{
		return (time() - $this->sync_time > 30 * 60);
	}

	/**
	 * 获取用所有权限
	 *
	 */
	public function get_permit ()
	{
		if (is_array($this->haspermit))
		{
			return!empty($this->haspermit);
		}
		else
		{
			throw new Exception(' property  must be array!');
		}
	}

	/**
	 * 判断是否有某个权限
	 *
	 * @param mix $permit_code 某个权限代码
	 * @return bool
	 */
	public function is_allow ($permits_code)
	{
		return Acl::test_permit($this->usn, $permits_code);
	}

}