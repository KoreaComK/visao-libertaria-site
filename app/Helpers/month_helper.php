<?php
	if(!function_exists('month_helper'))
	{
		function month_helper($mes=null,$tamanho=false,$ucfirst=true) : string
		{
			$traducao = '';
			switch (strtolower($mes)) {
				case 'january': $traducao='janeiro';break;
				case 'february': $traducao='fevereiro';break;
				case 'march': $traducao='março';break;
				case 'april': $traducao='abril';break;
				case 'may': $traducao='maio';break;
				case 'june': $traducao='junho';break;
				case 'july': $traducao='julho';break;
				case 'august': $traducao='agosto';break;
				case 'september': $traducao='setembro';break;
				case 'october': $traducao='outubro';break;
				case 'november': $traducao='novembro';break;
				case 'december': $traducao='dezembro';break;
				default: $traducao='';break;
			}
			if($tamanho!==false){
				$traducao = substr($traducao,0,$tamanho);
			}
			return ($ucfirst)?(ucfirst($traducao)):($traducao);
			
		}
	}
?>