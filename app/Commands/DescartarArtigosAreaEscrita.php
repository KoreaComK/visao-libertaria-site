<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\ArtigosHistoricos;
use App\Models\ArtigosModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\I18n\Time;

class DescartarArtigosAreaEscrita extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:descartar-artigos-area-escrita';

	protected $description = 'Descarta artigos parados na área de escrita (fase 1) há mais de 7 dias.';

	protected $usage = 'cron:descartar-artigos-area-escrita';

	public function run(array $params)
	{
		$limiteAtualizado = new Time('-7 days');

		$artigos = (new ArtigosModel())
			->where('fase_producao_id', 1)
			->where('atualizado <=', $limiteAtualizado->toDateTimeString())
			->where('descartado', null)
			->findAll();

		if ($artigos === []) {
			return $this->encerrar('Nenhum artigo para descartar na área de escrita.');
		}

		// Cron sem usuário logado: historico do Model exige sessão (colaborador sistema = 1).
		$session = \Config\Services::session();
		$session->start();
		$session->set('colaboradores', ['id' => 1]);

		$artigosHistoricos = new ArtigosHistoricos();
		$artigosAtualizacao = new ArtigosModel();

		foreach ($artigos as $artigo) {
			$artigosHistoricos->cadastraHistorico($artigo['id'], 'descartou', $artigo['escrito_colaboradores_id']);
			$artigosAtualizacao->update($artigo['id'], [
				'descartado' => $artigosAtualizacao->getNow(),
				'descartado_colaboradores_id' => $artigo['escrito_colaboradores_id'],
			]);
		}

		return $this->encerrar('Concluído: ' . count($artigos) . ' artigo(s) descartado(s).');
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
			$this->logger->error('cron:descartar-artigos-area-escrita: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:descartar-artigos-area-escrita: ' . $mensagem);
	}
}
