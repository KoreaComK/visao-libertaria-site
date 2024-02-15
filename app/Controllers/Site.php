<?php

namespace App\Controllers;

use App\Libraries\WidgetsSite;
use Config\App;
use CodeIgniter\I18n\Time;

class Site extends BaseController
{
	/*HOME PAGE*/
	public function index(): string
	{
		$data = array();
		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$data['config'] = array();
		$data['config']['home_banner'] = (int)$configuracaoModel->find('home_banner')['config_valor'];
		$data['config']['home_banner_mostrar'] = $configuracaoModel->find('home_banner_mostrar')['config_valor'];
		$data['config']['home_newsletter_mostrar'] = $configuracaoModel->find('home_newsletter_mostrar')['config_valor'];
		$data['config']['home_talvez_goste'] = (int)$configuracaoModel->find('home_talvez_goste')['config_valor'];
		$data['config']['home_talvez_goste_mostrar'] = $configuracaoModel->find('home_talvez_goste_mostrar')['config_valor'];
		$data['config']['home_ultimos_videos'] = (int)$configuracaoModel->find('home_ultimos_videos')['config_valor'];
		$data['config']['home_ultimos_videos_mostrar'] = $configuracaoModel->find('home_ultimos_videos_mostrar')['config_valor'];



		$data['colaboradores'] = $this->session->get('colaboradores');

		$widgets = new WidgetsSite();

		//$data['widgetCategorias'] = $widgets->widgetCategorias();
		$data['widgetEsteiraProducao'] = $widgets->widgetArtigosByFaseProducaoCount();
		$artigosModel = new \App\Models\ArtigosModel();
		
		$quantidade_artigos = 0;
		if($data['config']['home_banner_mostrar'] == '1') {
			$quantidade_artigos += $data['config']['home_banner'];
		}
		if($data['config']['home_ultimos_videos_mostrar'] == '1') {
			$quantidade_artigos += $data['config']['home_ultimos_videos'];
		}

		$artigos = $artigosModel->getArtigosHome($quantidade_artigos);
		if ($artigos === null || empty($artigos)) {
			$data['artigos'] = false;
		} else {
			$data['banner'] = [];
			$data['artigos'] = [];
			for ($i = 0; $i < count($artigos); $i++) {
				if (isset($artigos[$i]) && count($data['banner']) < $data['config']['home_banner']) {
					$data['banner'][] = $artigos[$i];
				} else {
					$data['artigos'][] = $artigos[$i];
				}
			}
		}

		$quantidade_artigos = 0;
		if($data['config']['home_talvez_goste_mostrar'] == '1') {
			$quantidade_artigos += $data['config']['home_talvez_goste'];
		}
		$artigos = $artigosModel->getArtigosHomeRand($quantidade_artigos);
		if ($artigos === null || empty($artigos)) {
			$data['rand'] = false;
		} else {
			$data['rand'] = $artigos;
		}


		helper('colors_helper');
		return view('home', $data);
	}

	/*LISTAGEM DE ARTIGOS*/
	public function artigos($id_categoria = null): string
	{
		$data = array();

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];

		$artigosModel = new \App\Models\ArtigosModel();
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
		//$artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();

		$artigosModel->where('url_friendly', $url_friendly);
		$query = $artigosModel->get();

		if ($artigosModel->countAllResults() > 0) {
			$data['artigo'] = $query->getRowArray();

			//$data['artigo']['categorias'] = $artigosCategoriasModel->getCategoriasArtigo($data['artigo']['id']);
			$data['artigo']['colaboradores'] = $artigosModel->getColaboradoresArtigo($data['artigo']['id'])[0];
			return view('artigo', $data);
		} else {
			return redirect()->to(base_url() . 'site/artigos');
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
					if ($colaborador['strike_data'] !== NULL && Time::parse($colaborador['strike_data'])->difference(Time::parse(Time::now()))->seconds < 0) {
						return $retorno->retorno(false, 'Sua conta está bloqueada até ' . Time::createFromFormat('Y-m-d H:i:s', $colaborador['strike_data'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'), true);
					}

					$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
					$colaboradoresAtribuicoes = $colaboradoresAtribuicoesModel->getAtribuicoesColaborador($colaborador['id']);

					if (empty($colaboradoresAtribuicoes)) {
						return $retorno->retorno(false, 'Atenção! Você não possui nenhuma atribuição. Acesso negado.', true);
					}
					if(get_cookie('lembrar') != 1) {
						if (!$this->verificaCaptcha($post['h-captcha-response'])) {
							return $retorno->retorno(false, 'Você não resolveu corretamente o Captcha.', true);
						}
					}

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
					foreach ($colaboradoresAtribuicoes as $atribuicao) {
						$permissoes[] = $atribuicao['atribuicoes_id'];
					}
					$estrutura_session['colaboradores']['permissoes'] = $permissoes;

					$this->session->set($estrutura_session);

					if (isset($post['lembrar'])) {
						set_cookie('email', $post['email'], 60 * 60 * 24 * 7);
						set_cookie('senha', $post['senha'], 60 * 60 * 24 * 7);
						set_cookie('lembrar', true, 60 * 60 * 24 * 7);
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
			$this->session->destroy();

			$get = service('request')->getGet();
			$url = false;
			if(!empty($get) && isset($get['url'])) {
				$url = $get['url'];
			}
			$data['url'] = $url;

			if (get_cookie('email') !== null && get_cookie('senha') !== null) {
				$data['email_form'] = get_cookie('email');
				$data['senha_form'] = get_cookie('senha');
				$data['lembrar'] = get_cookie('lembrar');
			} else {
				$data['email_form'] = '';
				$data['senha_form'] = '';
				$data['lembrar'] = '';
			}
			return view('login', $data);
		}
	}

	public function logout()
	{
		$this->session->destroy();
		$link = base_url() . 'site/login';
		$get = $this->request->getGet();
		if(!empty($get)) {
			$link.='?url='.$get['url'];
		}
		return redirect()->to($link);
	}

	/* CONTATO */
	public function contato()
	{
		$data = array();
		if ($this->request->isAJAX()) {
			$validaFormularios = new \App\Libraries\ValidaFormularios();
			$retorno = new \App\Libraries\RetornoPadrao();
			$post = $this->request->getPost();

			$valida = $validaFormularios->validaFormularioContato($post);
			if (empty($valida->getErrors())) {
				if (!$this->verificaCaptcha($post['h-captcha-response'])) {
					return $retorno->retorno(false, 'Você não resolveu corretamente o Captcha.', true);
				} else {
					$configuracaoModel = new \App\Models\ConfiguracaoModel();
					$config = array();
					$config['contato_email'] = $configuracaoModel->find('contato_email')['config_valor'];
					$config['contato_email_copia'] = $configuracaoModel->find('contato_email_copia')['config_valor'];
					$config['contato_email_copia'] = ($config['contato_email_copia']=='')?(false):($config['contato_email_copia']);
					$enviaEmail = new \App\Libraries\EnviaEmail();
					$enviaEmail->enviaEmail($config['contato_email'], 'CONTATO - '.$post['assunto'], $enviaEmail->getMensagemContato($post['mensagem'],$post['email']), $config['contato_email_copia']);
					return $retorno->retorno(true, 'Contato enviado com sucesso. Iremos responder assim que possível.', true);
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
		if(($this->session->has('colaboradores'))) {
			$data['email'] = $this->session->get('colaboradores')['email'];
		}
		return view('contato', $data);
	}

	private function verificaCaptcha($captcha_response)
	{
		if($captcha_response == NULL || $captcha_response == '') {
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

	public function calculadoras()
	{
		return view('calculadoras');
	}

}
