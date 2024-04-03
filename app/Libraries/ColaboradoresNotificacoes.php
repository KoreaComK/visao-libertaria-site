<?php

namespace App\Libraries;

use App\Models\ColaboradoresHistoricosModel;
use App\Models\ArtigosModel;
use App\Models\ArtigosComentariosModel;
use App\Models\ColaboradoresNotificacoesModel;
use App\Models\PautasModel;
use App\Models\PautasComentariosModel;

class ColaboradoresNotificacoes {

	protected $colaboradoresModel;
	protected $artigosModel;
	protected $pautasModel;
	protected $artigosComentariosModel;
	protected $pautasComentariosModel;
	protected $colaboradoresNotificacoesModel;

	function __construct()
	{
		$this->colaboradoresModel = new ColaboradoresHistoricosModel();
		$this->artigosModel = new ArtigosModel();
		$this->pautasModel = new PautasModel();
		$this->artigosComentariosModel = new ArtigosComentariosModel();
		$this->pautasComentariosModel = new PautasComentariosModel();
		$this->colaboradoresNotificacoesModel = new ColaboradoresNotificacoesModel();
	}

	public function cadastraNotificacao($sujeito, $acao, $objeto, $notificacao, $idObjeto, $comentario = false)
	{
		$colaboradores_id = $this->buscaDestinatariosNotificacoes($objeto, $idObjeto, $comentario);
		if($colaboradores_id === false) {
			return false;
		}
		if(empty($colaboradores_id) || $colaboradores_id == null) {
			return null;
		}

		if($objeto == 'pautas') {
			$pauta = $this->pautasModel->find($idObjeto);
			$notificacao.=' {link}'.$pauta['titulo'].'{/link}';
		} elseif($objeto == 'artigos') {
			$artigo = $artigo = $this->artigosModel->withDeleted()->find($idObjeto);
			$notificacao.=' {link}'.$artigo['titulo'].'{/link}';
		}

		foreach($colaboradores_id as $cid) {
			$dados = [
				'id' => $this->colaboradoresNotificacoesModel->getNovaUUID(),
				'sujeito_colaboradores_id' => $sujeito,
				'acao' => $acao,
				'objeto' => $objeto,
				'notificacao' => $notificacao,
				'id_objeto' => $idObjeto,
				'colaboradores_id' => $cid,
				'criado' => $this->colaboradoresNotificacoesModel->getNow()
			];
			$this->colaboradoresNotificacoesModel->insert($dados);
		}
		return true;
	}
	
	private function buscaDestinatariosNotificacoes($objeto = false, $idObjeto = false, $comentario = false) {
		if($objeto === false){ return false; }
		if($idObjeto === false){ return false; }

		$destinatarios = array();
		if($objeto == 'pautas') {
			$pauta = $this->pautasModel->find($idObjeto);
			$destinatarios[] = $pauta['colaboradores_id'];
			if($comentario === true) {
				$this->pautasComentariosModel->where("pautas_id",$idObjeto);
				$this->pautasComentariosModel->where("excluido",null);
				$pautasComentarios = $this->pautasComentariosModel->get()->getResultArray();
				if($pautasComentarios !== false && !empty($pautasComentarios)) {
					foreach($pautasComentarios as $pc) {
						if(!in_array($pc['colaboradores_id'],$destinatarios)) {
							$destinatarios[] = $pc['colaboradores_id'];
						}
					}
				}
			}
		}
		if($objeto == 'artigos') {
			$artigo = $this->artigosModel->withDeleted()->find($idObjeto);
			if($artigo['sugerido_colaboradores_id'] !== NULL && $artigo['sugerido_colaboradores_id'] !== '') {
				if(!in_array($artigo['sugerido_colaboradores_id'],$destinatarios)) {
					$destinatarios[] = $artigo['sugerido_colaboradores_id'];
				}
			}
			if($artigo['escrito_colaboradores_id'] !== NULL && $artigo['sugerido_colaboradores_id'] !== '') {
				if(!in_array($artigo['escrito_colaboradores_id'],$destinatarios)) {
					$destinatarios[] = $artigo['escrito_colaboradores_id'];
				}
			}
			if($artigo['revisado_colaboradores_id'] !== NULL && $artigo['sugerido_colaboradores_id'] !== '') {
				if(!in_array($artigo['revisado_colaboradores_id'],$destinatarios)) {
					$destinatarios[] = $artigo['revisado_colaboradores_id'];
				}
			}
			if($artigo['narrado_colaboradores_id'] !== NULL && $artigo['sugerido_colaboradores_id'] !== '') {
				if(!in_array($artigo['narrado_colaboradores_id'],$destinatarios)) {
					$destinatarios[] = $artigo['narrado_colaboradores_id'];
				}
			}
			if($artigo['produzido_colaboradores_id'] !== NULL && $artigo['sugerido_colaboradores_id'] !== '') {
				if(!in_array($artigo['produzido_colaboradores_id'],$destinatarios)) {
					$destinatarios[] = $artigo['produzido_colaboradores_id'];
				}
			}
			if($artigo['marcado_colaboradores_id'] !== NULL && $artigo['sugerido_colaboradores_id'] !== '') {
				if(!in_array($artigo['marcado_colaboradores_id'],$destinatarios)) {
					$destinatarios[] = $artigo['marcado_colaboradores_id'];
				}
			}
			if($comentario === true) {
				$this->artigosComentariosModel->where("artigos_id",$idObjeto);
				$this->artigosComentariosModel->where("excluido",null);
				$artigosComentarios = $this->artigosComentariosModel->get()->getResultArray();
				if($artigosComentarios !== false && !empty($artigosComentarios)) {
					foreach($artigosComentarios as $ac) {
						if(!in_array($ac['colaboradores_id'],$destinatarios)) {
							$destinatarios[] = $ac['colaboradores_id'];
						}
					}
				}
			}
		}
		return $destinatarios;
	}
	
}