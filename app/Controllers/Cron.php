<?php

namespace App\Controllers;

use Config\App;
use CodeIgniter\I18n\Time;
use App\Libraries\ArtigosHistoricos;

class Cron extends BaseController
{
	/*HOME PAGE*/
	public function index($hash = NULL)
	{
		/*VERIFICANDO SE O CRON ESTÁ SENDO PROCESSADO PELO SISTEMA E NÃO POR UM USUÁRIO QUALQUER*/
		if($hash === null) {
			return redirect()->to(base_url());
		}
		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$hash_base = $configuracaoModel->find('cron_hash')['config_valor'];
		if($hash != $hash_base) {
			return redirect()->to(base_url());
		}

		/*PARTE RELACIONADA A LIMPEZA DAS PAUTAS*/
		$cronPautas = $configuracaoModel->find('cron_pautas_status_delete')['config_valor'];
		if($cronPautas == '1') {
			$cronDataPautas = $configuracaoModel->find('cron_pautas_data_delete')['config_valor'];
			
			$time = new Time('-'.$cronDataPautas);
			$pautasModel = new \App\Models\PautasModel();
			$pautasModel->where("criado <= '".$time->toDateTimeString()."'");
			$pautasModel->where('reservado',null);
			$pautasModel->where('tag_fechamento',null);
			$pautasModel->where('redator_colaboradores_id',null);
			$pautasModel->withDeleted();
			$pautas = $pautasModel->get()->getResultArray();
			foreach($pautas as $pauta) {
				$pautasComentariosModel = new \App\Models\PautasComentariosModel();
				$pautasComentariosModel->where("pautas_id",$pauta['id']);
				$comentarios = $pautasComentariosModel->get()->getResultArray();
				foreach($comentarios as $comentario) {
					$pautasComentariosModel->delete($comentario['id'],true);
				}
				$pautasModel->delete($pauta['id'],true);
			}

			/*EXCLUSÃO SOFT DO ARTIGO CRIADO ATRAVÉS DE PAUTA DE REDATOR*/
			$pautasModel = new \App\Models\PautasModel();
			$pautasModel->where('redator_colaboradores_id IS NOT NULL');
			$pautas = $pautasModel->get()->getResultArray();
			foreach($pautas as $pauta) {
				$artigosModel = new \App\Models\ArtigosModel();
				$artigosModel->where("link",$pauta['link']);
				$artigosModel->where("escrito_colaboradores_id",$pauta['redator_colaboradores_id']);
				if($artigosModel->countAllResults() > 0) {
					$pautasModel->delete($pauta['id']);
				}
			}
		}

		/*PARTE RELACIONADA A DESMARCAÇÃO DOS ARTIGOS*/
		$cronArtigos = $configuracaoModel->find('cron_artigos_desmarcar_status')['config_valor'];
		if($cronArtigos == '1') {
			$artigosHistoricos = new ArtigosHistoricos;
			/*Revisão*/
			$cronArtigos = $configuracaoModel->find('cron_artigos_teoria_desmarcar_data_revisao')['config_valor'];
			$time = new Time('-'.$cronArtigos);
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->where("marcado <= '".$time->toDateTimeString()."'");
			$artigosModel->where("fase_producao_id",2);
			$artigosModel->where("tipo_artigo",'T');
			$artigos = $artigosModel->get()->getResultArray();
			foreach($artigos as $artigo) {
				$artigosHistoricos->cadastraHistorico($artigo['id'], 'desmarcou', $artigo['marcado_colaboradores_id']);
				$artigosModel->update($artigo['id'],array('marcado'=>NULL, 'marcado_colaboradores_id'=>NULL));
			}
			unset($artigosModel);
			
			/*Narração*/
			$cronArtigos = $configuracaoModel->find('cron_artigos_teoria_desmarcar_data_narracao')['config_valor'];
			$time = new Time('-'.$cronArtigos);
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->where("marcado <= '".$time->toDateTimeString()."'");
			$artigosModel->where("fase_producao_id",3);
			$artigosModel->where("tipo_artigo",'T');
			$artigos = $artigosModel->get()->getResultArray();
			foreach($artigos as $artigo) {
				$artigosHistoricos->cadastraHistorico($artigo['id'], 'desmarcou', $artigo['marcado_colaboradores_id']);
				$artigosModel->update($artigo['id'],array('marcado'=>NULL, 'marcado_colaboradores_id'=>NULL));
			}
			unset($artigosModel);

			/*Produção*/
			$cronArtigos = $configuracaoModel->find('cron_artigos_teoria_desmarcar_data_producao')['config_valor'];
			$time = new Time('-'.$cronArtigos);
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->where("marcado <= '".$time->toDateTimeString()."'");
			$artigosModel->where("fase_producao_id",4);
			$artigosModel->where("tipo_artigo",'T');
			$artigos = $artigosModel->get()->getResultArray();
			foreach($artigos as $artigo) {
				$artigosHistoricos->cadastraHistorico($artigo['id'], 'desmarcou', $artigo['marcado_colaboradores_id']);
				$artigosModel->update($artigo['id'],array('marcado'=>NULL, 'marcado_colaboradores_id'=>NULL));
			}
			unset($artigosModel);

			/*Revisão*/
			$cronArtigos = $configuracaoModel->find('cron_artigos_noticia_desmarcar_data_revisao')['config_valor'];
			$time = new Time('-'.$cronArtigos);
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->where("marcado <= '".$time->toDateTimeString()."'");
			$artigosModel->where("fase_producao_id",2);
			$artigosModel->where("tipo_artigo",'N');
			$artigos = $artigosModel->get()->getResultArray();
			foreach($artigos as $artigo) {
				$artigosHistoricos->cadastraHistorico($artigo['id'], 'desmarcou', $artigo['marcado_colaboradores_id']);
				$artigosModel->update($artigo['id'],array('marcado'=>NULL, 'marcado_colaboradores_id'=>NULL));
			}
			unset($artigosModel);
			
			/*Narração*/
			$cronArtigos = $configuracaoModel->find('cron_artigos_noticia_desmarcar_data_narracao')['config_valor'];
			$time = new Time('-'.$cronArtigos);
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->where("marcado <= '".$time->toDateTimeString()."'");
			$artigosModel->where("fase_producao_id",3);
			$artigosModel->where("tipo_artigo",'N');
			$artigos = $artigosModel->get()->getResultArray();
			foreach($artigos as $artigo) {
				$artigosHistoricos->cadastraHistorico($artigo['id'], 'desmarcou', $artigo['marcado_colaboradores_id']);
				$artigosModel->update($artigo['id'],array('marcado'=>NULL, 'marcado_colaboradores_id'=>NULL));
			}
			unset($artigosModel);

			/*Produção*/
			$cronArtigos = $configuracaoModel->find('cron_artigos_noticia_desmarcar_data_producao')['config_valor'];
			$time = new Time('-'.$cronArtigos);
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->where("marcado <= '".$time->toDateTimeString()."'");
			$artigosModel->where("fase_producao_id",4);
			$artigosModel->where("tipo_artigo",'N');
			$artigos = $artigosModel->get()->getResultArray();
			foreach($artigos as $artigo) {
				$artigosHistoricos->cadastraHistorico($artigo['id'], 'desmarcou', $artigo['marcado_colaboradores_id']);
				$artigosModel->update($artigo['id'],array('marcado'=>NULL, 'marcado_colaboradores_id'=>NULL));
			}
			unset($artigosModel);
		}

		/*PARTE RELACIONADA A MANDAR E-MAIL PARA PESSOAS QUE CONTRIBUIRAM E NÃO POSSUEM ENDEREÇOS DE BITCOIN CADASTRADOS*/
		$cronDiasEmailCarteira = $configuracaoModel->find('cron_email_carteira_data')['config_valor'];
		$time = new Time('+'.$cronDiasEmailCarteira);
		$cron_email_carteira = $configuracaoModel->find('cron_email_carteira')['config_valor'];
		$cron_email_carteira = Time::createFromFormat('Y-m-d', $cron_email_carteira);
		if($time->getMonth() != $time->today()->getMonth() && $cron_email_carteira->getMonth() != $time->getMonth()) {
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->select("escrito_colaboradores_id,revisado_colaboradores_id,narrado_colaboradores_id,produzido_colaboradores_id");
			$artigosModel->where("fase_producao_id",6);
			$artigos = $artigosModel->get()->getResultArray();
			if(!empty($artigos)) {
				$colaboradores = array();
				foreach($artigos as $artigo) {
					$colaboradores[] = $artigo['escrito_colaboradores_id'];
					$colaboradores[] = $artigo['revisado_colaboradores_id'];
					$colaboradores[] = $artigo['narrado_colaboradores_id'];
					$colaboradores[] = $artigo['produzido_colaboradores_id'];
				}
				$colaboradores = array_unique($colaboradores);
				$colaboradoresModel = new \App\Models\ColaboradoresModel();
				$colaboradoresModel->whereIn('id',$colaboradores);
				$colaboradoresModel->where("(carteira IS NULL OR carteira = '')");
				$colaboradores = $colaboradoresModel->get()->getResultArray();
				if(!empty($colaboradores)) {
					$emails = array();
					foreach($colaboradores as $colaborador) {
						$emails[] = $colaborador['email'];
					}
					$enviaEmail = new \App\Libraries\EnviaEmail();
					$enviaEmail->enviaEmail(NULL, 'VISÃO LIBERTÁRIA - CARTEIRA NÃO CADASTRADA', $enviaEmail->getMensagemCarteiraVazia(), false, $emails);
				}
			}
			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$configuracaoModel->update('cron_email_carteira', array('config_valor' => $time->toDateString()));
		}

		/*PARTE RELACIONADA A LIMPEZA DAS NOTIFICAÇÕES*/
		$cronNotificacoes = $configuracaoModel->find('cron_notificacoes_status_delete')['config_valor'];
		if($cronNotificacoes == '1') {

			/*EXCLUSÃO DAS PAUTAS VISUALIZADAS*/
			$cronDataNotificacoesVisualizadas = $configuracaoModel->find('cron_notificacoes_data_visualizado')['config_valor'];
			$time = new Time('-'.$cronDataNotificacoesVisualizadas);
			$colaboradoresNotificacoesModel = new \App\Models\ColaboradoresNotificacoesModel();
			$colaboradoresNotificacoesModel->where("data_visualizado <= '".$time->toDateTimeString()."'");;
			$notificacoes = $colaboradoresNotificacoesModel->get()->getResultArray();
			foreach($notificacoes as $notificacao) {
				$colaboradoresNotificacoesModel->delete($notificacao['id'],true);
			}

			/*EXCLUSÃO DAS PAUTAS NÃO VISUALIZADAS*/
			$cronDataNotificacoesNaoVisualizadas = $configuracaoModel->find('cron_notificacoes_data_cadastrado')['config_valor'];
			$time = new Time('-'.$cronDataNotificacoesNaoVisualizadas);
			$colaboradoresNotificacoesModel = new \App\Models\ColaboradoresNotificacoesModel();
			$colaboradoresNotificacoesModel->where("criado <= '".$time->toDateTimeString()."'");;
			$notificacoes = $colaboradoresNotificacoesModel->get()->getResultArray();
			foreach($notificacoes as $notificacao) {
				$colaboradoresNotificacoesModel->delete($notificacao['id'],true);
			}
		}

		/*PARTE RELACIONADA A DESCARTE DOS ARTIGOS ABANDONADOS*/
		$cronDescarteArtigosStatus = $configuracaoModel->find('cron_artigos_descartar_status')['config_valor'];
		if($cronDescarteArtigosStatus == '1') {

			$cronDataDescarteArtigos = $configuracaoModel->find('cron_artigos_descartar_data')['config_valor'];
			
			$time = new Time('-'.$cronDataDescarteArtigos);
			unset($artigosModel);
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->where("criado <= '".$time->toDateTimeString()."'");
			$artigosModel->where("descartado",NULL);
			$artigosModel->whereIn("fase_producao_id",array('1','2','3','4'));
			$artigos = $artigosModel->get()->getResultArray();

			foreach($artigos as $artigo) {
				$artigosModel->update($artigo['id'],array('descartado_colaboradores_id'=>1));
				$artigosModel->delete($artigo['id']);
			}
		}
				
		return 'Cron Finalizado';
	}

}
