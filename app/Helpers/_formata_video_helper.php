<?php

/**
 * Helper com funções para formatação de dados de vídeos.
 */

if (!function_exists('extrair_id_video_youtube')) {
	/**
	 * Extrai o ID do vídeo a partir de uma URL do YouTube, de um ID já “cru” ou de string vazia.
	 *
	 * @return string|null ID do vídeo (em geral 11 caracteres) ou null se não for possível extrair.
	 */
	function extrair_id_video_youtube(?string $url): ?string
	{
		if ($url === null) {
			return null;
		}

		$url = trim($url);
		if ($url === '') {
			return null;
		}

		if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
			return $url;
		}

		$patterns = [
			'~/youtu\.be/([a-zA-Z0-9_-]{11})~',
			'~/[?&]v=([a-zA-Z0-9_-]{11})~',
			'~/youtube\.com/embed/([a-zA-Z0-9_-]{11})~',
			'~/youtube\.com/shorts/([a-zA-Z0-9_-]+)~',
			'~/youtube\.com/live/([a-zA-Z0-9_-]+)~',
		];

		foreach ($patterns as $pattern) {
			if (preg_match($pattern, $url, $matches)) {
				return $matches[1];
			}
		}

		return null;
	}
}

if (!function_exists('cria_url_thumb')) {
	/**
	 * Cria a URL da thumbnail de alta resolução de um vídeo do YouTube.
	 *
	 * @param string $id_video O ID do vídeo do YouTube.
	 * @return string A URL da imagem da thumbnail.
	 */
	function cria_url_thumb(string $id_video): string
	{
		return 'https://img.youtube.com/vi/' . $id_video . '/maxresdefault.jpg';
	}
}

if (!function_exists('cria_link_watch')) {
	/**
	 * Monta o link “assistir no YouTube” a partir do ID do vídeo.
	 *
	 * @param string $id_video O ID do vídeo do YouTube.
	 * @return string URL de watch.
	 */
	function cria_link_watch(string $id_video): string
	{
		return 'https://www.youtube.com/watch?v=' . $id_video;
	}
}
