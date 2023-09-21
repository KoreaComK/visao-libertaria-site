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
		$data['pautasList'] = [
			'pautas' => $pautas->paginate(12, 'pautas'),
			'pager' => $pautas->pager
		];
		return view('colaboradores/pautas_list', $data);
	}

	public function cadastrar()
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('1');
		$data = array();
		$retorno = new \App\Libraries\RetornoPadrao();
		$data['titulo'] = 'Sugira uma pauta';
		if ($this->request->isAJAX()) {
			$post = service('request')->getPost();

			$pautasModel = new \App\Models\PautasModel();
			$countPautas = $pautasModel->isPautaCadastrada($post['link_pauta']);

			if ($countPautas == 0) {
				$main_url = $post['link_pauta'];
				@$str = file_get_contents($main_url);

				if (strlen($str) > 0) {
					$str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
					preg_match("/\<title\>(.*)\<\/title\>/i", $str, $title);
				}

				$b = $main_url;
				@$url = parse_url($b);
				@$tags = get_meta_tags($main_url);

				$titulo = isset($tags['twitter:title']) ? ($tags['twitter:title']) : ('');
				$descricao = isset($tags['description']) ? ($tags['description']) : ('');

				$d = new \DOMDocument();
				if($d === null || empty($str)) {
					return $retorno->retorno(false,'Não foi possível trazer informações da pauta automaticamente.', true);
				}
				@$d->loadHTML($str);
				$xp = new \DOMXPath($d);
				foreach ($xp->query("//meta[@property='og:image']") as $el) {
					$l2 = parse_url($el->getAttribute("content"));
					if ($l2['scheme']) {
						$img[] = $el->getAttribute("content");
					} else {

					}
				}
				if (isset($title) && $titulo != '' && isset($tags['description']) && $descricao != '') {
					$retorno = [
						'status' => true,
						'titulo' => $titulo,
						'texto' => $descricao,
						'imagem' => $img
					];
					return json_encode($retorno);
				} else {
					return $retorno->retorno(false,'Não foi possível trazer informações da pauta automaticamente.', true);
				}
			} else {
				return $retorno->retorno(false,'Pauta já cadastrada', true);
			}
		}

		if ($this->request->getMethod() == 'post') {
			$post = service('request')->getPost();
			$session = $this->session->get('colaboradores');

			$pautasModel = new \App\Models\PautasModel();

			$validaFormularios = new \App\Libraries\ValidaFormularios();
			$valida = $validaFormularios->validaFormularioPauta($post);
			if (empty($valida->getErrors())) {

				$dados = array();
				$dados['id'] = $pautasModel->getNovaUUID();
				$dados['colaboradores_id'] = $session['id'];
				$dados['link'] = $post['link'];
				$dados['titulo'] = $post['titulo'];
				$dados['texto'] = $post['texto'];
				$dados['imagem'] = $post['imagem'];

				$pautas = $pautasModel->save($dados);
				if ($pautas) {
					return redirect()->to(base_url() . 'colaboradores/pautas?status=true');
				} else {
					$data['erros'] = $retorno->retorno(false,'Ocorreu um erro ao cadastrar a pauta', false);
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
		return view('colaboradores/pautas_form', $data);
	}

	public function fechar()
	{
		
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('10');
		if ($this->request->getMethod() == 'post') {
			$post = service('request')->getPost();
			$retorno = new \App\Libraries\RetornoPadrao();
			$pautasModel = new \App\Models\PautasModel();
			if(isset($post['metodo'])&&$post['metodo']=='descartar')
			{
				$pautasModel->delete($post['id']);
				return $retorno->retorno(true, '', true);
			}
			if(isset($post['metodo'])&&$post['metodo']=='reservar')
			{
				$gravar['id'] = $post['id'];
				$gravar['reservado'] = $pautasModel->getNow();
				$gravar['tag_fechamento'] = trim($post['tag']);
				$pautasModel->save($gravar);
				return $retorno->retorno(true, '', true);
			}
			if(isset($post['metodo'])&&$post['metodo']=='cancelar')
			{
				$gravar['id'] = $post['id'];
				$gravar['reservado'] = NULL;
				$gravar['tag_fechamento'] = NULL;
				$pautasModel->save($gravar);
				return $retorno->retorno(true, '', true);
			}
			if(isset($post['metodo'])&&$post['metodo']=='fechar')
			{
				helper('date');
				
				$pautasFechadasModel = new \App\Models\PautasFechadasModel();
				$pautasPautasFechadasModel = new \App\Models\PautasPautasFechadasModel();	
				
				$gravar['titulo'] = ($post['titulo'] == '')?('Pauta do dia '.Time::now()->toLocalizedString('dd MMMM yyyy')):($post['titulo']);
				
				$pautas = $pautasModel->getPautasFechamento();
				if($pautas == null || empty($pautas)){
					return $retorno->retorno(false, 'Atenção! Não foi reservada nenhuma pauta.', true);
				}

				$idPautasFechadas = $pautasFechadasModel->insert($gravar);
				foreach($pautas as $pauta){
					$pautasPautasFechadasModel->insert(array('pautas_fechadas_id'=>$idPautasFechadas, 'pautas_id'=>$pauta['id']));
					$pautasModel->delete($pauta['id']);
				}				

				return $retorno->retorno(true, 'Fechamento da pauta feita com sucesso. A página será recarregada dentro de instantes.', true);
			}
			return false;
		}
		
		
		$data = array();
		$data['titulo'] = 'Fechamento de Pautas';
		$pautasModel = new \App\Models\PautasModel();
		$pautas = $pautasModel->getPautas(null);
		$data['pautasList'] = [
			'pautas' => $pautas->paginate(12, 'pautas'),
			'pager' => $pautas->pager
		];
		return view('colaboradores/pautas_closing', $data);
	}

	public function fechadas($idPautasFechadas = null)
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('10');
		if ($this->request->getMethod() == 'post') {
			$post = service('request')->getPost();
			$retorno = new \App\Libraries\RetornoPadrao();
			$pautasModel = new \App\Models\PautasModel();
			if(isset($post['metodo'])&&$post['metodo']=='cancelar')
			{
				$gravar['id'] = $post['id'];
				$gravar['reservado'] = NULL;
				$gravar['tag_fechamento'] = NULL;
				$pautasModel->save($gravar);
				return $retorno->retorno(true, '', true);
			}
			return false;
		}
		
		$data = array();
		$pautasFechadasModel = new \App\Models\PautasFechadasModel();
		
		if($idPautasFechadas == null){
			$data['titulo'] = 'Pautas Fechadas';
			$pautas = $pautasFechadasModel->getPautasFechadas();
			$data['pautasList'] = [
				'pautas' => $pautas->paginate(12, 'pautas'),
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
			foreach($pautasPautaFechada as $ppf){
				if($tag != $ppf['tag_fechamento'])
				{
					$pautasArray[$ppf['tag_fechamento']] = array();
					$pautasArray[$ppf['tag_fechamento']]['pautas'] = array();
					$pautasArray[$ppf['tag_fechamento']]['colaboradores'] = array();
					$tag = $ppf['tag_fechamento'];
				}
				$pautasArray[$ppf['tag_fechamento']]['pautas'][] = $ppf;
				if(!in_array($ppf['apelido'],$pautasArray[$ppf['tag_fechamento']]['colaboradores']))
				{
					$pautasArray[$ppf['tag_fechamento']]['colaboradores'][] = $ppf['apelido'];
				}
			}
			
			$data['pautasList'] = $pautasArray;
			return view('colaboradores/pautas_fechadas_detail', $data);
		}
		
	}

}