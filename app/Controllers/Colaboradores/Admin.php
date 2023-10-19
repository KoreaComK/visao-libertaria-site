<?php

namespace App\Controllers\Colaboradores;

use App\Controllers\BaseController;

use App\Libraries\VerificaPermissao;
use CodeIgniter\I18n\Time;

class Admin extends BaseController
{
	public $verificaPermissao;
	function __construct()
	{
		$this->verificaPermissao = new verificaPermissao();
		helper('url_friendly,data');
	}

	public function administracao()
	{

		$this->verificaPermissao->PermiteAcesso('7');
		$data=array();
		return view('colaboradores/administracao_detail', $data);
	}

	public function permissoes($idColaboradores = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('9');
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$atribuicoesModel = new \App\Models\AtribuicoesModel();
		$colaboradoresAtribuicoesModel = new \App\Models\ColaboradoresAtribuicoesModel();
		$retorno = new \App\Libraries\RetornoPadrao();
		$data['atribuicoes'] = $atribuicoesModel->findall();

		if ($idColaboradores != NULL) {
			$colaboradores_atribuicoes = $colaboradoresAtribuicoesModel->getAtribuicoesColaborador($idColaboradores);
			$data['colaboradores_atribuicoes'] = array();
			if ($colaboradores_atribuicoes != NULL && !empty($colaboradores_atribuicoes)) {
				foreach ($colaboradores_atribuicoes as $ca) {
					$data['colaboradores_atribuicoes'][] = $ca['atribuicoes_id'];
				}
			}
			$data['colaboradores'] = $colaboradoresModel->find($idColaboradores);
			$data['titulo'] = 'Cadastro do Colaborador';
			return view('colaboradores/permissoes_form', $data);
		}

		if ($this->request->getMethod() == 'post') {
			$post = service('request')->getPost();
			if (isset($post['atribuicoes']) && isset($post['colaborador_id'])) {
				$colaboradoresAtribuicoesModel->db->transStart();
				$colaboradoresAtribuicoesModel->deletarAtribuicoesColaborador($post['colaborador_id']);
				$colaboradoresAtribuicoesModel->db->transComplete();
				foreach ($post['atribuicoes'] as $atribuicao) {
					$colaboradoresAtribuicoesModel->db->transStart();
					$colaboradoresAtribuicoesModel->insert(array('colaboradores_id' => $post['colaborador_id'], 'atribuicoes_id' => $atribuicao));
					$colaboradoresAtribuicoesModel->db->transComplete();
				}
				return $retorno->retorno(true, 'Permissões do colaborador salvas.', true);
			}
			return $retorno->retorno(false, 'Ocorreu um erro na hora de salvar as permissões do usuário', true);
		}

		$data['titulo'] = 'Permissões - Colaboradores';

		return view('colaboradores/permissoes_list', $data);
	}

	public function permissoesList()
	{

		$this->verificaPermissao->PermiteAcesso('9');
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		if ($this->request->getMethod() == 'get') {
			$get = service('request')->getGet();
			$colaboradores = $colaboradoresModel->getTodosColaboradores($get['apelido'], $get['email'], $get['atribuicao'], $get['status']);
			$data['colaboradoresList'] = [
				'colaboradores' => $colaboradores->paginate(12, 'colaboradores'),
				'pager' => $colaboradores->pager
			];
		}
		return view('template/templatePermissoesList', $data);
	}

	public function historico()
	{

		$this->verificaPermissao->PermiteAcesso('9');
		$colaboradoresHistoricosModel = new \App\Models\ColaboradoresHistoricosModel();
		if ($this->request->getMethod() == 'get') {
			$get = service('request')->getGet();
			$colaboradoresHistoricos = $colaboradoresHistoricosModel->where('colaboradores_id',$get['apelido'])->orderBy('criado','DESC');
			$data['colaboradoresHistoricosList'] = [
				'colaboradoresHistoricos' => $colaboradoresHistoricos->paginate(12, 'historico'),
				'pager' => $colaboradoresHistoricos->pager
			];
		}
		return view('template/templateHistoricosList', $data);
	}

	public function financeiro($acao = null)
	{
		$pagamentosModel = new \App\Models\PagamentosModel();
		$data = array();

		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('8');

		if ($acao == 'pagar') {
			$data['titulo'] = 'Pagar artigos publicados';
			return view('colaboradores/pagamentos_form', $data);
		}

		if ($acao == 'preview') {
			if ($this->request->getMethod() == 'post') {
				$post = service('request')->getPost();
				$data = array();
				$data = $this->geraPreviewPagamento($post);
				return view('template/templatePagamentosArtigosList', $data);
			}
		}

		if ($acao == 'salvar') {
			if ($this->request->getMethod() == 'post') {
				$post = service('request')->getPost();
				$data = array();
				$validaFormularios = new \App\Libraries\ValidaFormularios();
				$retorno = new \App\Libraries\RetornoPadrao();
				$valida = $validaFormularios->validaFormularioCadastroPagamento($post);
				if (empty($valida->getErrors())) {
					$data = $this->salvaPagamento($post);
					if ($data) {
						return $retorno->retorno(true, 'Pagamento Salvo', true);
					} else {
						return $retorno->retorno(false, 'Ocorreu um erro ao gravar o Pagamento', true);
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

		if ($acao == 'detalhe') {
			if ($this->request->getMethod() == 'post') {
				$post = service('request')->getPost();
				$data = array();
				$data = $this->geraPreviewPagamento($post);
				return view('template/templatePagamentosArtigosList', $data);
			}
		}


		if ($acao !== NULL) {
			$data['titulo'] = 'Pagar artigos publicados';
			$pagamentosModel = new \App\Models\PagamentosModel();
			$data['pagamentos'] = $pagamentosModel->find($acao);
			return view('colaboradores/pagamentos_form', $data);
		} else {
			$data['titulo'] = 'Pagamentos realizados';
			$pagamentosModel = new \App\Models\PagamentosModel();
			$pagamentos = $pagamentosModel->getPagamentos();
			$data['pagamentosList'] = [
				'pagamentos' => $pagamentos->paginate(12, 'pagamentos'),
				'pager' => $pagamentos->pager
			];
			return view('colaboradores/pagamentos_list', $data);
		}
	}

	private function geraPreviewPagamento($post)
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('8');

		$data = array();
		$pagamentos_id = NULL;

		if(isset($post['pagamento_id'])){
			$pagamentosModel = new \App\Models\PagamentosModel();
			$pagamentos_id = $post['pagamento_id'];
			$post=$pagamentosModel->find($pagamentos_id);
		}

		$multiplicadores = array();
		$multiplicadores['escrito'] = (float) $post['multiplicador_escrito'] / 100;
		$multiplicadores['revisado'] = (float) $post['multiplicador_revisado'] / 100;
		$multiplicadores['narrado'] = (float) $post['multiplicador_narrado'] / 100;
		$multiplicadores['produzido'] = (float) $post['multiplicador_produzido'] / 100;
		$repasse_bitcoin = (float) $post['quantidade_bitcoin'];

		$artigosModel = new \App\Models\ArtigosModel();
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		
		if($pagamentos_id!==null){
			$pagamentosArtigosModel = new \App\Models\PagamentosArtigosModel();
			$pagamentosArtigos=$pagamentosArtigosModel->where('pagamentos_id',$pagamentos_id)->get()->getResultArray();
			$artigos_id = array();
			foreach($pagamentosArtigos as $pa){
				$artigos_id[] = $pa['artigos_id'];
			}
			$data['artigos'] = $artigosModel->getArtigos($artigos_id)->get()->getResultArray();
			$data['pagamentos_id'] = $pagamentos_id;
			
		} else {
			$data['artigos'] = $artigosModel->getArtigos('6')->get()->getResultArray();
		}
		if($data['artigos'] == NULL || empty($data['artigos'])){
			$data['artigos'] = NULL;
			$data['usuarios'] = NULL;
			return $data;
		}

		$usuarios = array();
		$usuarios_id = array();
		$pontos_totais = 0;
		$repasse_string = "";
		$dados = [
			'endereco' => null,
			'apelido' => null,
			'pontos_escrita' => 0,
			'pontos_revisao' => 0,
			'pontos_narracao' => 0,
			'pontos_producao' => 0,
			'pontos_total' => 0,
			'repasse' => 0,
		];
		foreach ($data['artigos'] as $i => $artigo) {
			$usuarios_id[] = $artigo['escrito_colaboradores_id'];
			$usuarios_id[] = $artigo['revisado_colaboradores_id'];
			$usuarios_id[] = $artigo['narrado_colaboradores_id'];
			$usuarios_id[] = $artigo['produzido_colaboradores_id'];
			$data['artigos'][$i]['total_pontuacao'] = (float) $artigo['palavras_escritor'] * $multiplicadores['escrito'] + $artigo['palavras_revisor'] * $multiplicadores['revisado'] + $artigo['palavras_narrador'] * $multiplicadores['narrado'] + $artigo['palavras_produtor'] * $multiplicadores['produzido'];
		}
		$usuarios_id = array_unique($usuarios_id);
		$usuarios_dados = $colaboradoresModel->find($usuarios_id);
		foreach ($usuarios_dados as $usuario) {
			$usuarios[$usuario['id']] = $dados;
			$usuarios[$usuario['id']]['endereco'] = $usuario['carteira'];
			$usuarios[$usuario['id']]['apelido'] = $usuario['apelido'];
		}

		foreach ($data['artigos'] as $artigo) {
			$usuarios[$artigo['escrito_colaboradores_id']]['pontos_escrita'] += (float) $artigo['palavras_escritor'] * $multiplicadores['escrito'];
			$usuarios[$artigo['revisado_colaboradores_id']]['pontos_revisao'] += (float) $artigo['palavras_revisor'] * $multiplicadores['revisado'];
			$usuarios[$artigo['narrado_colaboradores_id']]['pontos_narracao'] += (float) $artigo['palavras_narrador'] * $multiplicadores['narrado'];
			$usuarios[$artigo['produzido_colaboradores_id']]['pontos_producao'] += (float) $artigo['palavras_produtor'] * $multiplicadores['produzido'];
		}

		foreach ($usuarios as $i => $u) {
			if ($u['endereco'] != NULL) {
				$usuarios[$i]['pontos_total'] = $u['pontos_escrita'] + $u['pontos_revisao'] + $u['pontos_narracao'] + $u['pontos_producao'];
				$pontos_totais += $usuarios[$i]['pontos_total'];
			}
		}

		foreach ($usuarios as $i => $u) {
			if ($u['endereco'] != NULL) {
				$usuarios[$i]['repasse'] = $repasse_bitcoin * ($usuarios[$i]['pontos_total'] / $pontos_totais);
				$repasse_string .= $usuarios[$i]['endereco'] . ' ' . number_format($usuarios[$i]['repasse'], 8, ',', '.') . "\n";
			}
		}
		$data['repasse_string'] = $repasse_string;
		$data['usuarios'] = $usuarios;
		return $data;
	}

	private function salvaPagamento($post)
	{
		$verifica = new verificaPermissao();
		$verifica->PermiteAcesso('8');

		$data = array();
		$artigosModel = new \App\Models\ArtigosModel();
		$pagamentosModel = new \App\Models\PagamentosModel();
		$pagamentosArtigosModel = new \App\Models\PagamentosArtigosModel();
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$faseProducaoModel = new \App\Models\FaseProducaoModel();

		$artigos = $artigosModel->getArtigos('6')->get()->getResultArray();
		if($artigos == null || empty($artigos)){
			return false;
		}

		$gravar = array();
		$gravar['titulo'] = ($post['titulo'] == '') ? ('Pagamento do mês ' . Time::now()->toLocalizedString('MMMM yyyy')) : ($post['titulo']);
		$gravar['quantidade_bitcoin'] = $post['quantidade_bitcoin'];
		$gravar['multiplicador_escrito'] = $post['multiplicador_escrito'];
		$gravar['multiplicador_revisado'] = $post['multiplicador_revisado'];
		$gravar['multiplicador_narrado'] = $post['multiplicador_narrado'];
		$gravar['multiplicador_produzido'] = $post['multiplicador_produzido'];
		$gravar['hash_transacao'] = $post['hash_transacao'];
		$pagamentosModel->db->transStart();
		$idPagamentos = $pagamentosModel->insert($gravar);
		$pagamentosModel->db->transComplete();

		$faseProducao = $faseProducaoModel->find(6);
		$faseProducao = $faseProducao['etapa_posterior'];
		foreach ($artigos as $artigo) {
			
			$pagamentosArtigosModel->save(
				array(
					'artigos_id' => $artigo['id'],
					'pagamentos_id' => $idPagamentos
				)
			);

			$colaborador = $colaboradoresModel->find($artigo['escrito_colaboradores_id']);
			$colaborador['pontuacao_total'] = $colaborador['pontuacao_total'] + $artigo['palavras_escritor'];
			$colaboradoresModel->save($colaborador);

			$colaborador = $colaboradoresModel->find($artigo['revisado_colaboradores_id']);
			$colaborador['pontuacao_total'] = $colaborador['pontuacao_total'] + $artigo['palavras_revisor'];
			$colaboradoresModel->save($colaborador);

			$colaborador = $colaboradoresModel->find($artigo['narrado_colaboradores_id']);
			$colaborador['pontuacao_total'] = $colaborador['pontuacao_total'] + $artigo['palavras_narrador'];
			$colaboradoresModel->save($colaborador);

			$colaborador = $colaboradoresModel->find($artigo['produzido_colaboradores_id']);
			$colaborador['pontuacao_total'] = $colaborador['pontuacao_total'] + $artigo['palavras_produtor'];
			$colaboradoresModel->save($colaborador);

			$art = array();
			$art['atualizado'] = $artigosModel->getNow();
			$art['fase_producao_id'] = $faseProducao;
			$artigosModel->update($artigo['id'], $art);
		}
		return true;
	}
}
