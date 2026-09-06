<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\ArtigosHistoricos;
use App\Models\ArtigosModel;
use App\Models\ConfiguracaoModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\I18n\Time;

class DesmarcarArtigosExpirados extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:desmarcar-artigos-expirados';

	protected $description = 'Desmarca artigos com marcação expirada (teoria e notícia × revisão, narração, produção).';

	protected $usage = 'cron:desmarcar-artigos-expirados';

	public function run(array $params)
	{
		$configuracaoModel = new ConfiguracaoModel();
		if ($configuracaoModel->find('cron_artigos_desmarcar_status')['config_valor'] != '1') {
			return $this->encerrar('Desmarcação de artigos desativada na configuração. Nada a fazer.');
		}

		$artigosHistoricos = new ArtigosHistoricos();
		$regras = [
			['cron_artigos_teoria_desmarcar_data_revisao', 2, 'T'],
			['cron_artigos_teoria_desmarcar_data_narracao', 3, 'T'],
			['cron_artigos_teoria_desmarcar_data_producao', 4, 'T'],
			['cron_artigos_noticia_desmarcar_data_revisao', 2, 'N'],
			['cron_artigos_noticia_desmarcar_data_narracao', 3, 'N'],
			['cron_artigos_noticia_desmarcar_data_producao', 4, 'N'],
		];

		$total = 0;
		foreach ($regras as [$configChave, $faseProducaoId, $tipoArtigo]) {
			$total += $this->desmarcarArtigosPorPrazo(
				$configuracaoModel,
				$artigosHistoricos,
				$configChave,
				$faseProducaoId,
				$tipoArtigo
			);
		}

		return $this->encerrar('Concluído: ' . $total . ' artigo(s) desmarcado(s).');
	}

	private function desmarcarArtigosPorPrazo(
		ConfiguracaoModel $configuracaoModel,
		ArtigosHistoricos $artigosHistoricos,
		string $configChave,
		int $faseProducaoId,
		string $tipoArtigo
	): int {
		$prazo = $configuracaoModel->find($configChave)['config_valor'];
		$limiteMarcado = new Time('-' . $prazo);

		$artigos = (new ArtigosModel())
			->where('marcado <=', $limiteMarcado->toDateTimeString())
			->where('fase_producao_id', $faseProducaoId)
			->where('tipo_artigo', $tipoArtigo)
			->findAll();

		if ($artigos === []) {
			return 0;
		}

		$artigosAtualizacao = new ArtigosModel();
		foreach ($artigos as $artigo) {
			$artigosHistoricos->cadastraHistorico($artigo['id'], 'desmarcou', $artigo['marcado_colaboradores_id']);
			$artigosAtualizacao->update($artigo['id'], [
				'marcado' => null,
				'marcado_colaboradores_id' => null,
			]);
		}

		return count($artigos);
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
			$this->logger->error('cron:desmarcar-artigos-expirados: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:desmarcar-artigos-expirados: ' . $mensagem);
	}
}
