<?php

/**
 * AJAX辅助工具类
 *
 */
class AjaxUtils
{
	static public function xml_response ($err_no = 0, $err_txt = '', $content = null)
	{
		header('content-type: text/xml');
		echo '<results errno="' . $err_no . '" errtxt="' . $err_txt . '">' . $content . '</results>';
	}

	static public function xml ($content)
	{
		self::xml_response(0, 0, $content);
	}

	static public function xml_err ($err_no, $err_txt)
	{
		self::xml_response($err_no, $err_txt);
	}

	static public function json_response ($err_no = '', $err_txt = '', $content = '', $ext_property = null)
	{
		$obj = new stdClass();

		if (!empty($ext_property) && is_array($ext_property))
		{
			foreach ($ext_property as $key => $val)
			{
				$obj->$key = $val;
			}
		}
		
		$obj->err_no = $err_no;
		$obj->err_text = $err_txt;
		$obj->content = $content;

		echo json_encode($obj);
	}

	static public function json ($content)
	{
		self::json_response('', '', $content);
	}

	static public function json_err ($err_no, $err_txt)
	{
		self::json_response($err_no, $err_txt);
	}
}