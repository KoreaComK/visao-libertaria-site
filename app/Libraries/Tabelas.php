<?php

namespace App\Libraries;

class Tabelas
{
	public function adicionaEstruturaTabela($conteudo) {
		return view('components/tabelas',$conteudo);
	}
}