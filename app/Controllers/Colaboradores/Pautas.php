<?php

namespace App\Controllers\Colaboradores;

use App\Controllers\BaseController;

use App\Libraries\VerificaPermissao;
use CodeIgniter\I18n\Time;

class Pautas extends BaseController
{
	public function index(): string
	{
		$data = array();
		$data['titulo'] = 'Pautas Sugeridas';
		$pautasModel = new \App\Models\PautasModel();
		$pautas = $pautasModel->getPautas();

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];
		$data['pautasList'] = [
			'pautas' => $pautas->paginate($config['site_quantidade_listagem'], 'pautas'),
			'pager' => $pautas->pager
		];
		return view('colaboradores/pautas_list', $data);
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

		if ($this->request->isAJAX()) {
			$post = service('request')->getPost();

			$countPautas = $pautasModel->isPautaCadastrada($post['link_pauta'], $idPautas);

			if ($countPautas == 0) {
				return $this->getInformacaoLink($post);
			} else {
				return $retorno->retorno(false, 'Pauta já cadastrada', true);
			}
		}

		if ($this->request->getMethod() == 'post') {
			$pautasModel = new \App\Models\PautasModel();

			$post = service('request')->getPost();
			if($idPautas !== null) {
				$post['link'] = $pautasModel->find($idPautas)['link'];
			}

			$session = $this->session->get('colaboradores');
			

			$time = Time::today();
			$time = $time->toDateString();
			$quantidade_pautas = $pautasModel->getPautasPorUsuario($time, $session['id'])[0]['contador'];
			if ($quantidade_pautas >= $data['config']['limite_pautas_diario']) {
				$data['erros'] = $retorno->retorno(false, 'O limite diário de pautas foi atingido. Tente novamente amanhã.', false);
				return view('colaboradores/pautas_form', $data);
			}

			$time = new Time('-7 days');
			$time = $time->toDateString();
			$quantidade_pautas = $pautasModel->getPautasPorUsuario($time,$session['id'])[0]['contador'];
			if($quantidade_pautas >= $data['config']['limite_pautas_semanal']) {
				$data['erros'] = $retorno->retorno(false, 'O limite semanal de pautas foi atingido. Tente novamente outro dia.', false);
				return view('colaboradores/pautas_form', $data);
			}

			$gerenciadorTextos = new \App\Libraries\GerenciadorTextos();
			$validaFormularios = new \App\Libraries\ValidaFormularios();
			$valida = $validaFormularios->validaFormularioPauta($post);
			if (empty($valida->getErrors())) {
				if (is_array(@getimagesize($post['imagem']))) {
					if($gerenciadorTextos->contaPalavras($post['texto']) > $data['config']['pauta_tamanho_maximo'] || $gerenciadorTextos->contaPalavras($post['texto']) < $data['config']['pauta_tamanho_minimo']) {
						$data['erros'] = $retorno->retorno(false, 'O tamanho do texto está fora dos limites.', false);
						return view('colaboradores/pautas_form', $data);
					}
					$dados = array();
					$dados['colaboradores_id'] = $session['id'];
					$dados['link'] = $post['link'];
					$dados['titulo'] = $post['titulo'];
					$dados['texto'] = $post['texto'];
					$dados['imagem'] = $post['imagem'];
					if(isset($post['pauta_antiga']) && $post['pauta_antiga']=='S') {
						$dados['pauta_antiga'] = $post['pauta_antiga'];
					}
					if ($idPautas != null) {
						$pautas = $this->gravarPautas('update', $dados, $idPautas);
					} else {
						$dados['id'] = $pautasModel->getNovaUUID();
						$pautas = $this->gravarPautas('insert', $dados);
					}
	
					if ($pautas) {
						return redirect()->to(base_url() . 'colaboradores/pautas?status=true');
					} else {
						$data['erros'] = $retorno->retorno(false, 'Ocorreu um erro ao cadastrar a pauta', false);
					}
				} else {
					$data['erros'] = $retorno->retorno(false, 'O link informado não é uma imagem.', false);
					$data['post'] = $post;
				}
			} else {
				$erros = $valida->getErrors();
				$string_erros = '';
				foreach ($erros as $erro) {
					$string_erros .= $erro . "<br/>";
				}
				$data['erros'] = $retorno->retorno(false, $string_erros, false);
				$data['post'] = $post;
			}
		}

		if ($idPautas != null) {
			$data['post'] = $pautasModel->find($idPautas);
			if ($data['post']['colaboradores_id'] != $this->session->get('colaboradores')['id']) {
				return redirect()->to(base_url() . 'colaboradores/pautas');
			}
		}
		return view('colaboradores/pautas_form', $data);
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
		$data['titulo'] = 'Leia a pauta';

		$pautasModel = new \App\Models\PautasModel();

		if ($idPautas != null) {
			$data['post'] = $pautasModel->withDeleted()->find($idPautas);
			if ($data['post'] == null || empty($data['post'])) {
				return redirect()->to(base_url() . 'colaboradores/pautas');
			}
		} else {
			return redirect()->to(base_url() . 'colaboradores/pautas');
		}
		$data['readOnly'] = true;
		return view('colaboradores/pautas_form', $data);
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
			return redirect()->to(base_url() . 'colaboradores/pautas');
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
			if(!isset($get['pesquisa']) || $get['pesquisa'] == '') {
				$get['pesquisa'] = NULL;
			}
			$pautas = $pautasModel->getPautasPesquisa($get['pesquisa']);

			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$config = array();
			$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];
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
			$pautas = $pautasFechadasModel->getPautasFechadas();

			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$config = array();
			$config['site_quantidade_listagem'] = (int)$configuracaoModel->find('site_quantidade_listagem')['config_valor'];
			$data['pautasList'] = [
				'pautas' => $pautas->paginate($config['site_quantidade_listagem'], 'pautas'),
				'pager' => $pautas->pager
			];
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

	private function getInformacaoLink($post)
	{
		$retorno = new \App\Libraries\RetornoPadrao();
		$main_url = $post['link_pauta'];
		@$str = file_get_contents($main_url);

		if($str === false) {
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
			if ($l2['scheme']) {
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
			if($content !== NULL && !empty($content)) {
				$time = strtotime($content);
				$agora = Time::now();
				$time = Time::parse(date ('Y-m-d',$time));
				$dias = $time->difference($agora);
			}
		}
		
		if($dias == null) {	
			foreach($xp->query("//script") as $i) {
				if(strpos($i->nodeValue,"datePublished") > 0) {

					if(strpos($i->nodeValue,'datePublished":"') > 0 && $dias === null) {
						$x = explode('datePublished":"',$i->nodeValue)[1];
						$x = explode('"',$x)[0];
						$time = strtotime($x);
						$agora = Time::now();
						$time = Time::parse(date ('Y-m-d',$time));
						$dias = $time->difference($agora);
					}
					
					$json = json_decode($i->nodeValue);
					if($json !== NULL && is_array($json) && $dias === null) {
						foreach($json as $j) {
							if($j !== NULL && is_object($j) && isset($j->datePublished)) {
								$time = strtotime($j->datePublished);
								$agora = Time::now();
								$time = Time::parse(date ('Y-m-d',$time));
								$dias = $time->difference($agora);
							}
						}
					}

					if($json !== NULL && is_object($json) && isset($json->datePublished) && $dias === null) {
						$time = strtotime($json->datePublished);
						$agora = Time::now();
						$time = Time::parse(date ('Y-m-d',$time));

						$dias = $time->difference($agora);
					}
				}
			}
		}

		if ($titulo != '' && $descricao != '') {
			if(!mb_detect_encoding($titulo,'UTF-8',true)) {
				$titulo = utf8_encode($titulo);
			}
			if(!mb_detect_encoding($descricao,'UTF-8',true)) {
				$descricao = utf8_encode($descricao);
			}
			if(!mb_detect_encoding($img,'UTF-8',true)) {
				$img = utf8_encode($img);
			}

			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$dataMaximaPauta = (int)$configuracaoModel->find('pauta_dias_antigo')['config_valor'];

			$retorno = [
				'status' => true,
				'titulo' => html_entity_decode($titulo),
				'texto' => html_entity_decode($descricao),
				'imagem' => $img,
				'dias' => ($dias !== null)?($dias->days):($dias),
				'mensagem' => ($dias !== null && $dias->days > $dataMaximaPauta)?('ATENÇÃO! A pauta é de mais de '.$dataMaximaPauta.' dias atrás. Ela será marcada como antiga.'):(NULL)
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
		$pautasModel->db->transStart();
		switch ($tipo) {
			case 'update':
				$retorno = $pautasModel->update($id, $dados);
				break;
			case 'insert':
				$retorno = $pautasModel->insert($dados);
				break;
			case 'save':
				$retorno = $pautasModel->save($dados);
				break;
			case 'delete':
				$retorno = $pautasModel->delete($id);
				break;
			default:
				$retorno = false;
				break;
		}
		$pautasModel->db->transComplete();
		return $retorno;
	}

}
