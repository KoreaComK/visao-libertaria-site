<?php

namespace App\Controllers\Colaboradores;

use App\Controllers\BaseController;
use App\Libraries\VerificaPermissao;
use App\Models\ColaboradoresNotificacoesModel;

use CodeIgniter\I18n\Time;

class Perfil extends BaseController
{
	function __construct()
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('1');
	}
	public function index()
	{
		$data = array();
		$session = $this->session->get('colaboradores');

		if ($this->request->isAJAX()) {
			$dados = array();
			$post = service('request')->getPost();
			$retorno = new \App\Libraries\RetornoPadrao();

			if (isset($post['apelido'])) {
				$gerenciadorTextos = new \App\Libraries\GerenciadorTextos();
				$avatar = $this->request->getFile('avatar');

				$validaFormularios = new \App\Libraries\ValidaFormularios();

				$post['twitter'] = $gerenciadorTextos->simplificaString($post['twitter']);
				$valida = $validaFormularios->validaFormularioPerfilColaborador($post, $session['id']);
				if (empty($valida->getErrors())) {

					$colaboradoresModel = new \App\Models\ColaboradoresModel();
					$dados['id'] = $session['id'];
					$dados['carteira'] = $post['carteira'];
					$dados['apelido'] = $colaboradoresModel->db->escapeString($post['apelido']);
					$dados['twitter'] = $gerenciadorTextos->simplificaString($post['twitter']);

					if ($avatar->getName() != '') {
						$valida = $validaFormularios->validaFormularioPerfilColaboradorFile();
						if (empty($valida->getErrors())) {
							$nome_arquivo = $session['id'] . '.' . $avatar->guessExtension();
							if ($avatar->move('public/assets/avatars', $nome_arquivo, true)) {
								$dados['avatar'] = base_url('public/assets/avatars/' . $nome_arquivo);
								$session['avatar'] = $dados['avatar'];
							}
						} else {
							$erros = $valida->getErrors();
							$string_erros = '';
							foreach ($erros as $erro) {
								$string_erros .= $erro . "<br/>";
							}
							return $retorno->retorno(false, $string_erros, true);
						}
					}
					//$colaborador = $colaboradoresModel->save($dados);
					$colaborador = $this->gravarColaborador('save', $dados);
					if ($colaborador) {
						$session['nome'] = $dados['apelido'];
						$this->session->set(array('colaboradores' => $session));
					}
					return $retorno->retorno(true, 'Perfil atualizado com sucesso.', true);

				} else {
					$erros = $valida->getErrors();
					$string_erros = '';
					foreach ($erros as $erro) {
						$string_erros .= $erro . "<br/>";
					}
					return $retorno->retorno(false, $string_erros, true);
				}
			}

			if (isset($post['senha_nova'])) {

				$colaboradoresModel = new \App\Models\ColaboradoresModel();
				$colaborador = $colaboradoresModel->find($session['id']);

				$validaFormularios = new \App\Libraries\ValidaFormularios();
				$valida = $validaFormularios->validaFormularioTrocarSenhaColaborador($post);
				if (empty($valida->getErrors())) {
					if ($colaborador['senha'] == hash('sha256', $post['senha_antiga'])) {
						$dados['id'] = $session['id'];
						$dados['senha'] = hash('sha256', $post['senha_nova']);
						//$salvado = $colaboradoresModel->save($dados);
						$salvado = $this->gravarColaborador('save', $dados);
						if ($salvado) {
							return $retorno->retorno(true, 'Senha alterada com sucesso.', true);
						} else {
							return $retorno->retorno(false, 'Houve um erro ao alterar sua senha, entre em contato com o suporte.', true);	
						}
					} else {
						return $retorno->retorno(false, 'Senha atual incorreta.', true);
					}
				} else {
					$erros = $valida->getErrors();
					$string_erros = '';
					foreach ($erros as $erro) {
						$string_erros .= $erro . "<br/>";
					}
					return $retorno->retorno(false, $string_erros, true);
				}
			}
		}

		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$data['colaboradores'] = $colaboradoresModel->find($session['id']);

		$data['atribuicoes'] = $this->widgetAtribuicoes($session);

		$data['contribuicoes_mensal'] = $this->widgetContribuicoes($session, null, array(6));

		$data['contribuicoes_total'] = $data['colaboradores']['pontuacao_total'];

		$data['lista_artigos_mes'] = $this->widgetArtigosContribuicoes($session, null, array(6));

		$data['lista_pagamentos'] = $this->widgetPagamentos();

		$data['lista_pautas'] = $this->widgetPautas($session);

		return view('colaboradores/perfil', $data);
	}

	public function notificacoes()
	{
		$session = $this->session->get('colaboradores');
		$data = array();
		$colaboradoresNotificacoesModel = new ColaboradoresNotificacoesModel();
		$notificacoes = $colaboradoresNotificacoesModel
		->select('colaboradores.apelido AS apelido, colaboradores.avatar AS avatar, colaboradores_notificacoes.*')
		->join('colaboradores','colaboradores.id = colaboradores_notificacoes.sujeito_colaboradores_id')
		->where('colaboradores_notificacoes.colaboradores_id',$session['id'])
		->orderBy('colaboradores_notificacoes.criado','DESC')->get()->getResultArray();

		$hoje = Time::now();

		foreach($notificacoes as $i => $n) {
			$criado = Time::parse($n['criado']);
			$diferenca = $criado->difference($hoje);
			if($diferenca->minutes < 1) {
				$notificacoes[$i]['tempo'] = 'agora';
			} elseif($diferenca->hours < 1) {
				$s=($diferenca->minutes>1)?('s'):('');
				$notificacoes[$i]['tempo'] = $diferenca->minutes.' minuto'.$s.' atrás';
			} elseif($diferenca->days < 1) {
				$s=($diferenca->hours>1)?('s'):('');
				$notificacoes[$i]['tempo'] = $diferenca->hours.' hora'.$s.' atrás';
			} elseif($diferenca->weeks < 1) {
				$s=($diferenca->days>1)?('s'):('');
				$notificacoes[$i]['tempo'] = $diferenca->days.' dia'.$s.' atrás';
			} elseif($diferenca->months < 1) {
				$s=($diferenca->weeks>1)?('s'):('');
				$notificacoes[$i]['tempo'] = $diferenca->weeks.' semana'.$s.' atrás';
			} elseif($diferenca->years < 1) {
				$s=($diferenca->weeks>1)?('s'):('');
				$notificacoes[$i]['tempo'] = $diferenca->weeks.' mese'.$s.' atrás';
			}
		}

		$agora = $colaboradoresNotificacoesModel->getNow();

		$colaboradoresNotificacoesModel
    	->where('colaboradores_id',$session['id'])
		->where('data_visualizado',NULL)
    	->set(['data_visualizado' => $agora])
    	->update();


		if($notificacoes != NULL && !empty($notificacoes)) {
			$data['notificacoes'] = $notificacoes;
		} else {
			$data['notificacoes'] = false;
		}
		return view('colaboradores/notificacoes_list', $data);
	}

	public function fechadas($pagamentoId = NULL)
	{
		if($pagamentoId == null) {
			return false;
		}
		$session = $this->session->get('colaboradores');

		$pagamentoId = (int)$pagamentoId;
		$pagamentosArtigosModel = new \App\Models\PagamentosArtigosModel();
		$listaArtigos = $pagamentosArtigosModel->where('pagamentos_artigos.pagamentos_id',$pagamentoId)
		->select('*, artigos.titulo AS titulo')
		->join('artigos','artigos.id = pagamentos_artigos.artigos_id')
		->join('pagamentos','pagamentos.id = pagamentos_artigos.pagamentos_id')
		->where("(artigos.escrito_colaboradores_id = ".$session['id']." OR artigos.revisado_colaboradores_id = ".$session['id']." OR artigos.narrado_colaboradores_id = ".$session['id']." OR artigos.produzido_colaboradores_id = ".$session['id'].")")
		->get()->getResultArray();
		if(empty($listaArtigos)) {
			return '<td colspan="4" class="text-center">Não houve colaborações suas no mês informado.</td>';
		}
		$html = '';
		foreach($listaArtigos as $chave => $artigo) {
			$total = 0;
			$html .= '
					<tr>
						<th scope="row">
							'.($chave + 1).'
						</th>
						<td><a
								href="'.site_url("/site/artigo/".$artigo["url_friendly"]).'">'.$artigo["titulo"].'</a>
						</td>
						<td>';
			if ($artigo['escrito_colaboradores_id'] == $session['id']) {
				$total += $artigo['palavras_escritor'] * $artigo['multiplicador_escrito'] / 100 ;
				$html.='<label class="badge badge-info">Escritor</label>';
			}
			if ($artigo['revisado_colaboradores_id'] == $session['id']) {
				$total += $artigo['palavras_revisor'] * $artigo['multiplicador_revisado'] / 100 ;
				$html.='<label class="badge badge-info">Revisor</label>';
			}
			if ($artigo['narrado_colaboradores_id'] == $session['id']) {
				$total += $artigo['palavras_narrador'] * $artigo['multiplicador_narrado'] / 100 ;
				$html.='<label class="badge badge-info">Narrador</label>';
			}
			if ($artigo['produzido_colaboradores_id'] == $session['id']) {
				$total += $artigo['palavras_produtor'] * $artigo['multiplicador_produzido'] / 100 ;
				$html.='<label class="badge badge-info">Produtor</label>';
			}
			$html.='
						</td>
						<td>
							'.number_format($total, 0, ",", ".").'
						</td>
					</tr>
			';
		}
		return $html;
	}

	private function widgetAtribuicoes($colaborador)
	{
		$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
		$colaboradoresAtribuicoes = $colaboradoresAtribuicoesModel->getNomeAtribuicoesColaborador($colaborador['id']);
		return $colaboradoresAtribuicoes;
	}

	private function widgetArtigosContribuicoes($colaborador, $data, $fases)
	{
		$ArtigosModel = new \App\Models\ArtigosModel();
		$artigos = $ArtigosModel->getArtigosColaboradores($colaborador['id'], $data, $fases);
		return $artigos;
	}


	private function widgetContribuicoes($colaborador, $data = null, $fases = array())
	{
		$artigosModel = new \App\Models\ArtigosModel();
		$artigos = $artigosModel->getQuantidadeColaboracoesArtigosEscritos($colaborador['id'], $data, $fases);
		return $artigos;
	}

	private function widgetPagamentos()
	{
		$pagamentosModel = new \App\Models\PagamentosModel();
		$pagamentos = $pagamentosModel->getPagamentos()->get()->getResultArray();
		return $pagamentos;
	}

	private function widgetPautas($colaborador)
	{
		$pautasModel = new \App\Models\PautasModel();
		$pautas = $pautasModel->where('colaboradores_id',$colaborador['id'])
		->where('reservado IS NOT NULL')
		->where('tag_fechamento IS NOT NULL')
		->where('excluido IS NOT NULL')
		->orderBy('reservado','DESC')
		->get()->getResultArray();
		return $pautas;
	}

	private function gravarColaborador($tipo, $dados, $id = null)
	{
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$retorno = null;
		$colaboradoresModel->db->transStart();
		switch ($tipo) {
			case 'update':
				$retorno = $colaboradoresModel->update($id, $dados);
				break;
			case 'insert':
				$retorno = $colaboradoresModel->insert($dados);
				break;
			case 'save':
				$retorno = $colaboradoresModel->save($dados);
				break;
			case 'delete':
				$retorno = $colaboradoresModel->delete($id);
				break;
			default:
				$retorno = false;
				break;
		}
		$colaboradoresModel->db->transComplete();
		return $retorno;
	}
}