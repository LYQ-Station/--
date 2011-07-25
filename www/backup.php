<?php

/*
 * 备份数据库的服务器端脚本
 * 此脚本可以配合其它客户端使用，前提是使用统一的调度协议
 * 这些协议包括测试、连接、取得表、备份表，每个操作都有特定的返回值，只要适当的调度及分析返回值就能进行备份任务了
 * 
 * @author StevenLi
 * @date 2011-07-10
 */

/**
 * MySQL备份类
 */
class LYQMysqlBackup
{
	/**
	 * 备份用的临时文件所在目录，确保可删写
	 * 为了安全起见，最好单独建立一个666的目录作临时目录
	 */
	const BACKUP_DIRECTORY		= '../cache/';
	
	private $conn_id;
	
	private $server_account;
	
	static public function test_content (array $server_account)
	{
		$conn_id = @mysql_connect($server_account['host'], $server_account['username'], $server_account['password']);
		
		if (!$conn_id)
		{
			throw new Exception('无法连接数据库，可能是服务器未开启或提供的用户名或密码错误', 1001);
		}
		
		if (!@mysql_select_db($server_account['dbname']))
		{
			//$err_txt = mysql_error($conn_id);
			mysql_close($conn_id);
			throw new Exception("{$server_account['dbname']}数据库不存在", 1002);
		}
		
		mysql_close($conn_id);
	}
	
	static public function test_write_backupfile ()
	{
		$backupfile_name = self::BACKUP_DIRECTORY . 'lyqmysqlbackup_' . time();
		
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
		unlink($backupfile_name);
		
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
		$res = mysql_query($sql, $this->conn_id);
		if (!$res)
		{
			throw new Exception(mysql_error($this->conn_id), 3002);
		}
		
		$ret_arr = array();
		
		while ($arr = mysql_fetch_assoc($res))
		{
			if (empty($arr['Engine']))
			{
				continue;
			}
			
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

	public function backup ($bk_file, $table, $start_id = 0, $include = 0)
	{
		$fp = fopen($bk_file, 'a+');
		if (!$fp)
		{
			throw new Exception('无法建立备份文件', 5001);
		}
		
		if (0 == $start_id)
		{
			if (1 == $include || 2 == $include)
			{
				$sql = "SHOW CREATE TABLE `$table`";
				$res = @mysql_query($sql, $this->conn_id);

				if (!$res)
				{
					throw new Exception(mysql_error($this->conn_id), 3003);
				}

				$table_create_syntax = "\n\n#" . str_repeat('--', '10') . "\n#-- struct $table\n";
				$table_create_syntax .= "DROP TABLE IF EXISTS `$table`;\n";
				$table_create_syntax .= end(mysql_fetch_row($res)) . ";";
				fwrite($fp, $table_create_syntax);
				
				if (1 == $include)
				{
					fclose($fp);
					return 0;
				}
			}
			
			fwrite($fp, "\n\n#-- data $table\n");
		}
		
		$sql = "SELECT * FROM `$table` LIMIT $start_id, 3000";
		$res = @mysql_query($sql);
		
		if (!$res)
		{
			throw new Exception(mysql_error($this->conn_id), 3003);
		}
		
		$affected_rows = mysql_num_rows($res);
		if (0 == $affected_rows)
		{
			fclose($fp);
			return 0;
		}
		
        fwrite($fp, "\nINSERT INTO `$table` VALUES ");
        
        $tmp = null;
        
        do
        {
            if ($tmp)
            {
                fwrite($fp, $tmp);
            }
            
            
            if ($arr)
            {
                $tmp = '("' . join('","', $arr) . '"),';
            }
			
			$arr = mysql_fetch_row($res);
			if (!$arr)
			{
				$tmp = substr_replace($tmp, '', -1, 1);
                fwrite($fp, $tmp);
                break;
			}
        }
        while (true);
        
		fwrite($fp, ';');
		fclose($fp);
		
		if (3000 > mysql_num_rows($res))
		{
			return 0;
		}
		
		return $affected_rows;
	}
	
	public function send_file ($bk_file)
	{
		$filesize = sprintf("%u", filesize($bk_file));
        if (!$filesize)
        {
			throw new Exception('文件无法发送', 5001);
        }
		
		header("Content-type:application/octet-stream");
        header("Content-disposition: attachment; filename=\"".$bk_file."\"");
        header('Content-transfer-encoding: binary');
		
		$range = getenv('HTTP_RANGE');
		if ($range)
        {
			$range = explode('=', $range);
			$range = $range[1];

			header("HTTP/1.1 206 Partial Content");
			header("Date: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($url))." GMT");
			header("Accept-Ranges: bytes");
			header("Content-Length:".($filesize - $range));
			header("Content-Range: bytes ".$range.($filesize-1)."/".$filesize);
			header("Connection: close\n\n");
        }
        else
        {
			header("Content-Length:".$filesize."\n\n");
			$range = 0;
        }
		
		$buffer = '';
        $cnt = 0;       
        $handle = @fopen($bk_file, 'rb');
        if ($handle === false)
		{
			throw new Exception('文件无法发送', 5002);
        }
		
		if ($range)
		{
			fseek($handle, $range);
		}
		
        while (true)
		{
			if (feof($handle))
			{
				fclose($handle);
				return 2;
			}
			
			$buffer = fread($handle, 1024*1024);
			echo $buffer;
        }
		
        fclose($handle);
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
//		$server_account = array(
//			'host'		=> 'localhost',
//			'username'	=> 'root',
//			'password'	=> '',
//			'dbname'	=> 'mobile_cartoon'
//		);

		$server_account = array(
			'host'		=> $this->get_request('host'),
			'username'	=> $this->get_request('username'),
			'password'	=> $this->get_request('password'),
			'dbname'	=> $this->get_request('dbname')
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
		$start_id = intval($this->get_request('start_id'));
		$include = intval($this->get_request('include_type'));
		
		if (empty($table))
		{
			throw new Exception('请指定表名', 9003);
		}
		
		$rows_backuped = $backuper->backup($server_account['bk_file'], $table, $start_id, $include);
		
		if (0 === $rows_backuped)
		{
				//表备份完毕
			$this->response_xml(100, $server_account['bk_file'], null);
		}
		else
		{
				//段备份完毕
			$this->response_xml(101, $rows_backuped, null);
		}
	}
	
	public function act_download ()
	{
		$server_account = $_SESSION['server_account'];
		$backuper = new LYQMysqlBackup($server_account);
		if (2 == $backuper->send_file($server_account['bk_file']))
		{
			unlink($server_account['bk_file']);
		}
	}
}
