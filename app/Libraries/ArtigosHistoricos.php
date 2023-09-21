<?php

namespace App\Libraries;

use App\Models\ArtigosHistoricosModel;

class ArtigosHistoricos {
	
	public function cadastraHistorico($idArtigo, $acao, $idColaboradores)
	{
		$artigosHistoricosModel = new ArtigosHistoricosModel();
		$dados = [
			'id' => $artigosHistoricosModel->getNovaUUID(),
			'artigos_id' => $idArtigo,
			'acao' => $acao,
			'colaboradores_id' => $idColaboradores,
			'criado' => $artigosHistoricosModel->getNow()
		];
		$artigosHistoricosModel->insert($dados);
		return true;
	}

	public function buscaHistorico($idArtigo)
	{
		$artigosHistoricosModel = new ArtigosHistoricosModel();
		$dados = $artigosHistoricosModel
		->select('artigos_historicos.*, colaboradores.apelido AS apelido')
		->join('colaboradores','colaboradores.id = artigos_historicos.colaboradores_id')
		->where('artigos_id',$idArtigo)
		->orderBy('criado','ASC')
		->get()->getResultArray();
		return $dados;
	}
	
}