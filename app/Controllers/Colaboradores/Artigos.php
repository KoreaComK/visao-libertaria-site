<?php

namespace App\Controllers\Colaboradores;

use App\Controllers\BaseController;

use App\Libraries\VerificaPermissao;
use App\Libraries\ArtigosHistoricos;
use App\Libraries\ArtigosMarcacao;

class Artigos extends BaseController
{
	public $verificaPermissao;
	public $artigosHistoricos;
	public $artigosMarcacao;
	public $iniciaVariavel;
	function __construct()
	{
		$this->verificaPermissao = new verificaPermissao();
		$this->artigosHistoricos = new ArtigosHistoricos;
		$this->artigosMarcacao = new ArtigosMarcacao;
		helper('url_friendly');
		$this->iniciaVariavel = [
			'titulo' => null,
			'pauta' => array(),
			'fase_producao' => null,
			//'categorias_artigo' => array(),
			'artigo' => [
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
	public function index()
	{
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
		$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];

		$data['artigosList'] = [
			'artigos' => $artigos->paginate($config['site_quantidade_listagem'], 'artigos'),
			'pager' => $artigos->pager
		];
		return view('colaboradores/artigos_list', $data);
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
			$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];
			$data['artigosList'] = [
				'artigos' => $artigos->paginate($config['site_quantidade_listagem'], 'artigos'),
				'pager' => $artigos->pager
			];
			return view('colaboradores/artigos_list', $data);
		} else {

			$artigo = $artigosModel->where('id',$artigoId)->get()->getResultArray()[0];
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

	public function cadastrar($id_artigo = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('2');
		$retorno = new \App\Libraries\RetornoPadrao();
		$data = $this->iniciaVariavel;
		$data['fase_producao'] = '1';
		$data['titulo'] = 'Artigo';
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
					$artigoId = $this->alterarArtigo($id_artigo);
					if ($artigoId != false) {
						$data['retorno'] = $retorno->retorno(true, 'Artigo salvo com sucesso', false);
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

		//QUANDO O CADASTRO FOR BASEADO EM ALGUMA PAUTA
		//PRÉ ESCREVER O CONTEÚDO COM A PAUTA
		if ($id_artigo == null && $this->request->getMethod() == 'get' && $this->request->getGet('pauta') !== null) {
			$pautaId = $this->request->getGet('pauta');
			$pautasModel = new \App\Models\PautasModel();
			$pauta = $pautasModel->find($pautaId);
			$data['pauta'] = $pauta;
			$data['artigo']['titulo'] = $pauta['titulo'];
			$data['artigo']['texto_original'] = $pauta['texto'];
			$data['artigo']['imagem'] = $pauta['imagem'];
		}

		$widgetsSite = new \App\Libraries\WidgetsSite();
		// $data['categorias'] = $widgetsSite->widgetCategorias();
		if ($this->request->getGet('status') !== null && $valida == null) {
			$data['retorno'] = $retorno->retorno(true, 'Artigo salvo com sucesso', false);
		}
		
		$data['historico'] = $this->artigosHistoricos->buscaHistorico($id_artigo);
		return view('colaboradores/artigos_form', $data);
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
			$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];
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
					$status_alteracao = $this->revisarArtigo($artigoId);
					if ($status_alteracao) {
						$data['artigo']['texto_revisado'] = $artigo['texto_original'];
						$data['retorno'] = $retorno->retorno(true, 'Revisão feita com sucesso.', false);
					} else {
						$data['retorno'] = $retorno->retorno(false, 'Houve um erro no cadastro da revisão.', false);
					}
				} else {
					$data['retorno'] = $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), false);
				}
			} else {
				if($data['artigo']['fase_producao_id'] == 2 && $data['artigo']['texto_revisado'] != NULL) {
					$data['artigo']['texto_original'] = $data['artigo']['texto_revisado'];
				}
			}

			$widgetsSite = new \App\Libraries\WidgetsSite();
			// $data['categorias'] = $widgetsSite->widgetCategorias();

			$data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);
			return view('colaboradores/artigos_form', $data);
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
			$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];
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
			$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];
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
			$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];
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
					if ($artigo['palavras_escritor'] > str_word_count(preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç|Ç)/"),explode(" ","a A e E i I o O u U n N c"),$artigo['texto_revisado']))) {
						$artigo['palavras_escritor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç|Ç)/"),explode(" ","a A e E i I o O u U n N c"),$artigo['texto_revisado']));
					}

					$faseProducaoModel = new \App\Models\FaseProducaoModel();

					$faseProducao = $faseProducaoModel->find($artigo['fase_producao_id']);
					$artigo['fase_producao_id'] = $faseProducao['etapa_posterior'];

					//$retorno = $artigosModel->save($artigo);
					$retorno = $this->gravarArtigos('save', $artigo);
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

		if($idArtigo == null) {
			return $retorno->retorno(false, 'Artigo não informado, atualize a página', true);
		}

		if ($this->request->isAJAX()) {
			$post = $this->request->getPost();
			if(!isset($post['tags'])) {
				return $retorno->retorno(false, 'Tags não foram fornecidas.', true);
			}
			$configuracao = $configuracaoModel->find('descricao_padrao_youtube');
			$artigo = $artigosModel->find($idArtigo);
			$descricaoVideo = '';
			$descricaoVideo = str_replace('{referencias}',$artigo['referencias'],$configuracao['config_valor']);
			$descricaoVideo = str_replace('{tags}',$post['tags'],$descricaoVideo);
			return json_encode(['status'=> true, 'descricao' => $descricaoVideo]);
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
					return $retorno->retorno(true, '', true);
				}
				return $retorno->retorno(false, 'Erro ao excluir o comentário.', true);
			}
			if (isset($post['metodo']) && $post['metodo'] == 'inserir' && trim($post['comentario']) !== '') {
				$comentario = [
					'id' => $artigosComentariosModel->getNovaUUID(),
					'colaboradores_id' => $this->session->get('colaboradores')['id'],
					'artigos_id' => $idArtigo,
					'comentario' => $post['comentario']
				];
				$artigosComentariosModel->db->transStart();
				$save = $artigosComentariosModel->insert($comentario);
				$artigosComentariosModel->db->transComplete();
				return $retorno->retorno(true, '', true);
			}
			if (isset($post['metodo']) && $post['metodo'] == 'alterar' && trim($post['id_comentario']) !== '') {
				$comentario = $artigosComentariosModel->find($post['id_comentario']);
				if ($comentario !== null && $comentario['colaboradores_id'] == $this->session->get('colaboradores')['id']) {
					$comentario['atualizado'] = $artigosComentariosModel->getNow();
					$comentario['comentario'] = $post['comentario'];
					$artigosComentariosModel->db->transStart();
					$artigosComentariosModel->save($comentario);
					$artigosComentariosModel->db->transComplete();
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
			$retorno = new \App\Libraries\RetornoPadrao();
			
			$artigo = $artigosModel->find($idArtigo);
			$idColaborador = $this->session->get('colaboradores')['id'];

			if($artigo === null || empty($artigo)) {
				return $retorno->retorno(false, 'Erro ao encontrar artigo.', true);
			}
			$dado = $this->artigosMarcacao->marcarArtigo($artigo['id'],$idColaborador);
			if($dado){
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

			if($artigo === null || empty($artigo)) {
				return $retorno->retorno(false, 'Erro ao encontrar artigo.', true);
			}
			if($artigo['marcado_colaboradores_id'] != $idColaborador) {
				return $retorno->retorno(false, 'O artigo não está marcado por você.', true);
			}
			$dado = $this->artigosMarcacao->desmarcarArtigo($artigo['id']);
			if($dado){
				return $retorno->retorno(true, 'Artigo desmarcado com sucesso.', true);
			} else {
				return $retorno->retorno(false, 'Ocorreu um erro ao desmarcar o artigo.', true);
			}
		} else {
			return redirect()->to(base_url() . 'colaboradores/artigos/');
		}
	}

	/*FUNÇÕES PRIVADAS DO CONTROLLER*/


	private function artigoProximo($idArtigo)
	{
		$artigosModel = new \App\Models\ArtigosModel();
		$artigo = $artigosModel->find($idArtigo);

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
		$retorno = $this->gravarArtigos('save', $artigo);
		$this->artigosMarcacao->desmarcarArtigo($idArtigo);
		return $retorno;
	}

	private function artigoDescartar($idArtigo)
	{
		$artigosModel = new \App\Models\ArtigosModel();
		$artigo = $artigosModel->find($idArtigo);

		$artigo['descartado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
		//$artigosModel->save($artigo);
		$retorno = $this->gravarArtigos('save', $artigo);
		$this->artigosMarcacao->desmarcarArtigo($idArtigo);
		//$retorno = $artigosModel->delete($artigo);
		$retorno = $this->gravarArtigos('delete', $artigo);
		$this->artigosHistoricos->cadastraHistorico($idArtigo, 'descartou', $this->session->get('colaboradores')['id']);
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
		$this->artigosMarcacao->desmarcarArtigo($idArtigo);
		$this->artigosHistoricos->cadastraHistorico($idArtigo, 'reverteu', $this->session->get('colaboradores')['id']);
		return $retorno;
	}
	private function revisarArtigo($idArtigo)
	{
		if ($this->request->getMethod() == 'post') {
			$artigosModel = new \App\Models\ArtigosModel();

			$post = $this->request->getPost();

			$gravar['titulo'] = $post['titulo'];
			$gravar['texto_revisado'] = $post['texto_original'];
			$gravar['imagem'] = $post['imagem'];
			$gravar['gancho'] = $post['gancho'];
			$gravar['referencias'] = $post['referencias'];
			$gravar['palavras_revisor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç|Ç)/"),explode(" ","a A e E i I o O u U n N c"),$post['texto_original']));
			$gravar['link'] = $post['link'];
			$gravar['atualizado'] = $artigosModel->getNow();
			$gravar['revisado_colaboradores_id'] = $this->session->get('colaboradores')['id'];

			//$artigo_id = $artigosModel->update(['id' => $idArtigo], $gravar);
			$artigo_id = $this->gravarArtigos('update', $gravar, $idArtigo);
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
			$gravar['titulo'] = $post['titulo'];
			$gravar['url_friendly'] = url_friendly($post['titulo']);
			$gravar['texto_original'] = $post['texto_original'];
			$gravar['imagem'] = $post['imagem'];
			$gravar['gancho'] = $post['gancho'];
			$gravar['referencias'] = $post['referencias'];
			$gravar['escrito_colaboradores_id'] = $session['id'];
			$gravar['palavras_escritor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç|Ç)/"),explode(" ","a A e E i I o O u U n N c"),$post['texto_original']));
			$gravar['fase_producao_id'] = 2;

			if ($this->request->getGet('pauta') !== null) {
				$pautasModel = new \App\Models\PautasModel();
				$pauta = $pautasModel->find($this->request->getGet('pauta'));
				$gravar['link'] = $pauta['link'];
				$gravar['sugerido_colaboradores_id'] = $pauta['colaboradores_id'];
			} else {
				$gravar['link'] = $post['link'];
			}
			//$artigo_id = $artigosModel->insert($gravar);
			$artigo_id = $this->gravarArtigos('insert', $gravar);
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

			$gravar['titulo'] = $post['titulo'];
			$gravar['texto_original'] = $post['texto_original'];
			$gravar['imagem'] = $post['imagem'];
			$gravar['gancho'] = $post['gancho'];
			$gravar['referencias'] = $post['referencias'];
			$gravar['palavras_escritor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç|Ç)/"),explode(" ","a A e E i I o O u U n N c"),$post['texto_original']));
			$gravar['link'] = $post['link'];
			$gravar['atualizado'] = $artigosModel->getNow();
			$artigoGravado = $artigosModel->find($idArtigo);
			if($artigoGravado['fase_producao_id'] == '1') {
				$gravar['fase_producao_id'] = 2;
			}

			//$artigo_id = $artigosModel->update(['id' => $idArtigo], $gravar);
			$artigo_id = $this->gravarArtigos('update', $gravar, $idArtigo);
			$this->artigosHistoricos->cadastraHistorico($idArtigo, 'alterou', $this->session->get('colaboradores')['id']);

			// $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
			// $artigosCategoriasModel->deleteArtigoCategoria($idArtigo);
			// foreach ($post['categorias'] as $categoria) {
			// 	$artigosCategoriasModel->insertArtigoCategoria($idArtigo, $categoria);
			// }
			return $artigo_id;
		}
	}

	private function descartar($idArtigo, $metodo)
	{
		if ($this->request->getGet('descartar') != NULL) {
			$retorno = $this->artigoDescartar($idArtigo);
			if ($retorno === true) {
				return redirect()->to(base_url() . 'colaboradores/artigos/' . $metodo . '?status=true');
			} else {
				return redirect()->to(base_url() . 'colaboradores/artigos/' . $metodo . '/' . $idArtigo . '?status=false');
			}
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
		$config['artigo_visualizacao_narracao'] = explode("\n",$config['artigo_visualizacao_narracao']);

		$colaboradores = "";
		$colaboradores .= ($artigo['colaboradores']['sugerido'] !== null) ? ('sugerido por ' . $artigo['colaboradores']['sugerido'].' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['escrito'] !== null) ? ('escrito por ' . $artigo['colaboradores']['escrito'].' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['revisado'] !== null) ? ('revisado por ' . $artigo['colaboradores']['revisado'].' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['narrado'] !== null) ? ('narrado por ' . $artigo['colaboradores']['narrado'].' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['produzido'] !== null) ? ('produzido por ' . $artigo['colaboradores']['produzido'].' ') : ('');

		foreach($config['artigo_visualizacao_narracao'] as $indice => $linha){
			$alterado = null;
			$alterado = str_replace("{gancho}",$artigo['gancho'],$linha);
			if($artigo['texto_revisado'] !== NULL) {
				$alterado = str_replace("{texto}",$artigo['texto_revisado'],$alterado);
			} else {
				$alterado = str_replace("{texto}",$artigo['texto_original'],$alterado);
			}

			$alterado = str_replace("{colaboradores}",$colaboradores,$alterado);
			$config['artigo_visualizacao_narracao'][$indice] = $alterado;
		}

		$config['artigo_visualizacao_narracao'] = '<p>'.implode('</p><p>',$config['artigo_visualizacao_narracao']).'</p>';
		$config['artigo_visualizacao_narracao'] = str_replace("\n", "<br/>", $config['artigo_visualizacao_narracao']);
		return $config['artigo_visualizacao_narracao'];
	}
}
