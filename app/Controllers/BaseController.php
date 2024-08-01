<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var CLIRequest|IncomingRequest
	 */
	protected $request;

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Be sure to declare properties for any property fetch you initialized.
	 * The creation of dynamic property is deprecated in PHP 8.2.
	 */
	protected $session;

	function __construct()
	{
		$this->session = \Config\Services::session();
		$this->session->start();
	}

	/**
	 * @return void
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		$this->session = \Config\Services::session();
		$this->session->start();

		if (!($this->session->has('site_config')) || !isset($this->session->get('colaboradores')['texto_nome'])) {
			$configuracaoModel = new \App\Models\ConfiguracaoModel();

			$site_nome = (array) json_decode($configuracaoModel->find('site_nome')['config_valor']);
			$site_nome = (isset($site_nome[site_url()]) && $site_nome[site_url()] != '') ? ($site_nome[site_url()]) : ($site_nome['default']);

			$site_descricao = (array) json_decode($configuracaoModel->find('site_descricao')['config_valor']);
			$site_descricao = (isset($site_descricao[site_url()]) && $site_descricao[site_url()] != '') ? ($site_descricao[site_url()]) : ($site_descricao['default']);

			$paginasEstaticasModel = new \App\Models\PaginasEstaticasModel();

			$paginas_estaticas = array();
			$paginas = $paginasEstaticasModel->where('ativo', 'A')->orderBy('localizacao', 'ASC')->get()->getResultArray();
			foreach ($paginas as $pagina) {
				if (!isset($paginas_estaticas[$pagina['localizacao']])) {
					$paginas_estaticas[$pagina['localizacao']] = array();
				}
				$pagina_estatica = array();
				$pagina_estatica['titulo'] = $pagina['titulo'];
				$pagina_estatica['link'] = $pagina['url_friendly'];
				$paginas_estaticas[$pagina['localizacao']][] = $pagina_estatica;
			}

			$estrutura_session = [
				'site_config' => [
					'texto_rodape' => $site_descricao,
					'texto_nome' => $site_nome,
					'paginas' => $paginas_estaticas
				]
			];
			$this->session->set($estrutura_session);
		}

		if (!($this->session->has('colaboradores'))) {
			$estrutura_session = [
				'colaboradores' => [
					'id' => null,
					'nome' => null,
					'email' => null,
					'avatar' => null,
					'notificacoes' => 0,
					'permissoes' => array()
				]
			];
			$this->session->set($estrutura_session);
		}

		$this->session = \Config\Services::session();
		$this->session->start();
		if ($this->session->has('colaboradores') && $this->session->get('colaboradores')['id'] !== NULL) {
			$colaboradores = $this->session->get('colaboradores');
			$colaboradoresNotificacoesModel = new \App\Models\ColaboradoresNotificacoesModel();
			$quantidadeNotificacoes = $colaboradoresNotificacoesModel->where('colaboradores_id', $colaboradores['id'])
				->where('data_visualizado', null)->countAllResults();
			if (!isset($colaboradores['notificacoes']) || $colaboradores['notificacoes'] !== $quantidadeNotificacoes) {
				$colaboradores['notificacoes'] = $quantidadeNotificacoes;
				$this->session->set(array('colaboradores' => $colaboradores));
			}
		}

		// Preload any models, libraries, etc, here.

		// E.g.: $this->session = \Config\Services::session();
		$this->session = \Config\Services::session();
	}
}
