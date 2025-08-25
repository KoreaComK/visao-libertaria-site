<?php

namespace App\Controllers;

use App\Libraries\WidgetsSite;
use Config\App;
use CodeIgniter\I18n\Time;

use App\Models\ProjetosModel;
use App\Models\ProjetosVideosModel;
use App\Models\ArtigosModel;

class Site extends BaseController
{
	protected $projetosModel;
	protected $projetosVideosModel;
	protected $artigosModel;

	public function __construct()
	{
		helper('_formata_video');

		$this->projetosModel = new ProjetosModel();
		$this->projetosVideosModel = new ProjetosVideosModel();
		$this->artigosModel = new ArtigosModel();
	}

	/*HOME PAGE*/
	// public function index(): string
	// {
	// 	$data = array();
	// 	$configuracaoModel = new \App\Models\ConfiguracaoModel();
	// 	$data['config'] = array();
	// 	$data['config']['home_banner'] = (int)$configuracaoModel->find('home_banner')['config_valor'];
	// 	$data['config']['home_banner_mostrar'] = $configuracaoModel->find('home_banner_mostrar')['config_valor'];
	// 	$data['config']['home_newsletter_mostrar'] = $configuracaoModel->find('home_newsletter_mostrar')['config_valor'];
	// 	$data['config']['home_talvez_goste'] = (int)$configuracaoModel->find('home_talvez_goste')['config_valor'];
	// 	$data['config']['home_talvez_goste_mostrar'] = $configuracaoModel->find('home_talvez_goste_mostrar')['config_valor'];
	// 	$data['config']['home_ultimos_videos'] = (int)$configuracaoModel->find('home_ultimos_videos')['config_valor'];
	// 	$data['config']['home_ultimos_videos_mostrar'] = $configuracaoModel->find('home_ultimos_videos_mostrar')['config_valor'];

	// 	$data['colaboradores'] = $this->session->get('colaboradores');

	// 	$widgets = new WidgetsSite();

	// 	//$data['widgetCategorias'] = $widgets->widgetCategorias();
	// 	$data['widgetEsteiraProducao'] = $widgets->widgetArtigosByFaseProducaoCount();
	// 	$artigosModel = new \App\Models\ArtigosModel();

	// 	$inicial = 4;
	// 	$quantidade_artigos = $inicial;
	// 	if($data['config']['home_banner_mostrar'] == '1') {
	// 		$quantidade_artigos += $data['config']['home_banner'];
	// 	}
	// 	if($data['config']['home_ultimos_videos_mostrar'] == '1') {
	// 		$quantidade_artigos += $data['config']['home_ultimos_videos'];
	// 	}

	// 	$artigos = $artigosModel->getArtigosHome($quantidade_artigos);
	// 	if ($artigos === null || empty($artigos)) {
	// 		$data['artigos'] = false;
	// 	} else {
	// 		$data['banner'] = [];
	// 		$data['artigos'] = [];
	// 		for ($i = 0; $i < count($artigos); $i++) {
	// 			if (isset($artigos[$i]) && count($data['banner']) < ($data['config']['home_banner'])+$inicial) {
	// 				$data['banner'][] = $artigos[$i];
	// 			} else {
	// 				$data['artigos'][] = $artigos[$i];
	// 			}
	// 		}
	// 	}

	// 	$quantidade_artigos = 0;
	// 	if($data['config']['home_talvez_goste_mostrar'] == '1') {
	// 		$quantidade_artigos += $data['config']['home_talvez_goste'];
	// 	}
	// 	$artigos = $artigosModel->getArtigosHomeRand($quantidade_artigos);
	// 	if ($artigos === null || empty($artigos)) {
	// 		$data['rand'] = false;
	// 	} else {
	// 		$data['rand'] = $artigos;
	// 	}

	// 	$data['avisos'] = false;
	// 	$avisosModel = new \App\Models\AvisosModel();
	// 	$avisosModel->orWhere('(fim IS NULL AND inicio IS NULL)');
	// 	$avisosModel->orWhere('(inicio <= NOW() AND fim IS NULL)');
	// 	$avisosModel->orWhere('(inicio IS NULL AND fim >= NOW())');
	// 	$avisosModel->orWhere('(inicio IS NOT NULL AND fim IS NOT NULL AND NOW() BETWEEN inicio and fim)');



	// 	$avisos = $avisosModel->get()->getResultArray();
	// 	$data['avisos'] = $avisos;

	// 	// Busca os últimos 7 vídeos de cada projeto
	// 	$projetosModel = new \App\Models\ProjetosModel();
	// 	$projetosVideosModel = new \App\Models\ProjetosVideosModel();

	// 	$projetos = $projetosModel->findAll();
	// 	$data['videos_projetos'] = [];

	// 	foreach ($projetos as $projeto) {
	// 		$videos = $projetosVideosModel->where('projetos_id', $projeto['id'])
	// 									->orderBy('publicado', 'DESC')
	// 									->limit(value: 7)->get()->getResultArray();
	// 		$data['videos_projetos'][$projeto['nome']] = $videos;
	// 	}

	// 	helper('colors_helper');
	// 	return view('home', $data);
	// }

	public function index()
	{
		// Buscar todos os projetos
		$projetos = $this->projetosModel->findAll();

		// Array para armazenar os vídeos por projeto
		$data['videos_por_projeto'] = [];

		// Para cada projeto, buscar os últimos 10 vídeos
		foreach ($projetos as $projeto) {
			if ($projeto['listar'] == 'S') {
				$videos = $this->projetosVideosModel
					->where('projetos_id', $projeto['id'])
					->orderBy('publicado', 'DESC')
					->limit(10)->get()->getResultArray();

				// Adicionar os vídeos ao array com o nome do projeto como chave
				$data['videos_por_projeto'][$projeto['nome']]['videos'] = $videos;
			}
		}

		// Buscar os últimos 10 artigos
		$data['ultimos_artigos'] = $this->artigosModel
			->whereIn('fase_producao_id', array(6, 7)) // Apenas artigos ativos
			->where('descartado', null)
			->orderBy('publicado', 'DESC')
			->limit(10)
			->get()
			->getResultArray();

		// Query para vídeos em destaque (mantém a original)
		$subquery = $this->projetosModel->db->table('projetos')
			->select('projetos.*, projetos_videos.*, ROW_NUMBER() OVER(PARTITION BY projetos_videos.projetos_id ORDER BY projetos_videos.publicado DESC) as rn')
			->join('projetos_videos', 'projetos_videos.projetos_id = projetos.id')
			->getCompiledSelect();

		$data['videos_destaque'] = $this->projetosModel->db->table("({$subquery}) as ranked_videos")
			->whereIn('rn', array(1, 2))
			->get()
			->getResultArray();


		$data['active_menu'] = 'home';

		return view('_home', $data);
	}

	public function videos($projeto = null)
	{
		// Verificar se é uma requisição AJAX para infinite scroll
		if ($this->request->isAJAX()) {
			return $this->videosAjax($projeto);
		}

		// Buscar todos os projetos
		$data['projetos'] = $this->projetosModel->findAll();

		// Configurar a query base
		$this->projetosVideosModel->select('projetos_videos.*, projetos.nome as projeto_nome');
		$this->projetosVideosModel->join('projetos', 'projetos.id = projetos_videos.projetos_id');
		$this->projetosVideosModel->orderBy('projetos_videos.publicado', 'DESC');

		// Se um projeto específico foi solicitado, filtrar
		if ($projeto !== null) {
			$projeto_decoded = urldecode($projeto);
			$this->projetosVideosModel->where('projetos.nome', $projeto_decoded);
			$data['projeto_atual'] = $projeto_decoded;
		}

		$data['videosList'] = [
			'videos' => $this->projetosVideosModel->paginate(10),
			'pager' => $this->projetosVideosModel->pager
		];

		$data['colaboradores'] = $this->session->get('colaboradores');

		$data['active_menu'] = 'videos';

		return view('_videos', $data);
	}

	private function videosAjax($projeto = null)
	{
		// Configurar a query base
		$this->projetosVideosModel->select('projetos_videos.*, projetos.nome as projeto_nome');
		$this->projetosVideosModel->join('projetos', 'projetos.id = projetos_videos.projetos_id');
		$this->projetosVideosModel->orderBy('projetos_videos.publicado', 'DESC');

		// Se um projeto específico foi solicitado, filtrar
		if ($projeto !== null) {
			$projeto_decoded = urldecode($projeto);
			$this->projetosVideosModel->where('projetos.nome', $projeto_decoded);
		}

		$videos = $this->projetosVideosModel->paginate(10);
		$pager = $this->projetosVideosModel->pager;

		// Retornar apenas os vídeos em HTML para o infinite scroll
		$html = '';
		foreach ($videos as $video) {
			$titulo = htmlspecialchars($video['titulo'] ?? '', ENT_QUOTES, 'UTF-8');
			$projeto_nome = htmlspecialchars($video['projeto_nome'] ?? 'Projeto', ENT_QUOTES, 'UTF-8');
			$video_id = $video['video_id'] ?? '';
			$publicado = $video['publicado'] ?? '';

			$html .= '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
			$html .= '<div class="card video-card h-100">';
			$html .= '<div class="video-thumbnail">';
			$html .= '<img src="' . cria_url_thumb($video_id) . '" alt="' . $titulo . '" class="card-img-top">';
			$html .= '<div class="play-overlay">';
			$html .= '<i class="bi bi-play-circle-fill play-icon"></i>';
			$html .= '<a href="' . cria_link_watch($video_id) . '" class="gen-video-popup" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></a>';
			$html .= '</div>';
			$html .= '<div class="project-badge">' . $projeto_nome . '</div>';
			$html .= '</div>';
			$html .= '<div class="card-body d-flex flex-column">';
			$html .= '<h6 class="card-title">' . $titulo . '</h6>';
			$html .= '<p class="card-text text-muted small">' . date('d/m/Y', strtotime($publicado)) . '</p>';
			$html .= '<div class="mt-auto">';
			$html .= '<a href="' . cria_link_watch($video_id) . '" class="gen-button gen-video-popup">';
			$html .= '<div class="gen-button-block">';
			$html .= '<span class="gen-button-line-left"></span>';
			$html .= '<span class="gen-button-text">Assistir</span>';
			$html .= '</div>';
			$html .= '</a>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}

		return $this->response->setJSON([
			'html' => $html,
			'hasMore' => $pager->hasMore(),
			'currentPage' => $pager->getCurrentPage(),
			'totalPages' => $pager->getPageCount()
		]);
	}














	/*LISTAGEM DE ARTIGOS*/
	public function artigos($id_categoria = null): string
	{
		$data = array();

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];

		$artigosModel = new \App\Models\ArtigosModel();
		$artigosModel->select('artigos.id AS id, imagem AS imagem, url_friendly AS url, titulo AS titulo, apelido AS autor, publicado AS publicacao, gancho AS texticulo,\'artigo\' AS tipo_conteudo');
		$artigosModel->join('colaboradores', 'colaboradores.id = artigos.escrito_colaboradores_id');
		$artigosModel->whereIn('fase_producao_id', array(6, 7));
		$artigosModel->orderBy('publicado', 'DESC');

		$widgets = new WidgetsSite();

		//$data['widgetCategorias'] = $widgets->widgetCategorias();
		$data['idCategoriaAtual'] = $id_categoria;
		$data['nomeCategoriaAtual'] = null;
		/*foreach ($data['widgetCategorias'] as $cat) {
				  if ($cat['id'] == $id_categoria) {
					  $data['nomeCategoriaAtual'] = $cat['nome'];
				  }
			  }

			  if ($id_categoria !== null) {
				  $artigosModel->join('artigos_categorias', 'artigos.id=artigos_categorias.artigos_id')->where('artigos_categorias.categorias_id', $id_categoria);
			  }*/

		$data['artigosList'] = [
			'artigos' => $artigosModel->paginate($config['site_quantidade_listagem'], 'artigos'),
			'pager' => $artigosModel->pager
		];

		return view('artigos', $data);
	}

	/*DETALHE DO ARTIGO*/
	public function artigo($url_friendly = null)
	{
		if ($url_friendly === null) {
			return redirect()->to(base_url() . 'site/artigos');
		}

		$data = array();

		$artigosModel = new \App\Models\ArtigosModel();
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		//$artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();

		$artigosModel->where('url_friendly', $url_friendly);
		$query = $artigosModel->get();

		if ($artigosModel->countAllResults() > 0) {
			$data['artigo'] = $query->getRowArray();



			$data['artigo']['colaboradores'] = array();
			$data['artigo']['colaboradores']['sugerido'] = ($data['artigo']['sugerido_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($data['artigo']['sugerido_colaboradores_id'])) : (NULL);
			$data['artigo']['colaboradores']['escrito'] = ($data['artigo']['escrito_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($data['artigo']['escrito_colaboradores_id'])) : (NULL);
			$data['artigo']['colaboradores']['revisado'] = ($data['artigo']['revisado_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($data['artigo']['revisado_colaboradores_id'])) : (NULL);
			$data['artigo']['colaboradores']['narrado'] = ($data['artigo']['narrado_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($data['artigo']['narrado_colaboradores_id'])) : (NULL);
			$data['artigo']['colaboradores']['produzido'] = ($data['artigo']['produzido_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($data['artigo']['produzido_colaboradores_id'])) : (NULL);

			$data['meta'] = array();
			$data['meta']['title'] = $data['artigo']['titulo'];
			$data['meta']['image'] = $data['artigo']['imagem'];
			$data['meta']['description'] = addslashes(substr($data['artigo']['texto'], 0, 250)) . '...';

			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->whereIn('fase_producao_id', array(6, 7));
			$artigosModel->where('publicado > ', $data['artigo']['publicado']);
			$artigosModel->orderBy('publicado', 'ASC');
			$artigosModel->limit(1);
			$data['artigo']['proximo'] = $artigosModel->get()->getResultArray();
			unset($artigosModel);
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosModel->whereIn('fase_producao_id', array(6, 7));
			$artigosModel->where('publicado < ', $data['artigo']['publicado']);
			$artigosModel->orderBy('publicado', 'DESC');
			$artigosModel->limit(1);
			$data['artigo']['anterior'] = $artigosModel->get()->getResultArray();


			return view('artigo', $data);
		} else {
			return redirect()->to(base_url() . 'site/artigos');
		}
	}

	/*DETALHE DA PAUTA*/
	public function pauta($idPauta = null)
	{
		if ($idPauta === null) {
			return redirect()->to(base_url() . 'site');
		}

		$data = array();

		$pautasModel = new \App\Models\PautasModel();
		$colaboradoresModel = new \App\Models\ColaboradoresModel();

		$pauta = $pautasModel->find($idPauta);

		if ($pauta !== null) {
			$data['pauta'] = $pauta;

			$data['pauta']['colaborador'] = $colaboradoresModel->find($pauta['colaboradores_id']);

			$data['meta'] = array();
			$data['meta']['title'] = $data['pauta']['titulo'];
			$data['meta']['image'] = $data['pauta']['imagem'];
			$data['meta']['description'] = addslashes($data['pauta']['texto']);

			return view('pauta', $data);
		} else {
			return redirect()->to(base_url() . 'site');
		}
	}

	/* CADASTRO DO USUÁRIO */
	public function cadastrar()
	{
		$data = array();
		if ($this->request->isAJAX()) {
			$validaFormularios = new \App\Libraries\ValidaFormularios();
			$retorno = new \App\Libraries\RetornoPadrao();
			$post = $this->request->getPost();

			$valida = $validaFormularios->validaFormularioCadastroColaborador($post);
			if (empty($valida->getErrors())) {
				if (!$this->verificaCaptcha($post['h-captcha-response'])) {
					return $retorno->retorno(false, 'Você não resolveu corretamente o Captcha.', true);
				} else {
					$colaboradoresModel = new \App\Models\ColaboradoresModel();
					$gravar = array();
					$gravar['apelido'] = $colaboradoresModel->db->escapeString($post['apelido']);
					$gravar['email'] = $post['email'];
					$gravar['senha'] = hash('sha256', $post['senha']);
					$gravar['confirmacao_hash'] = hash('sha256', $post['email'] . rand() . $post['senha']);
					$colaboradoresModel->save($gravar);
					$enviaEmail = new \App\Libraries\EnviaEmail();
					$enviaEmail->enviaEmail($gravar['email'], 'VISÃO LIBERTÁRIA - CONFIRMAR SEU E-MAIL', $enviaEmail->getMensagemCadastro($gravar['confirmacao_hash']));
					return $retorno->retorno(true, 'Foi enviado um e-mail para confirmação. Clique no link para ter acesso a área de colaboração.', true);
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
		return view('cadastrar', $data);
	}

	/* CONFIRMAÇÃO DO E-MAIL */
	public function confirmacao($hash = null)
	{
		if ($hash == null) {
			return false;
		}
		if ($hash !== null) {
			$colaboradoresModel = new \App\Models\ColaboradoresModel();
			$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
			$colaboradores = $colaboradoresModel->getColaboradorPeloHash($hash);
			if ($colaboradores !== NULL && $colaboradores !== false && !empty($colaboradores)) {
				$gravar = array();
				$gravar['id'] = $colaboradores['id'];
				$gravar['confirmado_data'] = $colaboradoresModel->getNow();
				$gravar['confirmacao_hash'] = NULL;
				$gravar['atualizado'] = $gravar['confirmado_data'];
				$colaboradoresModel->save($gravar);
				$colaboradoresAtribuicoesModel->save(['colaboradores_id' => $gravar['id'], 'atribuicoes_id' => '1']);
				$colaboradoresAtribuicoesModel->save(['colaboradores_id' => $gravar['id'], 'atribuicoes_id' => '2']);
			}
			return redirect()->to(base_url() . 'site/login');
		}
	}

	/* ESQUECI SENHA */
	public function esqueci($hash = null)
	{
		$data = array();
		$retorno = new \App\Libraries\RetornoPadrao();
		$data['formulario'] = ($hash === null) ? ('email') : ('senha');
		if ($this->request->isAJAX()) {
			$post = $this->request->getPost();
			$validaFormularios = new \App\Libraries\ValidaFormularios();
			if ($hash == null) {
				$valida = $validaFormularios->validaFormularioEsqueciSenhaEmailColaborador($post);
				if (empty($valida->getErrors())) {
					$colaboradoresModel = new \App\Models\ColaboradoresModel();
					$colaborador = $colaboradoresModel->getColaboradorPeloEmail($post['email']);
					if ($colaborador['excluido'] != NULL) {
						return $retorno->retorno(false, 'Esta conta está excluída e é impossível acessá-la novamente.', true);
					}
					if (!$this->verificaCaptcha($post['h-captcha-response'])) {
						return $retorno->retorno(false, 'Você não resolveu corretamente o Captcha.', true);
					} else {
						$gravar = array();
						$gravar['confirmacao_hash'] = hash('sha256', $colaborador['email'] . rand() . $colaborador['senha']);
						$gravar['id'] = $colaborador['id'];
						$colaboradoresModel->save($gravar);
						$enviaEmail = new \App\Libraries\EnviaEmail();
						$enviaEmail->enviaEmail($colaborador['email'], 'VISÃO LIBERTÁRIA - REDEFINIÇÃO DE SENHA', $enviaEmail->getMensagemEsqueciSenha($gravar['confirmacao_hash']));
						return $retorno->retorno(true, 'Foi enviado um e-mail para redefinição de senha. Clique no link para ter acesso a área redefinição de senha.', true);
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
			if ($hash != null) {
				$valida = $validaFormularios->validaFormularioEsqueciSenhaSenhaColaborador($post);
				if (empty($valida->getErrors())) {
					if (!$this->verificaCaptcha($post['h-captcha-response'])) {
						return $retorno->retorno(false, 'Você não resolveu corretamente o Captcha.', true);
					} else {
						$colaboradoresModel = new \App\Models\ColaboradoresModel();
						$colaborador = $colaboradoresModel->getColaboradorPeloHash($hash);
						$gravar = array();
						$gravar['id'] = $colaborador['id'];
						$gravar['senha'] = hash('sha256', $post['senha']);
						$gravar['confirmacao_hash'] = NULL;
						if ($colaborador['confirmado_data'] == NULL) {
							$gravar['confirmado_data'] = $colaboradoresModel->getNow();
							$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
							$colaboradoresAtribuicoesModel->save(['colaboradores_id' => $gravar['id'], 'atribuicoes_id' => '1']);
							$colaboradoresAtribuicoesModel->save(['colaboradores_id' => $gravar['id'], 'atribuicoes_id' => '2']);
						}
						$colaboradoresModel->save($gravar);
						return $retorno->retorno(true, 'Senhas alteradas. Você será redicionado para a área de login em 5 segundos.', true);
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
		return view('esqueci', $data);
	}

	/* EXCLUIR CONTA */
	public function excluir($hash = null)
	{
		$data = array();
		$data['mensagem'] = NULL;
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		if ($this->request->isAJAX() && $hash == null) {
			$colaboradores = $this->session->get('colaboradores');
			$colaborador = $colaboradoresModel->find($colaboradores['id']);
			$colaborador['confirmacao_hash'] = hash('sha256', $colaborador['email'] . rand() . $colaborador['senha']);
			$colaboradoresModel->save($colaborador);
			$enviaEmail = new \App\Libraries\EnviaEmail();
			$enviaEmail->enviaEmail($colaborador['email'], 'VISÃO LIBERTÁRIA - EXCLUSÃO DEFINITIVA DE CONTA', $enviaEmail->getMensagemExcluirConta($colaborador['confirmacao_hash']));
			$retorno['status'] = true;
			$retorno['mensagem'] = 'Foi enviado um e-mail para confirmação de exclusão de conta. Clique no link para excluir definitivamente sua conta do site.';
			return json_encode($retorno);
		}
		if ($hash !== null) {
			$colaborador = $colaboradoresModel->getColaboradorPeloHash($hash);
			if (empty($colaborador)) {
				return redirect()->to(base_url() . 'site/logout');
			}
			$colaborador['apelido'] = 'COLABORADOR EXCLUÍDO';
			$colaborador['avatar'] = NULL;
			$colaborador['carteira'] = NULL;
			$colaborador['senha'] = NULL;
			$colaborador['confirmacao_hash'] = NULL;
			$now = $colaboradoresModel->getNow();
			$colaborador['excluido'] = $now;
			$colaborador['atualizado'] = $now;
			$colaboradoresModel->save($colaborador);
			$data['mensagem'] = 'Exclusão da conta feita com sucesso. Seus dados foram apagados (menos o seu e-mail) e você não poderá mais acessar sua conta.';
			return view('excluir', $data);
		}
		return redirect()->to(base_url() . 'site/logout');
	}

	/*LOGIN DA ÁREA DO COLABORADOR*/
	public function login()
	{
		helper('cookie');
		$data = array();
		$retorno = new \App\Libraries\RetornoPadrao();

		if ($this->request->isAJAX()) {
			$resposta = array();
			$post = service('request')->getPost();

			$validaFormularios = new \App\Libraries\ValidaFormularios();
			$valida = $validaFormularios->validaFormularioLoginColaborador($post);

			if (empty($valida->getErrors())) {

				$retorno = new \App\Libraries\RetornoPadrao();
				$colaboradoresModel = new \App\Models\ColaboradoresModel();
				$colaborador = $colaboradoresModel->getColaboradores($post['email'], hash('sha256', $post['senha']));
				if (count($colaborador) > 0) {
					$colaborador = $colaborador[0];
					if ($colaborador['excluido'] !== NULL) {
						return $retorno->retorno(false, 'Esta conta está excluída.', true);
					}
					if ($colaborador['confirmado_data'] === NULL) {
						$enviaEmail = new \App\Libraries\EnviaEmail();
						$enviaEmail->enviaEmail($colaborador['email'], 'VISÃO LIBERTÁRIA - CONFIRMAR SEU E-MAIL', $enviaEmail->getMensagemCadastro($colaborador['confirmacao_hash']));
						return $retorno->retorno(false, 'Sua conta não foi confirmada. Foi enviado novamente uma pedido de confirmação para o seu e-mail.', true);
					}
					if ($colaborador['bloqueado'] !== 'N') {
						return $retorno->retorno(false, 'Sua conta está bloqueada indefinidamente.', true);
					}
					if ($colaborador['strike_data'] !== NULL && Time::parse($colaborador['strike_data'])->difference(Time::parse(Time::now()))->seconds < 0) {
						return $retorno->retorno(false, 'Sua conta está bloqueada até ' . Time::createFromFormat('Y-m-d H:i:s', $colaborador['strike_data'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'), true);
					}

					$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
					$colaboradoresAtribuicoes = $colaboradoresAtribuicoesModel->getAtribuicoesColaborador($colaborador['id']);

					if (empty($colaboradoresAtribuicoes)) {
						return $retorno->retorno(false, 'Atenção! Você não possui nenhuma atribuição. Acesso negado.', true);
					}
					if (!$this->verificaCaptcha($post['h-captcha-response'])) {
						return $retorno->retorno(false, 'Você não resolveu corretamente o Captcha.', true);
					}

					$colaboradoresNotificacoesModel = new \App\Models\ColaboradoresNotificacoesModel();
					$quantidadeNotificacoes = $colaboradoresNotificacoesModel->where('colaboradores_id', $colaborador['id'])
						->where('data_visualizado', null)->countAllResults();

					$estrutura_session = [
						'colaboradores' => [
							'id' => $colaborador['id'],
							'nome' => $colaborador['apelido'],
							'email' => $colaborador['email'],
							'avatar' => ($colaborador['avatar'] != NULL) ? ($colaborador['avatar']) : (site_url('public/assets/avatar-default.png')),
							'notificacoes' => $quantidadeNotificacoes,
							'permissoes' => array(),
						]
					];

					$permissoes = array();
					foreach ($colaboradoresAtribuicoes as $atribuicao) {
						$permissoes[] = $atribuicao['atribuicoes_id'];
					}
					$estrutura_session['colaboradores']['permissoes'] = $permissoes;

					$this->session->set($estrutura_session);

					$colaboradoresHistorico = new \App\Libraries\ColaboradoresHistoricos();
					$colaboradoresHistorico->cadastraHistorico($colaborador['id'], 'acessar', NULL, NULL);

					if (isset($post['lembrar'])) {
						set_cookie('hash', $this->secured_encrypt(md5($post['email'] . hash('sha256', $post['senha']))), 60 * 60 * 24 * 7);
					}

					return $retorno->retorno(true, 'Bem-vindo de volta ' . $colaborador['apelido'], true);
				} else {
					return $retorno->retorno(false, 'E-mail ou Senha inválida.', true);
				}

			} else {
				$erros = $valida->getErrors();
				$string_erros = '';
				foreach ($erros as $erro) {
					$string_erros .= $erro . "<br/>";
				}
				return $retorno->retorno(false, $string_erros, true);
			}
		} else {
			$this->session->remove('colaboradores');

			if (get_cookie('hash') !== null) {
				$retorno = $this->logar_cookie();
				$get = service('request')->getGet();
				$url = 'colaboradores/perfil';
				if (!empty($get) && isset($get['url']) && str_contains($get['url'], site_url())) {
					$url = $get['url'];
				}
				if ($retorno) {
					return redirect()->to($url);
				}
			}

			$get = service('request')->getGet();
			$url = false;
			if (!empty($get) && isset($get['url']) && str_contains($get['url'], site_url())) {
				$url = $get['url'];
			}
			$data['url'] = $url;
			$data['email_form'] = '';
			$data['senha_form'] = '';
			$data['lembrar'] = '';
			return view('login', $data);
		}
	}

	public function logout()
	{
		helper('cookie');
		$this->session->remove('colaboradores');
		$link = base_url() . 'site/login';
		$get = $this->request->getGet();
		if (!empty($get)) {
			$link .= '?url=' . $get['url'];
		} else {
			delete_cookie('hash');
		}
		return redirect()->to($link)->withCookies();
	}

	/* CONTATO */
	public function contato()
	{
		$data = array();
		if ($this->request->isAJAX()) {
			$validaFormularios = new \App\Libraries\ValidaFormularios();
			$retorno = new \App\Libraries\RetornoPadrao();
			$post = $this->request->getPost();

			$bloqueio = false;
			if ($post['select-assunto'] == '2') {
				$bloqueio = true;
			}
			$valida = $validaFormularios->validaFormularioContato($post, $bloqueio);
			if (empty($valida->getErrors())) {
				if (!$this->verificaCaptcha($post['h-captcha-response'])) {
					return $retorno->retorno(false, 'Você não resolveu corretamente o Captcha.', true);
				} else {
					$contatosModel = new \App\Models\ContatosModel();
					$inserir = array();
					$inserir['id'] = $contatosModel->getNovaUUID();
					$inserir['email'] = $post['email'];
					$inserir['contatos_assuntos_id'] = $post['select-assunto'];
					if ($post['select-assunto'] == '2') {
						$inserir['descricao'] = "Rede social que sofreu banimento: " . $post['redesocial'] . "<br/>Perfil banido: " . $post['perfil'] . "<br/>" . $post['mensagem'];
					} else {
						$inserir['descricao'] = $post['mensagem'];
					}
					$retornoInsert = $contatosModel->insert($inserir);
					if ($retornoInsert !== false) {
						return $retorno->retorno(true, 'Contato enviado com sucesso. Iremos responder assim que possível.', true);
					} else {
						return $retorno->retorno(false, 'Erro ao enviar contato, tente novamente.', true);
					}
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
		if (($this->session->has('colaboradores'))) {
			$data['email'] = $this->session->get('colaboradores')['email'];
		}
		$contatosAssuntosModel = new \App\Models\ContatosAssuntosModel();
		$data['assuntos'] = $contatosAssuntosModel->findAll();
		return view('contato', $data);
	}

	public function pagina($url = NULL)
	{
		$paginasEstaticasModel = new \App\Models\PaginasEstaticasModel();
		if ($url === null) {
			return redirect()->to(base_url() . 'site');
		}
		$pagina = $paginasEstaticasModel->where('url_friendly', $url)->get()->getResultArray();
		if ($pagina == NULL || empty($pagina)) {
			return redirect()->to(base_url() . 'site');
		}
		$data = array();
		$data['estatica'] = $pagina[0];
		return view('pagina', $data);
	}

	public function escritor($apelido = NULL)
	{
		if ($apelido === null) {
			return redirect()->to(base_url() . 'site');
		}

		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$apelido = urldecode($apelido);
		$colaborador = $colaboradoresModel->where('apelido', $apelido)->get()->getResultArray();

		if ($colaborador === null || empty($colaborador)) {
			return redirect()->to(base_url() . 'site');
		}

		$data = array();
		$colaborador = $colaborador[0];

		$artigosModel = new \App\Models\ArtigosModel();
		$artigos = $artigosModel->where('escrito_colaboradores_id', $colaborador['id'])->where('descartado', NULL)->where('publicado IS NOT NULL')->get()->getResultArray();
		$data['contador_artigos'] = 0;
		if ($artigos !== null && !empty($artigos)) {
			$data['contador_artigos'] = count($artigos);
		}

		$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
		$colaboradoresAtribuicoes = $colaboradoresAtribuicoesModel->getNomeAtribuicoesColaborador($colaborador['id'], false);
		$data['atribuicoes'] = $colaboradoresAtribuicoes;

		$data['tempo'] = Time::parse($colaborador['criado'], 'America/Sao_Paulo')->humanize();
		$data['colaborador'] = $colaborador;

		$colaboradoresConquistasModel = new \App\Models\ColaboradoresConquistasModel();
		$data['conquistas'] = $colaboradoresConquistasModel->join('conquistas', 'conquistas.id = colaboradores_conquistas.conquistas_id')->where('colaboradores_id', $colaborador['id'])->where("(tipo = 'escritor' or tipo is null)")->orderBy('conquistas_id', 'ASC')->get()->getResultArray();

		return view('escritor', $data);
	}

	public function escritorList($apelido = NULL)
	{
		if ($apelido === NULL) {
			return false;
		}

		$apelido = urldecode($apelido);
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$colaborador = $colaboradoresModel->where('apelido', $apelido)->get()->getResultArray();
		if ($colaborador === null || empty($colaborador)) {
			return false;
		}
		$colaborador = $colaborador[0];

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];

		$artigosModel = new \App\Models\ArtigosModel();
		$artigosModel->select('artigos.id AS id, imagem AS imagem, url_friendly AS url, titulo AS titulo, apelido AS autor, publicado AS publicacao, gancho AS texticulo, \'artigo\' AS tipo_conteudo');
		$artigosModel->join('colaboradores', 'colaboradores.id = artigos.escrito_colaboradores_id');
		$artigos = $artigosModel->where('escrito_colaboradores_id', $colaborador['id'])->where('descartado', NULL)->where('publicado IS NOT NULL')->orderBy('publicado', 'DESC');
		if ($this->request->getMethod() == 'get') {
			$data['listas'] = [
				'lista' => $artigos->paginate($config['site_quantidade_listagem'], 'lista'),
				'pager' => $artigos->pager
			];
		}
		$data['urlComponente'] = '\App\Libraries\Cards::cardsVerticaisSimples';
		$data['classeListaCSS'] = 'listagem-escritor';
		return view_cell('\App\Libraries\Listas::listasVerticaisSimples', $data);
	}

	public function colaborador($apelido = NULL)
	{
		if ($apelido === null) {
			return redirect()->to(base_url() . 'site');
		}

		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$apelido = urldecode($apelido);
		$colaborador = $colaboradoresModel->where('apelido', $apelido)->get()->getResultArray();

		if ($colaborador === null || empty($colaborador)) {
			return redirect()->to(base_url() . 'site');
		}

		$data = array();
		$colaborador = $colaborador[0];

		$pautasModel = new \App\Models\PautasModel();
		$pautas = $pautasModel->where('colaboradores_id', $colaborador['id'])->where('reservado IS NOT NULL')->where('tag_fechamento IS NOT NULL')->withDeleted()->get()->getResultArray();
		$data['contador_pautas'] = 0;
		if ($pautas !== null && !empty($pautas)) {
			$data['contador_pautas'] = count($pautas);
		}

		$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
		$colaboradoresAtribuicoes = $colaboradoresAtribuicoesModel->getNomeAtribuicoesColaborador($colaborador['id'], false);
		$data['atribuicoes'] = $colaboradoresAtribuicoes;

		$data['tempo'] = Time::parse($colaborador['criado'], 'America/Sao_Paulo')->humanize();
		$data['colaborador'] = $colaborador;
		$data['classeListaCSS'] = 'listagem-colaborador';

		return view('colaborador', $data);
	}

	public function colaboradorList($apelido = NULL)
	{
		if ($apelido === NULL) {
			return false;
		}

		$apelido = urldecode($apelido);
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$colaborador = $colaboradoresModel->where('apelido', $apelido)->get()->getResultArray();
		if ($colaborador === null || empty($colaborador)) {
			return false;
		}
		$colaborador = $colaborador[0];

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];

		$pautasModel = new \App\Models\PautasModel();
		$pautasModel->select('pautas.id AS id, imagem AS imagem, link AS url, titulo AS titulo, apelido AS autor, reservado AS publicacao, texto AS texticulo, \'pauta\' AS tipo_conteudo');
		$pautasModel->join('colaboradores', 'pautas.colaboradores_id = colaboradores.id');
		$pautas = $pautasModel->where('colaboradores_id', $colaborador['id'])->where('reservado IS NOT NULL')->where('tag_fechamento IS NOT NULL')->withDeleted()->orderBy('reservado', 'DESC');
		if ($this->request->getMethod() == 'get') {
			$data['listas'] = [
				'lista' => $pautas->paginate($config['site_quantidade_listagem'], 'lista'),
				'pager' => $pautas->pager
			];
		}
		$data['urlComponente'] = '\App\Libraries\Cards::cardsVerticaisSimples';
		$data['classeListaCSS'] = 'listagem-colaborador';
		return view_cell('\App\Libraries\Listas::listasVerticaisSimples', $data);
	}

	public function links(): string
	{
		$data = array();
		return view('links', $data);
	}

	private function verificaCaptcha($captcha_response)
	{
		if (getenv('CI_ENVIRONMENT') == 'development') {
			return true;
		}
		if ($captcha_response == NULL || $captcha_response == '') {
			return false;
		}
		$data = array(
			'secret' => "ES_99f25bb22874418ea4aff1e104784bb3",
			'response' => $captcha_response
		);
		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($verify);
		// var_dump($response);
		$responseData = json_decode($response);
		if ($responseData->success) {
			return true;
		} else {
			return false;
		}
	}

	private function logar_cookie()
	{
		$retorno = new \App\Libraries\RetornoPadrao();
		if (get_cookie('hash') !== null) {
			$colaboradoresModel = new \App\Models\ColaboradoresModel();
			$colaboradoresModel->where("'" . $this->secured_decrypt(get_cookie('hash')) . "' = MD5(CONCAT(email,senha))");
			$colaborador = $colaboradoresModel->get()->getResultArray();
			if (empty($colaborador)) {
				delete_cookie('hash');
				return false;
			}
			$colaborador = $colaborador[0];
			$estrutura_session = [
				'colaboradores' => [
					'id' => $colaborador['id'],
					'nome' => $colaborador['apelido'],
					'email' => $colaborador['email'],
					'avatar' => ($colaborador['avatar'] != NULL) ? ($colaborador['avatar']) : (site_url('public/assets/avatar-default.png')),
					'permissoes' => array()
				]
			];

			$permissoes = array();
			$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
			$colaboradoresAtribuicoes = $colaboradoresAtribuicoesModel->getAtribuicoesColaborador($colaborador['id']);
			foreach ($colaboradoresAtribuicoes as $atribuicao) {
				$permissoes[] = $atribuicao['atribuicoes_id'];
			}
			$estrutura_session['colaboradores']['permissoes'] = $permissoes;

			$colaboradoresHistorico = new \App\Libraries\ColaboradoresHistoricos();
			$colaboradoresHistorico->cadastraHistorico($colaborador['id'], 'acessar', NULL, NULL);

			$this->session->set($estrutura_session);
			return true;
		}
		return false;
	}

	private function secured_encrypt($string)
	{
		$first_key = getenv('FIRSTKEY');
		$second_key = getenv('SECONDKEY');

		$method = getenv('METHOD');
		$method_hmac = getenv('METHOD_HMAC');

		$iv_length = openssl_cipher_iv_length($method);
		$iv = openssl_random_pseudo_bytes($iv_length);

		$first_encrypted = openssl_encrypt($string, $method, $first_key, OPENSSL_RAW_DATA, $iv);
		$second_encrypted = hash_hmac($method_hmac, $first_encrypted, $second_key, TRUE);

		$output = base64_encode($iv . $second_encrypted . $first_encrypted);
		return $output;
	}

	private function secured_decrypt($input)
	{
		$first_key = getenv('FIRSTKEY');
		$second_key = getenv('SECONDKEY');
		$mix = base64_decode($input);

		$method = getenv('METHOD');
		$method_hmac = getenv('METHOD_HMAC');

		$iv_length = openssl_cipher_iv_length($method);
		$iv = substr($mix, 0, $iv_length);

		$second_encrypted = substr($mix, $iv_length, 64);
		$first_encrypted = substr($mix, $iv_length + 64);

		$data = openssl_decrypt($first_encrypted, $method, $first_key, OPENSSL_RAW_DATA, $iv);
		$second_encrypted_new = hash_hmac($method_hmac, $first_encrypted, $second_key, TRUE);

		if (hash_equals($second_encrypted, $second_encrypted_new)) {
			return $data;
		}

		return false;
	}

	public function calculadoras()
	{
		return view('calculadoras');
	}

}
