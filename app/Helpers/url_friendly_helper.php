<?php
	if(!function_exists('url_friendly'))
	{
		function url_friendly($x) : string
		{
			$x = preg_replace('/[\@\.\;\" "\?\!]+/', '-', $x);
			$x = preg_replace('/[^A-Za-z0-9\-]/', '', $x);
			$x = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$x);
			$x =  urlencode(strtolower($x));
			return $x;
		}
	}
?>
