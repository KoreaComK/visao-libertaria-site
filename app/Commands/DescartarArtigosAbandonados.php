<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\ArtigosModel;
use App\Models\ConfiguracaoModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\I18n\Time;

class DescartarArtigosAbandonados extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:descartar-artigos-abandonados';

	protected $description = 'Descarta artigos abandonados nas fases 1–4 conforme prazo configurado.';

	protected $usage = 'cron:descartar-artigos-abandonados';

	public function run(array $params)
	{
		$configuracaoModel = new ConfiguracaoModel();
		if ($configuracaoModel->find('cron_artigos_descartar_status')['config_valor'] != '1') {
			return $this->encerrar('Descarte de artigos abandonados desativado na configuração. Nada a fazer.');
		}

		$prazo = $configuracaoModel->find('cron_artigos_descartar_data')['config_valor'];
		$limiteCriacao = new Time('-' . $prazo);

		$artigos = (new ArtigosModel())
			->where('criado <=', $limiteCriacao->toDateTimeString())
			->where('descartado', null)
			->whereIn('fase_producao_id', ['1', '2', '3', '4'])
			->findAll();

		if ($artigos === []) {
			return $this->encerrar('Nenhum artigo abandonado para descartar.');
		}

		// Cron sem usuário logado: historico do Model exige sessão (colaborador sistema = 1).
		$session = \Config\Services::session();
		$session->start();
		$session->set('colaboradores', ['id' => 1]);

		$artigosExclusao = new ArtigosModel();
		foreach ($artigos as $artigo) {
			$artigosExclusao->update($artigo['id'], ['descartado_colaboradores_id' => 1]);
			$artigosExclusao->delete($artigo['id']);
		}

		return $this->encerrar('Concluído: ' . count($artigos) . ' artigo(s) abandonado(s) descartado(s).');
	}

	private function encerrar(string $mensagem, string $nivel = 'info'): int
	{
		$this->registrar($mensagem, $nivel);

		return EXIT_SUCCESS;
	}

	private function registrar(string $mensagem, string $nivel = 'info'): void
	{
		if ($nivel === 'error') {
			CLI::error($mensagem);
			$this->logger->error('cron:descartar-artigos-abandonados: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:descartar-artigos-abandonados: ' . $mensagem);
	}
}
