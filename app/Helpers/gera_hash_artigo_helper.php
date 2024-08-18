<?php
	if(!function_exists('gera_hash_artigo'))
	{
		function gera_hash_artigo($artigo) : string
		{
			if($artigo !== NULL && !empty($artigo)) {
				return hash('sha256', $artigo['titulo'].$artigo['gancho'].$artigo['texto'].$artigo['referencias']);
			}
			return hash('sha256', NULL);
		}
	}
?>
