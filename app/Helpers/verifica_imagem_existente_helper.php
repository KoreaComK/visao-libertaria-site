<?php
	if(!function_exists('verifica_imagem_existente'))
	{
		function verifica_imagem_existente($url) : string
		{	
			if (@getimagesize($url)) {
				return $url;
			} else {
				return site_url('public/assets/img/imagem-artigo-default.jpg');
			}
		}
	}
?>