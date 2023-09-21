<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ColaboradoresConquistas extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');

		for ($i = 1; $i < 1000; $i++) {

			$rand = $faker->numberBetween(0, 25);
			
			for ($j = 1; $j < $rand; $j++) {
				$this->db->table('colaboradores_conquistas')->insert(array('conquistas_id'=>$j,'colaboradores_id'=>$i));
			}
			
		}
	}
}
