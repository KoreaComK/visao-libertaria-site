<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;
use DOMElement;

class ExtratorMetadadosLink
{
	private const TIMEOUT = 3;
	private const MAX_BYTES = 2097152;
	private const MAX_REDIRECTS = 3;
	private const MAX_DESCRICAO = 1000;

	private const TIPOS_IMAGEM = [
		'image/jpeg',
		'image/png',
		'image/gif',
		'image/webp',
		'image/avif',
		'image/svg+xml',
		'image/bmp',
		'image/x-icon',
	];

	public function montarRespostaApi(string $url, int $dataMaximaPauta): array
	{
		$resultado = $this->obterMetadados($url);

		if (!$resultado['sucesso']) {
			return [
				'status' => false,
				'mensagem' => $resultado['erro'],
				'parametros' => false,
			];
		}

		$dias = $resultado['dias'];

		return [
			'status' => true,
			'mensagem' => ($dias !== null && $dias > $dataMaximaPauta)
				? ('ATENÇÃO! A pauta é de mais de ' . $dataMaximaPauta . ' dias atrás. Ela será marcada como antiga.')
				: null,
			'parametros' => false,
			'titulo' => $resultado['titulo'],
			'texto' => $resultado['descricao'],
			'imagem' => $resultado['imagem'] ?? '',
			'dias' => $dias,
		];
	}

	public function obterMetadados(string $url): array
	{
		$urlValidada = $this->validarUrl($url);
		if ($urlValidada === null) {
			return [
				'sucesso' => false,
				'erro' => 'URL inválida ou não permitida.',
			];
		}

		$html = $this->buscarHtml($urlValidada);
		if ($html === null) {
			return [
				'sucesso' => false,
				'erro' => 'Não foi possível trazer informações da pauta automaticamente.',
			];
		}

		$metadados = $this->extrairMetadadosDeHtml($html, $urlValidada);
		if ($metadados['titulo'] === '' || $metadados['descricao'] === '') {
			return [
				'sucesso' => false,
				'erro' => 'Não foi possível trazer informações da pauta automaticamente.',
			];
		}

		return [
			'sucesso' => true,
			'titulo' => $metadados['titulo'],
			'descricao' => $metadados['descricao'],
			'imagem' => $metadados['imagem'],
			'dias' => $metadados['dias'],
		];
	}

	public function extrairMetadadosDeHtml(string $html, string $baseUrl): array
	{
		$metadados = $this->extrairMetadados($html, $baseUrl);
		$metadados['descricao'] = $this->truncarTexto($metadados['descricao'], self::MAX_DESCRICAO);
		$metadados['imagem'] = $this->validarImagemRemota($metadados['imagem']);

		return $metadados;
	}

	public function validarUrl(string $url): ?string
	{
		$url = trim($url);
		if ($url === '' || strlen($url) > 2048) {
			return null;
		}

		$partes = parse_url($url);
		if ($partes === false || !isset($partes['scheme'], $partes['host'])) {
			return null;
		}

		$scheme = strtolower($partes['scheme']);
		if (!in_array($scheme, ['http', 'https'], true)) {
			return null;
		}

		$host = strtolower($partes['host']);
		if ($host === 'localhost' || str_ends_with($host, '.local') || str_ends_with($host, '.localhost')) {
			return null;
		}

		if (!$this->hostResolveParaIpPublico($host)) {
			return null;
		}

		$porta = isset($partes['port']) ? ':' . $partes['port'] : '';
		$caminho = $partes['path'] ?? '/';
		$query = isset($partes['query']) ? '?' . $partes['query'] : '';

		return $scheme . '://' . $partes['host'] . $porta . $caminho . $query;
	}

	public function resolverUrlRelativa(string $url, string $baseUrl): string
	{
		$url = trim($url);
		if ($url === '') {
			return $baseUrl;
		}

		if (preg_match('#^https?://#i', $url)) {
			return $url;
		}

		if (str_starts_with($url, '//')) {
			$base = parse_url($baseUrl);
			$scheme = $base['scheme'] ?? 'https';

			return $scheme . ':' . $url;
		}

		$base = parse_url($baseUrl);
		$scheme = $base['scheme'] ?? 'https';
		$host = $base['host'] ?? '';
		$porta = isset($base['port']) ? ':' . $base['port'] : '';
		$caminho = $base['path'] ?? '/';

		if (str_starts_with($url, '/')) {
			return $scheme . '://' . $host . $porta . $url;
		}

		$diretorio = preg_replace('#/[^/]*$#', '/', $caminho) ?: '/';

		return $scheme . '://' . $host . $porta . $diretorio . $url;
	}

	private function hostResolveParaIpPublico(string $host): bool
	{
		$ips = [];

		if (filter_var($host, FILTER_VALIDATE_IP)) {
			$ips[] = $host;
		} else {
			$registros = @dns_get_record($host, DNS_A + DNS_AAAA);
			if (is_array($registros) && $registros !== []) {
				foreach ($registros as $registro) {
					if (!empty($registro['ip'])) {
						$ips[] = $registro['ip'];
					}
					if (!empty($registro['ipv6'])) {
						$ips[] = $registro['ipv6'];
					}
				}
			} else {
				$ip = gethostbyname($host);
				if ($ip === $host) {
					return false;
				}
				$ips[] = $ip;
			}
		}

		if ($ips === []) {
			return false;
		}

		foreach ($ips as $ip) {
			if ($this->isIpPrivadoOuReservado($ip)) {
				return false;
			}
		}

		return true;
	}

	private function isIpPrivadoOuReservado(string $ip): bool
	{
		if (!filter_var($ip, FILTER_VALIDATE_IP)) {
			return true;
		}

		return filter_var(
			$ip,
			FILTER_VALIDATE_IP,
			FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
		) === false;
	}

	private function buscarHtml(string $url): ?string
	{
		$urlAtual = $url;

		for ($redirect = 0; $redirect <= self::MAX_REDIRECTS; $redirect++) {
			$urlValidada = $this->validarUrl($urlAtual);
			if ($urlValidada === null) {
				return null;
			}

			try {
				$response = $this->requisicaoHttp('GET', $urlValidada, [
					'Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8',
				]);
				if ($response === null) {
					return null;
				}

				[$status, $body, $headers] = $response;

				if ($status >= 300 && $status < 400) {
					$location = trim($headers['Location'] ?? '');
					if ($location === '') {
						return null;
					}
					$urlAtual = $this->resolverUrlRelativa($location, $urlValidada);
					continue;
				}

				if ($status < 200 || $status >= 400 || $body === '') {
					return null;
				}

				if (strlen($body) > self::MAX_BYTES) {
					$body = substr($body, 0, self::MAX_BYTES);
				}

				return $body;
			} catch (\Throwable $e) {
				return null;
			}
		}

		return null;
	}

	private function requisicaoHttp(string $metodo, string $url, array $headersExtras = []): ?array
	{
		$urlValidada = $this->validarUrl($url);
		if ($urlValidada === null) {
			return null;
		}

		try {
			$client = \Config\Services::curlrequest([
				'timeout' => self::TIMEOUT,
				'connect_timeout' => self::TIMEOUT,
				'allow_redirects' => false,
				'headers' => array_merge([
					'User-Agent' => 'VisaoLibertariaBot/1.0',
				], $headersExtras),
			]);

			$response = $client->request($metodo, $urlValidada, ['http_errors' => false]);
			$headersResposta = [];
			foreach ($response->headers() as $nome => $header) {
				$headersResposta[$nome] = $response->getHeaderLine($nome);
			}

			return [
				$response->getStatusCode(),
				(string) $response->getBody(),
				$headersResposta,
			];
		} catch (\Throwable $e) {
			return null;
		}
	}

	private function extrairMetadados(string $html, string $baseUrl): array
	{
		$documento = new \DOMDocument();
		libxml_use_internal_errors(true);
		$html = $this->normalizarUtf8($html) ?? '';
		$documento->loadHTML('<?xml encoding="utf-8" ?>' . $html);
		libxml_clear_errors();

		$xpath = new \DOMXPath($documento);
		$jsonLd = $this->extrairJsonLd($xpath, $baseUrl);

		$titulo = $this->metaPorAtributo($xpath, 'property', 'og:title')
			?? $this->metaPorAtributo($xpath, 'name', 'twitter:title')
			?? $jsonLd['titulo']
			?? $this->metaPorAtributo($xpath, 'name', 'title')
			?? $this->primeiroTexto($xpath, '//title')
			?? $this->primeiroTexto($xpath, '//h1')
			?? $this->primeiroTexto($xpath, '//h2')
			?? $this->primeiroTexto($xpath, '//h3')
			?? $this->primeiroTexto($xpath, '//h4')
			?? '';

		$descricao = $this->metaPorAtributo($xpath, 'property', 'og:description')
			?? $this->metaPorAtributo($xpath, 'name', 'twitter:description')
			?? $jsonLd['descricao']
			?? $this->metaPorAtributo($xpath, 'name', 'description')
			?? $this->primeiroTexto($xpath, '//p')
			?? '';

		$imagem = $this->metaPorAtributo($xpath, 'property', 'og:image')
			?? $this->metaPorAtributo($xpath, 'name', 'twitter:image')
			?? $jsonLd['imagem']
			?? $this->primeiraImagemComAlt($xpath, $baseUrl);

		if ($imagem !== null && $imagem !== '') {
			$imagem = $this->resolverUrlRelativa($imagem, $baseUrl);
		} else {
			$imagem = null;
		}

		$dias = $this->extrairDiasPublicacao($xpath, $jsonLd['dataPublicacao']);

		return [
			'titulo' => $this->decodificarTexto(trim($titulo)),
			'descricao' => $this->decodificarTexto(trim($descricao)),
			'imagem' => $imagem !== null ? $this->decodificarTexto($imagem) : null,
			'dias' => $dias,
		];
	}

	private function extrairJsonLd(\DOMXPath $xpath, string $baseUrl): array
	{
		$resultado = [
			'titulo' => null,
			'descricao' => null,
			'imagem' => null,
			'dataPublicacao' => null,
		];

		$nodes = $xpath->query("//script[@type='application/ld+json']");
		if ($nodes === false) {
			return $resultado;
		}

		foreach ($nodes as $node) {
			$dados = json_decode(trim($node->nodeValue ?? ''), true);
			if (!is_array($dados)) {
				continue;
			}

			foreach ($this->normalizarJsonLd($dados) as $item) {
				if ($resultado['titulo'] === null) {
					$resultado['titulo'] = $this->valorTexto($item['headline'] ?? null)
						?? $this->valorTexto($item['name'] ?? null);
				}

				if ($resultado['descricao'] === null) {
					$resultado['descricao'] = $this->valorTexto($item['description'] ?? null);
				}

				if ($resultado['imagem'] === null) {
					$resultado['imagem'] = $this->extrairImagemJsonLd($item['image'] ?? null, $baseUrl);
				}

				if ($resultado['dataPublicacao'] === null) {
					$resultado['dataPublicacao'] = $this->valorTexto($item['datePublished'] ?? null);
				}
			}
		}

		return $resultado;
	}

	private function normalizarJsonLd(array $dados): array
	{
		if (isset($dados['@graph']) && is_array($dados['@graph'])) {
			return $dados['@graph'];
		}

		if ($this->isListaAssociativa($dados)) {
			return [$dados];
		}

		return $dados;
	}

	private function isListaAssociativa(array $dados): bool
	{
		if ($dados === []) {
			return true;
		}

		return array_keys($dados) !== range(0, count($dados) - 1);
	}

	private function extrairImagemJsonLd($imagem, string $baseUrl): ?string
	{
		if ($imagem === null) {
			return null;
		}

		if (is_string($imagem)) {
			return $this->resolverUrlRelativa($imagem, $baseUrl);
		}

		if (is_array($imagem)) {
			if (isset($imagem['url'])) {
				return $this->resolverUrlRelativa((string) $imagem['url'], $baseUrl);
			}

			foreach ($imagem as $item) {
				$url = $this->extrairImagemJsonLd($item, $baseUrl);
				if ($url !== null && $url !== '') {
					return $url;
				}
			}
		}

		return null;
	}

	private function valorTexto($valor): ?string
	{
		if (!is_string($valor)) {
			return null;
		}

		$valor = trim($valor);

		return $valor !== '' ? $valor : null;
	}

	private function metaPorAtributo(\DOMXPath $xpath, string $atributo, string $valor): ?string
	{
		$nodes = $xpath->query('//meta[@' . $atributo . '="' . $valor . '"]');
		if ($nodes === false) {
			return null;
		}

		foreach ($nodes as $node) {
			if (!$node instanceof DOMElement) {
				continue;
			}

			$conteudo = trim($node->getAttribute('content'));
			if ($conteudo !== '') {
				return $conteudo;
			}
		}

		return null;
	}

	private function primeiroTexto(\DOMXPath $xpath, string $consulta): ?string
	{
		$nodes = $xpath->query($consulta);
		if ($nodes === false) {
			return null;
		}

		foreach ($nodes as $node) {
			$texto = trim($node->nodeValue ?? '');
			if ($texto !== '') {
				return $texto;
			}
		}

		return null;
	}

	private function primeiraImagemComAlt(\DOMXPath $xpath, string $baseUrl): ?string
	{
		$nodes = $xpath->query('//img[@alt!=""]');
		if ($nodes === false) {
			return null;
		}

		foreach ($nodes as $node) {
			if (!$node instanceof DOMElement) {
				continue;
			}

			$src = trim($node->getAttribute('src'));
			if ($src !== '') {
				return $this->resolverUrlRelativa($src, $baseUrl);
			}
		}

		return null;
	}

	private function extrairDiasPublicacao(\DOMXPath $xpath, ?string $dataJsonLd): ?int
	{
		foreach ($xpath->query("//meta[@property='article:published_time']") as $node) {
			if (!$node instanceof DOMElement) {
				continue;
			}

			$diferenca = $this->diferencaEmDias($node->getAttribute('content'));
			if ($diferenca !== null) {
				return $diferenca->days;
			}
		}

		if ($dataJsonLd !== null) {
			$diferenca = $this->diferencaEmDias($dataJsonLd);
			if ($diferenca !== null) {
				return $diferenca->days;
			}
		}

		return null;
	}

	private function diferencaEmDias(?string $data): ?\CodeIgniter\I18n\TimeDifference
	{
		if ($data === null || trim($data) === '') {
			return null;
		}

		$timestamp = strtotime($data);
		if ($timestamp === false) {
			return null;
		}

		$publicacao = Time::parse(date('Y-m-d', $timestamp));

		return $publicacao->difference(Time::now());
	}

	private function validarImagemRemota(?string $url): string
	{
		if ($url === null || trim($url) === '') {
			return '';
		}

		$url = $this->codificarSegmentosUrl($url);
		if ($this->validarUrl($url) === null) {
			return '';
		}

		$resposta = $this->requisicaoHttp('HEAD', $url, [
			'Accept' => 'image/*,*/*;q=0.8',
		]);

		if ($resposta === null) {
			return '';
		}

		[$status, , $headers] = $resposta;
		if ($status < 200 || $status >= 400) {
			return '';
		}

		$contentType = strtolower(trim(explode(';', $headers['Content-Type'] ?? '')[0]));
		if ($contentType !== '' && !in_array($contentType, self::TIPOS_IMAGEM, true)) {
			if (!$this->extensaoPareceImagem($url)) {
				return '';
			}
		}

		return $url;
	}

	private function extensaoPareceImagem(string $url): bool
	{
		$caminho = parse_url($url, PHP_URL_PATH) ?? '';

		return (bool) preg_match('/\.(jpe?g|png|gif|webp|avif|svg|bmp|ico)$/i', $caminho);
	}

	private function codificarSegmentosUrl(string $url): string
	{
		$partes = explode('://', $url, 2);
		if (count($partes) < 2) {
			return $url;
		}

		$segmentos = explode('/', $partes[1]);
		foreach ($segmentos as $k => $segmento) {
			$segmentos[$k] = rawurlencode($segmento);
		}

		return $partes[0] . '://' . implode('/', $segmentos);
	}

	private function truncarTexto(string $texto, int $limite): string
	{
		if (mb_strlen($texto) <= $limite) {
			return $texto;
		}

		return rtrim(mb_substr($texto, 0, $limite - 1)) . '…';
	}

	private function decodificarTexto(string $texto): string
	{
		return html_entity_decode($this->normalizarUtf8($texto) ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}

	private function normalizarUtf8(?string $texto): ?string
	{
		if ($texto === null || $texto === '') {
			return $texto;
		}

		if (mb_check_encoding($texto, 'UTF-8')) {
			return $texto;
		}

		return mb_convert_encoding($texto, 'UTF-8', 'ISO-8859-1');
	}
}
