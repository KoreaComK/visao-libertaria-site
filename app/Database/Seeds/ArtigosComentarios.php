<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ArtigosComentarios extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');

		for ($artigos_id = 1; $artigos_id < 1000; $artigos_id++) {

			$rand = $faker->numberBetween(0, 10);
			$categorias = array();

			$r = $this->db->query('SELECT id FROM artigos')->getResult();

			for ($j = 1; $j < $rand; $j++) {
				$data_criado = $faker->dateTimeBetween('-90 days', '-1 days')->format('Y/m/d H:i:s');
				$data_excluido = ($faker->numberBetween(1, 100) > 95) ? ($faker->dateTimeBetween('-10 days', '-1 days')->format('Y/m/d H:i:s')) : (null);

				$data_maior = ($data_excluido == null || $data_criado > $data_excluido) ? ($data_criado) : ($data_excluido);
				$data = [
					'id' => $faker->uuid(),
					'artigos_id' => $r[$artigos_id]->id,
					'colaboradores_id' => $faker->numberBetween(1, 1000),
					'comentario' => $faker->paragraphs(1),
					'criado' => $data_criado,
					'atualizado' => $data_maior,
					'excluido' => $data_excluido
				];

				$this->db->table('artigos_comentarios')->insert($data);

			}
		}
	}
}