<?php

use App\Libraries\ExtratorMetadadosLink;
use CodeIgniter\Test\CIUnitTestCase;

class ExtratorMetadadosLinkTest extends CIUnitTestCase
{
	private ExtratorMetadadosLink $extrator;

	protected function setUp(): void
	{
		parent::setUp();
		$this->extrator = new ExtratorMetadadosLink();
	}

	public function testValidarUrlRejeitaLocalhost(): void
	{
		$this->assertNull($this->extrator->validarUrl('http://localhost/noticia'));
		$this->assertNull($this->extrator->validarUrl('https://127.0.0.1/noticia'));
	}

	public function testValidarUrlRejeitaEsquemaInvalido(): void
	{
		$this->assertNull($this->extrator->validarUrl('ftp://example.com/noticia'));
	}

	public function testResolverUrlRelativa(): void
	{
		$base = 'https://www.exemplo.com/noticias/artigo';

		$this->assertSame(
			'https://www.exemplo.com/assets/foto.jpg',
			$this->extrator->resolverUrlRelativa('/assets/foto.jpg', $base)
		);

		$this->assertSame(
			'https://www.exemplo.com/noticias/imagem.jpg',
			$this->extrator->resolverUrlRelativa('imagem.jpg', $base)
		);

		$this->assertSame(
			'https://cdn.exemplo.com/foto.jpg',
			$this->extrator->resolverUrlRelativa('//cdn.exemplo.com/foto.jpg', $base)
		);
	}

	public function testExtrairMetadadosOpenGraph(): void
	{
		$html = <<<'HTML'
		<!DOCTYPE html>
		<html>
		<head>
			<meta property="og:title" content="Título OG">
			<meta property="og:description" content="Descrição OG">
			<meta property="og:image" content="https://cdn.exemplo.com/foto.jpg">
		</head>
		<body></body>
		</html>
		HTML;

		$metadados = $this->extrator->extrairMetadadosDeHtml($html, 'https://www.exemplo.com/noticia');

		$this->assertSame('Título OG', $metadados['titulo']);
		$this->assertSame('Descrição OG', $metadados['descricao']);
		$this->assertSame('', $metadados['imagem']);
	}

	public function testExtrairMetadadosJsonLd(): void
	{
		$html = <<<'HTML'
		<!DOCTYPE html>
		<html>
		<head>
			<script type="application/ld+json">
			{
				"@type": "NewsArticle",
				"headline": "Título JSON-LD",
				"description": "Descrição JSON-LD",
				"datePublished": "2024-01-15T10:00:00+00:00",
				"image": "https://cdn.exemplo.com/jsonld.jpg"
			}
			</script>
		</head>
		<body></body>
		</html>
		HTML;

		$metadados = $this->extrator->extrairMetadadosDeHtml($html, 'https://www.exemplo.com/noticia');

		$this->assertSame('Título JSON-LD', $metadados['titulo']);
		$this->assertSame('Descrição JSON-LD', $metadados['descricao']);
		$this->assertNotNull($metadados['dias']);
	}

	public function testExtrairMetadadosImagemRelativa(): void
	{
		$html = <<<'HTML'
		<!DOCTYPE html>
		<html>
		<head>
			<meta property="og:title" content="Título">
			<meta property="og:description" content="Descrição">
		</head>
		<body>
			<img src="/media/foto.png" alt="Foto da notícia">
		</body>
		</html>
		HTML;

		$metadados = $this->extrator->extrairMetadadosDeHtml($html, 'https://www.exemplo.com/noticias/artigo');

		$this->assertSame('Título', $metadados['titulo']);
		$this->assertSame('Descrição', $metadados['descricao']);
	}

	public function testTruncarDescricao(): void
	{
		$descricaoLonga = str_repeat('Palavra ', 300);
		$html = <<<HTML
		<!DOCTYPE html>
		<html>
		<head>
			<meta property="og:title" content="Título">
			<meta property="og:description" content="{$descricaoLonga}">
		</head>
		<body></body>
		</html>
		HTML;

		$metadados = $this->extrator->extrairMetadadosDeHtml($html, 'https://www.exemplo.com/noticia');

		$this->assertLessThanOrEqual(1000, mb_strlen($metadados['descricao']));
		$this->assertStringEndsWith('…', $metadados['descricao']);
	}

	public function testMontarRespostaApiErro(): void
	{
		$resposta = $this->extrator->montarRespostaApi('http://localhost/teste', 30);

		$this->assertFalse($resposta['status']);
		$this->assertSame('URL inválida ou não permitida.', $resposta['mensagem']);
		$this->assertFalse($resposta['parametros']);
	}
}
