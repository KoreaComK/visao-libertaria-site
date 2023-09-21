<?php
	if(!function_exists('getColors'))
	{
		function getColors($x) : string
		{
			if(strtolower($x)=='pautas'){
				return 'badge-secondary';
			}
			if(strtolower($x)=='revisar'){
				return 'badge-danger';
			}
			if(strtolower($x)=='narrar'){
				return 'badge-warning';
			}
			if(strtolower($x)=='produzir'){
				return 'badge-success';
			}
			return 'badge-primary';
		}
	}
?>