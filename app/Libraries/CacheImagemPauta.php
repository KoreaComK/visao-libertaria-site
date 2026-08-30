<?php

declare(strict_types=1);

namespace App\Libraries;

use App\Models\PautasModel;

/**
 * Baixa a og:image da pauta, gera um thumb e grava só no disco deste servidor
 * quando ele for a origem (pautas_imagens_base_url).
 */
class CacheImagemPauta
{
	public const DIR_RELATIVO = 'public/assets/pautas';
	public const LARGURA_MAX = 480;
	public const QUALIDADE_WEBP = 78;
	public const QUALIDADE_JPEG = 82;
	public const MAX_BYTES = 5242880;
	public const TIMEOUT = 10;
	public const MAX_REDIRECTS = 3;

	private ?string $ultimoErroDownload = null;

	public function __construct(
		private ?ExtratorMetadadosLink $extrator = null,
		private ?string $diretorioDestino = null,
		private ?string $caminhoDefault = null,
		private ?PautasModel $pautasModel = null,
	) {
		$this->extrator ??= new ExtratorMetadadosLink();
	}

	public function ultimoErroDownload(): ?string
	{
		return $this->ultimoErroDownload;
	}

	public function diretorioAbsoluto(): string
	{
		if ($this->diretorioDestino !== null && $this->diretorioDestino !== '') {
			return rtrim($this->diretorioDestino, '/\\');
		}

		return rtrim(ROOTPATH, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, self::DIR_RELATIVO);
	}

	public function caminhoDefault(): string
	{
		if ($this->caminhoDefault !== null && $this->caminhoDefault !== '') {
			return $this->caminhoDefault;
		}

		return rtrim(ROOTPATH, '/\\') . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'imagem-default.png';
	}

	public function caminhoAbsoluto(string $pautaId, string $extensao): string
	{
		return $this->diretorioAbsoluto() . DIRECTORY_SEPARATOR . strtolower($pautaId) . '.' . $extensao;
	}

	public function caminhoExistente(string $pautaId): ?string
	{
		if (! pauta_imagem_id_valido($pautaId)) {
			return null;
		}

		$id = strtolower(trim($pautaId));
		foreach (['webp', 'jpg'] as $ext) {
			$caminho = $this->caminhoAbsoluto($id, $ext);
			if (is_file($caminho) && filesize($caminho) > 0) {
				return $caminho;
			}
		}

		return null;
	}

	/**
	 * Apaga o thumb local desta pauta (webp/jpg), se existir neste disco.
	 */
	public function removerArquivo(string $pautaId): bool
	{
		helper('pauta_imagem');
		if (! pauta_imagem_id_valido($pautaId)) {
			return false;
		}

		$id = strtolower(trim($pautaId));
		$apagou = false;
		foreach (['webp', 'jpg'] as $ext) {
			$caminho = $this->caminhoAbsoluto($id, $ext);
			if (is_file($caminho) && @unlink($caminho)) {
				$apagou = true;
			}
		}

		return $apagou;
	}

	/**
	 * Busca a pauta no banco e gera o arquivo. Não aceita URL solta — só a coluna imagem.
	 *
	 * @return array{ok: bool, eh_origem: bool, gerado: bool, ja_existia: bool, caminho: string, motivo: string}
	 */
	public function garantir(string $pautaId, bool $forcar = false): array
	{
		helper('pauta_imagem');

		$ehOrigem = sou_origem_imagens_pauta();
		$padrao = $this->caminhoDefault();

		if (! pauta_imagem_id_valido($pautaId)) {
			return $this->resultado(false, $ehOrigem, false, false, $padrao, 'id_invalido');
		}

		if (! $ehOrigem) {
			return $this->resultado(false, false, false, false, $padrao, 'nao_origem');
		}

		$id = strtolower(trim($pautaId));
		$existente = $this->caminhoExistente($id);
		if ($existente !== null && ! $forcar) {
			return $this->resultado(true, true, false, true, $existente, 'ja_existe');
		}

		$this->pautasModel ??= new PautasModel();
		$pauta = $this->pautasModel->find($id);
		if (! is_array($pauta)) {
			return $this->resultado(false, true, false, false, $padrao, 'pauta_nao_encontrada');
		}

		return $this->garantirParaUrl($id, (string) ($pauta['imagem'] ?? ''), $forcar);
	}

	/**
	 * Gera o thumb a partir de uma URL já conhecida (uso interno e testes).
	 *
	 * @return array{ok: bool, eh_origem: bool, gerado: bool, ja_existia: bool, caminho: string, motivo: string}
	 */
	public function garantirParaUrl(string $pautaId, string $urlImagem, bool $forcar = false): array
	{
		helper('pauta_imagem');

		$padrao = $this->caminhoDefault();
		if (! pauta_imagem_id_valido($pautaId)) {
			return $this->resultado(false, true, false, false, $padrao, 'id_invalido');
		}

		$id = strtolower(trim($pautaId));
		$existente = $this->caminhoExistente($id);
		if ($existente !== null && ! $forcar) {
			return $this->resultado(true, true, false, true, $existente, 'ja_existe');
		}

		if ($this->ehImagemPadrao($urlImagem) || trim($urlImagem) === '') {
			return $this->resultado(true, true, false, false, $padrao, 'imagem_padrao');
		}

		if ($this->ehUrlDaPropriaThumb($urlImagem)) {
			return $this->resultado(true, true, false, false, $existente ?? $padrao, 'url_circular');
		}

		$binario = $this->baixarImagem($urlImagem);
		if ($binario === null) {
			return $this->resultado(true, true, false, false, $padrao, 'download_falhou');
		}

		$gerado = $this->gerarDeBinario($id, $binario);
		if (! $gerado['ok']) {
			return $this->resultado(true, true, false, false, $padrao, 'processamento_falhou');
		}

		return $this->resultado(true, true, true, false, $gerado['caminho'], 'ok');
	}

	/**
	 * Redimensiona o binário e grava WebP (ou JPEG se GD não tiver WebP).
	 *
	 * @return array{ok: bool, caminho: string}
	 */
	public function gerarDeBinario(string $pautaId, string $binario): array
	{
		helper('pauta_imagem');

		$padrao = $this->caminhoDefault();
		if (! pauta_imagem_id_valido($pautaId) || $binario === '') {
			return ['ok' => false, 'caminho' => $padrao];
		}

		$origem = @imagecreatefromstring($binario);
		if ($origem === false) {
			return ['ok' => false, 'caminho' => $padrao];
		}

		$largura = imagesx($origem);
		$altura = imagesy($origem);
		if ($largura < 1 || $altura < 1) {
			imagedestroy($origem);

			return ['ok' => false, 'caminho' => $padrao];
		}

		if ($largura > self::LARGURA_MAX) {
			$novaLargura = self::LARGURA_MAX;
			$novaAltura = max(1, (int) round($altura * (self::LARGURA_MAX / $largura)));
			$destino = imagecreatetruecolor($novaLargura, $novaAltura);
			if ($destino === false) {
				imagedestroy($origem);

				return ['ok' => false, 'caminho' => $padrao];
			}
			imagealphablending($destino, false);
			imagesavealpha($destino, true);
			$transparente = imagecolorallocatealpha($destino, 0, 0, 0, 127);
			if ($transparente !== false) {
				imagefilledrectangle($destino, 0, 0, $novaLargura, $novaAltura, $transparente);
			}
			imagecopyresampled($destino, $origem, 0, 0, 0, 0, $novaLargura, $novaAltura, $largura, $altura);
			imagedestroy($origem);
			$origem = $destino;
		} else {
			imagealphablending($origem, false);
			imagesavealpha($origem, true);
		}

		if (! $this->garantirDiretorio()) {
			imagedestroy($origem);

			return ['ok' => false, 'caminho' => $padrao];
		}

		$id = strtolower(trim($pautaId));
		$usarWebp = function_exists('imagewebp');
		$ext = $usarWebp ? 'webp' : 'jpg';
		$caminho = $this->caminhoAbsoluto($id, $ext);

		if ($usarWebp) {
			$ok = imagewebp($origem, $caminho, self::QUALIDADE_WEBP);
		} else {
			$fundo = imagecreatetruecolor(imagesx($origem), imagesy($origem));
			if ($fundo === false) {
				imagedestroy($origem);

				return ['ok' => false, 'caminho' => $padrao];
			}
			$branco = imagecolorallocate($fundo, 255, 255, 255);
			if ($branco !== false) {
				imagefilledrectangle($fundo, 0, 0, imagesx($origem), imagesy($origem), $branco);
			}
			imagecopy($fundo, $origem, 0, 0, 0, 0, imagesx($origem), imagesy($origem));
			$ok = imagejpeg($fundo, $caminho, self::QUALIDADE_JPEG);
			imagedestroy($fundo);
		}

		imagedestroy($origem);

		if (! $ok || ! is_file($caminho) || filesize($caminho) < 1) {
			return ['ok' => false, 'caminho' => $padrao];
		}

		return ['ok' => true, 'caminho' => $caminho];
	}

	private function baixarImagem(string $url): ?string
	{
		$this->ultimoErroDownload = null;
		$urlAtual = html_entity_decode(trim($url), ENT_QUOTES | ENT_HTML5, 'UTF-8');

		for ($redirect = 0; $redirect <= self::MAX_REDIRECTS; $redirect++) {
			$urlValidada = $this->extrator->validarUrl($urlAtual);
			if ($urlValidada === null) {
				$this->ultimoErroDownload = 'url_rejeitada';

				return null;
			}

			$resposta = $this->requisicaoCurl($urlValidada);
			if ($resposta === null) {
				return null;
			}

			[$status, $body, $location, $contentType] = $resposta;

			if ($status >= 300 && $status < 400) {
				if ($location === '') {
					$this->ultimoErroDownload = 'redirect_sem_location';

					return null;
				}
				$urlAtual = $this->extrator->resolverUrlRelativa($location, $urlValidada);
				continue;
			}

			if ($status < 200 || $status >= 400) {
				$this->ultimoErroDownload = 'http_' . $status;

				return null;
			}

			if ($body === '') {
				$this->ultimoErroDownload = 'corpo_vazio';

				return null;
			}

			if (strlen($body) > self::MAX_BYTES) {
				$this->ultimoErroDownload = 'arquivo_grande';

				return null;
			}

			if ($contentType !== '' && ! str_starts_with($contentType, 'image/')) {
				if (! $this->binarioPareceImagem($body)) {
					$this->ultimoErroDownload = 'nao_e_imagem';

					return null;
				}
			}

			return $body;
		}

		$this->ultimoErroDownload = 'muitos_redirects';

		return null;
	}

	/**
	 * @return array{0: int, 1: string, 2: string, 3: string}|null
	 */
	private function requisicaoCurl(string $url): ?array
	{
		$resposta = $this->executarCurl($url, true);
		if ($resposta === null && ENVIRONMENT === 'development') {
			$resposta = $this->executarCurl($url, false);
		}

		return $resposta;
	}

	/**
	 * @return array{0: int, 1: string, 2: string, 3: string}|null
	 */
	private function executarCurl(string $url, bool $verificarSsl): ?array
	{
		$ch = curl_init($url);
		if ($ch === false) {
			$this->ultimoErroDownload = 'curl_init';

			return null;
		}

		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_TIMEOUT => self::TIMEOUT,
			CURLOPT_CONNECTTIMEOUT => self::TIMEOUT,
			CURLOPT_USERAGENT => 'VisaoLibertariaBot/1.0',
			CURLOPT_HTTPHEADER => [
				'Accept: image/webp,image/avif,image/jpeg,image/png,image/gif,*/*;q=0.8',
			],
			CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
			CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
			CURLOPT_SSL_VERIFYPEER => $verificarSsl,
			CURLOPT_SSL_VERIFYHOST => $verificarSsl ? 2 : 0,
			CURLOPT_HEADER => true,
		]);

		$bruto = curl_exec($ch);
		$errno = curl_errno($ch);
		$erro = curl_error($ch);
		$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		curl_close($ch);

		if ($bruto === false) {
			$this->ultimoErroDownload = $erro !== '' ? $erro : ('curl_' . $errno);

			return null;
		}

		$cabecalhos = substr($bruto, 0, $headerSize);
		$body = substr($bruto, $headerSize);
		$location = '';
		$contentType = '';
		foreach (preg_split("/\r\n|\n|\r/", $cabecalhos) ?: [] as $linha) {
			if (stripos($linha, 'Location:') === 0) {
				$location = trim(substr($linha, 9));
			}
			if (stripos($linha, 'Content-Type:') === 0) {
				$contentType = strtolower(trim(explode(';', substr($linha, 13))[0]));
			}
		}

		return [$status, $body, $location, $contentType];
	}

	private function ehImagemPadrao(string $url): bool
	{
		return str_contains(strtolower($url), 'imagem-default.png');
	}

	private function ehUrlDaPropriaThumb(string $url): bool
	{
		return str_contains(strtolower($url), '/site/pauta-imagem/');
	}

	private function binarioPareceImagem(string $binario): bool
	{
		$info = @getimagesizefromstring($binario);

		return is_array($info);
	}

	private function garantirDiretorio(): bool
	{
		$dir = $this->diretorioAbsoluto();
		if (is_dir($dir)) {
			return is_writable($dir);
		}

		return mkdir($dir, 0755, true);
	}

	/**
	 * @return array{ok: bool, eh_origem: bool, gerado: bool, ja_existia: bool, caminho: string, motivo: string}
	 */
	private function resultado(
		bool $ok,
		bool $ehOrigem,
		bool $gerado,
		bool $jaExistia,
		string $caminho,
		string $motivo,
	): array {
		return [
			'ok' => $ok,
			'eh_origem' => $ehOrigem,
			'gerado' => $gerado,
			'ja_existia' => $jaExistia,
			'caminho' => $caminho,
			'motivo' => $motivo,
		];
	}
}
