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
 * @date 2011-05-27
 */
class Token
{
	/**
	 * 单实例
	 *
	 * @var Token
	 */
	static protected $instance;
    
	protected $db;
	protected $sn;
    protected $data     = array();

    private function __construct ($sn)
	{
		$this->db = GlobalFactory::get_db();

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
		$select = $this->db->select()->from(DBTables::TOKEN)->where('sn = ?', $this->sn)->limit(1);
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
		$this->data['uname'] = $profile['uname'];
		$this->data['login_time'] = $profile['login_time'];
		$this->data['sync_time'] = $profile['sync_time'];
		$this->data['login_ip'] = $profile['login_ip'];

		return true;
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

		$select = $this->db->select()->from(DBTables::TOKEN, 'sn')->where('sn = ?', $this->sn)->limit(1);
		$profile = $this->db->fetchRow($select);

		if (!empty($profile))
		{
			$where = $this->db->quoteInto('sn=?', $this->sn);
			$this->db->update(DBTables::TOKEN, $db_fields, $where);
		}
		else
		{
			$db_fields['login_time'] = $now;
			$db_fields['login_ip'] = NetUtils::get_client_ip_long();

			$this->db->insert(DBTables::TOKEN, $db_fields);
		}
	}

	/**
	 * 清除登录信息
	 *
	 */
	public function destroy ()
	{
		$where = $this->db->quoteInto('sn = ?', $this->sn);
		$this->db->delete(DBTables::TOKEN, $where);
		$this->sn = null;
	}

	/**
	 * 是否登录状态
	 *
	 * @return bool
	 */
	public function is_logined ()
	{
		return '' != $this->uid;
	}

	/**
	 * 是否过期
	 *
	 * @return bool
	 */
	public function is_expire ()
	{
		return ((time() - $this->sync_time) > 30 * 60);
	}
    
    /**
     * 是否为同一IP
     *
     * @return bool
     */
    public function is_same_ip ()
    {
        return NetUtils::get_client_ip_long() == $this->login_ip;
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