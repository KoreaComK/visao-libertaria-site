<?php

/**
 * Helper com funções para formatação de dados de vídeos.
 */

if (!function_exists('extrair_id_video_youtube')) {
    /**
     * Extrai o ID do vídeo de uma URL do YouTube.
     *
     * @param string $url A URL do vídeo do YouTube.
     * @return string|null O ID do vídeo ou null se não for uma URL válida.
     */
    function extrair_id_video_youtube(string $url): ?string
    {
        $pattern = '/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/';
        return preg_replace($pattern, '$1', $url);
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
     * Cria a URL da thumbnail de alta resolução de um vídeo do YouTube.
     *
     * @param string $id_video O ID do vídeo do YouTube.
     * @return string A URL da imagem da thumbnail.
     */
    function cria_link_watch(string $id_video): string
    {
        return 'https://www.youtube.com/watch?v=' . $id_video;
    }
}