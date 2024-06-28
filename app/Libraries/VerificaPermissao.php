<?php

namespace App\Libraries;

class VerificaPermissao
{

	public function PermiteAcesso($codigoPermissao = null, $url = null, $isValidar = false)
	{
		$url = ($url === null) ? (base_url() . 'site/logout?url='.current_url()) : ($url);
		if ($codigoPermissao == null) {
			header("location: " . $url);
		}
		$session = \Config\Services::session();
		$session->start();
		if ($session->has('colaboradores')) {
			$permissoes = $session->get('colaboradores');
			$permissoes = $permissoes['permissoes'];
			if (is_array($codigoPermissao))
			{
				foreach($codigoPermissao as $codPer){
					if (in_array((string) $codPer, $permissoes)) {
						return true;
					}	
				}
			}else {
				if (in_array((string) $codigoPermissao, $permissoes)) {
					return true;
				}
			}
			if($isValidar) {
				return false;
			}
			header("location: " . $url);
			die();
		}
		header("location: " . $url);
		die();
	}

}