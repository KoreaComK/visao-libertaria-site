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

		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$data['colaboradores'] = $colaboradoresModel->find($session['id']);

		$data['atribuicoes'] = $this->widgetAtribuicoes($session);

		$data['contribuicoes_mensal'] = $this->widgetContribuicoes($session, null, array(6));

		$data['contribuicoes_total'] = $data['colaboradores']['pontuacao_total'];

		$data['lista_artigos_mes'] = $this->widgetArtigosContribuicoes($session, null, array(6));

		$data['lista_pagamentos'] = $this->widgetPagamentos($session);

		//$data['lista_pautas'] = $this->widgetPautas($session);

		$data['limites'] = $this->widgetLimites($session);

		$recadosPorPagina = 10;
		$notificacoes = $this->listarRecados($session['id'], 0, $recadosPorPagina);
		$data['notificacoes'] = !empty($notificacoes) ? $notificacoes : false;
		$data['recados_nao_lidos'] = $this->contarRecadosNaoLidos($session['id']);
		$data['recados_offset'] = count($notificacoes);
		$data['recados_tem_mais'] = count($notificacoes) === $recadosPorPagina;
		$data['recados_por_pagina'] = $recadosPorPagina;

		return view('colaboradores/perfil', $data);
	}

	public function atualizarPerfil()
	{
		$retorno = new \App\Libraries\RetornoPadrao();

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'Requisição inválida.', true);
		}

		$session = $this->session->get('colaboradores');
		$post = service('request')->getPost();
		$dados = array();
		$gerenciadorTextos = new \App\Libraries\GerenciadorTextos();
		$avatar = $this->request->getFile('avatar');
		$validaFormularios = new \App\Libraries\ValidaFormularios();

		$post['twitter'] = $gerenciadorTextos->simplificaString($post['twitter'] ?? '');
		$valida = $validaFormularios->validaFormularioPerfilColaborador($post, $session['id']);
		if (!empty($valida->getErrors())) {
			return $retorno->retorno(false, $this->errosValidacao($valida), true);
		}

		$dados['id'] = $session['id'];
		$dados['carteira'] = $post['carteira'];
		$dados['apelido'] = $post['apelido'];
		$dados['twitter'] = $gerenciadorTextos->simplificaString($post['twitter']);

		if ($avatar !== null && $avatar->getName() != '') {
			$valida = $validaFormularios->validaFormularioPerfilColaboradorFile();
			if (!empty($valida->getErrors())) {
				return $retorno->retorno(false, $this->errosValidacao($valida), true);
			}
			$nome_arquivo = $session['id'] . '.' . $avatar->guessExtension();
			if ($avatar->move('public/assets/avatars', $nome_arquivo, true)) {
				$dados['avatar'] = base_url('public/assets/avatars/' . $nome_arquivo);
				$session['avatar'] = $dados['avatar'];
			}
		}

		$colaborador = $this->gravarColaborador('save', $dados);
		if ($colaborador) {
			$session['nome'] = $dados['apelido'];
			$this->session->set(array('colaboradores' => $session));
		}
		return $retorno->retorno(true, 'Perfil atualizado com sucesso.', true);
	}

	public function removerAvatar()
	{
		$retorno = new \App\Libraries\RetornoPadrao();

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'Requisição inválida.', true);
		}

		$session = $this->session->get('colaboradores');
		$avatarPadrao = avatar_url();
		$dados = [
			'id' => $session['id'],
			'avatar' => null,
		];

		$colaborador = $this->gravarColaborador('save', $dados);
		if (!$colaborador) {
			return $retorno->retorno(false, 'Não foi possível remover o avatar. Tente novamente.', true);
		}

		$this->excluirArquivoAvatar((int) $session['id']);
		$session['avatar'] = $avatarPadrao;
		$this->session->set(['colaboradores' => $session]);

		return $retorno->retorno(true, 'Avatar removido. O avatar padrão foi restaurado.', true, [
			'avatar' => $avatarPadrao,
		]);
	}

	public function trocarSenha()
	{
		$retorno = new \App\Libraries\RetornoPadrao();

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'Requisição inválida.', true);
		}

		$session = $this->session->get('colaboradores');
		$post = service('request')->getPost();
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$colaborador = $colaboradoresModel->find($session['id']);

		$validaFormularios = new \App\Libraries\ValidaFormularios();
		$valida = $validaFormularios->validaFormularioTrocarSenhaColaborador($post);
		if (!empty($valida->getErrors())) {
			return $retorno->retorno(false, $this->errosValidacao($valida), true);
		}
		if ($colaborador['senha'] != hash('sha256', $post['senha_antiga'])) {
			return $retorno->retorno(false, 'Senha atual incorreta.', true);
		}

		$dados['id'] = $session['id'];
		$dados['senha'] = hash('sha256', $post['senha_nova']);
		$salvado = $this->gravarColaborador('save', $dados);
		if ($salvado) {
			return $retorno->retorno(true, 'Senha alterada com sucesso.', true);
		}
		return $retorno->retorno(false, 'Houve um erro ao alterar sua senha, entre em contato com o suporte.', true);
	}

	public function notificacoes()
	{
		return redirect()->to(site_url('colaboradores/perfil'));
	}

	public function marcarRecadosLidos()
	{
		$retorno = new \App\Libraries\RetornoPadrao();

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'Requisição inválida.', true);
		}

		$session = $this->session->get('colaboradores');
		$colaboradoresNotificacoesModel = new ColaboradoresNotificacoesModel();
		$agora = $colaboradoresNotificacoesModel->getNow();

		$colaboradoresNotificacoesModel
			->where('colaboradores_id', $session['id'])
			->where('data_visualizado', null)
			->set(['data_visualizado' => $agora])
			->update();

		$colaboradores = $this->session->get('colaboradores');
		$colaboradores['notificacoes'] = 0;
		$colaboradores['notificacoes_cache_em'] = time();
		$this->session->set(['colaboradores' => $colaboradores]);

		return $retorno->retorno(true, 'Recados marcados como lidos.', true);
	}

	public function recadosMais()
	{
		if (!$this->request->isAJAX()) {
			return $this->response->setStatusCode(400)->setJSON([
				'status' => false,
				'mensagem' => 'Requisição inválida.',
			]);
		}

		$session = $this->session->get('colaboradores');
		$offset = max(0, (int) $this->request->getGet('offset'));
		$limite = 10;
		$notificacoes = $this->listarRecados($session['id'], $offset, $limite);

		$html = '';
		if (!empty($notificacoes)) {
			$html = view('colaboradores/perfil_recados_itens', [
				'notificacoes' => $notificacoes,
				'colaboradores' => ['id' => $session['id']],
				'idx_inicio' => $offset,
			]);
		}

		return $this->response->setJSON([
			'status' => true,
			'html' => $html,
			'quantidade' => count($notificacoes),
			'tem_mais' => count($notificacoes) === $limite,
			'proximo_offset' => $offset + count($notificacoes),
		]);
	}

	private function listarRecados($colaboradorId, int $offset = 0, int $limit = 10): array
	{
		$colaboradoresNotificacoesModel = new ColaboradoresNotificacoesModel();
		$notificacoes = $colaboradoresNotificacoesModel
			->select('colaboradores.apelido AS apelido, colaboradores.avatar AS avatar, colaboradores_notificacoes.*')
			->join('colaboradores', 'colaboradores.id = colaboradores_notificacoes.sujeito_colaboradores_id')
			->where('colaboradores_notificacoes.colaboradores_id', $colaboradorId)
			->orderBy('colaboradores_notificacoes.criado', 'DESC')
			->findAll($limit, $offset);

		foreach ($notificacoes as $i => $n) {
			$notificacoes[$i]['tempo'] = tempo_relativo($n['criado']);
		}

		return $notificacoes;
	}

	private function contarRecadosNaoLidos($colaboradorId): int
	{
		$colaboradoresNotificacoesModel = new ColaboradoresNotificacoesModel();
		return $colaboradoresNotificacoesModel
			->where('colaboradores_id', $colaboradorId)
			->where('data_visualizado', null)
			->countAllResults();
	}

	public function fechadas($pagamentoId = NULL)
	{
		if ($pagamentoId == null) {
			return false;
		}

		$session = $this->session->get('colaboradores');
		$pagamentoId = (int) $pagamentoId;
		$colaboradorId = (int) $session['id'];

		$pagamentosArtigosModel = new \App\Models\PagamentosArtigosModel();
		$listaArtigos = $pagamentosArtigosModel
			->select('*, artigos.titulo AS titulo')
			->join('artigos', 'artigos.id = pagamentos_artigos.artigos_id')
			->join('pagamentos', 'pagamentos.id = pagamentos_artigos.pagamentos_id')
			->where('pagamentos_artigos.pagamentos_id', $pagamentoId)
			->groupStart()
				->where('artigos.escrito_colaboradores_id', $colaboradorId)
				->orWhere('artigos.revisado_colaboradores_id', $colaboradorId)
				->orWhere('artigos.narrado_colaboradores_id', $colaboradorId)
				->orWhere('artigos.produzido_colaboradores_id', $colaboradorId)
			->groupEnd()
			->get()
			->getResultArray();

		return view('colaboradores/perfil_colaboracoes_fechadas', [
			'lista_artigos' => $listaArtigos,
			'colaborador_id' => $colaboradorId,
		]);
	}

	private function widgetAtribuicoes($colaborador)
	{
		$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
		$colaboradoresAtribuicoes = $colaboradoresAtribuicoesModel->getNomeAtribuicoesColaborador($colaborador['id']);
		return $colaboradoresAtribuicoes;
	}

	private function widgetLimites($colaborador)
	{
		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$pautasModel = new \App\Models\PautasModel();
		$limites = array();
		$limites['limite_pautas_diario'] = (int)$configuracaoModel->find('limite_pautas_diario')['config_valor'];
		$limites['limite_pautas_semanal'] = (int)$configuracaoModel->find('limite_pautas_semanal')['config_valor'];


		
		$time = new Time('-7 days');
		$time = $time->toDateString();
		$limites['limite_pautas_semanal_usadas'] = $pautasModel->getPautasPorUsuario($time,$colaborador['id'])[0]['contador'];

		$lista_pautas = $pautasModel->select('criado')->where('colaboradores_id',$colaborador['id'])->where("criado >= '".$time."'")
		->orderBy('criado',"ASC")->get()->getResultArray();
		if($lista_pautas !== null && !empty($lista_pautas)) {
			$time = Time::parse($lista_pautas[0]['criado']);
			$time = $time->addDays(8);
		} else {
			$time = Time::today();
		}

		$limites['limite_pautas_semanal_permitido'] = $time;

		$time = Time::today();
		$time = $time->toDateString();
		$limites['limite_pautas_diario_usadas'] = $pautasModel->getPautasPorUsuario($time,$colaborador['id'])[0]['contador'];

		$limites['limite_pautas_diario_permitido'] = Time::today()->addDays(1);
		
		return $limites;
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
		$pagamentosModel = new \App\Models\PagamentosModel();
		return $pagamentosModel->getPagamentosColaborador((int) $colaborador['id']);
	}

	// private function widgetPautas($colaborador)
	// {
	// 	$pautasModel = new \App\Models\PautasModel();
	// 	$pautas = $pautasModel->where('colaboradores_id',$colaborador['id'])
	// 	->where('reservado IS NOT NULL')
	// 	->where('tag_fechamento IS NOT NULL')
	// 	->where('excluido IS NOT NULL')
	// 	->orderBy('reservado','DESC')
	// 	->get()->getResultArray();
	// 	return $pautas;
	// }

	private function excluirArquivoAvatar(int $colaboradorId): void
	{
		if ($colaboradorId < 1) {
			return;
		}

		$diretorio = FCPATH . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR;
		foreach (glob($diretorio . $colaboradorId . '.*') ?: [] as $arquivo) {
			if (is_file($arquivo)) {
				@unlink($arquivo);
			}
		}
	}

	private function errosValidacao($valida)
	{
		$string_erros = '';
		foreach ($valida->getErrors() as $erro) {
			$string_erros .= $erro . "<br/>";
		}
		return $string_erros;
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
