<?php

namespace App\Libraries;

use App\Controllers\BaseController;

class RetornoPadrao extends BaseController
{

	public function retorno($status = false, $mensagem = NULL, $json = false)
	{
		$retorno = array();
		$retorno['status'] = $status;
		$retorno['mensagem'] = $mensagem;
		if ($json) {
			return json_encode($retorno);
		} else {
			return $retorno;
		}
	}

	public function montaStringErro($erros)
	{
		$string_erros = '';
		foreach ($erros as $erro) {
			$string_erros .= $erro . "<br/>";
		}
		return $string_erros;
	}
}
