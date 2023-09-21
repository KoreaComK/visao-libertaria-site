<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Main extends Seeder
{
	public function run()
	{
		$this->call('Colaboradores');
		$this->call('Conquistas');
		$this->call('Atribuicoes');
		$this->call('ColaboradoresAtribuicoes');
		$this->call('ColaboradoresConquistas');
		$this->call('FaseProducao');
		$this->call('Categorias');
		$this->call('Pautas');
		$this->call('Artigos');
		$this->call('ArtigosCategorias');
		$this->call('ArtigosComentarios');
		
	}
}

