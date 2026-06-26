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
	protected const NOTIFICACOES_CACHE_TTL = 60;
	protected const HOME_CACHE_TTL = 300;

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

		$versaoSiteConfig = $this->obterVersaoSiteConfig();
		$versaoSessao = $this->session->get('site_config_version');

		$deveRecarregarSiteConfig = ! $this->siteConfigCacheHabilitado()
			|| ! $this->session->has('site_config')
			|| $versaoSessao !== $versaoSiteConfig;

		if ($deveRecarregarSiteConfig) {
			$configuracaoModel = new \App\Models\ConfiguracaoModel();

			$site_nome = json_decode($configuracaoModel->find('site_nome')['config_valor'], true);
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
					'paginas' => $paginas_estaticas,
					'pauta_tamanho_minimo' => $configuracaoModel->find('pauta_tamanho_minimo')['config_valor'],
					'pauta_tamanho_maximo' => $configuracaoModel->find('pauta_tamanho_maximo')['config_valor']
				],
				'site_config_version' => $versaoSiteConfig,
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
					'notificacoes_cache_em' => 0,
					'permissoes' => array()
				]
			];
			$this->session->set($estrutura_session);
		}

		$this->session = \Config\Services::session();
		$this->session->start();
		$this->atualizarContagemNotificacoesSeNecessario();

		// Preload any models, libraries, etc, here.

		// E.g.: $this->session = \Config\Services::session();
		$this->session = \Config\Services::session();
	}

	protected function siteConfigCacheHabilitado(): bool
	{
		return getenv('CI_ENVIRONMENT') === 'production';
	}

	protected function homeCacheHabilitado(): bool
	{
		return $this->siteConfigCacheHabilitado();
	}

	protected function usuarioAnonimo(): bool
	{
		if (! $this->session->has('colaboradores')) {
			return true;
		}

		return $this->session->get('colaboradores')['id'] === null;
	}

	protected function chaveCacheHomeAnonima(): string
	{
		return 'home_anon_' . $this->obterVersaoSiteConfig() . '_' . $this->obterVersaoCacheHome();
	}

	protected function obterVersaoCacheHome(): string
	{
		$arquivo = WRITEPATH . 'cache/home_version.txt';

		if (! is_file($arquivo)) {
			return '0';
		}

		$versao = trim((string) file_get_contents($arquivo));

		return $versao !== '' ? $versao : '0';
	}

	protected function invalidarCacheHome(): void
	{
		if (! $this->homeCacheHabilitado()) {
			return;
		}

		$arquivo = WRITEPATH . 'cache/home_version.txt';
		$diretorio = dirname($arquivo);

		if (! is_dir($diretorio)) {
			mkdir($diretorio, 0775, true);
		}

		$cache = \Config\Services::cache();
		$chaveAntiga = $this->chaveCacheHomeAnonima();

		file_put_contents($arquivo, (string) time());
		$cache->delete($chaveAntiga);
	}

	protected function obterVersaoSiteConfig(): string
	{
		$arquivo = WRITEPATH . 'cache/site_config_version.txt';

		if (! is_file($arquivo)) {
			return '0';
		}

		$versao = trim((string) file_get_contents($arquivo));

		return $versao !== '' ? $versao : '0';
	}

	protected function invalidarSiteConfig(): void
	{
		if ($this->siteConfigCacheHabilitado()) {
			$arquivo = WRITEPATH . 'cache/site_config_version.txt';
			$diretorio = dirname($arquivo);

			if (! is_dir($diretorio)) {
				mkdir($diretorio, 0775, true);
			}

			file_put_contents($arquivo, (string) time());
		}

		$this->session->remove('site_config');
		$this->session->remove('site_config_version');
	}

	protected function atualizarContagemNotificacoesSeNecessario(): void
	{
		if (! $this->session->has('colaboradores')) {
			return;
		}

		$colaboradores = $this->session->get('colaboradores');
		if ($colaboradores['id'] === null) {
			return;
		}

		$cacheEm = (int) ($colaboradores['notificacoes_cache_em'] ?? 0);
		if ($cacheEm > 0 && (time() - $cacheEm) < self::NOTIFICACOES_CACHE_TTL) {
			return;
		}

		$colaboradoresNotificacoesModel = new \App\Models\ColaboradoresNotificacoesModel();
		$quantidadeNotificacoes = $colaboradoresNotificacoesModel
			->where('colaboradores_id', $colaboradores['id'])
			->where('data_visualizado', null)
			->countAllResults();

		$colaboradores['notificacoes'] = $quantidadeNotificacoes;
		$colaboradores['notificacoes_cache_em'] = time();
		$this->session->set(['colaboradores' => $colaboradores]);
	}

	protected function invalidarCacheNotificacoes(): void
	{
		if (! $this->session->has('colaboradores')) {
			return;
		}

		$colaboradores = $this->session->get('colaboradores');
		if ($colaboradores['id'] === null) {
			return;
		}

		unset($colaboradores['notificacoes_cache_em']);
		$this->session->set(['colaboradores' => $colaboradores]);
	}
}
