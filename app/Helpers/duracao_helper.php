<?php

if (!function_exists('duracao_hhmm_normalizar')) {
	function duracao_hhmm_normalizar($valor): string
	{
		$valor = trim((string) $valor);
		$valor = str_replace([',', '.', 'h', 'H', ' '], [':', ':', '', '', ''], $valor);
		if (preg_match('/^\d{1,4}$/', $valor)) {
			return $valor . ':00';
		}
		if (preg_match('/^(\d{1,4}):(\d)$/', $valor, $m)) {
			return $m[1] . ':0' . $m[2];
		}
		return $valor;
	}
}

if (!function_exists('duracao_hhmm_valida')) {
	function duracao_hhmm_valida($valor): bool
	{
		return (bool) preg_match('/^\d{1,4}:[0-5]\d$/', duracao_hhmm_normalizar($valor));
	}
}

if (!function_exists('duracao_hhmm_para_decimal')) {
	function duracao_hhmm_para_decimal($valor): ?string
	{
		$valor = duracao_hhmm_normalizar($valor);
		if (!preg_match('/^(\d{1,4}):([0-5]\d)$/', $valor, $m)) {
			return null;
		}
		$minutos = ((int) $m[1] * 60) + (int) $m[2];
		if ($minutos < 1) {
			return null;
		}
		return number_format($minutos / 60, 2, '.', '');
	}
}

if (!function_exists('decimal_para_duracao_hhmm')) {
	function decimal_para_duracao_hhmm($decimal): string
	{
		if ($decimal === null || $decimal === '') {
			return '';
		}
		$minutos = (int) round(((float) $decimal) * 60);
		if ($minutos < 0) {
			$minutos = 0;
		}
		return sprintf('%d:%02d', intdiv($minutos, 60), $minutos % 60);
	}
}
