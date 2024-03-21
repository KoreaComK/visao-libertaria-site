<?php

namespace App\Libraries;

use App\Models\ColaboradoresHistoricosModel;

class ColaboradoresHistoricos {
	
	public function cadastraHistorico($idUsuario, $acao, $objeto, $idObjeto)
	{
		$colaboradoresHistoricosModel = new ColaboradoresHistoricosModel();
		$dados = [
			'id' => $colaboradoresHistoricosModel->getNovaUUID(),
			'colaboradores_id' => $idUsuario,
			'acao' => $acao,
			'objeto' => $objeto,
			'objeto_id' => $idObjeto,
			'criado' => $colaboradoresHistoricosModel->getNow()
		];
		$colaboradoresHistoricosModel->insert($dados);
		return true;
	}
	
}