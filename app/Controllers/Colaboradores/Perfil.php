<?php

namespace App\Controllers\Colaboradores;

use App\Controllers\BaseController;
use App\Libraries\VerificaPermissao;

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
				$avatar = $this->request->getFile('avatar');

				$validaFormularios = new \App\Libraries\ValidaFormularios();
				$valida = $validaFormularios->validaFormularioPerfilColaborador($post, $session['id']);
				if (empty($valida->getErrors())) {

					$colaboradoresModel = new \App\Models\ColaboradoresModel();
					$dados['id'] = $session['id'];
					$dados['carteira'] = $post['carteira'];
					$dados['apelido'] = $post['apelido'];

					if ($avatar->getName() != '') {
						$valida = $validaFormularios->validaFormularioPerfilColaboradorFile();
						if (empty($valida->getErrors())) {
							$nome_arquivo = $session['id'] . '.' . $avatar->guessExtension();
							if ($avatar->move('../public/assets/avatars', $nome_arquivo, true)) {
								$dados['avatar'] = base_url('assets/avatars/' . $nome_arquivo);
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
					$colaborador = $colaboradoresModel->save($dados);
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
						$salvado = $colaboradoresModel->save($dados);
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

		$data['contribuicoes_mensal'] = $this->widgetContribuicoes($session, date('Y-m-d'), array(6, 7));

		$data['contribuicoes_total'] = $this->widgetContribuicoes($session, null, array(6, 7));

		$data['lista_artigos_mes'] = $this->widgetArtigosContribuicoes($session, date('Y-m-d'), array(6, 7));

		return view('colaboradores/perfil', $data);
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

	private function widgetPagamentos($colaborador)
	{
	}
}