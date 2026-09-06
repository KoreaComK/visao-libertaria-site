<?php

namespace App\Controllers;

class Cron extends BaseController
{
	/**
	 * Endpoint HTTP legado. As tarefas de manutenção migraram para scripts CLI (cron_*.php / spark).
	 */
	public function index($hash = null)
	{
		if ($hash === null) {
			return redirect()->to(base_url());
		}

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$hashBase = $configuracaoModel->find('cron_hash')['config_valor'];
		if ($hash != $hashBase) {
			return redirect()->to(base_url());
		}

		return 'Cron HTTP legado: tarefas migradas para scripts CLI (cron_*.php).';
	}

	/**
	 * Lista artigos na fase Publicando (5), não descartados: título, link_produzido e link_shorts.
	 * URL: /cron/listar-produzidos/{hash}
	 */
	public function listarProduzidos($hash = null)
	{
		if ($hash === null) {
			return redirect()->to(base_url());
		}

		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$hashBase = $configuracaoModel->find('cron_hash')['config_valor'];
		if ($hash != $hashBase) {
			return redirect()->to(base_url());
		}

		$artigos = (new \App\Models\ArtigosModel())
			->select('titulo, link_produzido, link_shorts')
			->where('fase_producao_id', 5)
			->where('descartado', null)
			->orderBy('titulo', 'ASC')
			->findAll();

		return $this->response->setJSON([
			'total' => count($artigos),
			'artigos' => $artigos,
		]);
	}
}
