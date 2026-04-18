<?php

namespace App\Libraries;

class GerenciadorTextos
{

	public function contaPalavras($string = null)
	{
		if ($string === null) {
			return false;
		}
		helper('palavras');

		return contar_palavras_texto($string);
	}

	public function simplificaString($string, $caixaAlta = true): string
	{
		$string = trim($string);
		$string = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "/(Ç)/"), explode(" ", "a A e E i I o O u U n N c C"), $string);
		$string = preg_replace('/[^A-Za-z0-9\" "\-\_]/', '', $string);
		if ($caixaAlta) {
			$string = strtoupper($string);
		} else {
			$string = strtolower($string);
		}
		return $string;
	}

}