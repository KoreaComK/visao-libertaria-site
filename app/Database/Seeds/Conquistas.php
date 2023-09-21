<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;


class Conquistas extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');
		for ($i=0; $i < 25; $i++) { 
			$data = [
				'nome' => $faker->words(5, true),
				'descricao' => $faker->words(25, true),
				'imagem' => $faker->imageUrl(500,500, true),
				'excluido' => ($faker->randomDigit()>8)?($faker->date('Y/m/d ').$faker->time('H:i:s')):(null)
			];

			$this->db->table('conquistas')->insert($data);
		}
	}
}
