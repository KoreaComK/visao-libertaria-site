<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\CacheImagemPauta;
use App\Models\PautasModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CachearImagensPautas extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:cachear-imagens-pautas';

	protected $description = 'Na origem, gera thumbs das pautas mais recentes da listagem pública.';

	protected $usage = 'cron:cachear-imagens-pautas';

	public function run(array $params)
	{
		helper('pauta_imagem');

		if (! sou_origem_imagens_pauta()) {
			return $this->encerrar('Servidor não é origem. Nada a fazer.');
		}

		$cache = new CacheImagemPauta();
		$pautasModel = new PautasModel();
		$pautasModel->getPautas(false, false, false);
		$pautas = $pautasModel->limit(60)->findAll();

		$gerados = 0;
		foreach ($pautas as $pauta) {
			$id = (string) ($pauta['id'] ?? '');
			if ($cache->caminhoExistente($id) !== null) {
				continue;
			}

			$cache->garantirParaUrl($id, (string) ($pauta['imagem'] ?? ''));
			$gerados++;
			if ($gerados >= 15) {
				break;
			}
		}

		return $this->encerrar('Concluído: ' . $gerados . ' thumb(s) gerada(s).');
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
			$this->logger->error('cron:cachear-imagens-pautas: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:cachear-imagens-pautas: ' . $mensagem);
	}
}
