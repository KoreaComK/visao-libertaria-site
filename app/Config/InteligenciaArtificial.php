<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * API de inteligência artificial.
 * Provedor e modelo vêm só do .env — sem lista fixa no código.
 */
class InteligenciaArtificial extends BaseConfig
{
	public const LOTE_PADRAO = 5;
	public const TIMEOUT_PADRAO = 25;

	/**
	 * Chave da API. Vazio = o comando não chama a API.
	 */
	public string $apiKey;

	/**
	 * Provedor (ex.: gemini, openai, anthropic). Livre; sem lista no código.
	 */
	public string $provedor;

	/**
	 * Identificador do modelo na API escolhida. Livre; sem lista no código.
	 */
	public string $modelo;

	/**
	 * Quantas pautas órfãs processar por execução.
	 */
	public int $lote;

	/**
	 * Timeout HTTP em segundos.
	 */
	public int $timeout;

	public function __construct()
	{
		parent::__construct();

		$this->apiKey = trim((string) (env('IA_API_KEY', '') ?? ''));
		$this->provedor = trim((string) (env('IA_PROVEDOR', '') ?? ''));
		$this->modelo = trim((string) (env('IA_MODELO', '') ?? ''));
		$this->lote = $this->inteiroDoEnv('IA_LOTE', self::LOTE_PADRAO, 1, 100);
		$this->timeout = $this->inteiroDoEnv('IA_TIMEOUT', self::TIMEOUT_PADRAO, 5, 120);
	}

	public function temChave(): bool
	{
		return $this->apiKey !== '';
	}

	public function temModelo(): bool
	{
		return $this->modelo !== '';
	}

	public function temProvedor(): bool
	{
		return $this->provedor !== '';
	}

	private function inteiroDoEnv(string $chave, int $padrao, int $minimo, int $maximo): int
	{
		$valor = env($chave, $padrao);
		if (! is_numeric($valor)) {
			return $padrao;
		}

		$inteiro = (int) $valor;
		if ($inteiro < $minimo || $inteiro > $maximo) {
			return $padrao;
		}

		return $inteiro;
	}
}
