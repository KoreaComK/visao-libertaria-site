<?php

namespace App\Libraries;

use App\Models\ArtigosModel;

class ArtigosMarcacao
{

	public function marcarArtigo($idArtigo, $idColaboradores, $desmarcarArtigosMarcados = true)
	{
		$artigosModel = new ArtigosModel();
		$artigo = $artigosModel->find($idArtigo);
		if ($artigo['marcado_colaboradores_id'] !== null && $artigo['marcado_colaboradores_id'] != $idColaboradores) {
			return false;
		}
		if ($desmarcarArtigosMarcados) {
			$idArtigosLista = $this->buscaArtigosMarcadosUsuario($idColaboradores);
			if ($idArtigosLista !== false) {
				foreach ($idArtigosLista as $idArtigos) {
					$dados = [
						'marcado_colaboradores_id' => null,
						'marcado' => null,
					];
					$artigosModel->update($idArtigos, $dados);
				}
			}
		}
		$dados = [
			'marcado_colaboradores_id' => $idColaboradores,
			'marcado' => $artigosModel->getNow(),
		];
		return $artigosModel->update($idArtigo, $dados);
	}

	public function desmarcarArtigo($idArtigo)
	{
		$artigosModel = new ArtigosModel();
		$dados = [
			'marcado_colaboradores_id' => null,
			'marcado' => null,
		];
		return $artigosModel->update($idArtigo, $dados);
	}

	private function buscaArtigosMarcadosUsuario($idColaboradores)
	{
		$artigosHistoricosModel = new ArtigosModel();
		$dados = $artigosHistoricosModel
			->select('artigos.*')
			->where('marcado_colaboradores_id', $idColaboradores)
			->get()->getResultArray();
		$retorno = array();
		if ($dados === null || empty($dados)) {
			return false;
		} else {
			foreach ($dados as $dado) {
				$retorno[] = $dado['id'];
			}
		}
		return $retorno;
	}

}