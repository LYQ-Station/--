<?php

/*
 * 备份数据库的服务器端脚本
 * 此脚本可以配合其它客户端使用，前提是使用统一的调度协议
 * 这些协议包括测试、连接、取得表、备份表，每个操作都有特定的返回值，只要适当的调度及分析返回值就能进行备份任务了
 * 
 * @author Steven
 * @date 2011-7-10
 */

/**
 * MySQL备份类
 */
class LYQMysqlBackup
{
	private $conn_id;
	
	private $server_account;
	
	static public function test_content (array $server_account)
	{
		$conn_id = @mysql_connect($server_account['host'], $server_account['username'], $server_account['password']);
		
		if (!$conn_id)
		{
			throw new Exception(mysql_error(), 1001);
		}
		
		if (!@mysql_select_db($server_account['dbname']))
		{
			mysql_close($conn_id);
			throw new Exception(mysql_error(), 1002);
		}
		
		mysql_close($conn_id);
	}
	
	static public function test_write_backupfile ()
	{
		$backupfile_name = '../cache/lyqmysqlbackup_'.time();
		
		if (is_file($backupfile_name))
		{
			throw new Exception('备份文件已经存在，稍候再试', 2001);
		}
		
		$fp = @fopen($backupfile_name, 'w');

		if (!$fp)
		{
			throw new Exception('无法建立备份文件', 2002);
		}

		fclose($fp);
		
		return $backupfile_name;
	}

	//---------------------------------------------------------------------------------------------------------
	
	public function __construct (array $server_account)
	{
		LYQMysqlBackup::test_content($server_account);
		
		if (empty($server_account['dbname']))
		{
			throw new Exception('务必指定要备份的数据库名', 1001);
		}
		
		if (empty($server_account['host']))
		{
			$server_account['host'] = 'localhost';
		}
		
		if (!isset($server_account['username']))
		{
			$server_account['username'] = '';
		}
		
		if (!isset($server_account['password']))
		{
			$server_account['password'] = '';
		}
		
		$this->server_account = $server_account;
		
		$this->conn_id = mysql_connect($server_account['host'], $server_account['username'], $server_account['password']);
		mysql_select_db($server_account['dbname'], $this->conn_id);
		mysql_query('SET NAMES UTF8');
	}
	
	public function __destruct ()
	{
		mysql_close($this->conn_id);
	}

	public function get_tables ()
	{
		$sql = 'SHOW TABLE STATUS';
		$res = mysql_query($sql);
		if (!$res)
		{
			throw new Exception(mysql_error(), 3002);
		}
		
		$ret_arr = array();
		
		while ($arr = mysql_fetch_assoc($res))
		{
			$ret_arr[] = array(
				'Name'		=> $arr['Name'],
				'Engine'	=> $arr['Engine'],
				'Rows'		=> $arr['Rows'],
				'Comment'	=> $arr['Comment'],
				'CreateTime'=> $arr['Create_time']
			);
		}
		
		return $ret_arr;
	}

	public function backup ($bk_file, $table, $start_id = 0)
	{
		$fp = fopen($bk_file, 'w+');
		if (!$fp)
		{
			throw new Exception('无法建立备份文件', 5001);
		}
		
		if (0 == $start_id)
		{
			$sql = "SHOW CREATE TABLE `$table`";
			$res = @mysql_query($sql);
			
			if (!$res)
			{
				throw new Exception(mysql_error(), 3003);
			}
			
			$table_create_syntax = str_repeat('--', '10') . "\n-- struct $table\n";
			$table_create_syntax .= end(mysql_fetch_row($res)) . ";\n\n-- data\n";
			fwrite($fp, $table_create_syntax);
		}
		
		$sql = "SELECT * FROM `$table` LIMIT $start_id, 3000";
		$res = @mysql_query($sql);
		
		if (!$res)
		{
			throw new Exception(mysql_error(), 3003);
		}
		
		$affected_rows = mysql_num_rows($res);
		if (0 == $affected_rows)
		{
			fclose($fp);
			return 0;
		}
		
        fwrite($fp, "INSER INTO `$table` VALUES ");
        
        $tmp = null;
        
        do
        {
            if ($tmp)
            {
                fwrite($fp, $tmp);
            }
            
            $arr = mysql_fetch_array($res);
            if ($arr)
            {
                $tmp = '("' . join('","', $arr) . '"),';
            }
            else
            {
                $tmp = substr_replace($tmp, '', -1, 1);
                fwrite($fp, $tmp);
                break;
            }
        }
        while (true);
        
		$tmp .= ';';
		
		fwrite($fp, $tmp);
		fclose($fp);
		
		if (3000 > mysql_num_rows($res))
		{
			return 0;
		}
		
		return 1;
	}
}

/**
 * 处理器基类
 */
class LYQProcesser
{
	public function act_noneaction ()
	{
		throw new Exception('无效的动作', 9001);
	}
	
	public function run ()
	{
		try
		{
			$this->router();
		}
		catch (Exception $e)
		{
			$this->response_xml($e->getCode(), $e->getMessage(), null);
		}
	}
	
	protected function router ()
	{
		$action = $this->get_request('action');
		
		if (empty($action))
		{
			$action = 'noneaction';
		}
		
		$action = "act_$action";
		
		if (!method_exists($this, $action))
		{
			$action = 'act_noneaction';
		}
		
		$this->init($action);
		$this->$action();
	}
	
	protected function init ($action)
	{
		
	}

	protected function get_request ($key)
	{
		return $_REQUEST[$key];
	}
	
	protected function response_xml ($err_no, $err_txt, $data)
	{
		header('content-type: text/xml; charset="utf-8"');
		
		echo "<response errno=\"$err_no\" errtext=\"$err_txt\">";
		echo $this->xml_encode($data);
		echo '</response>';
	}
	
	protected function response ($data)
	{
		$this->response_xml(null, null, $data);
	}

	protected function xml_encode ($data)
	{
		static $ret_xml = '';
		
		if (null === $data)
		{
			return '';
		}
		
		if (!is_array($data))
		{
			$ret_xml .= $data;
			return $ret_xml;
		}
		else if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				$ret_xml .= is_int($key) ? '<data>' : "<$key>";
				$this->xml_encode($val);
				$ret_xml .= is_int($key) ? '</data>' : "</$key>";
			}
			
			return $ret_xml;
		}
		
		return null;
	}
}

$processer = new DBBackupProcesser();
$processer->run();

/**
 * 备份用处理器
 */
class DBBackupProcesser extends LYQProcesser
{
	protected function init ($action)
	{
		if ('act_createsession' != $action)
		{
			if ('' == $_REQUEST['PHPSESSID'])
			{
				throw new Exception('未初始化令牌', 9002);
			}
			else
			{
				session_id($_REQUEST['PHPSESSID']);
			}
		}
		
		session_start();
	}

    public function act_createsession ()
	{
		session_start();
		$this->response(session_id());
	}
    
	public function act_test ()
	{
		$server_account = array(
			'host'		=> 'localhost',
			'username'	=> 'root',
			'password'	=> '',
			'dbname'	=> 'mobile_cartoon'
		);
		
		LYQMysqlBackup::test_content($server_account);
		$server_account['bk_file'] = LYQMysqlBackup::test_write_backupfile();
		
		$_SESSION['server_account'] = $server_account;
		
		$this->response('ok');
	}
	
	public function act_gettables ()
	{
		$server_account = $_SESSION['server_account'];
		$backuper = new LYQMysqlBackup($server_account);
		
		$tables = $backuper->get_tables();
		$this->response($tables);
	}
	
	public function act_backup ()
	{
		$server_account = $_SESSION['server_account'];
		$backuper = new LYQMysqlBackup($server_account);
		
		$table = $this->get_request('table');
		$start_id = intval($this->get_request('startid'));
		
		if (empty($table))
		{
			throw new Exception('请指定表名');
		}
		
		if (0 === $backuper->backup($server_account['bk_file'], $table, $start_id))
		{
				//表备份完毕
			$this->response_xml(100, null, null);
		}
		else
		{
				//段备份完毕
			$this->response_xml(101, null, null);
		}
	}
}