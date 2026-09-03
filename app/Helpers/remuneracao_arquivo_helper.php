<?php

if (!function_exists('remuneracao_arquivo_caminho')) {
	function remuneracao_arquivo_caminho(?string $arquivo): ?string
	{
		if ($arquivo === null || $arquivo === '') {
			return null;
		}
		$base = basename($arquivo);
		if ($base === '' || $base !== $arquivo) {
			return null;
		}
		$full = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'remuneracoes' . DIRECTORY_SEPARATOR . $base;
		if (!is_file($full)) {
			return null;
		}
		return $full;
	}
}
