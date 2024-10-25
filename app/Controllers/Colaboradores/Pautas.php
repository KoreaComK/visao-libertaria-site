<?php

namespace App\Controllers\Colaboradores;

use App\Controllers\BaseController;

use App\Libraries\VerificaPermissao;
use App\Libraries\ColaboradoresNotificacoes;
use CodeIgniter\I18n\Time;

class Pautas extends BaseController
{
	protected $colaboradoresNotificacoes;
	function __construct()
	{
		$this->colaboradoresNotificacoes = new ColaboradoresNotificacoes();
	}

	public function index(): string
	{
		$data = array();
		$data['titulo'] = 'Pautas Cadastradas';
		$pautasModel = new \App\Models\PautasModel();
		$pautas = $pautasModel->getPautas();

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];

		$data['config']['pauta_tamanho_maximo'] = $configuracaoModel->find('pauta_tamanho_maximo')['config_valor'];
		$data['config']['pauta_tamanho_minimo'] = $configuracaoModel->find('pauta_tamanho_minimo')['config_valor'];
		$data['config']['limite_pautas_diario'] = $configuracaoModel->find('limite_pautas_diario')['config_valor'];
		$data['config']['limite_pautas_semanal'] = $configuracaoModel->find('limite_pautas_semanal')['config_valor'];


		$data['pautasList'] = [
			'pautas' => $pautas->paginate($config['site_quantidade_listagem'], 'pautas'),
			'pager' => $pautas->pager
		];

		if ($this->request->getMethod() == 'get' && isset(service('request')->getGet()['page_pautas'])) {
			return view('template/templatePautasListColaboradores', $data);
		} else {
			return view('colaboradores/pautas_list', $data);
		}
	}

	// public function redatores(): string
	// {
	// 	$verifica = new verificaPermissao();
	// 	$verifica->PermiteAcesso('11');

	// 	$data = array();
	// 	$data['titulo'] = 'Pautas Sugeridas para Redatores';
	// 	$pautasModel = new \App\Models\PautasModel();

	// 	$permissoes = $this->session->get('colaboradores')['permissoes'];
	// 	if (in_array('7', $permissoes)) {
	// 		$pautas = $pautasModel->getPautas(false, false, true);
	// 	} else {
	// 		$pautas = $pautasModel->getPautas(false, false, $this->session->get('colaboradores')['id']);
	// 	}

	// 	$configuracaoModel = new \App\Models\ConfiguracaoModel();
	// 	$config = array();
	// 	$config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];
	// 	$data['pautasList'] = [
	// 		'pautas' => $pautas->paginate($config['site_quantidade_listagem'], 'pautas'),
	// 		'pager' => $pautas->pager
	// 	];
	// 	return view('colaboradores/pautas_list', $data);
	// }

	public function verificaPautaCadastrada()
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('1');

		$pautasModel = new \App\Models\PautasModel();

		$isAdmin = false;
		$permissoes = $this->session->get('colaboradores');
		$permissoes = $permissoes['permissoes'];
		if (in_array('7', $permissoes)) {
			$isAdmin = true;
		}

		$retorno = new \App\Libraries\RetornoPadrao();

		if ($this->request->isAJAX()) {
			$post = service('request')->getPost();

			if (isset($post['link_pauta'])) {
				$countPautas = $pautasModel->isPautaCadastrada($post['link_pauta']);

				if ($isAdmin || $countPautas == 0) {
					return $this->getInformacaoLink($post);
				} else {
					return $retorno->retorno(false, 'Pauta já cadastrada', true);
				}
			}
		}
		return $retorno->retorno(false, 'Não foi informado nenhum link', true);
	}

	public function cadastrar($idPautas = null)
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('1');

		$data = array();
		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$data['config'] = array();
		$data['config']['pauta_tamanho_maximo'] = $configuracaoModel->find('pauta_tamanho_maximo')['config_valor'];
		$data['config']['pauta_tamanho_minimo'] = $configuracaoModel->find('pauta_tamanho_minimo')['config_valor'];
		$data['config']['limite_pautas_diario'] = $configuracaoModel->find('limite_pautas_diario')['config_valor'];
		$data['config']['limite_pautas_semanal'] = $configuracaoModel->find('limite_pautas_semanal')['config_valor'];

		$retorno = new \App\Libraries\RetornoPadrao();
		$data['titulo'] = 'Sugira uma pauta';

		$pautasModel = new \App\Models\PautasModel();

		$isAdmin = false;
		$permissoes = $this->session->get('colaboradores');
		$permissoes = $permissoes['permissoes'];
		if (in_array('7', $permissoes)) {
			$isAdmin = true;
		}

		if ($this->request->isAJAX()) {

			$pautasModel = new \App\Models\PautasModel();

			$post = service('request')->getPost();
			if ($idPautas !== null) {
				$pautas = $pautasModel->find($idPautas);
				if ($pautas['colaboradores_id'] !== $this->session->get('colaboradores')['id']) {
					return $retorno->retorno(false, 'Você só pode alterar pautas que são suas e não de outros colaboradores.', true);
				}
				$post['link'] = $pautas['link'];
				$post['pauta_antiga'] = $pautas['pauta_antiga'];
			}

			$session = $this->session->get('colaboradores');

			$time = Time::today();
			$time = $time->toDateString();
			$quantidade_pautas = $pautasModel->getPautasPorUsuario($time, $session['id'])[0]['contador'];
			if (!$isAdmin && ($idPautas == null && $quantidade_pautas >= $data['config']['limite_pautas_diario'])) {
				return $retorno->retorno(false, 'O limite diário de pautas foi atingido. Tente novamente amanhã.', true);
			}

			$time = new Time('-7 days');
			$time = $time->toDateString();
			$quantidade_pautas = $pautasModel->getPautasPorUsuario($time, $session['id'])[0]['contador'];
			if (!$isAdmin && ($idPautas == null && $quantidade_pautas >= $data['config']['limite_pautas_semanal'])) {
				return $retorno->retorno(false, 'O limite semanal de pautas foi atingido. Tente novamente outro dia.', true);
			}

			$gerenciadorTextos = new \App\Libraries\GerenciadorTextos();
			$validaFormularios = new \App\Libraries\ValidaFormularios();
			$valida = $validaFormularios->validaFormularioPauta($post);
			if (empty($valida->getErrors())) {
				if (!is_array(@getimagesize($post['imagem']))) {
					$post['imagem'] = base_url('public/assets/imagem-default.png');
				}
				if (!$isAdmin && ($gerenciadorTextos->contaPalavras($post['texto']) > $data['config']['pauta_tamanho_maximo'] || $gerenciadorTextos->contaPalavras($post['texto']) < $data['config']['pauta_tamanho_minimo'])) {
					return $retorno->retorno(false, 'O tamanho do texto está fora dos limites.', true);
				}

				$countPautas = $pautasModel->isPautaCadastrada($post['link']);
				if ($countPautas != 0 && $idPautas == NULL) {
					return $retorno->retorno(false, 'Pauta já cadastrada', true);
				}

				$dados = array();
				$dados['colaboradores_id'] = $session['id'];
				$dados['link'] = htmlspecialchars($post['link'], ENT_QUOTES, 'UTF-8');
				$dados['titulo'] = htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8');
				$dados['texto'] = htmlspecialchars($post['texto'], ENT_QUOTES, 'UTF-8');
				$dados['imagem'] = htmlspecialchars($post['imagem'], ENT_QUOTES, 'UTF-8');
				if ($isAdmin && isset($post['redatores']) && $post['redatores'] !== "") {
					$dados['redator_colaboradores_id'] = $post['redatores'];
				}
				if (isset($post['pauta_antiga']) && $post['pauta_antiga'] == 'S') {
					$dados['pauta_antiga'] = $post['pauta_antiga'];
				}
				if ($idPautas != null) {
					$pautas = $this->gravarPautas('update', $dados, $idPautas);
				} else {
					$dados['id'] = $pautasModel->getNovaUUID();
					$pautas = $this->gravarPautas('insert', $dados);
				}

				if ($pautas) {
					return $retorno->retorno(true, 'Pauta cadastrada com sucesso', true);
				} else {
					return $retorno->retorno(false, 'Ocorreu um erro ao cadastrar a pauta', true);
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
		
		return redirect()->to(base_url() . 'colaboradores/pautas');

		// if ($isAdmin) {
		// 	$colaboradoresModel = new \App\Models\ColaboradoresModel();
		// 	$colaboradoresModel->join('colaboradores_atribuicoes', 'colaboradores.id = colaboradores_atribuicoes.colaboradores_id')
		// 		->where('colaboradores_atribuicoes.atribuicoes_id', '11');
		// 	$colaboradores = $colaboradoresModel->get()->getResultArray();
		// 	$data['redatores'] = $colaboradores;
		// }

		// if ($idPautas != null) {
		// 	$data['post'] = $pautasModel->find($idPautas);
		// 	if ($data['post'] == NULL || $data['post']['colaboradores_id'] != $this->session->get('colaboradores')['id']) {
		// 		return redirect()->to(base_url() . 'colaboradores/pautas');
		// 	}
		// }
		// return view('colaboradores/pautas_form', $data);
	}

	public function verificaImagem()
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('1');
		$retorno = new \App\Libraries\RetornoPadrao();
		$post = service('request')->getPost();
		if (is_array(@getimagesize($post['imagem']))) {
			return $retorno->retorno(true, '', true);
		} else {
			return $retorno->retorno(false, 'A imagem não é válida.', true);
		}

	}

	public function detalhe($idPautas = null)
	{
		$session = \Config\Services::session();
		$session->start();
		if (!$session->has('colaboradores') || $session->get('colaboradores')['id'] === NULL) {
			header("location: " . site_url('site/pauta/' . $idPautas));
			die();
		}
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('1');

		$retorno = new \App\Libraries\RetornoPadrao();
		$data = array();

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$data['config'] = array();
		$data['config']['pauta_tamanho_maximo'] = $configuracaoModel->find('pauta_tamanho_maximo')['config_valor'];
		$data['config']['pauta_tamanho_minimo'] = $configuracaoModel->find('pauta_tamanho_minimo')['config_valor'];
		$data['config']['limite_pautas_diario'] = $configuracaoModel->find('limite_pautas_diario')['config_valor'];
		$data['config']['limite_pautas_semanal'] = $configuracaoModel->find('limite_pautas_semanal')['config_valor'];
		$retorno = new \App\Libraries\RetornoPadrao();
		$data['titulo'] = 'Leia a pauta';

		$pautasModel = new \App\Models\PautasModel();

		if ($idPautas != null) {
			$data['post'] = $pautasModel->withDeleted()->find($idPautas);
			$data['post']['status'] = true;
			if ($data['post'] == null || empty($data['post'])) {
				return $retorno->retorno(false, 'Não encontramos a pauta informada.', true);
			}
		} else {
			return $retorno->retorno(false, 'Não foi informado nenhuma pauta.', true);
		}
		return json_encode($data['post']);
	}

	public function detalhamento($idPautas = null)
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('1');

		$retorno = new \App\Libraries\RetornoPadrao();
		$data = array();
		$pautasModel = new \App\Models\PautasModel();

		if ($idPautas != null) {
			$data['pauta'] = $pautasModel->withDeleted()->find($idPautas);
			if ($data['pauta'] != null || !empty($data['pauta'])) {
				return view('colaboradores/colaborador_pautas_detalhes', $data);
			}
		}
		return redirect()->to(base_url() . 'colaboradores/pautas');
	}

	public function excluir($idPauta = null)
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('1');
		$data = array();

		$retorno = new \App\Libraries\RetornoPadrao();

		if ($idPauta != null) {
			$pautasModel = new \App\Models\PautasModel();
			$pauta = $pautasModel->find($idPauta);

			if ($pauta != null && !empty($pauta) && $pauta['colaboradores_id'] == $this->session->get('colaboradores')['id']) {
				$this->gravarPautas('delete', null, $pauta['id']);
				return $retorno->retorno(true, 'Pauta excluída com sucesso.', true);
			}
			return $retorno->retorno(false, 'Atenção! Pauta não encontrada para o seu usuário.', true);
		} else {
			return $retorno->retorno(false, 'Atenção! Pauta não encontrada.', true);
		}
	}

	public function fechar()
	{

		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('10');
		if ($this->request->getMethod() == 'post') {
			$post = service('request')->getPost();
			$retorno = new \App\Libraries\RetornoPadrao();
			$pautasModel = new \App\Models\PautasModel();
			if (isset($post['metodo']) && $post['metodo'] == 'descartar') {
				$this->gravarPautas('delete', null, $post['id']);
				return $retorno->retorno(true, '', true);
			}
			if (isset($post['metodo']) && $post['metodo'] == 'reservar') {
				$gerenciadorTextos = new \App\Libraries\GerenciadorTextos();
				$gravar['id'] = $post['id'];
				$gravar['reservado'] = $pautasModel->getNow();
				$gravar['tag_fechamento'] = $gerenciadorTextos->simplificaString($post['tag']);
				$this->gravarPautas('save', $gravar);
				return $retorno->retorno(true, $gravar['tag_fechamento'], true);
			}
			if (isset($post['metodo']) && $post['metodo'] == 'cancelar') {
				$gravar['id'] = $post['id'];
				$gravar['reservado'] = NULL;
				$gravar['tag_fechamento'] = NULL;
				$this->gravarPautas('save', $gravar);
				return $retorno->retorno(true, '', true);
			}
			if (isset($post['metodo']) && $post['metodo'] == 'fechar') {
				helper('date');

				$pautasFechadasModel = new \App\Models\PautasFechadasModel();
				$pautasPautasFechadasModel = new \App\Models\PautasPautasFechadasModel();

				$gravar['titulo'] = ($post['titulo'] == '') ? ('Pauta do dia ' . Time::now()->toLocalizedString('dd MMMM yyyy')) : ($post['titulo']);

				$pautas = $pautasModel->getPautasFechamento();
				if ($pautas == null || empty($pautas)) {
					return $retorno->retorno(false, 'Atenção! Não foi reservada nenhuma pauta.', true);
				}

				$idPautasFechadas = $pautasFechadasModel->insert($gravar);
				foreach ($pautas as $pauta) {
					$pautasPautasFechadasModel->insert(array('pautas_fechadas_id' => $idPautasFechadas, 'pautas_id' => $pauta['id']));
					$this->gravarPautas('delete', null, $pauta['id']);
				}

				return $retorno->retorno(true, 'Fechamento da pauta feita com sucesso. A página será recarregada dentro de instantes.', true);
			}
			return false;
		}


		$data = array();
		$data['titulo'] = 'Fechamento de Pautas';
		return view('colaboradores/pautas_closing', $data);
	}

	public function pautasList()
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('10');
		if ($this->request->getMethod() == 'get') {
			$get = service('request')->getGet();
			$pautasModel = new \App\Models\PautasModel();
			if (!isset($get['pesquisa']) || $get['pesquisa'] == '') {
				$get['pesquisa'] = NULL;
			}
			$pautas = $pautasModel->getPautasPesquisa($get['pesquisa']);

			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$config = array();
			$config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];
			$data['pautasList'] = [
				'pautas' => $pautas->paginate($config['site_quantidade_listagem'], 'pautas'),
				'pager' => $pautas->pager
			];
		}
		return view('template/templatePautasList', $data);
	}

	public function fechadas($idPautasFechadas = null)
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('10');
		if ($this->request->getMethod() == 'post') {
			$post = service('request')->getPost();
			$retorno = new \App\Libraries\RetornoPadrao();
			$pautasModel = new \App\Models\PautasModel();
			if (isset($post['metodo']) && $post['metodo'] == 'cancelar') {
				$gravar['id'] = $post['id'];
				$gravar['reservado'] = NULL;
				$gravar['tag_fechamento'] = NULL;
				$this->gravarPautas('save', $gravar);
				return $retorno->retorno(true, '', true);
			}
			return false;
		}

		$data = array();
		$pautasFechadasModel = new \App\Models\PautasFechadasModel();

		if ($idPautasFechadas == null) {
			$data['titulo'] = 'Pautas Fechadas';
			return view('colaboradores/pautas_fechadas_list', $data);
		} else {
			$data['titulo'] = 'Informações da Pauta Fechada';
			$pautasPautasFechadasModel = new \App\Models\PautasPautasFechadasModel();
			$pautaDetail = $pautasFechadasModel->find($idPautasFechadas);
			$pautasPautaFechada = $pautasPautasFechadasModel->getPautasPorPautaFechada($idPautasFechadas);
			$data['pautaDetail'] = $pautaDetail;

			$pautasArray = [];
			$tag = NULL;
			foreach ($pautasPautaFechada as $ppf) {
				if ($tag != $ppf['tag_fechamento']) {
					$pautasArray[$ppf['tag_fechamento']] = array();
					$pautasArray[$ppf['tag_fechamento']]['pautas'] = array();
					$pautasArray[$ppf['tag_fechamento']]['colaboradores'] = array();
					$tag = $ppf['tag_fechamento'];
				}
				$pautasArray[$ppf['tag_fechamento']]['pautas'][] = $ppf;
				if (!in_array($ppf['apelido'], $pautasArray[$ppf['tag_fechamento']]['colaboradores'])) {
					$pautasArray[$ppf['tag_fechamento']]['colaboradores'][] = $ppf['apelido'];
				}
			}

			$data['pautasList'] = $pautasArray;
			return view('colaboradores/pautas_fechadas_detail', $data);
		}
	}

	public function pautasFechadasList()
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('10');

		$pautasFechadasModel = new \App\Models\PautasFechadasModel();

		$pautas = $pautasFechadasModel->orderBy('criado', 'DESC');

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];
		if ($this->request->getMethod() == 'get') {
			$data['pautasList'] = [
				'pautas' => $pautas->paginate($config['site_quantidade_listagem'], 'pautas'),
				'pager' => $pautas->pager
			];
		}
		return view('template/templatePautasFechadasList', $data);
	}

	public function comentarios($idPauta)
	{
		$pautasComentariosModel = new \App\Models\PautasComentariosModel();
		if ($this->request->getMethod() == 'post') {
			$retorno = new \App\Libraries\RetornoPadrao();
			$post = $this->request->getPost();
			if (isset($post['metodo']) && $post['metodo'] == 'excluir') {
				$comentario = $pautasComentariosModel->find($post['id_comentario']);
				if ($comentario !== null && $comentario['colaboradores_id'] == $this->session->get('colaboradores')['id']) {
					$comentario['atualizado'] = $pautasComentariosModel->getNow();
					$pautasComentariosModel->save($comentario);
					$pautasComentariosModel->db->transStart();
					$pautasComentariosModel->delete($comentario['id']);
					$pautasComentariosModel->db->transComplete();
					$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'excluiu', 'pautas', 'o comentário na pauta', $idPauta, true);
					return $retorno->retorno(true, 'Comentário excluído com sucesso', true);
				}
				return $retorno->retorno(false, 'Erro ao excluir o comentário.', true);
			}
			if (isset($post['metodo']) && $post['metodo'] == 'inserir' && trim($post['comentario']) !== '') {
				$comentario = [
					'id' => $pautasComentariosModel->getNovaUUID(),
					'colaboradores_id' => $this->session->get('colaboradores')['id'],
					'pautas_id' => $idPauta,
					'comentario' => htmlspecialchars($post['comentario'], ENT_QUOTES, 'UTF-8')
				];
				$pautasComentariosModel->db->transStart();
				$save = $pautasComentariosModel->insert($comentario);
				$pautasComentariosModel->db->transComplete();
				$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'comentou', 'pautas', 'na pauta', $idPauta, true);
				return $retorno->retorno(true, 'Comentário feito com sucesso.', true);
			}
			if (isset($post['metodo']) && $post['metodo'] == 'alterar' && trim($post['id_comentario']) !== '') {
				$comentario = $pautasComentariosModel->find($post['id_comentario']);
				if ($comentario !== null && $comentario['colaboradores_id'] == $this->session->get('colaboradores')['id']) {
					$comentario['atualizado'] = $pautasComentariosModel->getNow();
					$comentario['comentario'] = htmlspecialchars($post['comentario'], ENT_QUOTES, 'UTF-8');
					$pautasComentariosModel->db->transStart();
					$pautasComentariosModel->save($comentario);
					$pautasComentariosModel->db->transComplete();
					$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'alterou', 'pautas', 'o comentário na pauta', $idPauta, true);
					return $retorno->retorno(true, 'Comentário alterado com sucesso.', true);
				}
				return $retorno->retorno(false, 'Erro ao excluir o comentário.', true);
			}
			return $retorno->retorno(false, 'Erro ao salvar comentário', true);
		} else {
			$comentarios = $pautasComentariosModel->getComentarios($idPauta);
			return view('template/templateComentarios', array('comentarios' => $comentarios, 'colaborador' => $this->session->get('colaboradores')['id']));
		}
	}

	private function getInformacaoLink($post)
	{
		$retorno = new \App\Libraries\RetornoPadrao();
		$main_url = $post['link_pauta'];
		@$str = file_get_contents($main_url);

		if ($str === false) {
			return $retorno->retorno(false, 'Não foi possível trazer informações da pauta automaticamente.', true);
		}

		if (strlen($str) > 0) {
			$str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
			preg_match("/\<title\>(.*)\<\/title\>/i", $str, $title);
		}

		$b = $main_url;
		@$url = parse_url($b);
		@$tags = get_meta_tags($main_url);

		$titulo = null;
		if (isset($tags['twitter:title'])) {
			$titulo = @$tags['twitter:title'];
		} elseif (isset($tags['title'])) {
			$titulo = @$tags['title'];
		} else {
			$titulo = null;
		}

		$descricao = null;
		if (isset($tags['twitter:description'])) {
			$descricao = $tags['twitter:description'];
		} elseif (isset($tags['description'])) {
			$descricao = $tags['description'];
		} else {
			$descricao = null;
		}

		$img = null;

		$d = new \DOMDocument();
		// if (empty($str) && ($titulo == null && $descricao == null)) {
		// 	return $retorno->retorno(false, 'Não foi possível trazer informações da pauta automaticamente.', true);
		// }
		@$d->loadHTML($str);
		$xp = new \DOMXPath($d);

		if ($titulo == '') {
			foreach ($xp->query("//h1") as $i) {
				$titulo = $i->nodeValue;
			}
		}
		if ($titulo == '') {
			foreach ($xp->query("//h2") as $i) {
				$titulo = $i->nodeValue;
			}
		}
		if ($titulo == '') {
			foreach ($xp->query("//h3") as $i) {
				$titulo = $i->nodeValue;
			}
		}
		if ($titulo == '') {
			foreach ($xp->query("//h4") as $i) {
				$titulo = $i->nodeValue;
			}
		}
		if ($titulo == '') {
			foreach ($xp->query("//title") as $i) {
				$titulo = $i->nodeValue;
			}
		}
		if ($descricao == '') {
			foreach ($xp->query("//p") as $i) {
				$descricao = $i->nodeValue;
			}
		}
		foreach ($xp->query("//meta[@property='og:image']") as $el) {
			$l2 = parse_url($el->getAttribute("content"));
			if (isset($l2['scheme'])) {
				$img = $el->getAttribute("content");
			}
		}
		if ($img == null) {
			foreach ($xp->query("//img") as $el) {
				if ($img == null && $el->getAttribute('alt') != '') {
					if (strstr($el->getAttribute('src'), 'https://') || strstr($el->getAttribute('src'), 'http://')) {
						$img = $el->getAttribute('src');
					} else {
						$linkImagem = explode('/', str_replace('https://', '', $main_url))[0];
						$img = 'https://' . $linkImagem . $el->getAttribute('src');
					}
				}
			}
		}


		$dias = null;

		foreach ($xp->query("//meta[@property='article:published_time']") as $el) {
			$content = $el->getAttribute("content");
			if ($content !== NULL && !empty($content)) {
				$time = strtotime($content);
				$agora = Time::now();
				$time = Time::parse(date('Y-m-d', $time));
				$dias = $time->difference($agora);
			}
		}

		if ($dias == null) {
			foreach ($xp->query("//script") as $i) {
				if (strpos($i->nodeValue, "datePublished") > 0) {

					if (strpos($i->nodeValue, 'datePublished":"') > 0 && $dias === null) {
						$x = explode('datePublished":"', $i->nodeValue)[1];
						$x = explode('"', $x)[0];
						$time = strtotime($x);
						$agora = Time::now();
						$time = Time::parse(date('Y-m-d', $time));
						$dias = $time->difference($agora);
					}

					$json = json_decode($i->nodeValue);
					if ($json !== NULL && is_array($json) && $dias === null) {
						foreach ($json as $j) {
							if ($j !== NULL && is_object($j) && isset($j->datePublished)) {
								$time = strtotime($j->datePublished);
								$agora = Time::now();
								$time = Time::parse(date('Y-m-d', $time));
								$dias = $time->difference($agora);
							}
						}
					}

					if ($json !== NULL && is_object($json) && isset($json->datePublished) && $dias === null) {
						$time = strtotime($json->datePublished);
						$agora = Time::now();
						$time = Time::parse(date('Y-m-d', $time));

						$dias = $time->difference($agora);
					}
				}
			}
		}

		if ($titulo != '' && $descricao != '') {
			if (!mb_detect_encoding($titulo, 'UTF-8', true)) {
				$titulo = utf8_encode($titulo);
			}
			if (!mb_detect_encoding($descricao, 'UTF-8', true)) {
				$descricao = utf8_encode($descricao);
			}
			if (!mb_detect_encoding($img, 'UTF-8', true)) {
				$img = utf8_encode($img);
			}

			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$dataMaximaPauta = (int) $configuracaoModel->find('pauta_dias_antigo')['config_valor'];

			$a = explode('://', $img);
			if (count($a) > 1) {
				$b = explode('/', $a[1]);
				foreach ($b as $k => $c) {
					$b[$k] = rawurlencode($c);
				}
				$b = implode('/', $b);
				$a[1] = $b;
			}
			$img = implode('://', $a);

			if (empty($img) || !is_array(@getimagesize($img))) {
				$img = "";
			}

			$retorno = [
				'status' => true,
				'titulo' => html_entity_decode($titulo),
				'texto' => html_entity_decode($descricao),
				'imagem' => $img,
				'dias' => ($dias !== null) ? ($dias->days) : ($dias),
				'mensagem' => ($dias !== null && $dias->days > $dataMaximaPauta) ? ('ATENÇÃO! A pauta é de mais de ' . $dataMaximaPauta . ' dias atrás. Ela será marcada como antiga.') : (NULL)
			];
			return json_encode($retorno);
		} else {
			return $retorno->retorno(false, 'Não foi possível trazer informações da pauta automaticamente.', true);
		}
	}

	private function gravarPautas($tipo, $dados, $id = null)
	{
		$pautasModel = new \App\Models\PautasModel();
		$retorno = null;
		$acao = false;
		$pautasModel->db->transStart();
		switch ($tipo) {
			case 'update':
				$retorno = $pautasModel->update($id, $dados);
				$acao = 'alterou';
				break;
			case 'insert':
				$retorno = $pautasModel->insert($dados);
				$acao = 'cadastrou';
				break;
			case 'save':
				$retorno = $pautasModel->save($dados);
				$acao = 'alterou';
				break;
			case 'delete':
				$retorno = $pautasModel->delete($id);
				break;
			default:
				$retorno = false;
				break;
		}
		$pautasModel->db->transComplete();

		if ($acao !== false && ($id != null || !is_bool($retorno))) {
			$sujeito = $this->session->get('colaboradores')['id'];
			$idObjeto = ($id == null) ? ($retorno) : ($id);
			$this->colaboradoresNotificacoes->cadastraNotificacao($sujeito, $acao, 'pautas', 'a pauta', $idObjeto, true);
		}

		return $retorno;
	}

}
