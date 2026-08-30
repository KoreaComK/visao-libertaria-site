<?php

declare(strict_types=1);

use App\Models\ConfiguracaoModel;

if (! function_exists('pautas_imagens_normalizar_base_url')) {
	/**
	 * Garante URL absoluta com barra final. Vazio cai no site atual.
	 */
	function pautas_imagens_normalizar_base_url(?string $url): string
	{
		$url = trim((string) $url);
		if ($url === '') {
			$url = site_url();
		}

		if (! preg_match('#^https?://#i', $url)) {
			$url = site_url(ltrim($url, '/'));
		}

		return rtrim($url, '/') . '/';
	}
}

if (! function_exists('pautas_imagens_base_url')) {
	function pautas_imagens_base_url(): string
	{
		$valor = '';

		try {
			$linha = model(ConfiguracaoModel::class)->find('pautas_imagens_base_url');
			if (is_array($linha)) {
				$valor = (string) ($linha['config_valor'] ?? '');
			}
		} catch (Throwable) {
			$valor = '';
		}

		return pautas_imagens_normalizar_base_url($valor);
	}
}

if (! function_exists('sou_origem_imagens_pauta')) {
	function sou_origem_imagens_pauta(?string $baseOrigem = null, ?string $baseLocal = null): bool
	{
		$origem = pautas_imagens_normalizar_base_url($baseOrigem ?? pautas_imagens_base_url());
		$local = pautas_imagens_normalizar_base_url($baseLocal ?? site_url());

		return strcasecmp($origem, $local) === 0;
	}
}

if (! function_exists('pauta_imagem_id_valido')) {
	function pauta_imagem_id_valido(mixed $pautaId): bool
	{
		$id = strtolower(trim((string) $pautaId));

		return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $id) === 1;
	}
}

if (! function_exists('url_imagem_pauta')) {
	/**
	 * URL da thumb na origem (`site/pauta-imagem/{uuid}`).
	 *
	 * @param string|null $baseOrigem Sobrescreve a config (útil em testes).
	 */
	function url_imagem_pauta(mixed $pautaId, ?string $baseOrigem = null): string
	{
		if (! pauta_imagem_id_valido($pautaId)) {
			return site_url('public/assets/imagem-default.png');
		}

		$id = strtolower(trim((string) $pautaId));
		$base = pautas_imagens_normalizar_base_url($baseOrigem ?? pautas_imagens_base_url());

		return $base . 'site/pauta-imagem/' . rawurlencode($id);
	}
}
