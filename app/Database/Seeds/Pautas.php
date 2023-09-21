<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Pautas extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');
		for ($i=0; $i < 5000; $i++) { 
			$data_criado = $faker->dateTimeBetween('-90 days', '-1 days')->format('Y/m/d H:i:s');
			$data_reservado = ($faker->randomDigit()>3)?($faker->dateTimeBetween('-10 days', '-1 days')->format('Y/m/d H:i:s')):(null);
			$data_excluido = ($faker->randomDigit()>7)?($faker->dateTimeBetween('-10 days', '-1 days')->format('Y/m/d H:i:s')):(null);
			
			$data_maior = ($data_reservado==null || $data_criado > $data_reservado)?($data_criado):($data_reservado);
			$data_maior = ($data_excluido==null || $data_maior > $data_excluido)?($data_maior):($data_excluido);
			
			$data = [
				'id' => $faker->uuid(),
				'colaboradores_id' => $faker->numberBetween(1,1000),
				'link' => $faker->url(),
				'titulo' => $faker->sentence(10),
				'texto' => $faker->paragraph(1),
				'imagem' => $faker->imageUrl(500,500, true),
				'reservado' => $data_reservado,
				'criado' => $data_criado,
				'atualizado' => $data_maior,
				'excluido' => $data_excluido
			];

			$this->db->table('pautas')->insert($data);
		}
	}
}
