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

		if(!($this->session->has('site_config')) || !isset($this->session->get('colaboradores')['texto_nome']))
		{
			$configuracaoModel = new \App\Models\ConfiguracaoModel();
			
			$site_nome = (array)json_decode($configuracaoModel->find('site_nome')['config_valor']);
			$site_nome = (isset($site_nome[site_url()])&&$site_nome[site_url()]!='')?($site_nome[site_url()]):($site_nome['default']);

			$site_descricao = (array)json_decode($configuracaoModel->find('site_descricao')['config_valor']);
			$site_descricao = (isset($site_descricao[site_url()])&&$site_descricao[site_url()]!='')?($site_descricao[site_url()]):($site_descricao['default']);

			$site_youtube = (array)json_decode($configuracaoModel->find('link_youtube')['config_valor']);
			$site_youtube = (isset($site_youtube[site_url()])&&$site_youtube[site_url()]!='')?($site_youtube[site_url()]):(NULL);

			$site_instagram = (array)json_decode($configuracaoModel->find('link_instagram')['config_valor']);
			$site_instagram = (isset($site_instagram[site_url()])&&$site_instagram[site_url()]!='')?($site_instagram[site_url()]):(NULL);

			$site_twitter = (array)json_decode($configuracaoModel->find('link_twitter')['config_valor']);
			$site_twitter = (isset($site_twitter[site_url()])&&$site_twitter[site_url()]!='')?($site_twitter[site_url()]):(NULL);

			$estrutura_session = [
					'site_config' => [
						'youtube' => $site_youtube,
						'twitter' => $site_twitter,
						'instagram' => $site_instagram,
						'texto_rodape' => $site_descricao,
						'texto_nome' => $site_nome
					]
				];
			$this->session->set($estrutura_session);
		}

		if(!($this->session->has('colaboradores')))
		{
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
		if($this->session->has('colaboradores') && $this->session->get('colaboradores')['id'] !== NULL) {
			$colaboradores = $this->session->get('colaboradores');
			$colaboradoresNotificacoesModel = new \App\Models\ColaboradoresNotificacoesModel();
			$quantidadeNotificacoes = $colaboradoresNotificacoesModel->where('colaboradores_id',$colaboradores['id'])
			->where('data_visualizado',null)->countAllResults();
			if(!isset($colaboradores['notificacoes']) || $colaboradores['notificacoes']!==$quantidadeNotificacoes) {
				$colaboradores['notificacoes'] = $quantidadeNotificacoes;
				$this->session->set(array('colaboradores'=>$colaboradores));
			}
		}

		// Preload any models, libraries, etc, here.

		// E.g.: $this->session = \Config\Services::session();
		$this->session = \Config\Services::session();
	}
}
