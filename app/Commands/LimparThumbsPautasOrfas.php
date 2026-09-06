<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\CacheImagemPauta;
use App\Models\PautasModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class LimparThumbsPautasOrfas extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:limpar-thumbs-pautas-orfas';

	protected $description = 'Na origem, remove thumbs de pautas inexistentes ou já excluídas.';

	protected $usage = 'cron:limpar-thumbs-pautas-orfas';

	public function run(array $params)
	{
		helper('pauta_imagem');

		if (! sou_origem_imagens_pauta()) {
			return $this->encerrar('Servidor não é origem. Nada a fazer.');
		}

		$cache = new CacheImagemPauta();
		$diretorio = $cache->diretorioAbsoluto();
		if (! is_dir($diretorio)) {
			return $this->encerrar('Diretório de thumbs inexistente. Nada a fazer.');
		}

		$pautasModel = new PautasModel();
		$removidos = 0;

		foreach (['webp', 'jpg'] as $ext) {
			foreach (glob($diretorio . DIRECTORY_SEPARATOR . '*.' . $ext) ?: [] as $arquivo) {
				$id = strtolower((string) pathinfo($arquivo, PATHINFO_FILENAME));
				if (! pauta_imagem_id_valido($id)) {
					continue;
				}

				$pauta = $pautasModel->withDeleted()->find($id);
				if (! is_array($pauta) || ! empty($pauta['excluido'])) {
					$cache->removerArquivo($id);
					$removidos++;
				}
			}
		}

		return $this->encerrar('Concluído: ' . $removidos . ' thumb(s) órfã(s) removida(s).');
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
			$this->logger->error('cron:limpar-thumbs-pautas-orfas: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:limpar-thumbs-pautas-orfas: ' . $mensagem);
	}
}
