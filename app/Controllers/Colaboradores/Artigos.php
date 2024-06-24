<?php

namespace App\Controllers\Colaboradores;

use App\Controllers\BaseController;

use CodeIgniter\I18n\Time;

use App\Libraries\VerificaPermissao;
use App\Libraries\ColaboradoresNotificacoes;
use App\Libraries\ArtigosHistoricos;
use App\Libraries\ArtigosMarcacao;
use PHPUnit\Framework\Constraint\ExceptionMessageRegularExpression;

class Artigos extends BaseController
{
	public $verificaPermissao;
	public $artigosHistoricos;
	public $artigosMarcacao;
	public $iniciaVariavel;
	protected $colaboradoresNotificacoes;
	function __construct()
	{
		$this->verificaPermissao = new verificaPermissao();
		$this->artigosHistoricos = new ArtigosHistoricos;
		$this->artigosMarcacao = new ArtigosMarcacao;
		$this->colaboradoresNotificacoes = new ColaboradoresNotificacoes();
		helper('url_friendly');
		$this->iniciaVariavel = [
			'titulo' => null,
			'pauta' => array(),
			'fase_producao' => null,
			//'categorias_artigo' => array(),
			'artigo' => [
				'id' => null,
				'link' => null,
				'titulo' => null,
				'gancho' => null,
				'texto_original' => null,
				'referencias' => null,
				'imagem' => null
			],
			'historico' => null
		];
	}
	public function dashboard()
	{
		$data = array();
		$data['titulo'] = 'Listagem de Todos os Artigos';

		//Se usuário tem acesso a escritor
		$this->verificaPermissao->PermiteAcesso('2');

		return view('colaboradores/colaborador_dashboard', $data);
	}

	public function cadastrar($artigoId = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('2');
		$data = $this->iniciaVariavel;
		$data['fase_producao'] = '1';

		//Carrega formulário artigo apenas para cadastro
		if ($artigoId == null && $this->request->getMethod() == 'get' && $this->request->getGet('pauta') !== null) {
			$pautaId = $this->request->getGet('pauta');
			$pautasModel = new \App\Models\PautasModel();
			$pauta = $pautasModel->find($pautaId);
			if (!empty($pauta)) {
				$data['pauta'] = $pauta;
				$data['artigo']['titulo'] = $pauta['titulo'];
				$data['artigo']['texto_original'] = $pauta['texto'];
			}
		}

		//Carrega formulário artigo para edição
		if ($artigoId !== NULL) {
			$artigosModel = new \App\Models\ArtigosModel();
			$artigo = $artigosModel->find($artigoId);
			if ($artigo === NULL || empty($artigo) || $artigo['fase_producao_id'] !== '1') {
				return redirect()->to(base_url() . 'colaboradores/artigos/cadastrar/');
			}
			$data['artigo'] = $artigo;
		}

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['artigo_tamanho_maximo'] = (int) $configuracaoModel->find('artigo_tamanho_maximo')['config_valor'];
		$config['artigo_tamanho_minimo'] = (int) $configuracaoModel->find('artigo_tamanho_minimo')['config_valor'];
		$config['artigo_regras_escrever'] = $configuracaoModel->find('artigo_regras_escrever')['config_valor'];
		$data['config'] = $config;

		$data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);
		$data['cadastro'] = ($artigoId === NULL) ? (true) : (false);
		return view('colaboradores/colaborador_artigos_form', $data);
	}

	public function salvar($artigoId = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('2');
		$retorno = new \App\Libraries\RetornoPadrao();
		$validaFormularios = new \App\Libraries\ValidaFormularios();

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'O método só pode ser acessado via AJAX.', true);
		}

		if (!$this->request->getMethod() == 'post') {
			return $retorno->retorno(false, 'Dados não informados.', true);
		}

		$post = $this->request->getPost();
		$valida = $validaFormularios->validaFormularioArtigoSalvar($post, ($this->request->getGet('pauta')) ? (true) : (false));
		if (empty($valida->getErrors())) {
			if ($artigoId === NULL) {
				$retornoGravado = $this->cadastrarArtigo();
			}
			if ($artigoId !== NULL) {
				$retornoGravado = $this->alterarArtigo($artigoId);
			}
			if ($retornoGravado != false) {
				return $retorno->retorno(true, 'Artigo salvo com sucesso.', true, array('artigoId' => $retornoGravado));
			} else {
				return $retorno->retorno(false, 'Ocorreu um erro na hora de salvar.', true);
			}
		} else {
			return $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), true);
		}
	}

	public function meusArtigos()
	{
		$this->verificaPermissao->PermiteAcesso('2');
		$data['titulo'] = 'Meus artigos';

		$artigosModel = new \App\Models\ArtigosModel();
		$usuarioId = $this->session->get('colaboradores')['id'];
		$artigos = $artigosModel->where('escrito_colaboradores_id', $usuarioId)->get()->getResultArray();

		$data['resumo'] = array();
		$data['resumo']['escritos'] = 0;
		$data['resumo']['publicados'] = 0;
		$data['resumo']['palavras_totais'] = 0;
		$data['resumo']['total_mes'] = 0;
		$data['resumo']['autoral_mes'] = 0;
		if (!empty($artigos)) {
			foreach ($artigos as $artigo) {
				$data['resumo']['palavras_totais'] += $artigo['palavras_escritor'];
				$data['resumo']['escritos']++;
				if ($artigo['fase_producao_id'] == '6' || $artigo['fase_producao_id'] == '7') {
					$data['resumo']['publicados']++;
				}
			}
		}

		$artigosModel = new \App\Models\ArtigosModel();
		$artigos = $artigosModel->where('fase_producao_id', '6')->get()->getResultArray();
		if (!empty($artigos)) {
			foreach ($artigos as $artigo) {
				$data['resumo']['total_mes']++;
				if ($artigo['escrito_colaboradores_id'] == $usuarioId) {
					$data['resumo']['autoral_mes']++;
				}
			}
		}

		return view('colaboradores/colaborador_artigos_list', $data);
	}

	public function meusArtigosList()
	{
		$this->verificaPermissao->PermiteAcesso('2');

		$artigosModel = new \App\Models\ArtigosModel();
		$usuarioId = $this->session->get('colaboradores')['id'];
		$data = array();

		$post = $this->request->getGet();
		if (empty($post)) {
			return false;
		}

		$artigosModel->select('artigos.*, fase_producao.nome as nome');
		$artigosModel->where('escrito_colaboradores_id', $usuarioId);
		$artigosModel->join('fase_producao', 'fase_producao.id = artigos.fase_producao_id');

		if ($post['tipo'] == 'emProducao') {

			$artigosModel->whereNotIn('fase_producao_id', array('6', '7'));
			$artigosModel->where('descartado', NULL);

			if ($post['fase_producao'] != "" && $post['fase_producao'] != NULL) {
				$artigosModel->where('fase_producao_id', $post['fase_producao']);
			}

			if ($post['titulo'] != "" && $post['titulo'] != NULL) {
				$artigosModel->like('titulo', $post['titulo'], 'both', null, true);
			}

			$artigosModel->orderBy('atualizado', 'DESC');
			$artigos = $artigosModel->get()->getResultArray();
			if (!empty($artigos)) {
				foreach ($artigos as $chave => $artigo) {
					$artigos[$chave]['cor'] = $this->getCorFaseProducao($artigo['fase_producao_id']);
				}
			}
			$data['artigos'] = $artigos;
			return view('template/templateColaboradoresArtigosProducaoList', $data);
		}

		if ($post['tipo'] == 'finalizado') {

			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$config = array();
			$config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];

			$artigosModel->whereIn('fase_producao_id', array('6', '7'));

			if ($post['titulo'] != "" && $post['titulo'] != NULL) {
				$artigosModel->like('titulo', $post['titulo'], 'both', null, true);
			}

			$artigosModel->orderBy('publicado', 'DESC');
			$artigos = $artigosModel->paginate($config['site_quantidade_listagem'], 'artigos');
			if (!empty($artigos)) {
				foreach ($artigos as $chave => $artigo) {
					$artigos[$chave]['cor'] = $this->getCorFaseProducao($artigo['fase_producao_id']);
				}
			}

			$data['artigosList'] = [
				'artigos' => $artigos,
				'pager' => $artigosModel->pager
			];
			return view('template/templateColaboradoresArtigosFinalizadoList', $data);
		}
	}

	public function descartar($idArtigo)
	{
		$this->verificaPermissao->PermiteAcesso('2');

		$retorno = new \App\Libraries\RetornoPadrao();
		$artigosModel = new \App\Models\ArtigosModel();
		$permitido = false;

		$artigo = $artigosModel->find($idArtigo);
		if (empty($artigo) || $artigo == null) {
			return $retorno->retorno(false, 'Artigo não encontrado.', true);
		}

		$colaborador_permissoes = $this->session->get('colaboradores')['permissoes'];
		$colaborador = $this->session->get('colaboradores')['id'];
		if (in_array('7', $colaborador_permissoes)) {
			$permitido = true;
		}

		if ($permitido === false && in_array($artigo['fase_producao_id'], array(1, 2, 3, 4, 5))) {
			if ($artigo['fase_producao_id'] == '1' && in_array('2', $colaborador_permissoes) && $artigo['escrito_colaboradores_id'] == $colaborador) {
				$permitido = true;
			}
			if ($artigo['fase_producao_id'] == '2' && in_array('3', $colaborador_permissoes)) {
				$permitido = true;
			}
			if ($artigo['fase_producao_id'] == '3' && in_array('4', $colaborador_permissoes)) {
				$permitido = true;
			}
			if ($artigo['fase_producao_id'] == '4' && in_array('5', $colaborador_permissoes)) {
				$permitido = true;
			}
		}

		if ($permitido) {
			$artigoDescartar = $this->artigoDescartar($idArtigo);
			if ($artigoDescartar === true) {
				$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'descartou', 'artigos', 'o artigo', $idArtigo);
				return $retorno->retorno(true, 'Artigo descartado com sucesso.', true);
			} else {
				return $retorno->retorno(false, 'Houve um erro ao descartar o artigo.', true);
			}
		}

		return $retorno->retorno(false, 'Você não tem permissão para descartar o artigo.', true);

	}

	public function salvarImagem($artigoId = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('2');
		$artigosModel = new \App\Models\ArtigosModel();
		$retorno = new \App\Libraries\RetornoPadrao();
		if ($artigoId == NULL) {
			return $retorno->retorno(false, 'Artigo não informado.', true);
		} else {
			$artigo = $artigosModel->find($artigoId);
			if ($artigo === NULL || empty($artigo)) {
				return $retorno->retorno(false, 'Artigo não encontrado.', true);
			}
			$permitido = false;
			$colaborador_permissoes = $this->session->get('colaboradores')['permissoes'];
			$colaborador = $this->session->get('colaboradores')['id'];
			if (in_array('7', $colaborador_permissoes)) {
				$permitido = true;
			}
			if ($artigo['fase_producao_id'] == '1' && in_array('2', $colaborador_permissoes) && $artigo['escrito_colaboradores_id'] == $colaborador) {
				$permitido = true;
			}
			if ($artigo['fase_producao_id'] == '2' && in_array('3', $colaborador_permissoes)) {
				$permitido = true;
			}
			if ($permitido) {
				if ($this->request->getMethod() == 'post') {
					$validaFormularios = new \App\Libraries\ValidaFormularios();
					$imagem = $this->request->getFile('imagem');
					$valida = $validaFormularios->validaFormularioArtigoImagem();
					if (empty($valida->getErrors())) {
						if ($imagem->getName() != '') {
							$nome_arquivo = $artigo['id'] . '.' . $imagem->guessExtension();
							if ($imagem->move('public/assets/imagem', $nome_arquivo, true)) {
								$artigo['imagem'] = base_url('public/assets/imagem/' . $nome_arquivo);
								$isAtualizado = $this->gravarArtigos('update', $artigo, $artigoId);
								$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'alterou', 'artigos', 'o artigo', $artigoId);
								$this->artigosHistoricos->cadastraHistorico($artigoId, 'alterou', $this->session->get('colaboradores')['id']);
								if ($isAtualizado === true) {
									return $retorno->retorno(true, 'Imagem atualizada.', true);
								} else {
									return $retorno->retorno(false, 'Ocorreu um erro ao atualizar a imagem.', true);
								}
							}
						}
					} else {
						return $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), true);
					}
				} else {
					return $retorno->retorno(false, 'Imagem não enviada.', true);
				}
			}
			return $retorno->retorno(false, 'Imagem não atualizada. Você não tem permissão para alterar este artigo.', true);
		}
	}

	public function verificaPautaEscrita($artigoId = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('2');
		$retorno = new \App\Libraries\RetornoPadrao();

		$artigosModel = new \App\Models\ArtigosModel();
		$data = array();

		$post = $this->request->getPost();
		if (empty($post) || !isset($post['link'])) {
			return false;
		}
		if ($post['link'] !== NULL && $post['link'] !== "") {
			$artigosModel->like('link', $post['link']);
			$artigosModel->where('descartado', NULL);
			if ($artigoId !== NULL) {
				$artigosModel->whereNotIn('id', $artigoId);
			}
			$artigos = $artigosModel->get()->getResultArray();
			$contador = count($artigos);
			if ($contador > 0) {
				return $retorno->retorno(false, 'ATENÇÃO! Já existe artigo sobre esta pauta.', true);
			} else {
				return $retorno->retorno(true, '', true);
			}
		}

	}

	public function submeter($artigoId = NULL)
	{
		$retorno = new \App\Libraries\RetornoPadrao();
		if ($artigoId === NULL) {
			return $retorno->retorno(false, 'Artigo não informado.', true);
		}

		$artigosModel = new \App\Models\ArtigosModel();
		$artigo = $artigosModel->find($artigoId);
		if (empty($artigo) || $artigo === NULL) {
			return $retorno->retorno(false, 'Artigo não encontrado.', true);
		}

		if (!$this->session->has('colaboradores')) {
			return redirect()->to(base_url() . 'site/login');
		}

		$validaFormularios = new \App\Libraries\ValidaFormularios();
		$permissoes = $this->session->get('colaboradores')['permissoes'];
		$colaborador = $this->session->get('colaboradores')['id'];

		// DE ESCRITA PARA REVISAR
		if ($artigo['fase_producao_id'] == '1') {
			$this->verificaPermissao->PermiteAcesso('2');
			if($colaborador != $artigo['escrito_colaboradores_id']) {
				return $retorno->retorno(false, 'Apenas o escritor pode submeter o artigo para revisão.', true);
			}
			$valida = $validaFormularios->validaFormularioArtigo($artigo, true);
			if (empty($valida->getErrors())) {
				$configuracaoModel = new \App\Models\ConfiguracaoModel();
				$config = array();
				$config['artigo_tamanho_maximo'] = (int) $configuracaoModel->find('artigo_tamanho_maximo')['config_valor'];
				$config['artigo_tamanho_minimo'] = (int) $configuracaoModel->find('artigo_tamanho_minimo')['config_valor'];
				$palavras = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $artigo['texto_original']));
				if($palavras <= $config['artigo_tamanho_maximo'] && $palavras >= $config['artigo_tamanho_minimo']) {
					$isGravado = $this->artigoProximo($artigoId);
					if($isGravado === true) {
						return $retorno->retorno(true, 'Seu artigo foi submetido para revisão com sucesso.', true);
					} else {
						return $retorno->retorno(false, 'Ocorreu um erro ao submeter para revisão.', true);
					}
				} else {
					return $retorno->retorno(false, 'O texto possui '.number_format($palavras,0,',','.').' palavras e deve estar entre '.number_format($config['artigo_tamanho_minimo'],0,',','.').' e '.number_format($config['artigo_tamanho_maximo'],0,',','.').' para ser aceito.', true);
				}
			} else {
				return $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), true);
			}
		}



		die();
	}






















	/*

					   public function index($idColaborador = NULL)
					   {
						   $data = array();
						   $data['titulo'] = 'Listagem de Todos os Artigos';

						   //Se usuário tem acesso a escritor
						   $this->verificaPermissao->PermiteAcesso('1');
						   $artigosModel = new \App\Models\ArtigosModel();
						   $artigos = $artigosModel->getArtigosUsuario($this->session->get('colaboradores')['id']);
						   $data['permissoes'] = $this->session->get('colaboradores')['permissoes'];
						   $data['usuario'] = $this->session->get('colaboradores')['id'];

						   $configuracaoModel = new \App\Models\ConfiguracaoModel();
						   $faseProducaoModel = new \App\Models\FaseProducaoModel();
						   $data['fase_producao'] = $faseProducaoModel->findAll();
						   $config = array();
						   $config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];

						   $data['artigosList'] = [
							   'artigos' => $artigos->paginate($config['site_quantidade_listagem'], 'artigos'),
							   'pager' => $artigos->pager
						   ];

						   if ($idColaborador != NULL) {
							   $idColaborador = (int) $idColaborador;
							   if ($idColaborador != 0) {
								   $colaboradoresModel = new \App\Models\ColaboradoresModel();
								   $data['colaborador'] = $colaboradoresModel->find($idColaborador);
							   }
						   }
						   return view('colaboradores/artigos_search_list', $data);
					   }

					   public function artigosList()
					   {
						   $configuracaoModel = new \App\Models\ConfiguracaoModel();
						   $config = array();
						   $config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];
						   $data = array();

						   $this->verificaPermissao->PermiteAcesso('2');
						   $artigosModel = new \App\Models\ArtigosModel();
						   if ($this->request->getMethod() == 'get') {
							   $get = service('request')->getGet();

							   $data['permissoes'] = $this->session->get('colaboradores')['permissoes'];
							   $data['usuario'] = $this->session->get('colaboradores')['id'];

							   $artigosModel
								   ->select('
								   artigos.*,
								   A.apelido AS sugerido,
								   B.apelido AS escrito,
								   C.apelido AS revisado,
								   D.apelido AS narrado,
								   E.apelido AS produzido,
								   F.apelido AS publicado,
								   G.apelido AS descartado,
								   H.apelido AS marcado
							   ')
								   ->join('colaboradores A', 'artigos.sugerido_colaboradores_id = A.id', 'LEFT')
								   ->join('colaboradores B', 'artigos.escrito_colaboradores_id = B.id', 'LEFT')
								   ->join('colaboradores C', 'artigos.revisado_colaboradores_id = C.id', 'LEFT')
								   ->join('colaboradores D', 'artigos.narrado_colaboradores_id = D.id', 'LEFT')
								   ->join('colaboradores E', 'artigos.produzido_colaboradores_id = E.id', 'LEFT')
								   ->join('colaboradores F', 'artigos.publicado_colaboradores_id = F.id', 'LEFT')
								   ->join('colaboradores G', 'artigos.descartado_colaboradores_id = G.id', 'LEFT')
								   ->join('colaboradores H', 'artigos.marcado_colaboradores_id = H.id', 'LEFT');

							   if (!is_null($get['titulo']) && !empty($get['titulo']) && $get['titulo'] != '') {
								   $artigosModel->like('artigos.titulo', $get['titulo']);
							   }

							   if (!is_null($get['fase_producao']) && !empty($get['fase_producao']) && $get['fase_producao'] != '') {
								   $artigosModel->where('artigos.fase_producao_id', $get['fase_producao']);
							   }

							   if (!is_null($get['descartado']) && !empty($get['descartado']) && $get['descartado'] != 'N') {
								   $artigosModel->onlyDeleted();
							   }

							   if (!is_null($get['colaborador']) && !empty($get['colaborador']) && $get['colaborador'] != '') {
								   if (is_null($get['atribuicao']) || empty($get['atribuicao']) || $get['atribuicao'] == '') {
									   $artigosModel
										   ->where('(
										   A.apelido like \'%' . $get['colaborador'] . '%\' OR 
										   B.apelido like \'%' . $get['colaborador'] . '%\' OR 
										   C.apelido like \'%' . $get['colaborador'] . '%\' OR 
										   D.apelido like \'%' . $get['colaborador'] . '%\' OR 
										   E.apelido like \'%' . $get['colaborador'] . '%\' OR 
										   F.apelido like \'%' . $get['colaborador'] . '%\' OR 
										   G.apelido like \'%' . $get['colaborador'] . '%\' OR 
										   H.apelido like \'%' . $get['colaborador'] . '%\'
									   )');
								   } elseif ($get['atribuicao'] == 'sugerido') {
									   $artigosModel->like('A.apelido', $get['colaborador']);
								   } elseif ($get['atribuicao'] == 'escrito') {
									   $artigosModel->like('B.apelido', $get['colaborador']);
								   } elseif ($get['atribuicao'] == 'revisado') {
									   $artigosModel->like('C.apelido', $get['colaborador']);
								   } elseif ($get['atribuicao'] == 'narrado') {
									   $artigosModel->like('D.apelido', $get['colaborador']);
								   } elseif ($get['atribuicao'] == 'produzido') {
									   $artigosModel->like('E.apelido', $get['colaborador']);
								   } elseif ($get['atribuicao'] == 'marcado') {
									   $artigosModel->like('H.apelido', $get['colaborador']);
								   }
							   }


							   $artigos = $artigosModel;
							   $data['artigosList'] = [
								   'artigos' => $artigos->paginate($config['site_quantidade_listagem'], 'artigos'),
								   'pager' => $artigos->pager
							   ];
						   }
						   return view('template/templateArtigosList', $data);
					   }

					   public function detalhe($artigoId = null)
					   {
						   $this->verificaPermissao->PermiteAcesso('1');
						   $artigosModel = new \App\Models\ArtigosModel();

						   $data = $this->iniciaVariavel;
						   $data['fase_producao'] = '1';
						   $data['titulo'] = 'Detalhe do Artigo';

						   if ($artigoId == NULL) {
							   $data = array();
							   $data['titulo'] = 'Meus Artigos Cadastrados';

							   //Se usuário tem acesso a escritor
							   $this->verificaPermissao->PermiteAcesso('1');
							   $artigosModel = new \App\Models\ArtigosModel();
							   $artigos = $artigosModel->getArtigosUsuario($this->session->get('colaboradores')['id']);
							   $data['permissoes'] = $this->session->get('colaboradores')['permissoes'];
							   $data['usuario'] = $this->session->get('colaboradores')['id'];

							   $configuracaoModel = new \App\Models\ConfiguracaoModel();
							   $config = array();
							   $config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];
							   $data['artigosList'] = [
								   'artigos' => $artigos->paginate($config['site_quantidade_listagem'], 'artigos'),
								   'pager' => $artigos->pager
							   ];
							   return view('colaboradores/artigos_list', $data);
						   } else {

							   $artigo = $artigosModel->where('id', $artigoId)->get()->getResultArray()[0];
							   $colaboradores = $artigosModel->getColaboradoresArtigo($artigoId)[0];
							   foreach ($colaboradores as $indice => $dado) {
								   $artigo['colaboradores'][$indice] = $dado;
							   }
							   //$artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
							   //$data['categorias_artigo'] = $artigosCategoriasModel->getCategoriasArtigo($artigoId);
							   $data['artigo'] = $artigo;
							   $data['texto'] = $this->criaTextoDinamizado($artigo);

							   $data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);
							   return view('colaboradores/artigos_detail', $data);
						   }
					   }

					   

					   public function gravar($id_artigo)
					   {
						   $this->verificaPermissao->PermiteAcesso('2');
						   $retorno = new \App\Libraries\RetornoPadrao();
						   $data = $this->iniciaVariavel;
						   $data['fase_producao'] = '1';
						   $data['titulo'] = 'Novo artigo';
						   $validaFormularios = new \App\Libraries\ValidaFormularios();

						   // ALTERAÇÃO DO ARTIGO
						   // SÓ PODE SER ALTERADO PELO ESCRITOR E SE O ARTIGO ESTIVER NA ETAPA DE REVISÃO OU ESCRITA
						   if ($id_artigo != null) {

							   $artigosModel = new \App\Models\ArtigosModel();
							   $artigo = $artigosModel->find($id_artigo);
							   $valida = null;
							   $post = $this->request->getPost();
							   //$artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
							   //$artigosCategorias = $artigosCategoriasModel->getCategoriasArtigo($id_artigo);
							   // $data['categorias_artigo'] = array();
							   // foreach ($artigosCategorias as $ac) {
							   // 	$data['categorias_artigo'][] = $ac['id'];
							   // }

							   if ($artigo == null || !in_array($artigo['fase_producao_id'], array('1', '2')) || $artigo['escrito_colaboradores_id'] != $this->session->get('colaboradores')['id']) {
								   return redirect()->to(base_url() . 'colaboradores/artigos/');
							   }

							   //Altera artigo
							   if ($id_artigo != null && $this->request->getMethod() == 'post') {
								   $valida = $validaFormularios->validaFormularioArtigo($post, false);
								   if (empty($valida->getErrors())) {
									   if (is_array(@getimagesize($post['imagem']))) {
										   $artigoId = $this->alterarArtigo($id_artigo);
										   if ($artigoId != false) {
											   $data['retorno'] = $retorno->retorno(true, 'Artigo salvo com sucesso', false);
										   }
									   } else {
										   $data['retorno'] = $retorno->retorno(false, 'O link informado não é uma imagem.', false);
									   }
								   } else {
									   $data['retorno'] = $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), false);
								   }
								   $data['artigo'] = $post;
								   //$data['categorias_artigo'] = isset($post['categorias']) ? ($post['categorias']) : (array());
							   } else {
								   $data['artigo'] = $artigosModel->find($id_artigo);
							   }
						   }

						   // CADASTRO DO ARTIGO
						   if ($id_artigo == null && $this->request->getMethod() == 'post') {
							   $post = $this->request->getPost();
							   $valida = $validaFormularios->validaFormularioArtigo($post, ($this->request->getGet('pauta')) ? (true) : (false));
							   if (empty($valida->getErrors())) {

								   $imagem = $this->request->getFile('imagem');
								   if ($imagem->getName() != '') {
									   $nome_arquivo = $artigo['id'] . '.' . $imagem->guessExtension();
									   if ($imagem->move('public/assets/artigo', $nome_arquivo, true)) {
										   $artigo['arquivo_audio'] = base_url('public/assets/artigo/' . $nome_arquivo);

										   $artigo['narrado_colaboradores_id'] = $this->session->get('colaboradores')['id'];

										   $artigo['palavras_narrador'] = $artigo['palavras_revisor'];
										   $faseProducaoModel = new \App\Models\FaseProducaoModel();
										   $faseProducao = $faseProducaoModel->find($artigo['fase_producao_id']);
										   $artigo['fase_producao_id'] = $faseProducao['etapa_posterior'];

										   //$retorno = $artigosModel->save($artigo);
										   $retorno = $this->gravarArtigos('save', $artigo);
										   $this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'narrou', 'artigos', 'o artigo', $artigoId);
										   $this->artigosHistoricos->cadastraHistorico($artigoId, 'narrou', $this->session->get('colaboradores')['id']);
										   $this->artigosMarcacao->desmarcarArtigo($artigoId);
										   if ($retorno === true) {
											   return redirect()->to(base_url() . 'colaboradores/artigos/narrar?status=true');
										   } else {
											   return redirect()->to(base_url() . 'colaboradores/artigos/narrar/' . $artigoId . '?status=false');
										   }
									   }
								   }

								   $artigoId = $this->cadastrarArtigo();
								   return redirect()->to(base_url() . 'colaboradores/artigos/cadastrar/' . $artigoId . '?status=true');
							   } else {
								   $data['retorno'] = $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), false);
								   if ($this->request->getGet('pauta') !== null) {
									   $pautasModel = new \App\Models\PautasModel();
									   $data['pauta'] = $pautasModel->find($this->request->getGet('pauta'));
								   }
								   $data['artigo'] = $post;
								   // $data['categorias_artigo'] = isset($post['categorias']) ? ($post['categorias']) : (array());
							   }
						   }
					   }

					   public function revisar($artigoId = NULL)
					   {
						   $this->verificaPermissao->PermiteAcesso('3');
						   $artigosModel = new \App\Models\ArtigosModel();
						   $validaFormularios = new \App\Libraries\ValidaFormularios();
						   $retorno = new \App\Libraries\RetornoPadrao();
						   $valida = null;
						   $data = $this->iniciaVariavel;
						   $data['fase_producao'] = '2';
						   $data['titulo'] = 'Revisão de Artigo';

						   if ($artigoId == NULL) {
							   $artigos = $artigosModel->getArtigos(2);
							   $data['permissoes'] = $this->session->get('colaboradores')['permissoes'];
							   $data['usuario'] = $this->session->get('colaboradores')['id'];

							   $configuracaoModel = new \App\Models\ConfiguracaoModel();
							   $config = array();
							   $config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];
							   $data['artigosList'] = [
								   'artigos' => $artigos->paginate($config['site_quantidade_listagem'], 'artigos'),
								   'pager' => $artigos->pager
							   ];
							   $data['titulo'] = 'Artigos Esperando Revisão';
							   return view('colaboradores/artigos_list', $data);
						   } else {

							   if ($this->request->getMethod() == 'get') {
								   $this->descartar($artigoId, 'revisar');
								   $this->reverterFase($artigoId, 'revisar');

								   if ($this->request->getGet('proximo') != NULL) {
									   $retorno = $this->artigoProximo($artigoId);
									   if ($retorno === true) {
										   return redirect()->to(base_url() . 'colaboradores/artigos/revisar?status=true');
									   } else {
										   return redirect()->to(base_url() . 'colaboradores/artigos/revisar/' . $artigoId . '?status=false');
									   }
								   }
							   }

							   $artigo = $artigosModel->find($artigoId);
							   if ($artigo == null || $artigo['fase_producao_id'] != 2 || $artigo['escrito_colaboradores_id'] == $this->session->get('colaboradores')['id']) {
								   return redirect()->to(base_url() . 'colaboradores/artigos/revisar');
							   }
							   if ($artigo['marcado_colaboradores_id'] != $this->session->get('colaboradores')['id']) {
								   return redirect()->to(base_url() . 'colaboradores/artigos/revisar');
							   }
							   // $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
							   // $artigosCategorias = $artigosCategoriasModel->getCategoriasArtigo($artigoId);
							   // $data['categorias_artigo'] = array();
							   // foreach ($artigosCategorias as $ac) {
							   // 	$data['categorias_artigo'][] = $ac['id'];
							   // }
							   $data['artigo'] = $artigo;

							   if ($this->request->getMethod() == 'post') {
								   $data['artigo'] = $this->request->getPost();
								   $data['artigo']['id'] = $artigoId;
								   $valida = $validaFormularios->validaFormularioArtigo($this->request->getPost(), false);
								   if (empty($valida->getErrors())) {

									   if (is_array(@getimagesize($data['artigo']['imagem']))) {
										   $status_alteracao = $this->revisarArtigo($artigoId);
										   if ($status_alteracao) {
											   $data['artigo']['texto_revisado'] = $artigo['texto_original'];
											   $data['retorno'] = $retorno->retorno(true, 'Revisão feita com sucesso.', false);
										   } else {
											   $data['retorno'] = $retorno->retorno(false, 'Houve um erro no cadastro da revisão.', false);
										   }
									   } else {
										   $data['retorno'] = $retorno->retorno(false, 'O link informado não é uma imagem.', false);
									   }

								   } else {
									   $data['retorno'] = $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), false);
								   }
							   } else {
								   if ($data['artigo']['fase_producao_id'] == 2 && $data['artigo']['texto_revisado'] != NULL) {
									   $data['artigo']['texto_original'] = $data['artigo']['texto_revisado'];
								   }
							   }

							   $widgetsSite = new \App\Libraries\WidgetsSite();
							   // $data['categorias'] = $widgetsSite->widgetCategorias();

							   $configuracaoModel = new \App\Models\ConfiguracaoModel();
							   $config = array();
							   $config['artigo_tamanho_maximo'] = (int) $configuracaoModel->find('artigo_tamanho_maximo')['config_valor'];
							   $config['artigo_tamanho_minimo'] = (int) $configuracaoModel->find('artigo_tamanho_minimo')['config_valor'];
							   $config['artigo_regras_revisar'] = $configuracaoModel->find('artigo_regras_revisar')['config_valor'];
							   $data['config'] = $config;

							   $data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);
							   return view('colaboradores/colaborador_artigos_form', $data);
						   }
					   }

					   public function narrar($artigoId = NULL)
					   {
						   $this->verificaPermissao->PermiteAcesso('4');
						   $artigosModel = new \App\Models\ArtigosModel();

						   $data = $this->iniciaVariavel;
						   $data['fase_producao'] = '3';
						   $data['titulo'] = 'Artigos Esperando Narração';

						   if ($artigoId == NULL) {
							   $artigos = $artigosModel->getArtigos(3);
							   $data['permissoes'] = $this->session->get('colaboradores')['permissoes'];
							   $data['usuario'] = $this->session->get('colaboradores')['id'];

							   $configuracaoModel = new \App\Models\ConfiguracaoModel();
							   $config = array();
							   $config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];
							   $data['artigosList'] = [
								   'artigos' => $artigos->paginate($config['site_quantidade_listagem'], 'artigos'),
								   'pager' => $artigos->pager
							   ];
							   return view('colaboradores/artigos_list', $data);
						   } else {
							   if ($this->request->getMethod() == 'get') {
								   $this->descartar($artigoId, 'narrar');
								   $this->reverterFase($artigoId, 'narrar');
							   }

							   if ($this->request->getMethod() == 'post') {
								   $artigo = $artigosModel->find($artigoId);
								   $audio = $this->request->getFile('audio');
								   $validaFormularios = new \App\Libraries\ValidaFormularios();
								   $retorno = new \App\Libraries\RetornoPadrao();
								   $valida = $validaFormularios->validaFormularioArtigoNarracaoFile();
								   if (empty($valida->getErrors())) {
									   if ($audio->getName() != '') {
										   $nome_arquivo = $artigo['id'] . '.' . $audio->guessExtension();
										   if ($audio->move('public/assets/audio', $nome_arquivo, true)) {
											   $artigo['arquivo_audio'] = base_url('public/assets/audio/' . $nome_arquivo);

											   $artigo['narrado_colaboradores_id'] = $this->session->get('colaboradores')['id'];

											   $artigo['palavras_narrador'] = $artigo['palavras_revisor'];
											   $faseProducaoModel = new \App\Models\FaseProducaoModel();
											   $faseProducao = $faseProducaoModel->find($artigo['fase_producao_id']);
											   $artigo['fase_producao_id'] = $faseProducao['etapa_posterior'];

											   //$retorno = $artigosModel->save($artigo);
											   $retorno = $this->gravarArtigos('save', $artigo);
											   $this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'narrou', 'artigos', 'o artigo', $artigoId);
											   $this->artigosHistoricos->cadastraHistorico($artigoId, 'narrou', $this->session->get('colaboradores')['id']);
											   $this->artigosMarcacao->desmarcarArtigo($artigoId);
											   if ($retorno === true) {
												   return redirect()->to(base_url() . 'colaboradores/artigos/narrar?status=true');
											   } else {
												   return redirect()->to(base_url() . 'colaboradores/artigos/narrar/' . $artigoId . '?status=false');
											   }
										   }
									   }
								   } else {
									   $data['retorno'] = $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), false);
								   }
							   }

							   $artigo = $artigosModel->find($artigoId);
							   if ($artigo == null || $artigo['fase_producao_id'] != 3) {
								   return redirect()->to(base_url() . 'colaboradores/artigos/narrar');
							   }
							   $colaboradores = $artigosModel->getColaboradoresArtigo($artigoId)[0];
							   foreach ($colaboradores as $indice => $dado) {
								   $artigo['colaboradores'][$indice] = $dado;
							   }
							   // $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
							   // $data['categorias_artigo'] = $artigosCategoriasModel->getCategoriasArtigo($artigoId);
							   $data['artigo'] = $artigo;
							   $data['texto'] = $this->criaTextoDinamizado($artigo);
							   $configuracaoModel = new \App\Models\ConfiguracaoModel();
							   $config['artigo_regras_narrar'] = $configuracaoModel->find('artigo_regras_narrar')['config_valor'];

							   $data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);
							   return view('colaboradores/artigos_detail', $data);
						   }
					   }

					   public function produzir($artigoId = null)
					   {
						   $this->verificaPermissao->PermiteAcesso('5');
						   $artigosModel = new \App\Models\ArtigosModel();

						   $data = $this->iniciaVariavel;
						   $data['fase_producao'] = '4';
						   $data['titulo'] = 'Artigos Esperando Produção';

						   if ($artigoId == NULL) {
							   $artigos = $artigosModel->getArtigos(4);
							   $data['permissoes'] = $this->session->get('colaboradores')['permissoes'];
							   $data['usuario'] = $this->session->get('colaboradores')['id'];

							   $configuracaoModel = new \App\Models\ConfiguracaoModel();
							   $config = array();
							   $config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];
							   $data['artigosList'] = [
								   'artigos' => $artigos->paginate($config['site_quantidade_listagem'], 'artigos'),
								   'pager' => $artigos->pager
							   ];
							   return view('colaboradores/artigos_list', $data);
						   } else {

							   if ($this->request->getMethod() == 'get') {
								   $this->descartar($artigoId, 'produzir');
								   $this->reverterFase($artigoId, 'produzir');
							   }

							   if ($this->request->getMethod() == 'post') {
								   $artigo = $artigosModel->find($artigoId);
								   $post = $this->request->getPost();
								   $validaFormularios = new \App\Libraries\ValidaFormularios();
								   $retorno = new \App\Libraries\RetornoPadrao();
								   $valida = $validaFormularios->validaFormularioProducao($post);
								   if (empty($valida->getErrors())) {
									   $artigo['produzido_colaboradores_id'] = $this->session->get('colaboradores')['id'];
									   $artigo['palavras_produtor'] = $artigo['palavras_revisor'];
									   $artigo['link_produzido'] = $post['video_link'];
									   $artigo['link_shorts'] = $post['shorts_link'];
									   $faseProducaoModel = new \App\Models\FaseProducaoModel();

									   $faseProducao = $faseProducaoModel->find($artigo['fase_producao_id']);
									   $artigo['fase_producao_id'] = $faseProducao['etapa_posterior'];

									   //$retorno = $artigosModel->save($artigo);
									   $retorno = $this->gravarArtigos('save', $artigo);
									   $this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'produziu', 'artigos', 'o artigo', $artigoId, true);
									   $this->artigosMarcacao->desmarcarArtigo($artigoId);
									   $this->artigosHistoricos->cadastraHistorico($artigoId, 'produziu', $this->session->get('colaboradores')['id']);
									   if ($retorno === true) {
										   return redirect()->to(base_url() . 'colaboradores/artigos/produzir?status=true');
									   } else {
										   return redirect()->to(base_url() . 'colaboradores/artigos/produzir/' . $artigoId . '?status=false');
									   }
								   } else {
									   $data['retorno'] = $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), false);
								   }
							   }

							   $artigo = $artigosModel->find($artigoId);
							   if ($artigo == null || $artigo['fase_producao_id'] != 4) {
								   return redirect()->to(base_url() . 'colaboradores/artigos/produzir');
							   }
							   $colaboradores = $artigosModel->getColaboradoresArtigo($artigoId)[0];
							   foreach ($colaboradores as $indice => $dado) {
								   $artigo['colaboradores'][$indice] = $dado;
							   }
							   // $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
							   // $data['categorias_artigo'] = $artigosCategoriasModel->getCategoriasArtigo($artigoId);
							   $data['artigo'] = $artigo;
							   $data['texto'] = $this->criaTextoDinamizado($artigo);
							   $configuracaoModel = new \App\Models\ConfiguracaoModel();
							   $config['artigo_regras_narrar'] = $configuracaoModel->find('artigo_regras_narrar')['config_valor'];

							   $data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);
							   return view('colaboradores/artigos_detail', $data);
						   }
					   }

					   public function publicar($artigoId = null)
					   {
						   $this->verificaPermissao->PermiteAcesso('6');
						   $artigosModel = new \App\Models\ArtigosModel();

						   $data = $this->iniciaVariavel;
						   $data['fase_producao'] = '5';
						   $data['titulo'] = 'Artigos Esperando Publicação';

						   if ($artigoId == NULL) {
							   $artigos = $artigosModel->getArtigos(5);
							   $data['permissoes'] = $this->session->get('colaboradores')['permissoes'];
							   $data['usuario'] = $this->session->get('colaboradores')['id'];

							   $configuracaoModel = new \App\Models\ConfiguracaoModel();
							   $config = array();
							   $config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];
							   $data['artigosList'] = [
								   'artigos' => $artigos->paginate($config['site_quantidade_listagem'], 'artigos'),
								   'pager' => $artigos->pager
							   ];
							   return view('colaboradores/artigos_list', $data);
						   } else {

							   if ($this->request->getMethod() == 'get') {
								   $this->descartar($artigoId, 'publicar');
								   $this->reverterFase($artigoId, 'publicar');
							   }

							   if ($this->request->getMethod() == 'post') {
								   $artigo = $artigosModel->find($artigoId);
								   $post = $this->request->getPost();
								   $validaFormularios = new \App\Libraries\ValidaFormularios();
								   $retorno = new \App\Libraries\RetornoPadrao();
								   $valida = $validaFormularios->validaFormularioPublicacao($post);
								   if (empty($valida->getErrors())) {
									   $artigo['publicado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
									   $artigo['link_video_youtube'] = $post['link_video_youtube'];
									   $artigo['publicado'] = $artigosModel->getNow();

									   //SE A QUANTIDADE DE PALAVRAS DO REVISOR FOR MENOR DO QUE A QUANTIDADE DO ESCRITOR, ABAIXA A QUANTIDADE DO ESCRITOR.
									   if ($artigo['palavras_escritor'] > str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $artigo['texto_revisado']))) {
										   $artigo['palavras_escritor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $artigo['texto_revisado']));
									   }

									   $faseProducaoModel = new \App\Models\FaseProducaoModel();

									   $faseProducao = $faseProducaoModel->find($artigo['fase_producao_id']);
									   $artigo['fase_producao_id'] = $faseProducao['etapa_posterior'];

									   //$retorno = $artigosModel->save($artigo);
									   $retorno = $this->gravarArtigos('save', $artigo);
									   $this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'publicou', 'artigos', 'o artigo', $artigoId, true);
									   $this->artigosMarcacao->desmarcarArtigo($artigoId);
									   $this->artigosHistoricos->cadastraHistorico($artigoId, 'publicou', $this->session->get('colaboradores')['id']);
									   if ($retorno === true) {
										   return redirect()->to(base_url() . 'colaboradores/artigos/publicar?status=true');
									   } else {
										   return redirect()->to(base_url() . 'colaboradores/artigos/publicar/' . $artigoId . '?status=false');
									   }
								   } else {
									   $data['retorno'] = $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), false);
								   }
							   }

							   $artigo = $artigosModel->find($artigoId);
							   if ($artigo == null || $artigo['fase_producao_id'] != 5) {
								   return redirect()->to(base_url() . 'colaboradores/artigos/publicar');
							   }
							   $colaboradores = $artigosModel->getColaboradoresArtigo($artigoId)[0];
							   foreach ($colaboradores as $indice => $dado) {
								   $artigo['colaboradores'][$indice] = $dado;
							   }
							   // $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
							   // $data['categorias_artigo'] = $artigosCategoriasModel->getCategoriasArtigo($artigoId);
							   $data['artigo'] = $artigo;

							   $data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);
							   return view('colaboradores/artigos_publish', $data);
						   }
					   }

					   public function geraDescricaoVideo($idArtigo = null)
					   {
						   $this->verificaPermissao->PermiteAcesso('6');

						   $retorno = new \App\Libraries\RetornoPadrao();
						   $configuracaoModel = new \App\Models\ConfiguracaoModel();
						   $artigosModel = new \App\Models\ArtigosModel();

						   if ($idArtigo == null) {
							   return $retorno->retorno(false, 'Artigo não informado, atualize a página', true);
						   }

						   if ($this->request->isAJAX()) {
							   $post = $this->request->getPost();
							   if (!isset($post['tags'])) {
								   return $retorno->retorno(false, 'Tags não foram fornecidas.', true);
							   }
							   $configuracao = $configuracaoModel->find('descricao_padrao_youtube');
							   $artigo = $artigosModel->find($idArtigo);
							   $descricaoVideo = '';
							   $descricaoVideo = str_replace('{referencias}', $artigo['referencias'], $configuracao['config_valor']);
							   $descricaoVideo = str_replace('{tags}', $post['tags'], $descricaoVideo);
							   return json_encode(['status' => true, 'descricao' => $descricaoVideo]);
						   }
					   }


					   public function previa($artigoId)
					   {
						   $this->verificaPermissao->PermiteAcesso('2');
						   $artigosModel = new \App\Models\ArtigosModel();

						   $data = $this->iniciaVariavel;
						   $data['fase_producao'] = '2';

						   if ($this->request->getMethod() == 'get') {
							   $this->descartar($artigoId, 'revisar');
							   $this->reverterFase($artigoId, 'revisar');
							   if ($this->request->getGet('proximo') != NULL) {
								   $retorno = $this->artigoProximo($artigoId);
								   if ($retorno === true) {
									   return redirect()->to(base_url() . 'colaboradores/artigos/revisar?status=true');
								   } else {
									   return redirect()->to(base_url() . 'colaboradores/artigos/revisar/' . $artigoId . '?status=false');
								   }
							   }
						   }

						   $artigo = $artigosModel->find($artigoId);
						   if ($artigo == null || $artigo['fase_producao_id'] != 2) {
							   return redirect()->to(base_url() . 'colaboradores/artigos/revisar');
						   }
						   $colaboradores = $artigosModel->getColaboradoresArtigo($artigoId)[0];
						   foreach ($colaboradores as $indice => $dado) {
							   $artigo['colaboradores'][$indice] = $dado;
						   }
						   // $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
						   // $data['categorias_artigo'] = $artigosCategoriasModel->getCategoriasArtigo($artigoId);
						   $data['artigo'] = $artigo;
						   $data['texto'] = $this->criaTextoDinamizado($artigo);

						   $data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);
						   return view('colaboradores/artigos_detail', $data);
					   }

					   public function comentarios($idArtigo)
					   {
						   $artigosComentariosModel = new \App\Models\ArtigosComentariosModel();
						   if ($this->request->getMethod() == 'post') {
							   $retorno = new \App\Libraries\RetornoPadrao();
							   $post = $post = $this->request->getPost();
							   if (isset($post['metodo']) && $post['metodo'] == 'excluir') {
								   $comentario = $artigosComentariosModel->find($post['id_comentario']);
								   if ($comentario !== null && $comentario['colaboradores_id'] == $this->session->get('colaboradores')['id']) {
									   $comentario['atualizado'] = $artigosComentariosModel->getNow();
									   $artigosComentariosModel->save($comentario);
									   $artigosComentariosModel->db->transStart();
									   $artigosComentariosModel->delete($comentario['id']);
									   $artigosComentariosModel->db->transComplete();
									   $this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'excluiu', 'artigos', 'o comentário do artigo', $idArtigo, true);
									   return $retorno->retorno(true, '', true);
								   }
								   return $retorno->retorno(false, 'Erro ao excluir o comentário.', true);
							   }
							   if (isset($post['metodo']) && $post['metodo'] == 'inserir' && trim($post['comentario']) !== '') {
								   $comentario = [
									   'id' => $artigosComentariosModel->getNovaUUID(),
									   'colaboradores_id' => $this->session->get('colaboradores')['id'],
									   'artigos_id' => $idArtigo,
									   'comentario' => htmlspecialchars($post['comentario'], ENT_QUOTES, 'UTF-8')
								   ];
								   $artigosComentariosModel->db->transStart();
								   $save = $artigosComentariosModel->insert($comentario);
								   $artigosComentariosModel->db->transComplete();
								   $this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'comentou', 'artigos', 'no artigo', $idArtigo, true);
								   return $retorno->retorno(true, '', true);
							   }
							   if (isset($post['metodo']) && $post['metodo'] == 'alterar' && trim($post['id_comentario']) !== '') {
								   $comentario = $artigosComentariosModel->find($post['id_comentario']);
								   if ($comentario !== null && $comentario['colaboradores_id'] == $this->session->get('colaboradores')['id']) {
									   $comentario['atualizado'] = $artigosComentariosModel->getNow();
									   $comentario['comentario'] = htmlspecialchars($post['comentario'], ENT_QUOTES, 'UTF-8');
									   $artigosComentariosModel->db->transStart();
									   $artigosComentariosModel->save($comentario);
									   $artigosComentariosModel->db->transComplete();
									   $this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'alterou', 'artigos', 'o comentário do artigo', $idArtigo, true);
									   return $retorno->retorno(true, '', true);
								   }
								   return $retorno->retorno(false, 'Erro ao excluir o comentário.', true);
							   }
							   return $retorno->retorno(false, 'Erro ao salvar comentário', true);
						   } else {
							   $comentarios = $artigosComentariosModel->getComentarios($idArtigo);
							   return view('template/templateComentarios', array('comentarios' => $comentarios, 'colaborador' => $this->session->get('colaboradores')['id']));
						   }
					   }

					   public function marcar($idArtigo)
					   {
						   if ($this->request->isAJAX()) {
							   $artigosModel = new \App\Models\ArtigosModel();
							   $artigosHistoricosModel = new \App\Models\ArtigosHistoricosModel();
							   $retorno = new \App\Libraries\RetornoPadrao();

							   $configuracaoModel = new \App\Models\ConfiguracaoModel();
							   $config = array();
							   $config['artigo_tempo_bloqueio'] = $configuracaoModel->find('artigo_tempo_bloqueio')['config_valor'];
							   $time = new Time('-' . $config['artigo_tempo_bloqueio']);

							   $artigo = $artigosModel->find($idArtigo);
							   $idColaborador = $this->session->get('colaboradores')['id'];

							   $historico = $artigosHistoricosModel->where('artigos_id', $idArtigo)
								   ->where('acao', 'desmarcou')
								   ->where('colaboradores_id', $idColaborador)
								   ->where("criado >= '" . $time->toDateTimeString() . "'")
								   ->orderBy('criado', 'DESC')
								   ->get()->getResultArray();

							   if ($historico !== null && !empty($historico)) {
								   return $retorno->retorno(false, 'Você está impossibilitado de marcar este artigo até ' . Time::createFromFormat('Y-m-d H:i:s', $historico[0]['criado'])->addHours(explode(' ', $config['artigo_tempo_bloqueio'])[0])->toLocalizedString('dd MMMM yyyy HH:mm:ss') . '.', true);
							   }

							   if ($artigo === null || empty($artigo)) {
								   return $retorno->retorno(false, 'Erro ao encontrar artigo.', true);
							   }
							   $dado = $this->artigosMarcacao->marcarArtigo($artigo['id'], $idColaborador);
							   if ($dado) {
								   $this->artigosHistoricos->cadastraHistorico($artigo['id'], 'marcou', $idColaborador);
								   $this->colaboradoresNotificacoes->cadastraNotificacao($idColaborador, 'marcou', 'artigos', 'o artigo', $idArtigo);
								   return $retorno->retorno(true, 'Artigo marcado com sucesso.', true);
							   } else {
								   return $retorno->retorno(false, 'O artigo já está marcado por outro colaborador.', true);
							   }
						   } else {
							   return redirect()->to(base_url() . 'colaboradores/artigos/');
						   }
					   }

					   public function desmarcar($idArtigo)
					   {
						   if ($this->request->isAJAX()) {
							   $artigosModel = new \App\Models\ArtigosModel();
							   $retorno = new \App\Libraries\RetornoPadrao();

							   $artigo = $artigosModel->find($idArtigo);
							   $idColaborador = $this->session->get('colaboradores')['id'];
							   $permissoes = $this->session->get('colaboradores')['permissoes'];

							   if ($artigo === null || empty($artigo)) {
								   return $retorno->retorno(false, 'Erro ao encontrar artigo.', true);
							   }
							   if ($artigo['marcado_colaboradores_id'] != $idColaborador && !in_array('7', $permissoes)) {
								   return $retorno->retorno(false, 'O artigo não está marcado por você.', true);
							   }
							   $dado = $this->artigosMarcacao->desmarcarArtigo($artigo['id']);
							   if ($dado) {
								   $this->artigosHistoricos->cadastraHistorico($artigo['id'], 'desmarcou', $idColaborador);
								   $this->colaboradoresNotificacoes->cadastraNotificacao($idColaborador, 'desmarcou', 'artigos', 'o artigo', $artigo['id']);
								   return $retorno->retorno(true, 'Artigo desmarcado com sucesso.', true);
							   } else {
								   return $retorno->retorno(false, 'Ocorreu um erro ao desmarcar o artigo.', true);
							   }
						   } else {
							   return redirect()->to(base_url() . 'colaboradores/artigos/');
						   }
					   }
				   */
	/*FUNÇÕES PRIVADAS DO CONTROLLER*/

	private function getCorFaseProducao($faseProducaoId)
	{
		if ($faseProducaoId == '1') {
			return 'success';
		}
		if ($faseProducaoId == '2') {
			return 'primary';
		}
		if ($faseProducaoId == '3') {
			return 'info';
		}
		if ($faseProducaoId == '4') {
			return 'secondary';
		}
		if ($faseProducaoId == '5') {
			return 'danger';
		}
		if ($faseProducaoId == '6') {
			return 'warning';
		}
		if ($faseProducaoId == '7') {
			return 'dark';
		}
	}

	private function artigoDescartar($idArtigo)
	{
		$artigosModel = new \App\Models\ArtigosModel();
		$artigo = $artigosModel->find($idArtigo);

		$artigo['descartado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
		$retorno = $this->gravarArtigos('update', $artigo, $idArtigo);
		$this->artigosMarcacao->desmarcarArtigo($idArtigo);
		$retorno = $this->gravarArtigos('delete', $artigo);
		$this->artigosHistoricos->cadastraHistorico($idArtigo, 'descartou', $this->session->get('colaboradores')['id']);
		return $retorno;
	}


	private function artigoProximo($artigoId)
	{
		$artigosModel = new \App\Models\ArtigosModel();
		$artigo = $artigosModel->find($artigoId);

		$faseProducaoModel = new \App\Models\FaseProducaoModel();
		$faseProducao = $faseProducaoModel->find($artigo['fase_producao_id']);
		$artigo['fase_producao_id'] = $faseProducao['etapa_posterior'];
		if ($faseProducao['etapa_posterior'] == '3') {
			$artigo['revisado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
		}
		if ($faseProducao['etapa_posterior'] == '4') {
			$artigo['narrado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
		}
		if ($faseProducao['etapa_posterior'] == '5') {
			$artigo['produzido_colaboradores_id'] = $this->session->get('colaboradores')['id'];
		}
		if ($faseProducao['etapa_posterior'] == '6') {
			$artigo['publicado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
		}
		//$retorno = $artigosModel->save($artigo);
		$retorno = $this->gravarArtigos('update', $artigo, $artigoId);
		$this->artigosMarcacao->desmarcarArtigo($artigoId);
		return $retorno;
	}



	private function artigoReverter($idArtigo)
	{
		$artigosModel = new \App\Models\ArtigosModel();
		$artigo = $artigosModel->find($idArtigo);

		$faseProducaoModel = new \App\Models\FaseProducaoModel();
		$faseProducao = $faseProducaoModel->find($artigo['fase_producao_id']);
		$artigo['fase_producao_id'] = $faseProducao['etapa_anterior'];
		if ($faseProducao['etapa_anterior'] == '1') {
			$artigo['texto_revisado'] = NULL;
			$artigo['revisado_colaboradores_id'] = NULL;
			$artigo['palavras_revisor'] = 0;
		}
		if ($faseProducao['etapa_anterior'] == '2') {
			$artigo['revisado_colaboradores_id'] = NULL;
			$artigo['palavras_revisor'] = 0;
			$artigo['arquivo_audio'] = NULL;
			$artigo['narrado_colaboradores_id'] = NULL;
		}
		if ($faseProducao['etapa_anterior'] == '3') {
			$artigo['link_produzido'] = NULL;
			$artigo['link_shorts'] = NULL;
			$artigo['produzido_colaboradores_id'] = NULL;
			$artigo['arquivo_audio'] = NULL;
			$artigo['narrado_colaboradores_id'] = NULL;
		}
		if ($faseProducao['etapa_anterior'] == '4') {
			$artigo['publicado'] = NULL;
			$artigo['publicado_colaboradores_id'] = NULL;
			$artigo['link_produzido'] = NULL;
			$artigo['link_shorts'] = NULL;
			$artigo['produzido_colaboradores_id'] = NULL;
		}
		//$retorno = $artigosModel->save($artigo);
		$retorno = $this->gravarArtigos('save', $artigo);
		$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'reverteu', 'artigos', 'o artigo', $idArtigo);
		$this->artigosMarcacao->desmarcarArtigo($idArtigo);
		$this->artigosHistoricos->cadastraHistorico($idArtigo, 'reverteu', $this->session->get('colaboradores')['id']);
		return $retorno;
	}
	private function revisarArtigo($idArtigo)
	{
		if ($this->request->getMethod() == 'post') {
			$artigosModel = new \App\Models\ArtigosModel();

			$post = $this->request->getPost();

			$gravar['tipo_artigo'] = $post['tipo_artigo'];
			$gravar['titulo'] = htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8');
			$gravar['texto_revisado'] = htmlspecialchars($post['texto_original'], ENT_QUOTES, 'UTF-8');
			$gravar['imagem'] = htmlspecialchars($post['imagem'], ENT_QUOTES, 'UTF-8');
			$gravar['gancho'] = htmlspecialchars($post['gancho'], ENT_QUOTES, 'UTF-8');
			$gravar['referencias'] = htmlspecialchars($post['referencias'], ENT_QUOTES, 'UTF-8');
			$gravar['palavras_revisor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $post['texto_original']));
			$gravar['link'] = htmlspecialchars($post['link'], ENT_QUOTES, 'UTF-8');
			$gravar['atualizado'] = $artigosModel->getNow();
			$gravar['revisado_colaboradores_id'] = $this->session->get('colaboradores')['id'];

			//$artigo_id = $artigosModel->update(['id' => $idArtigo], $gravar);
			$artigo_id = $this->gravarArtigos('update', $gravar, $idArtigo);
			$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'revisou', 'artigos', 'o artigo', $idArtigo);
			$this->artigosHistoricos->cadastraHistorico($idArtigo, 'revisou', $this->session->get('colaboradores')['id']);

			// $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
			// $artigosCategoriasModel->deleteArtigoCategoria($idArtigo);
			// foreach ($post['categorias'] as $categoria) {
			// 	$artigosCategoriasModel->insertArtigoCategoria($idArtigo, $categoria);
			// }
			return $artigo_id;
		}
	}

	private function cadastrarArtigo()
	{
		if ($this->request->getMethod() == 'post') {
			$artigosModel = new \App\Models\ArtigosModel();
			$session = $this->session->get('colaboradores');
			$post = $this->request->getPost();
			$gravar = array();
			$gravar['id'] = $artigosModel->getNovaUUID();
			$gravar['tipo_artigo'] = $post['tipo_artigo'];
			$gravar['titulo'] = htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8');
			$gravar['url_friendly'] = url_friendly(htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8'));
			$gravar['texto_original'] = htmlspecialchars($post['texto_original'], ENT_QUOTES, 'UTF-8');
			$gravar['imagem'] = '';
			$gravar['gancho'] = htmlspecialchars($post['gancho'], ENT_QUOTES, 'UTF-8');
			$gravar['referencias'] = htmlspecialchars($post['referencias'], ENT_QUOTES, 'UTF-8');
			$gravar['escrito_colaboradores_id'] = $session['id'];
			$gravar['palavras_escritor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $post['texto_original']));
			$gravar['fase_producao_id'] = 1;
			$gravar['link'] = $post['link'];

			$pautasModel = new \App\Models\PautasModel();
			$pauta = $pautasModel->where('link', $post['link'])->get()->getResultArray();
			if (!empty($pauta)) {
				$gravar['sugerido_colaboradores_id'] = $pauta[0]['colaboradores_id'];
			}

			//$artigo_id = $artigosModel->insert($gravar);
			$artigo_id = $this->gravarArtigos('insert', $gravar);
			$this->colaboradoresNotificacoes->cadastraNotificacao($session['id'], 'escreveu', 'artigos', 'o artigo', $artigo_id);
			$this->artigosHistoricos->cadastraHistorico($artigo_id, 'escreveu', $this->session->get('colaboradores')['id']);
			// $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
			// foreach ($post['categorias'] as $categoria) {
			// 	$artigosCategoriasModel->insertArtigoCategoria($artigo_id, $categoria);
			// }
			return $gravar['id'];
		}
		return false;
	}

	private function alterarArtigo($idArtigo)
	{
		if ($this->request->getMethod() == 'post') {
			$artigosModel = new \App\Models\ArtigosModel();
			$post = $this->request->getPost();

			$gravar['tipo_artigo'] = $post['tipo_artigo'];
			$gravar['titulo'] = htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8');
			$gravar['texto_original'] = htmlspecialchars($post['texto_original'], ENT_QUOTES, 'UTF-8');
			$gravar['gancho'] = htmlspecialchars($post['gancho'], ENT_QUOTES, 'UTF-8');
			$gravar['referencias'] = htmlspecialchars($post['referencias'], ENT_QUOTES, 'UTF-8');
			$gravar['palavras_escritor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $post['texto_original']));
			$gravar['atualizado'] = $artigosModel->getNow();

			//$artigo_id = $artigosModel->update(['id' => $idArtigo], $gravar);
			$artigo_id = $this->gravarArtigos('update', $gravar, $idArtigo);
			$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'alterou', 'artigos', 'o artigo', $idArtigo, true);
			$this->artigosHistoricos->cadastraHistorico($idArtigo, 'alterou', $this->session->get('colaboradores')['id']);

			// $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
			// $artigosCategoriasModel->deleteArtigoCategoria($idArtigo);
			// foreach ($post['categorias'] as $categoria) {
			// 	$artigosCategoriasModel->insertArtigoCategoria($idArtigo, $categoria);
			// }
			return $artigo_id;
		}
	}



	private function reverterFase($idArtigo, $metodo)
	{
		if ($this->request->getGet('anterior') != NULL) {
			$retorno = $this->artigoReverter($idArtigo);
			if ($retorno === true) {
				return redirect()->to(base_url() . 'colaboradores/artigos/' . $metodo . '?status=true');
			} else {
				return redirect()->to(base_url() . 'colaboradores/artigos/' . $metodo . '/' . $idArtigo . '?status=false');
			}
		}
	}

	private function gravarArtigos($tipo, $dados, $id = null)
	{
		$artigosModel = new \App\Models\ArtigosModel();
		$retorno = null;
		$artigosModel->db->transStart();
		switch ($tipo) {
			case 'update':
				$retorno = $artigosModel->update($id, $dados);
				break;
			case 'insert':
				$retorno = $artigosModel->insert($dados);
				break;
			case 'save':
				$retorno = $artigosModel->save($dados);
				break;
			case 'delete':
				$retorno = $artigosModel->delete($dados['id']);
				break;
			default:
				$retorno = false;
				break;
		}
		$artigosModel->db->transComplete();
		return $retorno;
	}

	private function criaTextoDinamizado($artigo)
	{
		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['artigo_visualizacao_narracao'] = $configuracaoModel->find('artigo_visualizacao_narracao')['config_valor'];
		$config['artigo_visualizacao_narracao'] = explode("\n", $config['artigo_visualizacao_narracao']);

		$colaboradores = "";
		$colaboradores .= ($artigo['colaboradores']['sugerido'] !== null) ? ('sugerido por ' . $artigo['colaboradores']['sugerido'] . ' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['escrito'] !== null) ? ('escrito por ' . $artigo['colaboradores']['escrito'] . ' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['revisado'] !== null) ? ('revisado por ' . $artigo['colaboradores']['revisado'] . ' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['narrado'] !== null) ? ('narrado por ' . $artigo['colaboradores']['narrado'] . ' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['produzido'] !== null) ? ('produzido por ' . $artigo['colaboradores']['produzido'] . ' ') : ('');

		foreach ($config['artigo_visualizacao_narracao'] as $indice => $linha) {
			$alterado = null;
			$alterado = str_replace("{gancho}", $artigo['gancho'], $linha);
			if ($artigo['texto_revisado'] !== NULL) {
				$alterado = str_replace("{texto}", $artigo['texto_revisado'], $alterado);
			} else {
				$alterado = str_replace("{texto}", $artigo['texto_original'], $alterado);
			}

			$alterado = str_replace("{colaboradores}", $colaboradores, $alterado);
			$config['artigo_visualizacao_narracao'][$indice] = $alterado;
		}

		$config['artigo_visualizacao_narracao'] = '<p>' . implode('</p><p>', $config['artigo_visualizacao_narracao']) . '</p>';
		$config['artigo_visualizacao_narracao'] = str_replace("\n", "<br/>", $config['artigo_visualizacao_narracao']);
		return $config['artigo_visualizacao_narracao'];
	}
}
