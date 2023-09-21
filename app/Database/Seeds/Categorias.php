<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Categorias extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');
		for ($i = 0; $i < 25; $i++) {
			$data_criado = $faker->dateTimeBetween('-3000 days', '-2000 days')->format('Y/m/d H:i:s');
			$data_excluido = ($faker->randomDigit() > 8) ? ($faker->dateTimeBetween('-10 days', '-1 days')->format('Y/m/d H:i:s')) : (null);
			$data_maior = null;
			$data_maior = ($data_excluido == null || $data_maior > $data_excluido) ? ($data_criado) : ($data_excluido);

			$data = [
				'nome' => $faker->word(),
				'criado' => $data_criado,
				'atualizado' => $data_maior,
				'excluido' => $data_excluido
			];

			$this->db->table('categorias')->insert($data);
		}
	}
}