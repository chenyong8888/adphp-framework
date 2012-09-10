<?php
class StringTools{

	function checkMoney($C_Money)
	{
		if (!ereg("^[0-9][.][0-9]$", $C_Money)) return false;
		return true;
	}	

	function checkEmail($C_mailaddr)
	{
		if (!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*$", $C_mailaddr)){
			return false;
		}
		return true;
	}

	function checkWeburl($C_weburl)
	{
		if (!ereg("^http://[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$", $C_weburl)){
			return false;
		}
		return true;
	}

	function checkLengthBetween($C_cahr, $I_len1, $I_len2=100)
	{
		$C_cahr = trim($C_cahr);
		if (strlen($C_cahr) < $I_len1) return false;
		if (strlen($C_cahr) > $I_len2) return false;
		return true;
	}

	function checkUser($C_user,$I_len1=4, $I_len2=20)
	{
		if (!checkLengthBetween($C_user, $I_len1, $I_len2)) return false;
		if (!ereg("^[_a-zA-Z0-9]*$", $C_user)) return false;
		return true;
	}

	function checkPassword($C_passwd,$I_len1=4, $I_len2=20)
	{
		if (!checkLengthBetween($C_passwd, $I_len1, $I_len2)) return false;
		if (!ereg("^[_a-zA-Z0-9]*$", $C_passwd)) return false;
		return true;
	}
	
	function codeToHtml($content)
	{
		$content = str_replace("\n","<br/>",str_replace(" ","&nbsp;",$content));
		return $content;
	}

	function htmlToCode($content)
	{
		$content = str_replace("<br/>","\n",str_replace("&nbsp;"," ",$content));
		return $content;
	}
	
	function cutBadWords($str,$badWordsArray)
	{
		$temp = $str;
		for($i=0;$i<count($badWordsArray);$i++)
		{
			$temp = str_replace($badWordsArray[$i],"...",$temp);
		}
		$temp = $this->htmlToStr($temp);
		return $temp;
	}	

	function cut_str($string, $sublen, $start=0, $endstr='...', $code = 'utf-8')
	{
		if($code == 'utf-8')
		{
			$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string);
			if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
			return join('', array_slice($t_string[0], $start, $sublen));
		}else{
			$start = $start*2;
			$sublen = $sublen*2;
			$strlen = strlen($string);
			$tmpstr = '';
			for($i=0; $i< $strlen; $i++)
			{
				if($i>=$start && $i< ($start+$sublen))
				{
					if(ord(substr($string, $i, 1))>129)
					{
						$tmpstr.= substr($string, $i, 2);
					}
					else
					{
						$tmpstr.= substr($string, $i, 1);
					}
				}
				if(ord(substr($string, $i, 1))>129) $i++;
			}
			if(strlen($tmpstr)< $strlen ) $tmpstr.=$tmpstr.= $endstr;
			return $tmpstr;
		}
	}	

	function timer(){
		$_time =   explode("   ",microtime());
		$_usec =   (double)$_time[0];
		$_sec  =   (double)$_time[1];
		return   $_timer   =   $_usec   +   $_sec;
	}
	
	function formatStringTime($strTime,$format){
		$dt_element=explode(" ",$strTime);
		$date_element=explode("-",$dt_element[0]);
		$time_element=explode(":",$dt_element[1]);
		$year = $date_element[0];
		$month = $date_element[1];
		$day = $date_element[2];
		$h = $time_element[0];
		$i = $time_element[1];
		$s = $time_element[2];
		$format = strtolower($format);
		$format = str_replace('y',$year,$format);
		$format = str_replace('m',$month,$format);
		$format = str_replace('d',$day,$format);
		$format = str_replace('h',$h,$format);
		$format = str_replace('i',$i,$format);
		$format = str_replace('s',$s,$format);
		return $format;
	}
	
	function xml2array($contents, $get_attributes=1, $priority = 'tag') {
		if(!$contents) return array();
		if(!function_exists('xml_parser_create')) {			
			return array();
		}		
		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); 
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);
		if(!$xml_values) return;		
		$xml_array = array();
		$parents = array();
		$opened_tags = array();
		$arr = array();
		$current = &$xml_array; 		
		$repeated_tag_index = array();
		foreach($xml_values as $data) {
			unset($attributes,$value);			
			extract($data);
			$result = array();
			$attributes_data = array();
			if(isset($value)) {
				if($priority == 'tag') $result = $value;
				else $result['value'] = $value; 
			}
			
			if(isset($attributes) and $get_attributes) {
				foreach($attributes as $attr => $val) {
					if($priority == 'tag') $attributes_data[$attr] = $val;
					else $result['attr'][$attr] = $val; 
				}
			}
			
			if($type == "open") {
				$parent[$level-1] = &$current;
				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { 
					$current[$tag] = $result;
					if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
					$repeated_tag_index[$tag.'_'.$level] = 1;
					$current = &$current[$tag];
				} else {
					if(isset($current[$tag][0])) {
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
						$repeated_tag_index[$tag.'_'.$level]++;
					} else {
						$current[$tag] = array($current[$tag],$result);
						$repeated_tag_index[$tag.'_'.$level] = 2;

						if(isset($current[$tag.'_attr'])){
							$current[$tag]['0_attr'] = $current[$tag.'_attr'];
							unset($current[$tag.'_attr']);
						}

					}
					$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
					$current = &$current[$tag][$last_item_index];
				}

			} elseif($type == "complete") {				
				if(!isset($current[$tag])) {
					$current[$tag] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 1;
					if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

				} else {
					if(isset($current[$tag][0]) and is_array($current[$tag])) {
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

						if($priority == 'tag' and $get_attributes and $attributes_data) {
							$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag.'_'.$level]++;

					} else {
						$current[$tag] = array($current[$tag],$result);
						$repeated_tag_index[$tag.'_'.$level] = 1;
						if($priority == 'tag' and $get_attributes) {
							if(isset($current[$tag.'_attr'])) {
								$current[$tag]['0_attr'] = $current[$tag.'_attr'];
								unset($current[$tag.'_attr']);
							}

							if($attributes_data) {
								$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag.'_'.$level]++; 
					}
				}
			} elseif($type == 'close') {
				$current = &$parent[$level-1];
			}
		}
		return($xml_array);
	}

}
























