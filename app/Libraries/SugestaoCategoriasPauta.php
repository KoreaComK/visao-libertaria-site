<?php

declare(strict_types=1);

namespace App\Libraries;

use Config\InteligenciaArtificial;

/**
 * Pede à IA as categorias da pauta e devolve só IDs da lista ativa.
 * Não grava no banco.
 */
class SugestaoCategoriasPauta
{
	private InteligenciaArtificial $config;

	/** @var null|callable(string, string, array<string, string>, array<string, mixed>): array{status: int, body: string} */
	private $requisitar;

	public function __construct(
		?InteligenciaArtificial $config = null,
		?callable $requisitar = null,
	) {
		$this->config = $config ?? config('InteligenciaArtificial');
		$this->requisitar = $requisitar;
	}

	/**
	 * @param array<string, mixed> $pauta
	 * @param list<array<string, mixed>> $categoriasAtivas
	 * @return array{ok: bool, ids: list<int>, erro: string}
	 */
	public function sugerirPauta(array $pauta, array $categoriasAtivas): array
	{
		return $this->sugerir(
			(string) ($pauta['titulo'] ?? ''),
			(string) ($pauta['texto'] ?? ''),
			(string) ($pauta['link'] ?? ''),
			$categoriasAtivas
		);
	}

	/**
	 * @param list<array<string, mixed>> $categoriasAtivas
	 * @return array{ok: bool, ids: list<int>, erro: string}
	 */
	public function sugerir(string $titulo, string $texto, string $link, array $categoriasAtivas): array
	{
		if (! $this->config->temChave()) {
			return $this->falha('sem_chave');
		}

		if (! $this->config->temProvedor()) {
			return $this->falha('sem_provedor');
		}

		if (! $this->config->temModelo()) {
			return $this->falha('sem_modelo');
		}

		if ($categoriasAtivas === []) {
			return $this->falha('sem_categorias');
		}

		$provedor = $this->normalizarProvedor($this->config->provedor);
		$pedido = $this->montarPedido($provedor, $titulo, $texto, $link, $categoriasAtivas);
		if ($pedido === null) {
			return $this->falha('provedor_nao_suportado:' . $provedor);
		}

		try {
			$resposta = $this->enviar($pedido['metodo'], $pedido['url'], $pedido['headers'], $pedido['corpo']);
		} catch (\Throwable $e) {
			return $this->falha('http:' . $e->getMessage());
		}

		$status = $resposta['status'];
		if ($status < 200 || $status >= 300) {
			return $this->falha('http:status_' . $status . $this->detalheErroHttp($resposta['body']));
		}

		$textoModelo = $this->textoDaResposta($provedor, $resposta['body']);
		if ($textoModelo === null) {
			return $this->falha('resposta_vazia');
		}

		$idsBrutos = $this->idsDoJson($textoModelo);
		if ($idsBrutos === null) {
			return $this->falha('json_invalido');
		}

		return [
			'ok' => true,
			'ids' => $this->filtrarIdsValidos($idsBrutos, $categoriasAtivas),
			'erro' => '',
		];
	}

	/**
	 * Descarta ID inventado, inválido ou repetido. Só sobra o que está na lista ativa.
	 *
	 * @param list<mixed> $idsRecebidos
	 * @param list<array<string, mixed>> $categoriasAtivas
	 * @return list<int>
	 */
	public function filtrarIdsValidos(array $idsRecebidos, array $categoriasAtivas): array
	{
		$permitidos = [];
		foreach ($categoriasAtivas as $categoria) {
			$id = (int) ($categoria['id'] ?? 0);
			if ($id > 0) {
				$permitidos[$id] = true;
			}
		}

		$saida = [];
		$vistos = [];
		foreach ($idsRecebidos as $valor) {
			if (! is_numeric($valor)) {
				continue;
			}

			$id = (int) $valor;
			if ($id < 1 || ! isset($permitidos[$id]) || isset($vistos[$id])) {
				continue;
			}

			$vistos[$id] = true;
			$saida[] = $id;
		}

		return $saida;
	}

	/**
	 * @return list<mixed>|null
	 */
	public function idsDoJson(string $texto): ?array
	{
		$json = $this->extrairObjetoJson($texto);
		if ($json === null) {
			return null;
		}

		$dados = json_decode($json, true);
		if (! is_array($dados) || ! array_key_exists('ids', $dados) || ! is_array($dados['ids'])) {
			return null;
		}

		return array_values($dados['ids']);
	}

	private function normalizarProvedor(string $provedor): string
	{
		$nome = strtolower(trim($provedor));

		return match ($nome) {
			'google', 'google-ai', 'google_ai', 'gemini' => 'gemini',
			'gpt', 'chatgpt', 'openai' => 'openai',
			'claude', 'anthropic' => 'anthropic',
			default => $nome,
		};
	}

	/**
	 * @param list<array<string, mixed>> $categoriasAtivas
	 * @return array{metodo: string, url: string, headers: array<string, string>, corpo: array<string, mixed>}|null
	 */
	private function montarPedido(string $provedor, string $titulo, string $texto, string $link, array $categoriasAtivas): ?array
	{
		$instrucao = $this->instrucaoSistema();
		$usuario = $this->mensagemUsuario($titulo, $texto, $link, $categoriasAtivas);
		$modelo = $this->config->modelo;
		$chave = $this->config->apiKey;

		return match ($provedor) {
			'gemini' => [
				'metodo' => 'POST',
				'url' => 'https://generativelanguage.googleapis.com/v1beta/models/'
					. rawurlencode($this->nomeModeloGemini($modelo))
					. ':generateContent',
				'headers' => [
					'Content-Type' => 'application/json',
					'x-goog-api-key' => $chave,
				],
				'corpo' => [
					'system_instruction' => [
						'parts' => [['text' => $instrucao]],
					],
					'contents' => [
						['role' => 'user', 'parts' => [['text' => $usuario]]],
					],
					'generationConfig' => [
						'temperature' => 0.2,
						'responseMimeType' => 'application/json',
					],
				],
			],
			'openai' => [
				'metodo' => 'POST',
				'url' => 'https://api.openai.com/v1/chat/completions',
				'headers' => [
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer ' . $chave,
				],
				'corpo' => [
					'model' => $modelo,
					'temperature' => 0.2,
					'response_format' => ['type' => 'json_object'],
					'messages' => [
						['role' => 'system', 'content' => $instrucao],
						['role' => 'user', 'content' => $usuario],
					],
				],
			],
			'anthropic' => [
				'metodo' => 'POST',
				'url' => 'https://api.anthropic.com/v1/messages',
				'headers' => [
					'Content-Type' => 'application/json',
					'x-api-key' => $chave,
					'anthropic-version' => '2023-06-01',
				],
				'corpo' => [
					'model' => $modelo,
					'max_tokens' => 1024,
					'temperature' => 0.2,
					'system' => $instrucao,
					'messages' => [
						['role' => 'user', 'content' => $usuario],
					],
				],
			],
			default => null,
		};
	}

	private function nomeModeloGemini(string $modelo): string
	{
		$modelo = trim($modelo);
		if (str_starts_with($modelo, 'models/')) {
			return substr($modelo, strlen('models/'));
		}

		return $modelo;
	}

	private function instrucaoSistema(): string
	{
		return 'Você classifica pautas jornalísticas nas categorias pré-definidas. '
			. 'Use somente os IDs da lista. Não invente categoria nem ID. '
			. 'Atribua todas as categorias que se aplicarem. '
			. 'Se nenhuma se aplicar, devolva ids vazio. '
			. 'Responda apenas JSON no formato {"ids":[1,2]}.';
	}

	/**
	 * @param list<array<string, mixed>> $categoriasAtivas
	 */
	private function mensagemUsuario(string $titulo, string $texto, string $link, array $categoriasAtivas): string
	{
		$linhas = ["Categorias disponíveis:"];
		foreach ($categoriasAtivas as $categoria) {
			$id = (int) ($categoria['id'] ?? 0);
			$nome = trim((string) ($categoria['nome'] ?? ''));
			if ($id < 1 || $nome === '') {
				continue;
			}

			$linhas[] = $id . ' — ' . $nome;
		}

		$linhas[] = '';
		$linhas[] = 'Título: ' . $this->limitar($titulo, 500);
		$linhas[] = 'Texto: ' . $this->limitar($texto, 4000);
		$link = trim($link);
		if ($link !== '') {
			$linhas[] = 'Link: ' . $this->limitar($link, 2000);
		}

		return implode("\n", $linhas);
	}

	private function limitar(string $valor, int $maximo): string
	{
		$valor = trim($valor);
		if (mb_strlen($valor) <= $maximo) {
			return $valor;
		}

		return mb_substr($valor, 0, $maximo);
	}

	/**
	 * @param array<string, string> $headers
	 * @param array<string, mixed> $corpo
	 * @return array{status: int, body: string}
	 */
	private function enviar(string $metodo, string $url, array $headers, array $corpo): array
	{
		if ($this->requisitar !== null) {
			return ($this->requisitar)($metodo, $url, $headers, $corpo);
		}

		try {
			return $this->enviarHttp($metodo, $url, $headers, $corpo, true);
		} catch (\Throwable $e) {
			if (ENVIRONMENT === 'development' && $this->ehErroSsl($e)) {
				return $this->enviarHttp($metodo, $url, $headers, $corpo, false);
			}

			throw $e;
		}
	}

	/**
	 * @param array<string, string> $headers
	 * @param array<string, mixed> $corpo
	 * @return array{status: int, body: string}
	 */
	private function enviarHttp(string $metodo, string $url, array $headers, array $corpo, bool $verificarSsl): array
	{
		$client = \Config\Services::curlrequest([
			'timeout' => $this->config->timeout,
			'connect_timeout' => min(10, $this->config->timeout),
			'http_errors' => false,
			'headers' => $headers,
			'verify' => $verificarSsl,
		], null, null, false);

		$resposta = $client->request($metodo, $url, ['json' => $corpo]);

		return [
			'status' => $resposta->getStatusCode(),
			'body' => (string) $resposta->getBody(),
		];
	}

	private function ehErroSsl(\Throwable $e): bool
	{
		$mensagem = strtolower($e->getMessage());

		return str_contains($mensagem, 'ssl')
			|| str_contains($mensagem, 'certificate')
			|| str_contains($mensagem, '60 :');
	}

	private function detalheErroHttp(string $corpo): string
	{
		$dados = json_decode($corpo, true);
		$mensagem = '';
		if (is_array($dados)) {
			$mensagem = (string) ($dados['error']['message'] ?? $dados['message'] ?? '');
		}

		$mensagem = trim((string) preg_replace('/\s+/', ' ', $mensagem));
		if ($mensagem === '') {
			return '';
		}

		return ':' . mb_substr($mensagem, 0, 180);
	}

	private function textoDaResposta(string $provedor, string $corpo): ?string
	{
		$dados = json_decode($corpo, true);
		if (! is_array($dados)) {
			return null;
		}

		$texto = match ($provedor) {
			'gemini' => $this->textoGemini($dados),
			'openai' => $dados['choices'][0]['message']['content'] ?? null,
			'anthropic' => $this->textoAnthropic($dados),
			default => null,
		};

		if (! is_string($texto) || trim($texto) === '') {
			return null;
		}

		return $texto;
	}

	/**
	 * @param array<string, mixed> $dados
	 */
	private function textoGemini(array $dados): ?string
	{
		$partes = $dados['candidates'][0]['content']['parts'] ?? [];
		if (! is_array($partes)) {
			return null;
		}

		$textos = [];
		foreach ($partes as $parte) {
			if (is_array($parte) && isset($parte['text']) && is_string($parte['text'])) {
				$textos[] = $parte['text'];
			}
		}

		$junto = trim(implode("\n", $textos));

		return $junto === '' ? null : $junto;
	}

	/**
	 * @param array<string, mixed> $dados
	 */
	private function textoAnthropic(array $dados): ?string
	{
		$blocos = $dados['content'] ?? [];
		if (! is_array($blocos)) {
			return null;
		}

		foreach ($blocos as $bloco) {
			if (is_array($bloco) && ($bloco['type'] ?? '') === 'text' && isset($bloco['text']) && is_string($bloco['text'])) {
				return $bloco['text'];
			}
		}

		return null;
	}

	private function extrairObjetoJson(string $texto): ?string
	{
		$texto = trim($texto);
		if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $texto, $encontrado) === 1) {
			$texto = $encontrado[1];
		}

		$inicio = strpos($texto, '{');
		$fim = strrpos($texto, '}');
		if ($inicio === false || $fim === false || $fim < $inicio) {
			return null;
		}

		$json = substr($texto, $inicio, $fim - $inicio + 1);

		return json_decode($json, true) === null ? null : $json;
	}

	/**
	 * @return array{ok: bool, ids: list<int>, erro: string}
	 */
	private function falha(string $erro): array
	{
		return [
			'ok' => false,
			'ids' => [],
			'erro' => $erro,
		];
	}
}
