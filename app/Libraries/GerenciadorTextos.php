<?php

namespace App\Libraries;

class GerenciadorTextos
{

	public function contaPalavras($string = null)
	{
		$quantidade = 0;
		if($string===null) {
			return false;
		}
		$quantidade = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç|Ç)/"),explode(" ","a A e E i I o O u U n N c"),$string));
		return $quantidade;
	}

	
}