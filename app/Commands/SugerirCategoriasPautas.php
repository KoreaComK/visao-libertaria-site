<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\SugestaoCategoriasPauta;
use App\Models\CategoriasModel;
use App\Models\PautasCategoriasModel;
use App\Models\PautasModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\InteligenciaArtificial;

class SugerirCategoriasPautas extends BaseCommand
{
	protected $group = 'Pautas';

	protected $name = 'sugerir:categorias-pautas';

	protected $description = 'Classifica pautas sem categoria via IA (somente na origem).';

	protected $usage = 'sugerir:categorias-pautas';

	public function run(array $params)
	{
		helper('pauta_imagem');

		if (! sou_origem_imagens_pauta()) {
			return $this->encerrar('Servidor não é origem. Nada a fazer.');
		}

		/** @var InteligenciaArtificial $config */
		$config = config('InteligenciaArtificial');

		if (! $config->temChave()) {
			return $this->encerrar('IA_API_KEY vazia. Nada a fazer.', 'error');
		}

		if (! $config->temProvedor()) {
			return $this->encerrar('IA_PROVEDOR vazio. Nada a fazer.', 'error');
		}

		if (! $config->temModelo()) {
			return $this->encerrar('IA_MODELO vazio. Nada a fazer.', 'error');
		}

		$categorias = (new CategoriasModel())->listarAtivasIdNome();
		if ($categorias === []) {
			return $this->encerrar('Nenhuma categoria ativa. Nada a fazer.');
		}

		$pautas = (new PautasModel())->getPautasSemCategoria($config->lote);
		if ($pautas === []) {
			return $this->encerrar('Nenhuma pauta sem categoria.');
		}

		$ia = new SugestaoCategoriasPauta($config);
		$vinculos = new PautasCategoriasModel();
		$ok = 0;
		$falhas = 0;
		$avisos = 0;

		foreach ($pautas as $pauta) {
			$id = (string) ($pauta['id'] ?? '');
			if ($id === '') {
				$falhas++;
				$this->registrar('Pauta sem id no lote.', 'error');
				continue;
			}

			try {
				$resultado = $ia->sugerirPauta($pauta, $categorias);
				if (! $resultado['ok']) {
					$nivel = $this->nivelFalhaIa($resultado['erro']);
					if ($nivel === 'warning') {
						$avisos++;
					} else {
						$falhas++;
					}
					$this->registrar('Falha na pauta ' . $id . ': ' . $resultado['erro'], $nivel);
					continue;
				}

				foreach ($resultado['ids'] as $categoriaId) {
					$vinculos->insertPautaCategoriaIgnorarDuplicata($id, (int) $categoriaId);
				}

				$ok++;
				$lista = $resultado['ids'] === []
					? 'nenhuma (permanece órfã)'
					: implode(',', $resultado['ids']);
				CLI::write($id . ' → ' . $lista);
			} catch (\Throwable $e) {
				$nivel = $this->nivelFalhaIa($e->getMessage());
				if ($nivel === 'warning') {
					$avisos++;
				} else {
					$falhas++;
				}
				$this->registrar('Falha na pauta ' . $id . ': ' . $e->getMessage(), $nivel);
			}
		}

		return $this->encerrar(
			'Concluído: ' . $ok . ' ok, ' . $avisos . ' aviso(s), ' . $falhas . ' falha(s).'
		);
	}

	/**
	 * Timeout e alta demanda do modelo são transitórios: warning (não dispara e-mail de erro).
	 */
	private function nivelFalhaIa(string $mensagem): string
	{
		$texto = strtolower($mensagem);

		$timeout = str_contains($texto, 'timeout')
			|| str_contains($texto, 'timed out')
			|| str_contains($texto, 'curl error 28')
			|| str_contains($texto, 'operation timed out');

		$altaDemanda = str_contains($texto, 'high demand')
			|| str_contains($texto, 'overloaded')
			|| str_contains($texto, 'resource exhausted')
			|| str_contains($texto, 'resource_exhausted')
			|| str_contains($texto, 'unavailable')
			|| str_contains($texto, 'status_429')
			|| str_contains($texto, 'status_503')
			|| str_contains($texto, 'rate limit')
			|| str_contains($texto, 'rate_limit')
			|| str_contains($texto, 'too many requests');

		return ($timeout || $altaDemanda) ? 'warning' : 'error';
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
			$this->logger->error('sugerir:categorias-pautas: ' . $mensagem);
			return;
		}

		if ($nivel === 'warning') {
			CLI::write($mensagem, 'yellow');
			$this->logger->warning('sugerir:categorias-pautas: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('sugerir:categorias-pautas: ' . $mensagem);
	}
}
