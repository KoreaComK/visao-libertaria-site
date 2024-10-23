<?php

namespace App\Libraries;

class Listas
{
	public function listasVerticaisSimples($conteudo) {
		return view('components/listas-verticais-simples',$conteudo);
	}
}