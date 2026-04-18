<?php

/**
 * Contagem de palavras alinhada ao legado do projeto (normalização de acentos + str_word_count).
 * Usar em PHP e expor ao cliente via endpoint para o mesmo resultado no painel.
 */

if (! function_exists('normalizar_acentos_contagem_palavras')) {
	/**
	 * Substitui acentos comuns por equivalentes ASCII (mesmo conjunto de padrões usado historicamente).
	 */
	function normalizar_acentos_contagem_palavras(string $texto): string
	{
		$padroes = [
			'/(á|à|ã|â|ä)/u',
			'/(Á|À|Ã|Â|Ä)/u',
			'/(é|è|ê|ë)/u',
			'/(É|È|Ê|Ë)/u',
			'/(í|ì|î|ï)/u',
			'/(Í|Ì|Î|Ï)/u',
			'/(ó|ò|õ|ô|ö)/u',
			'/(Ó|Ò|Õ|Ô|Ö)/u',
			'/(ú|ù|û|ü)/u',
			'/(Ú|Ù|Û|Ü)/u',
			'/(ñ)/u',
			'/(Ñ)/u',
			'/(ç|Ç)/u',
		];
		$substituicoes = explode(' ', 'a A e E i I o O u U n N c');

		return (string) preg_replace($padroes, $substituicoes, $texto);
	}
}

if (! function_exists('contar_palavras_texto')) {
	/**
	 * Conta palavras do texto (artigos, pautas, etc.) com a mesma regra do servidor legado.
	 */
	function contar_palavras_texto(?string $texto): int
	{
		if ($texto === null || $texto === '') {
			return 0;
		}

		$normalizado = normalizar_acentos_contagem_palavras($texto);

		return (int) str_word_count($normalizado);
	}
}
