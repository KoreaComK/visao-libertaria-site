<?php
	if(!function_exists('getColors'))
	{
		function getColors($x) : string
		{
			if(strtolower($x)=='pautas'){
				return 'bg-secondary';
			}
			if(strtolower($x)=='revisar'){
				return 'bg-danger';
			}
			if(strtolower($x)=='narrar'){
				return 'bg-warning';
			}
			if(strtolower($x)=='produzir'){
				return 'bg-success';
			}
			return 'bg-primary';
		}
	}
?>