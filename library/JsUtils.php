<?php

/**
 * JS实用工具
 * 
 * @author Steven
 */
class JsUtils
{
	static protected $script_sections	= array();

	static public function start ()
	{
		echo '<script type="text/javascript">';
	}
	
	static public function end ()
	{
		echo '</script>';
	}
	
	static public function alert ($msg)
	{
		echo 'self.alert("'.$msg.'");';
	}
	
	static public function history ($no)
	{
		echo "self.history.go($no);";
	}
	
	static public function back ()
	{
		echo 'self.history.back();';
	}

	static public function rediect ($url)
	{
		echo "self.location.replace('$url');";
	}
	
	static public function close ()
	{
		echo 'self.close();';
	}
	
	static public function ob_start ()
	{
		ob_start();
	}
	
	static public function ob_end ()
	{
		$script = ob_get_clean();
		
		array_push(self::$script_sections, $script);
	}
	
	static public function ob_flush ()
	{
		if (empty(self::$script_sections))
		{
			return '';
		}
		
		while ($script = array_pop(self::$script_sections))
		{
			echo $script;
		}
	}
}