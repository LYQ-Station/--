<?php

/**
 * HTML实用工具
 * 
 */
class HTMLUtils
{
	static public function array_options ($array, $def_key = '', $def_label = '')
	{
		if(!is_array($array))
		{
			return ;
		}
		$ret_txt = '';
		foreach ($array as $key => $val)
		{
			$ret_txt .= '<option value="'.$key.'"';
			if ($def_key && $def_key == $key)
				$ret_txt .= ' selected="selected" ';

			if ($def_label && $def_label == $val)
				$ret_txt .= ' selected="selected" ';

			$ret_txt .= '>';
			$ret_txt .= $val;
			$ret_txt .= '</option>';
		}

		return $ret_txt;
	}
	
	static public function pick_value ($value = null, $def_value = '')
	{
		return (null === $value) ? $def_value : $value;
	}
	
	static public function pick_notempty_value($value = null)
	{
		if(is_array($value))
		{
			for($i = 0; $i < count($value); $i++)
			{
				if(!empty($value[$i]))
					return $value[$i];
			}
			return null;
		}
		return $value;
	}

	static public function pick_value2 ($condition, $val1, $val2)
	{
		return $condition ? $val1 : $val2;
	}
	
	static public function pick_arr_value ($arr, $key, $def_output = '')
	{
		return isset($arr[$key]) ? $arr[$key] : $def_output;
	}
	
	static public function cutstr($string, $length, $etc = '...', $encode = 'utf8')
	{
		if (mb_strlen($string, $encode) <= $length)
		{
			return $string;
		}
		
		return mb_substr($string, 0, $length, $encode).$etc;
	}
	
	static public function checked_flag ($val)
	{
		if (1 == intval($val) || 'Y' == $val || true === $val)
		{
			return ' checked="checked" ';
		}
		else 
		{
			return '';
		}	
	}
	static public function get_options($array, $mixkey)
	{
		if(!is_array($array))
		{
			return false;
		}
		$return = '';
		$state = array_key_exists($mixkey, $array);
	    if(true === $state)
	    {
	        return $array[$mixkey];
	    }
	    else
	    {
	        return array_shift($array);
	    }
	}
	static public function formart_date($date, $flag=1)
	{
		if(empty($date) || !is_string($date))
		{
			return false;
		}
		$arr = explode(' ', $date);
		if(1 == $flag)
		{
			return $arr[0];
		}
		elseif (2 == $flag)
		{
			return $arr[1];
		}
		elseif(3== $flag)
		{
			return $date;
		}
		else
		{
			return false;
		} 
	}
}