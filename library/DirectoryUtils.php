<?php
/**
 * 对目录操作的实用工具类
 *
 * @author Steven
 */
class DirectoryUtils
{
	const DIR_IS_EXIST		= 1;
	const NO_PERMITS		= 2;
	
	static public function create ($full_path, $mode = null, $recursive = false)
	{
		if (is_dir($full_path))
			throw new Exception('目录已经存在', self::DIR_IS_EXIST);
			
		if (!@mkdir($full_path, $mode, $recursive))
		{
			throw new Exception('没有权限创建文件夹', self::NO_PERMITS);
		}
	}
	
	static public function copy_into ($file, $des_dir, $recursive = false)
	{
		if (!is_file($file))
		{
			throw new Exception("不是文件:$file");
		}
		
		if (!$recursive)
		{
			if (!is_dir($des_dir))
			{
				throw new Exception("不是目录:$des_dir");
			}

			if (!is_writable($des_dir))
			{
				throw new Exception("目录无权写入:$des_dir");
			}
		}
		else
		{
			if (!is_dir($des_dir))
			{
				self::create($des_dir, 0777, $recursive);
			}
		}
		
		$des_dir .= '/';
		$filename = pathinfo($file, PATHINFO_BASENAME);
		
		if (!copy($file, $des_dir.$filename))
		{
			throw new Exception('移动文件时失败');
		}
		
		return $des_dir.$filename;
	}
}