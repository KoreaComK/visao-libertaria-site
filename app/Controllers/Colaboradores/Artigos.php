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
		helper('gera_hash_artigo');
		$this->iniciaVariavel = [
			'titulo' => null,
			'pauta' => array(),
			'fase_producao' => null,
			//'categorias_artigo' => array(),
			'artigo' => [
				'id' => null,
				'link' => null,
				'titulo' => null,
				'fase_producao_id' => null,
				'gancho' => null,
				'texto' => null,
				'referencias' => null,
				'imagem' => null
			],
			'historico' => null
		];
	}
	public function dashboard()
	{
		$data = array();
		$data['resumo'] = array();
		$data['resumo']['escrevendo'] = 0;
		$data['resumo']['revisando'] = 0;
		$data['resumo']['narrando'] = 0;
		$data['resumo']['produzindo'] = 0;
		$data['resumo']['publicando'] = 0;
		$data['resumo']['pagando'] = 0;

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$data['limite']['ativo'] = $configuracaoModel->find('cron_artigos_desmarcar_status')['config_valor'];
		$data['limite']['bloqueio'] = str_replace('days', 'dias', str_replace('hours', 'horas', $configuracaoModel->find('artigo_tempo_bloqueio')['config_valor']));
		$data['limite']['teoria']['revisao'] = str_replace('days', 'dias', str_replace('hours', 'horas', $configuracaoModel->find('cron_artigos_teoria_desmarcar_data_revisao')['config_valor']));
		$data['limite']['teoria']['narracao'] = str_replace('days', 'dias', str_replace('hours', 'horas', $configuracaoModel->find('cron_artigos_teoria_desmarcar_data_narracao')['config_valor']));
		$data['limite']['teoria']['producao'] = str_replace('days', 'dias', str_replace('hours', 'horas', $configuracaoModel->find('cron_artigos_teoria_desmarcar_data_producao')['config_valor']));
		$data['limite']['noticia']['revisao'] = str_replace('days', 'dias', str_replace('hours', 'horas', $configuracaoModel->find('cron_artigos_noticia_desmarcar_data_revisao')['config_valor']));
		$data['limite']['noticia']['narracao'] = str_replace('days', 'dias', str_replace('hours', 'horas', $configuracaoModel->find('cron_artigos_noticia_desmarcar_data_narracao')['config_valor']));
		$data['limite']['noticia']['producao'] = str_replace('days', 'dias', str_replace('hours', 'horas', $configuracaoModel->find('cron_artigos_noticia_desmarcar_data_producao')['config_valor']));
		$data['limite']['descartar']['ativo'] = str_replace('days', 'dias', str_replace('hours', 'horas', $configuracaoModel->find('cron_artigos_descartar_status')['config_valor']));
		$data['limite']['descartar']['tempo'] = str_replace('years', 'anos', str_replace('months', 'meses', str_replace('weeks', 'semanas', str_replace('days', 'dias', str_replace('hours', 'horas', $configuracaoModel->find('cron_artigos_descartar_data')['config_valor'])))));

		//Se usuário tem acesso a escritor
		$this->verificaPermissao->PermiteAcesso('2');

		$artigosModel = new \App\Models\ArtigosModel();
		$artigos = $artigosModel->select("artigos.*, A.shadowban AS sbe, B.shadowban AS sbr, C.shadowban AS sbn, D.shadowban AS sbp")
		->join('colaboradores A','A.id = artigos.escrito_colaboradores_id', 'left')
		->join('colaboradores B','B.id = artigos.revisado_colaboradores_id', 'left')
		->join('colaboradores C','C.id = artigos.narrado_colaboradores_id', 'left')
		->join('colaboradores D','D.id = artigos.produzido_colaboradores_id', 'left')
		->whereNotIn('fase_producao_id', array('7'))
		->where('descartado', NULL)->get()->getResultArray();
		foreach ($artigos as $artigo) {
			if ($artigo['fase_producao_id'] == '1' && $artigo['sbe'] != 'S') {
				$data['resumo']['escrevendo']++;
			}
			if ($artigo['fase_producao_id'] == '2' && $artigo['sbr'] != 'S') {
				$data['resumo']['revisando']++;
			}
			if ($artigo['fase_producao_id'] == '3' && $artigo['sbn'] != 'S') {
				$data['resumo']['narrando']++;
			}
			if ($artigo['fase_producao_id'] == '4' && $artigo['sbp'] != 'S') {
				$data['resumo']['produzindo']++;
			}
			if ($artigo['fase_producao_id'] == '5') {
				$data['resumo']['publicando']++;
			}
			if ($artigo['fase_producao_id'] == '6') {
				$data['resumo']['pagando']++;
			}
		}

		return view('colaboradores/colaborador_dashboard', $data);
	}

	public function cadastrar($artigoId = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('2');
		$data = $this->iniciaVariavel;

		//Carrega formulário artigo apenas para cadastro
		if ($artigoId == null && $this->request->getMethod() == 'get' && $this->request->getGet('pauta') !== null) {
			$pautaId = $this->request->getGet('pauta');
			$pautasModel = new \App\Models\PautasModel();
			$pauta = $pautasModel->find($pautaId);
			if (!empty($pauta)) {
				$data['pauta'] = $pauta;
				$data['artigo']['titulo'] = $pauta['titulo'];
				$data['artigo']['texto'] = $pauta['texto'];
			}
		}

		//Carrega formulário artigo para edição e revisão
		if ($artigoId !== NULL) {
			$artigosModel = new \App\Models\ArtigosModel();
			$artigo = $artigosModel->find($artigoId);

			$colaborador = $this->session->get('colaboradores')['id'];
			if (
				$artigo === NULL || empty($artigo) ||
				($artigo['fase_producao_id'] == '1' && $colaborador != $artigo['escrito_colaboradores_id']) ||
				($artigo['fase_producao_id'] == '2' && $colaborador == $artigo['escrito_colaboradores_id']) ||
				($artigo['fase_producao_id'] != '1' &&
					$artigo['fase_producao_id'] != '2' &&
					!in_array('6', $this->session->get('colaboradores')['permissoes']) &&
					!in_array('7', $this->session->get('colaboradores')['permissoes'])
				)
			) {
				return redirect()->to(base_url() . 'colaboradores/artigos/cadastrar/');
			}
			$data['artigo'] = $artigo;
		}

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['artigo_tamanho_maximo'] = (int) $configuracaoModel->find('artigo_tamanho_maximo')['config_valor'];
		$config['artigo_tamanho_minimo'] = (int) $configuracaoModel->find('artigo_tamanho_minimo')['config_valor'];

		$artigosTextosHistoricosModel = new \App\Models\ArtigosTextosHistoricosModel();
		$data['historicoTexto'] = $artigosTextosHistoricosModel->where('artigos_id', $artigoId)->get()->getResultArray();

		$data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);
		$data['cadastro'] = ($artigoId === NULL) ? (true) : (false);

		$data['artigo']['fase_producao_id'] = (!isset($artigo) || $artigo['fase_producao_id'] == '1') ? ('1') : ('2');
		if ($data['artigo']['fase_producao_id'] == '1') {
			$config['artigo_regras_escrever'] = $configuracaoModel->find('artigo_regras_escrever')['config_valor'];
		} else {
			$config['artigo_regras_revisar'] = $configuracaoModel->find('artigo_regras_revisar')['config_valor'];
		}
		$data['config'] = $config;
		return view('colaboradores/colaborador_artigos_form', $data);
	}

	public function artigosTextoHistorico($idArtigoHistorico = NULL)
	{
		$retorno = new \App\Libraries\RetornoPadrao();
		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'O método só pode ser acessado via AJAX.', true);
		}

		if (!$this->request->getMethod() == 'post') {
			return $retorno->retorno(false, 'Dados não informados.', true);
		}

		$artigosTextosHistoricosModel = new \App\Models\ArtigosTextosHistoricosModel();
		$historico = $artigosTextosHistoricosModel->find($idArtigoHistorico);
		return $retorno->retorno(true, 'Dados do histórico copiados.', true, $historico);
	}

	public function historicos($artigoId = NULL)
	{
		if ($artigoId === NULL) {
			return '';
		}

		$historico = $this->artigosHistoricos->buscaHistorico($artigoId);
		$html = '';
		foreach ($historico as $h) {
			$html .= '
				<li class="list-group-item p-1 border-0">
					<small>
						' . $h['apelido'] . ' ' . $h['acao'] .
				'<span class="badge badge-pill badge-secondary fw-light">'
				. Time::createFromFormat('Y-m-d H:i:s', $h['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss') .
				'</span>
					</small>
				</li>
			';
		}
		return $html;
	}

	public function artigosTextoHistoricosList($artigoId = NULL)
	{
		if ($artigoId === NULL) {
			return '';
		}

		$artigosTextosHistoricosModel = new \App\Models\ArtigosTextosHistoricosModel();
		$historicoTexto = $artigosTextosHistoricosModel->where('artigos_id', $artigoId)->get()->getResultArray();
		$html = '';
		foreach ($historicoTexto as $h) {
			$html .= '
				<li class="list-group-item p-1 border-0">
					<small><a class="btn-link btn-texto-historico" href="javascript:void(0);"
						data-bs-toggle="modal" data-bs-target="#modalVerTextoHistorico"
						id="btn-historico" data-historico-texto-id="' . $h['id'] . '" onclick="mostraHistoricoTexto(this);">
						Ver texto de
						' . Time::createFromFormat('Y-m-d H:i:s', $h['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss') . '
					</a></small>
				</li>
			';
		}
		$html .= "<script>$(function() {

});</script>";
		return $html;
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

		$permissoes = $this->session->get('colaboradores')['permissoes'];
		$permissao = false;
		if (in_array('7', $permissoes)) {
			$permissao = true;
		}
		if ($artigoId !== NULL) {
			$usuarioId = $this->session->get('colaboradores')['id'];
			$artigosModel = new \App\Models\ArtigosModel();
			$artigo = $artigosModel->find($artigoId);
			if ($permissao === false && $artigo['escrito_colaboradores_id'] != $usuarioId) {
				redirect()->to(site_url('site/logout'));
			}
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

		$data['artigos'] = array();
		$data['pautas'] = array();

		$time_atual = new Time('-30 days');
		$time_antigo = new Time('-60 days');
		$artigosModel = new \App\Models\ArtigosModel();

		$artigosModel->where("criado >= '" . $time_atual->toDateTimeString() . "'");
		$artigosModel->where("escrito_colaboradores_id", $usuarioId);
		$artigosModel->withDeleted();
		$artigos = $artigosModel->get()->getResultArray();
		$data['artigos']['atual'] = count($artigos);
		$data['artigos']['lista'] = $artigos;

		$artigosModel = new \App\Models\ArtigosModel();
		$artigosModel->where("criado >= '" . $time_antigo->toDateTimeString() . "'");
		$artigosModel->where("criado <= '" . $time_atual->toDateTimeString() . "'");
		$artigosModel->where("escrito_colaboradores_id", $usuarioId);
		$artigosModel->withDeleted();
		$artigos = $artigosModel->get()->getResultArray();
		$data['artigos']['antigo'] = count($artigos);

		$artigosModel = new \App\Models\ArtigosModel();
		$artigosModel->where("publicado >= '" . $time_atual->toDateTimeString() . "'");
		$artigosModel->where("escrito_colaboradores_id", $usuarioId);
		$artigosModel->withDeleted();
		$artigos = $artigosModel->get()->getResultArray();
		$data['artigos']['publicados_atual'] = count($artigos);

		$artigosModel = new \App\Models\ArtigosModel();
		$artigosModel->where("publicado >= '" . $time_antigo->toDateTimeString() . "'");
		$artigosModel->where("publicado <= '" . $time_atual->toDateTimeString() . "'");
		$artigosModel->where("escrito_colaboradores_id", $usuarioId);
		$artigosModel->withDeleted();
		$artigos = $artigosModel->get()->getResultArray();
		$data['artigos']['publicados_antigo'] = count($artigos);

		$data['artigos']['diferenca'] = $data['artigos']['atual'] - $data['artigos']['antigo'];
		$data['artigos']['publicados_diferenca'] = $data['artigos']['publicados_atual'] - $data['artigos']['publicados_antigo'];

		$pautasModel = new \App\Models\PautasModel();
		$pautasModel->where("criado >= '" . $time_atual->toDateTimeString() . "'");
		$pautasModel->where("colaboradores_id", $usuarioId);
		$pautasModel->withDeleted();
		$pautas = $pautasModel->get()->getResultArray();
		$data['pautas']['atual'] = count($pautas);
		$data['pautas']['lista'] = $pautas;

		$pautasModel = new \App\Models\PautasModel();
		$pautasModel->where("criado >= '" . $time_antigo->toDateTimeString() . "'");
		$pautasModel->where("criado <= '" . $time_atual->toDateTimeString() . "'");
		$pautasModel->where("colaboradores_id", $usuarioId);
		$pautasModel->withDeleted();
		$pautas = $pautasModel->get()->getResultArray();
		$data['pautas']['antigo'] = count($pautas);

		$pautasModel = new \App\Models\PautasModel();
		$pautasModel->where("reservado >= '" . $time_atual->toDateTimeString() . "'");
		$pautasModel->where("colaboradores_id", $usuarioId);
		$pautasModel->withDeleted();
		$pautas = $pautasModel->get()->getResultArray();
		$data['pautas']['utilizados_atual'] = count($pautas);

		$pautasModel = new \App\Models\PautasModel();
		$pautasModel->where("criado >= '" . $time_antigo->toDateTimeString() . "'");
		$pautasModel->where("criado <= '" . $time_atual->toDateTimeString() . "'");
		$pautasModel->where("colaboradores_id", $usuarioId);
		$pautasModel->withDeleted();
		$pautas = $pautasModel->get()->getResultArray();
		$data['pautas']['utilizados_antigo'] = count($pautas);

		$data['pautas']['diferenca'] = $data['pautas']['atual'] - $data['pautas']['antigo'];
		$data['pautas']['utilizados_diferenca'] = $data['pautas']['utilizados_atual'] - $data['pautas']['utilizados_antigo'];

		$artigosModel = new \App\Models\ArtigosModel();
		$time_antigo = new Time('-1 years');
		$artigosModel->where("publicado >= '" . $time_antigo->toDateTimeString() . "'");
		$artigosModel->where("escrito_colaboradores_id", $usuarioId);
		$artigosModel->orderBy("publicado", 'ASC');
		$artigos = $artigosModel->get()->getResultArray();

		$data['graficos'] = array();
		if ($artigos != NULL && !empty($artigos)) {
			$data['graficos']['base'] = array();
			for ($mes_base = 12; $mes_base >= 0; $mes_base--) {
				$data_base = new Time('-' . $mes_base . ' months');
				$data['graficos']['base'][$data_base->toLocalizedString('MMM yyyy')] = 0;
			}
			foreach ($artigos as $artigo) {
				if (isset($data['graficos']['base'][Time::createFromFormat('Y-m-d H:i:s', $artigo['publicado'])->toLocalizedString('MMM yyyy')])) {
					$data['graficos']['base'][Time::createFromFormat('Y-m-d H:i:s', $artigo['publicado'])->toLocalizedString('MMM yyyy')]++;
				}
			}
		}

		$data['estrutura_tabela_producao'] = array(
			'dados' => array(
				'cabecalho' => array(
					'titulo' => 'Meus artigos em produção',
					'sufixo' => 'producao',
					'botao' => array(
						'show' => true,
						'label' => 'Novo artigo',
						'url' => site_url('colaboradores/artigos/cadastrar'),
						'target' => '_blank'
					),
					'pesquisa' => array(
						'tipo' => 'simples',
						'campo' => 'titulo'
					)
				),
				'pesquisa' => array(
					'ajax_default' => 'tipo=emProducao',
					'url' => site_url('colaboradores/artigos/meusArtigosList')
				)
			)
		);

		$data['estrutura_tabela_publicados'] = array(
			'dados' => array(
				'cabecalho' => array(
					'titulo' => 'Meus artigos publicados',
					'sufixo' => 'publicados',
					'botao' => array(
						'show' => true,
						'label' => 'Ver página pública',
						'url' => site_url('site/escritor/' . urlencode($_SESSION['colaboradores']['nome'])),
						'target' => '_blank'
					),
					'pesquisa' => array(
						'tipo' => 'simples',
						'campo' => 'titulo'
					)
				),
				'pesquisa' => array(
					'ajax_default' => 'tipo=finalizado',
					'url' => site_url('colaboradores/artigos/meusArtigosList')
				)
			)
		);

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

			// if ($post['fase_producao'] != "" && $post['fase_producao'] != NULL) {
			// 	$artigosModel->where('fase_producao_id', $post['fase_producao']);
			// }

			if (isset($post['titulo']) && $post['titulo'] != "" && $post['titulo'] != NULL) {
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

			if (isset($post['titulo']) && $post['titulo'] != "" && $post['titulo'] != NULL) {
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

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'Ação só possível via AJAX.', true);
		}

		$artigo = $artigosModel->find($idArtigo);
		if (empty($artigo) || $artigo == null) {
			return $retorno->retorno(false, 'Artigo não encontrado.', true);
		}

		$colaborador = $this->session->get('colaboradores')['id'];
		if ($colaborador != $artigo['marcado_colaboradores_id'] && $artigo['marcado_colaboradores_id'] != NULL) {
			return $retorno->retorno(false, 'Apenas quem marcou o artigo pode revertê-lo.', true);
		}

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

	public function reverter($idArtigo)
	{
		$retorno = new \App\Libraries\RetornoPadrao();
		$artigosModel = new \App\Models\ArtigosModel();

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'Ação só possível via AJAX.', true);
		}

		$artigo = $artigosModel->find($idArtigo);
		if (empty($artigo) || $artigo == null) {
			return $retorno->retorno(false, 'Artigo não encontrado.', true);
		}

		$colaborador = $this->session->get('colaboradores')['id'];
		if ($colaborador != $artigo['marcado_colaboradores_id']) {
			return $retorno->retorno(false, 'Apenas quem marcou o artigo pode revertê-lo.', true);
		}

		$faseProducaoModel = new \App\Models\FaseProducaoModel();
		$faseProducao = $faseProducaoModel->find($artigo['fase_producao_id']);

		$artigo['fase_producao_id'] = $faseProducao['etapa_anterior'];
		if ($faseProducao['etapa_anterior'] == '1') {
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

		$retornoReverter = $this->gravarArtigos('update', $artigo, $artigo['id']);
		$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'reverteu', 'artigos', 'o artigo', $idArtigo);
		$this->artigosMarcacao->desmarcarArtigo($idArtigo);
		$this->artigosHistoricos->cadastraHistorico($idArtigo, 'reverteu', $this->session->get('colaboradores')['id']);
		if (!$retornoReverter) {
			return $retorno->retorno(false, 'Erro ao reverter artigo.', true);
		}
		return $retorno->retorno(true, 'Artigo revertido com sucesso.', true);
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
				$artigosModel->whereNotIn('id', array($artigoId));
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
			if ($colaborador != $artigo['escrito_colaboradores_id']) {
				return $retorno->retorno(false, 'Apenas o escritor pode submeter o artigo para revisão.', true);
			}
			$valida = $validaFormularios->validaFormularioArtigo($artigo, true);
			if (empty($valida->getErrors())) {
				$configuracaoModel = new \App\Models\ConfiguracaoModel();
				$config = array();
				$config['artigo_tamanho_maximo'] = (int) $configuracaoModel->find('artigo_tamanho_maximo')['config_valor'];
				$config['artigo_tamanho_minimo'] = (int) $configuracaoModel->find('artigo_tamanho_minimo')['config_valor'];
				$palavras = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $artigo['texto']));
				if ($palavras <= $config['artigo_tamanho_maximo'] && $palavras >= $config['artigo_tamanho_minimo']) {
					$isGravado = $this->artigoProximo($artigoId);
					if ($isGravado === true) {
						return $retorno->retorno(true, 'Seu artigo foi submetido para revisão com sucesso.', true);
					} else {
						return $retorno->retorno(false, 'Ocorreu um erro ao submeter para revisão.', true);
					}
				} else {
					return $retorno->retorno(false, 'O texto possui ' . number_format($palavras, 0, ',', '.') . ' palavras e deve estar entre ' . number_format($config['artigo_tamanho_minimo'], 0, ',', '.') . ' e ' . number_format($config['artigo_tamanho_maximo'], 0, ',', '.') . ' para ser aceito.', true);
				}
			} else {
				return $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), true);
			}
		}

		// DE REVISÃO PARA NARRAÇÃO
		if ($artigo['fase_producao_id'] == '2') {
			$isPermissao = $this->verificaPermissao->PermiteAcesso('3');
			if (!$isPermissao) {
				return $retorno->retorno(false, 'Você não tem permissão para enviar para narração.', true);
			}

			if ($colaborador != $artigo['marcado_colaboradores_id']) {
				return $retorno->retorno(false, 'Apenas quem marcou o artigo pode submetê-lo para narração.', true);
			}

			if ($colaborador != $artigo['revisado_colaboradores_id']) {
				return $retorno->retorno(false, 'Você não revisou o artigo. Revise e salve o artigo.', true);
			}

			$valida = $validaFormularios->validaFormularioArtigo($artigo, true);
			if (empty($valida->getErrors())) {
				$configuracaoModel = new \App\Models\ConfiguracaoModel();
				$config = array();
				$config['artigo_tamanho_maximo'] = (int) $configuracaoModel->find('artigo_tamanho_maximo')['config_valor'];
				$config['artigo_tamanho_minimo'] = (int) $configuracaoModel->find('artigo_tamanho_minimo')['config_valor'];
				$palavras = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $artigo['texto']));
				if ($palavras <= $config['artigo_tamanho_maximo'] && $palavras >= $config['artigo_tamanho_minimo']) {
					$isGravado = $this->artigoProximo($artigoId);
					if ($isGravado === true) {
						return $retorno->retorno(true, 'O artigo foi submetido para narração com sucesso.', true);
					} else {
						return $retorno->retorno(false, 'Ocorreu um erro ao submeter para narração.', true);
					}
				} else {
					return $retorno->retorno(false, 'O texto possui ' . number_format($palavras, 0, ',', '.') . ' palavras e deve estar entre ' . number_format($config['artigo_tamanho_minimo'], 0, ',', '.') . ' e ' . number_format($config['artigo_tamanho_maximo'], 0, ',', '.') . ' para ser aceito.', true);
				}
			} else {
				return $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), true);
			}
		}

		// DE NARRAÇÃO PARA PRODUÇÃO
		if ($artigo['fase_producao_id'] == '3') {
			$isPermissao = $this->verificaPermissao->PermiteAcesso('4');
			if (!$isPermissao) {
				return $retorno->retorno(false, 'Você não tem permissão para enviar para narração.', true);
			}

			if ($colaborador != $artigo['marcado_colaboradores_id']) {
				return $retorno->retorno(false, 'Apenas quem marcou o artigo pode submetê-lo para narração.', true);
			}

			if ($artigo['arquivo_audio'] === NULL) {
				return $retorno->retorno(false, 'Você não narrou o artigo. Envie o arquivo antes de submetê-lo para produção.', true);
			}

			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$isGravado = $this->artigoProximo($artigoId);
			if ($isGravado === true) {
				return $retorno->retorno(true, 'O artigo foi submetido para produção com sucesso.', true);
			} else {
				return $retorno->retorno(false, 'Ocorreu um erro ao submeter para produção.', true);
			}
		}

		// DE PRODUÇÃO PARA PUBLICAÇÃO
		if ($artigo['fase_producao_id'] == '4') {
			$isPermissao = $this->verificaPermissao->PermiteAcesso('5');
			if (!$isPermissao) {
				return $retorno->retorno(false, 'Você não tem permissão para enviar para publicação.', true);
			}

			if ($colaborador != $artigo['marcado_colaboradores_id']) {
				return $retorno->retorno(false, 'Apenas quem marcou o artigo pode submetê-lo para publicação.', true);
			}

			if ($artigo['link_produzido'] === NULL) {
				return $retorno->retorno(false, 'Você não produziu o artigo. Envie o link do vídeo antes de submetê-lo para publicação.', true);
			}

			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$isGravado = $this->artigoProximo($artigoId);
			if ($isGravado === true) {
				return $retorno->retorno(true, 'O artigo foi submetido para publicação com sucesso.', true);
			} else {
				return $retorno->retorno(false, 'Ocorreu um erro ao submeter para publicação.', true);
			}
		}

		// DE PUBLICAÇÃO PARA PAGAMENTO
		if ($artigo['fase_producao_id'] == '5') {
			$isPermissao = $this->verificaPermissao->PermiteAcesso('6');
			if (!$isPermissao) {
				return $retorno->retorno(false, 'Você não tem permissão para enviar para pagamento.', true);
			}

			if ($artigo['link_video_youtube'] === NULL) {
				return $retorno->retorno(false, 'Você não publicou o artigo. Envie o link do vídeo antes de submetê-lo para pagamento.', true);
			}

			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			$isGravado = $this->artigoProximo($artigoId);

			$this->atualizaColaboradoresConquistas($artigoId);

			if ($isGravado === true) {
				return $retorno->retorno(true, 'O artigo foi submetido para pagamento com sucesso.', true);
			} else {
				return $retorno->retorno(false, 'Ocorreu um erro ao submeter para pagamento.', true);
			}
		}
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
					H.apelido AS marcado,
					artigos.publicado AS data_publicado,
					I.nome AS nome
				')
				->join('colaboradores A', 'artigos.sugerido_colaboradores_id = A.id', 'LEFT')
				->join('colaboradores B', 'artigos.escrito_colaboradores_id = B.id', 'LEFT')
				->join('colaboradores C', 'artigos.revisado_colaboradores_id = C.id', 'LEFT')
				->join('colaboradores D', 'artigos.narrado_colaboradores_id = D.id', 'LEFT')
				->join('colaboradores E', 'artigos.produzido_colaboradores_id = E.id', 'LEFT')
				->join('colaboradores F', 'artigos.publicado_colaboradores_id = F.id', 'LEFT')
				->join('colaboradores G', 'artigos.descartado_colaboradores_id = G.id', 'LEFT')
				->join('colaboradores H', 'artigos.marcado_colaboradores_id = H.id', 'LEFT')
				->join('fase_producao I', 'artigos.fase_producao_id = I.id');
			if (!isset($get['descartado']) || $get['descartado'] == 'N') {
				$artigosModel->where('artigos.descartado', NULL);
			}

			if (!is_null($get['texto']) && !empty($get['texto']) && $get['texto'] != '') {
				$artigosModel->Like('artigos.titulo', $get['texto']);
			}

			if (!is_null($get['fase_producao']) && !empty($get['fase_producao']) && $get['fase_producao'] != '') {
				$artigosModel->whereIn('I.id', array($get['fase_producao']));
			}

			if (!is_null($get['colaborador']) && !empty($get['colaborador']) && $get['colaborador'] != '') {
				if (!is_null($get['fase_producao_colaborador']) && !empty($get['fase_producao_colaborador']) && $get['fase_producao_colaborador'] != '') {
					$artigosModel->like($get['fase_producao_colaborador'] . '.apelido', $get['colaborador']);
				}
			}

			if (isset($get['descartado']) && $get['descartado'] == 'S') {
				$artigosModel->where('artigos.descartado IS NOT NULL');
				$artigosModel->withDeleted();
			}

			$artigosModel->orderBy('data_publicado', 'DESC');

			$data['total'] = 1;
			$contador = $artigosModel;


			$artigos = $artigosModel->paginate($config['site_quantidade_listagem'], 'artigos');
			if (!empty($artigos)) {
				foreach ($artigos as $chave => $artigo) {
					$artigos[$chave]['cor'] = $this->getCorFaseProducao($artigo['fase_producao_id']);
				}
			}

			$colaborador_permissoes = $this->session->get('colaboradores')['permissoes'];
			$colaborador = $this->session->get('colaboradores')['id'];

			if (isset($get['admin']) && in_array('7', $colaborador_permissoes)) {
				$data['admin'] = true;
			}

			$data['artigosList'] = [
				'artigos' => $artigos,
				'pager' => $artigosModel->pager
			];
		}
		return view('template/templateColaboradoresArtigosDashboardList', $data);
	}

	public function artigosColaborar()
	{
		$data = array();
		$data['resumo'] = array();
		$data['resumo']['revisar'] = 0;
		$data['resumo']['narrar'] = 0;
		$data['resumo']['produzir'] = 0;
		$data['resumo']['publicar'] = 0;

		$fase_permitida = array();
		$data['primeira'] = NULL;
		if ($this->verificaPermissao->PermiteAcesso('3', null, true) === true) {
			$fase_permitida[] = '2';
		}
		if ($this->verificaPermissao->PermiteAcesso('4', null, true) === true) {
			$fase_permitida[] = '3';
		}
		if ($this->verificaPermissao->PermiteAcesso('5', null, true) === true) {
			$fase_permitida[] = '4';
		}
		if ($this->verificaPermissao->PermiteAcesso('6', null, true) === true) {
			$fase_permitida[] = '5';
		}

		if (empty($fase_permitida)) {
			return redirect()->to(base_url() . 'colaboradores/artigos/dashboard');
		}

		$data['permissoes'] = $this->session->get('colaboradores')['permissoes'];

		$data['primeira']['id'] = $fase_permitida[0];

		return view('colaboradores/colaborador_artigos_colaborar_list', $data);
	}

	public function artigosColaborarList()
	{
		$retorno = new \App\Libraries\RetornoPadrao();
		$nivel_acesso = false;

		if ($this->request->getMethod() != 'get') {
			return false;
		}
		$get = service('request')->getGet();
		if (!isset($get['fase_producao_id'])) {
			return false;
		}
		$faseProducaoId = $get['fase_producao_id'];

		switch ($faseProducaoId) {
			case '2':
				$nivel_acesso = $this->verificaPermissao->PermiteAcesso('3');
				break;
			case '3':
				$nivel_acesso = $this->verificaPermissao->PermiteAcesso('4');
				break;
			case '4':
				$nivel_acesso = $this->verificaPermissao->PermiteAcesso('5');
				break;
			case '5':
				$nivel_acesso = $this->verificaPermissao->PermiteAcesso('6');
				break;
		}

		if (!$nivel_acesso) {
			return false;
		}

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config = array();
		$config['site_quantidade_listagem'] = (int) $configuracaoModel->find('site_quantidade_listagem')['config_valor'];

		$artigosModel = new \App\Models\ArtigosModel();
		$data = array();
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
				H.apelido AS marcado,
				artigos.publicado AS data_publicado,
				I.nome AS nome
			')
			->join('colaboradores A', 'artigos.sugerido_colaboradores_id = A.id', 'LEFT')
			->join('colaboradores B', 'artigos.escrito_colaboradores_id = B.id', 'LEFT')
			->join('colaboradores C', 'artigos.revisado_colaboradores_id = C.id', 'LEFT')
			->join('colaboradores D', 'artigos.narrado_colaboradores_id = D.id', 'LEFT')
			->join('colaboradores E', 'artigos.produzido_colaboradores_id = E.id', 'LEFT')
			->join('colaboradores F', 'artigos.publicado_colaboradores_id = F.id', 'LEFT')
			->join('colaboradores G', 'artigos.descartado_colaboradores_id = G.id', 'LEFT')
			->join('colaboradores H', 'artigos.marcado_colaboradores_id = H.id', 'LEFT')
			->join('fase_producao I', 'artigos.fase_producao_id = I.id');
		$artigosModel->where('artigos.descartado', NULL);
		$artigosModel->where('B.shadowban', 'N');
		if (isset($get['texto']) && $get['texto'] != '') {
			$artigosModel->like('artigos.titulo', $get['texto']);
		}

		if (isset($get['tipo']) && $get['tipo'] != '') {
			$artigosModel->like('artigos.tipo_artigo', $get['tipo']);
		}
		$artigosModel->where('artigos.fase_producao_id', (int) $faseProducaoId);

		$artigosModel->orderBy('artigos.atualizado', 'ASC');
		$artigos = $artigosModel->paginate($config['site_quantidade_listagem'], 'artigos');

		$faseProducaoModel = new \App\Models\FaseProducaoModel();
		$data['atualizar'] = array();
		$data['atualizar']['nome'] = $faseProducaoModel->find($get['fase_producao_id'])['nome'];
		$data['fase_producao_id'] = $get['fase_producao_id'];
		$data['colaborador'] = $this->session->get('colaboradores')['id'];

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$limite_descarte = explode(' ', $configuracaoModel->find('cron_artigos_descartar_data')['config_valor']);

		if (!empty($artigos)) {
			foreach ($artigos as $chave => $artigo) {
				$artigos[$chave]['cor'] = $this->getCorFaseProducao($artigo['fase_producao_id']);
				if ($limite_descarte[1] == 'days') {
					$tempo = Time::parse($artigo['criado'], 'America/Sao_Paulo')->addDays($limite_descarte[0]);
				}
				if ($limite_descarte[1] == 'weeks') {
					$tempo = Time::parse($artigo['criado'], 'America/Sao_Paulo')->addDays($limite_descarte[0] * 7);
				}
				if ($limite_descarte[1] == 'months') {
					$tempo = Time::parse($artigo['criado'], 'America/Sao_Paulo')->addMonths($limite_descarte[0]);
				}
				if ($limite_descarte[1] == 'years') {
					$tempo = Time::parse($artigo['criado'], 'America/Sao_Paulo')->addYears($limite_descarte[0]);
				}
				$artigos[$chave]['limite'] = $tempo;
			}
		}
		$data['artigosList'] = [
			'artigos' => $artigos,
			'pager' => $artigosModel->pager
		];

		return view('template/templateColaboradoresArtigosColaborarList', $data);
	}

	public function artigosProduzindo($idFaseArtigo = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('2');
		$data[] = array();
		$data['titulo'] = 'Artigos em produção';
		$data['artigos'] = null;

		$permissoes = $this->session->get('colaboradores')['permissoes'];
		$isAdmin = false;
		if (in_array('7', $permissoes)) {
			$isAdmin = true;
		}

		$artigosModel = new \App\Models\ArtigosModel();
		$artigosModel->select('artigos.*, fase_producao.nome');
		if ($idFaseArtigo == NULL) {
			$artigosModel->whereNotIn('fase_producao_id', array(6, 7));
		} else {
			$artigosModel->where('fase_producao_id', $idFaseArtigo);
		}
		$artigos = $artigosModel->where('descartado', NULL)->join('fase_producao', 'fase_producao.id = artigos.fase_producao_id')
			->get()->getResultArray();

		if (!empty($artigos)) {
			foreach ($artigos as $chave => $artigo) {
				$artigos[$chave]['cor'] = $this->getCorFaseProducao($artigo['fase_producao_id']);
			}
		}
		$data['artigos'] = $artigos;
		$data['admin'] = $isAdmin;

		return view('template/templateColaboradoresListArtigosProduzindo', $data);
	}

	public function buscaArtigoJSON($artigoId = NULL)
	{
		$retorno = new \App\Libraries\RetornoPadrao();

		$artigosModel = new \App\Models\ArtigosModel();
		$data = array();

		if ($artigoId === NULL) {
			return $retorno->retorno(false, 'Artigo não informado.', true);
		}
		$artigo = $artigosModel->find($artigoId);
		if (empty($artigo) || $artigoId === NULL) {
			return $retorno->retorno(false, 'Artigo não encontrado.', true);
		}

		$retornar = array();
		$retornar['titulo'] = $artigo['titulo'];
		$retornar['imagem'] = $artigo['imagem'];
		$retornar['texto'] = '<p>' . str_replace("\n", "</p><p>", $artigo['texto']) . '</p>';
		$retornar['gancho'] = $artigo['gancho'];
		$retornar['referencias'] = $artigo['referencias'];
		return $retorno->retorno(true, 'Artigo encontrado.', true, $retornar);
	}

	public function marcar($idArtigo)
	{
		$retorno = new \App\Libraries\RetornoPadrao();
		if ($this->request->isAJAX()) {
			$artigosModel = new \App\Models\ArtigosModel();
			$artigosHistoricosModel = new \App\Models\ArtigosHistoricosModel();

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
			return $retorno->retorno(false, 'Artigo náo encontrado.', true);
		}
	}

	public function desmarcar($idArtigo)
	{
		$retorno = new \App\Libraries\RetornoPadrao();
		if ($this->request->isAJAX()) {
			$artigosModel = new \App\Models\ArtigosModel();

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
			return $retorno->retorno(false, 'Artigo náo encontrado.', true);
		}
	}

	public function revisar($artigoId = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('3');
		$retorno = new \App\Libraries\RetornoPadrao();
		$validaFormularios = new \App\Libraries\ValidaFormularios();

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'O método só pode ser acessado via AJAX.', true);
		}

		if (!$this->request->getMethod() == 'post') {
			return $retorno->retorno(false, 'Dados não informados.', true);
		}

		$post = $this->request->getPost();

		$artigosModel = new \App\Models\ArtigosModel();

		$artigo = $artigosModel->find($artigoId);
		$post['imagem'] = $artigo['imagem'];
		unset($artigo);

		$valida = $validaFormularios->validaFormularioArtigo($post);
		if (empty($valida->getErrors())) {
			if ($artigoId !== NULL) {
				$retornoGravado = $this->revisarArtigo($artigoId);
			}
			if ($retornoGravado != false) {
				return $retorno->retorno(true, 'Artigo revisado com sucesso.', true, array('artigoId' => $retornoGravado));
			} else {
				return $retorno->retorno(false, 'Ocorreu um erro na hora de revisar.', true);
			}
		} else {
			return $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), true);
		}
	}

	public function produzir($artigoId = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('5');
		$retorno = new \App\Libraries\RetornoPadrao();
		$validaFormularios = new \App\Libraries\ValidaFormularios();

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'O método só pode ser acessado via AJAX.', true);
		}

		if (!$this->request->getMethod() == 'post') {
			return $retorno->retorno(false, 'Dados não informados.', true);
		}

		$post = $this->request->getPost();
		$valida = $validaFormularios->validaFormularioProducao($post);
		if (empty($valida->getErrors())) {
			if ($artigoId !== NULL) {
				$enviar = array();
				$enviar['link_produzido'] = $post['video_link'];
				$enviar['link_shorts'] = $post['shorts_link'];
				if (!isset($post['admin'])) {
					$enviar['produzido_colaboradores_id'] = $this->session->get('colaboradores')['id'];
				}
				$retornoGravado = $this->gravarArtigos('update', $enviar, $artigoId);
				if (!isset($post['admin'])) {
					$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'produziu', 'artigos', 'o artigo', $artigoId);
					$this->artigosHistoricos->cadastraHistorico($artigoId, 'produziu', $this->session->get('colaboradores')['id']);
				} else {
					$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'alterou', 'artigos', 'o artigo', $artigoId);
					$this->artigosHistoricos->cadastraHistorico($artigoId, 'alterou', $this->session->get('colaboradores')['id']);
				}

			}
			if ($retornoGravado != false) {
				return $retorno->retorno(true, 'Artigo salvo com sucesso.', true);
			} else {
				return $retorno->retorno(false, 'Ocorreu um erro ao salvar o artigo.', true);
			}
		} else {
			return $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), true);
		}
	}

	public function detalhamento($artigoId = null)
	{
		$this->verificaPermissao->PermiteAcesso('1');
		if ($artigoId === null) {
			return redirect()->to(base_url() . 'colaboradores/artigos/artigosColaborar');
		}

		$data = array();

		$artigosModel = new \App\Models\ArtigosModel();
		$artigo = $artigosModel->withDeleted()->find($artigoId);

		if (empty($artigo) || $artigo == null) {
			return redirect()->to(base_url() . 'colaboradores/artigos/artigosColaborar');
		}

		$permitido = false;

		$colaborador_permissoes = $this->session->get('colaboradores')['permissoes'];
		$colaborador = $this->session->get('colaboradores')['id'];
		if (in_array('7', $colaborador_permissoes)) {
			$permitido = true;
		}

		if ($permitido === false && in_array($artigo['fase_producao_id'], array(3, 4, 5))) {
			if ($artigo['fase_producao_id'] == '3' && in_array('4', $colaborador_permissoes)) {
				$permitido = true;
			}
			if ($artigo['fase_producao_id'] == '4' && in_array('5', $colaborador_permissoes)) {
				$permitido = true;
			}
			if ($artigo['fase_producao_id'] == '5' && in_array('6', $colaborador_permissoes)) {
				$permitido = true;
			}
		}

		$data['permitido'] = $permitido;

		if (!$permitido && ($artigo['fase_producao_id'] == '6' || $artigo['fase_producao_id'] == '7')) {
			return redirect()->to(base_url() . 'site/artigo/' . $artigo['url_friendly']);
		}

		$colaboradoresModel = new \App\Models\ColaboradoresModel();

		$data['artigo'] = $artigo;
		$data['artigo']['colaboradores'] = array();
		$data['artigo']['colaboradores']['sugerido'] = ($artigo['sugerido_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($artigo['sugerido_colaboradores_id'])) : (NULL);
		$data['artigo']['colaboradores']['escrito'] = ($artigo['escrito_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($artigo['escrito_colaboradores_id'])) : (NULL);
		$data['artigo']['colaboradores']['revisado'] = ($artigo['revisado_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($artigo['revisado_colaboradores_id'])) : (NULL);
		$data['artigo']['colaboradores']['narrado'] = ($artigo['narrado_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($artigo['narrado_colaboradores_id'])) : (NULL);
		$data['artigo']['colaboradores']['produzido'] = ($artigo['produzido_colaboradores_id'] !== NULL) ? ($colaboradoresModel->find($artigo['produzido_colaboradores_id'])) : (NULL);

		$data['artigo']['texto_producao'] = $this->criaTextoDinamizado($data['artigo']);

		$data['historico'] = $this->artigosHistoricos->buscaHistorico($artigoId);

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$config['artigo_regras_narrar'] = $configuracaoModel->find('artigo_regras_narrar')['config_valor'];
		$config['artigo_regras_produzir'] = $configuracaoModel->find('artigo_regras_produzir')['config_valor'];
		$data['config'] = $config;

		return view('colaboradores/colaborador_artigos_detalhes', $data);

	}

	public function salvarAudio($artigoId = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('4');
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
			if ($artigo['fase_producao_id'] == '3' && in_array('4', $colaborador_permissoes)) {
				$permitido = true;
			}
			if ($permitido) {
				if ($this->request->getMethod() == 'post') {
					$post = $this->request->getPost();
					$validaFormularios = new \App\Libraries\ValidaFormularios();
					$imagem = $this->request->getFile('audio');
					$valida = $validaFormularios->validaFormularioArtigoNarracaoFile();
					if (empty($valida->getErrors())) {
						if ($imagem->getName() != '') {
							$nome_arquivo = $artigo['id'] . '.' . $imagem->guessExtension();
							if ($imagem->move('public/assets/audio', $nome_arquivo, true)) {
								$artigo['arquivo_audio'] = base_url('public/assets/audio/' . $nome_arquivo);
								if (!isset($post['admin'])) {
									$artigo['narrado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
								}
								$isAtualizado = $this->gravarArtigos('update', $artigo, $artigoId);
								if (!isset($post['admin'])) {
									$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'narrou', 'artigos', 'o artigo', $artigoId);
									$this->artigosHistoricos->cadastraHistorico($artigoId, 'narrou', $this->session->get('colaboradores')['id']);
								} else {
									$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'alterou', 'artigos', 'o artigo', $artigoId);
									$this->artigosHistoricos->cadastraHistorico($artigoId, 'alterou', $this->session->get('colaboradores')['id']);
								}
								if ($isAtualizado === true) {
									return $retorno->retorno(true, 'Arquivo de áudio atualizado.', true, array('audio' => $artigo['arquivo_audio']));
								} else {
									return $retorno->retorno(false, 'Ocorreu um erro ao atualizar o áudio.', true);
								}
							}
						}
					} else {
						return $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), true);
					}
				} else {
					return $retorno->retorno(false, 'Áudio não enviado.', true);
				}
			}
			return $retorno->retorno(false, 'Áudio não gravado. Você não tem permissão para enviar áudio para este artigo.', true);
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

	public function publicar($artigoId = NULL)
	{
		$this->verificaPermissao->PermiteAcesso('6');
		$retorno = new \App\Libraries\RetornoPadrao();
		$validaFormularios = new \App\Libraries\ValidaFormularios();

		if (!$this->request->isAJAX()) {
			return $retorno->retorno(false, 'O método só pode ser acessado via AJAX.', true);
		}

		if (!$this->request->getMethod() == 'post') {
			return $retorno->retorno(false, 'Dados não informados.', true);
		}

		$post = $this->request->getPost();

		$valida = $validaFormularios->validaFormularioPublicacao($post);
		if (empty($valida->getErrors())) {
			if ($artigoId !== NULL) {
				$enviar = array();
				$enviar['link_video_youtube'] = $post['link_video_youtube'];
				if (!isset($post['admin'])) {
					$enviar['publicado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
				}
				$artigosModel = new \App\Models\ArtigosModel();
				$enviar['publicado'] = $artigosModel->getNow();
				$retornoGravado = $this->gravarArtigos('update', $enviar, $artigoId);
				if (!isset($post['admin'])) {
					$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'publicou', 'artigos', 'o artigo', $artigoId);
					$this->artigosHistoricos->cadastraHistorico($artigoId, 'publicou', $this->session->get('colaboradores')['id']);
				}
			}
			if ($retornoGravado != false) {
				return $retorno->retorno(true, 'Artigo salvo com sucesso.', true);
			} else {
				return $retorno->retorno(false, 'Ocorreu um erro ao salvar o artigo.', true);
			}
		} else {
			return $retorno->retorno(false, $retorno->montaStringErro($valida->getErrors()), true);
		}
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
					return $retorno->retorno(true, 'Comentário excluído com sucesso.', true);
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
				return $retorno->retorno(true, 'Comentário inserido com sucesso.', true);
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
					return $retorno->retorno(true, 'Comentário atualizado com sucesso.', true);
				}
				return $retorno->retorno(false, 'Erro ao excluir o comentário.', true);
			}
			return $retorno->retorno(false, 'Erro ao salvar comentário', true);
		} else {
			$comentarios = $artigosComentariosModel->getComentarios($idArtigo);
			return view('template/templateComentarios', array('comentarios' => $comentarios, 'colaborador' => $this->session->get('colaboradores')['id']));
		}
	}

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
			$artigo['palavras_narrador'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $artigo['texto']));
		}
		if ($faseProducao['etapa_posterior'] == '5') {
			$artigo['produzido_colaboradores_id'] = $this->session->get('colaboradores')['id'];
			$artigo['palavras_produtor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $artigo['texto']));
		}
		if ($faseProducao['etapa_posterior'] == '6') {
			$artigo['publicado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
		}
		//$retorno = $artigosModel->save($artigo);
		$retorno = $this->gravarArtigos('update', $artigo, $artigoId);
		$this->artigosMarcacao->desmarcarArtigo($artigoId);
		return $retorno;
	}


	private function revisarArtigo($idArtigo)
	{
		if ($this->request->getMethod() == 'post') {
			$artigosModel = new \App\Models\ArtigosModel();

			$post = $this->request->getPost();

			$gravar['tipo_artigo'] = $post['tipo_artigo'];
			$gravar['titulo'] = htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8');
			$gravar['texto'] = htmlspecialchars($post['texto'], ENT_QUOTES, 'UTF-8');
			$gravar['gancho'] = htmlspecialchars($post['gancho'], ENT_QUOTES, 'UTF-8');
			$gravar['referencias'] = htmlspecialchars($post['referencias'], ENT_QUOTES, 'UTF-8');
			$gravar['palavras_revisor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $post['texto']));
			$gravar['atualizado'] = $artigosModel->getNow();
			if (!isset($post['admin'])) {
				$gravar['revisado_colaboradores_id'] = $this->session->get('colaboradores')['id'];
			}


			$artigo_anterior = $artigosModel->find($idArtigo);

			$artigo_id = $this->gravarArtigos('update', $gravar, $idArtigo);
			if (!isset($post['admin'])) {
				$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'revisou', 'artigos', 'o artigo', $idArtigo);
				$this->artigosHistoricos->cadastraHistorico($idArtigo, 'revisou', $this->session->get('colaboradores')['id']);
			} else {
				$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'alterou', 'artigos', 'o artigo', $idArtigo);
				$this->artigosHistoricos->cadastraHistorico($idArtigo, 'alterou', $this->session->get('colaboradores')['id']);
			}
			$this->cadastraHistoricoTextoArtigo($idArtigo, gera_hash_artigo($artigo_anterior));

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
			$gravar['texto'] = htmlspecialchars($post['texto'], ENT_QUOTES, 'UTF-8');
			$gravar['imagem'] = '';
			$gravar['gancho'] = htmlspecialchars($post['gancho'], ENT_QUOTES, 'UTF-8');
			$gravar['referencias'] = htmlspecialchars($post['referencias'], ENT_QUOTES, 'UTF-8');
			$gravar['escrito_colaboradores_id'] = $session['id'];
			$gravar['palavras_escritor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $post['texto']));
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
			$this->cadastraHistoricoTextoArtigo($artigo_id, gera_hash_artigo(NULL));
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
			$gravar['texto'] = htmlspecialchars($post['texto'], ENT_QUOTES, 'UTF-8');
			$gravar['gancho'] = htmlspecialchars($post['gancho'], ENT_QUOTES, 'UTF-8');
			$gravar['referencias'] = htmlspecialchars($post['referencias'], ENT_QUOTES, 'UTF-8');
			$gravar['palavras_escritor'] = str_word_count(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç|Ç)/"), explode(" ", "a A e E i I o O u U n N c"), $post['texto']));
			$gravar['atualizado'] = $artigosModel->getNow();

			$artigo_anterior = $artigosModel->find($idArtigo);

			//$artigo_id = $artigosModel->update(['id' => $idArtigo], $gravar);
			$artigo_id = $this->gravarArtigos('update', $gravar, $idArtigo);
			$this->colaboradoresNotificacoes->cadastraNotificacao($this->session->get('colaboradores')['id'], 'alterou', 'artigos', 'o artigo', $idArtigo, true);
			$this->artigosHistoricos->cadastraHistorico($idArtigo, 'alterou', $this->session->get('colaboradores')['id']);
			$this->cadastraHistoricoTextoArtigo($idArtigo, gera_hash_artigo($artigo_anterior));

			// $artigosCategoriasModel = new \App\Models\ArtigosCategoriasModel();
			// $artigosCategoriasModel->deleteArtigoCategoria($idArtigo);
			// foreach ($post['categorias'] as $categoria) {
			// 	$artigosCategoriasModel->insertArtigoCategoria($idArtigo, $categoria);
			// }
			return $artigo_id;
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
		$colaboradores .= ($artigo['colaboradores']['sugerido'] !== null) ? ('sugerido por ' . $artigo['colaboradores']['sugerido']['apelido'] . ' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['escrito'] !== null) ? ('escrito por ' . $artigo['colaboradores']['escrito']['apelido'] . ' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['revisado'] !== null) ? ('revisado por ' . $artigo['colaboradores']['revisado']['apelido'] . ' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['narrado'] !== null) ? ('narrado por ' . $artigo['colaboradores']['narrado']['apelido'] . ' ') : ('');
		$colaboradores .= ($artigo['colaboradores']['produzido'] !== null) ? ('produzido por ' . $artigo['colaboradores']['produzido']['apelido'] . ' ') : ('');

		foreach ($config['artigo_visualizacao_narracao'] as $indice => $linha) {
			$alterado = null;
			$alterado = str_replace("{gancho}", $artigo['gancho'], $linha);
			$alterado = str_replace("{texto}", $artigo['texto'], $alterado);

			$alterado = str_replace("{colaboradores}", $colaboradores, $alterado);
			$config['artigo_visualizacao_narracao'][$indice] = $alterado;
		}

		$config['artigo_visualizacao_narracao'] = '<p>' . implode('</p><p>', $config['artigo_visualizacao_narracao']) . '</p>';
		$config['artigo_visualizacao_narracao'] = str_replace("\n", "<br/>", $config['artigo_visualizacao_narracao']);
		return $config['artigo_visualizacao_narracao'];
	}

	private function atualizaColaboradoresConquistas($idArtigo)
	{
		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$artigosModel = new \App\Models\ArtigosModel();
		$conquistasModel = new \App\Models\ConquistasModel();
		$colaboradoresConquistasModel = new \App\Models\ColaboradoresConquistasModel();

		$artigo = $artigosModel->find($idArtigo);
		$colaborador = $colaboradoresModel->find($artigo['escrito_colaboradores_id']);
		$pontuacao = $colaborador['pontos_escritor'] + 1;
		$colaboradoresModel->update($colaborador['id'], array('pontos_escritor' => $pontuacao));

		$conquistas = $conquistasModel->where('tipo', 'escritor')->where('pontuacao', $pontuacao)->get()->getResultArray();
		if (!empty($conquistas) && isset($conquistas[0])) {
			$colaboradoresConquistasModel->insert(array('colaboradores_id' => $artigo['escrito_colaboradores_id'], 'conquistas_id' => $conquistas[0]['id']));
		}
	}

	private function cadastraHistoricoTextoArtigo($idArtigo, $hashArtigo)
	{
		$artigosModel = new \App\Models\ArtigosModel();
		$artigosTextoHistoricosModel = new \App\Models\ArtigosTextosHistoricosModel();
		$artigo = $artigosModel->find($idArtigo);
		$hash = gera_hash_artigo($artigo);
		if ($hash != $hashArtigo) {
			$colaborador = $this->session->get('colaboradores')['id'];
			$gravar = array();
			$gravar['artigos_id'] = $artigo['id'];
			$gravar['titulo'] = $artigo['titulo'];
			$gravar['gancho'] = $artigo['gancho'];
			$gravar['texto'] = $artigo['texto'];
			$gravar['referencias'] = $artigo['referencias'];
			$gravar['colaboradores_id'] = $colaborador;
			$gravar['id'] = $artigosTextoHistoricosModel->getNovaUUID();
			$retorno = $artigosTextoHistoricosModel->insert($gravar);
			return $retorno;
		}
		return false;
	}
}
