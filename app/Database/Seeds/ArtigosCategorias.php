<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ArtigosCategorias extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');

		for ($artigos_id = 1; $artigos_id < 1000; $artigos_id++) {
			
			$rand = $faker->numberBetween(0, 50);
			$categorias = array();

			$r = $this->db->query('SELECT id FROM artigos')->getResult();
			
			for ($j = 1; $j < $rand; $j++) {
				
				$categoria_id = $faker->numberBetween(1, 25);
				
				if(!isset($categorias[$categoria_id])){
					$categorias[$categoria_id] = $categoria_id;
					$this->db->table('artigos_categorias')->insert(array('categorias_id'=>$categoria_id,'artigos_id'=>$r[$artigos_id]->id));
				}
			}
		}
	}
}
