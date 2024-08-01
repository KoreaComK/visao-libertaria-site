<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Colaboradores extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');

		$data = [
			'apelido' => $faker->name,
			'avatar' => NULL,
			'email' => 'admin@admin.com',
			'carteira' => NULL,
			'senha' => hash('sha256', '12345678'),
			'strikes' => NULL,
			'strike_data' => NULL,
			'pontuacao_total' => 0,
			'pontuacao_mensal' => 0,
			'confirmacao_hash' => hash('sha256', $faker->shuffle('qq4luzck97cvndxdg74m234wteq6yxyf940he2j')),
			'criado' => $faker->dateTimeBetween('-9999 days', '-365 days')->format('Y/m/d H:i:s'),
			'atualizado' => $faker->dateTimeThisYear('-1 days')->format('Y/m/d H:i:s'),
			'confirmado_data' => $faker->dateTimeBetween('-1000 days', '-1 days')->format('Y/m/d H:i:s'),
			'excluido' => NULL
		];
		$this->db->table('colaboradores')->insert($data);

		for ($i=0; $i < 1000; $i++) { 
			$strike = $faker->numberBetween(1,3);
			$confirmado = $faker->randomDigit();
			$data = [
				'apelido' => $faker->name,
				'avatar' => ($faker->randomDigit()>5)?($faker->imageUrl(500,500, true)):(null),
				'email' => $faker->safeEmail,
				'carteira' => ($faker->randomDigit()<8)?('bc1'.$faker->shuffle('qq4luzck97cvndxdg74m234wteq6yxyf940he2j')):(null),
				'senha' => hash('sha256', '12345678'),
				'strikes' => ($faker->randomDigit()<8)?($faker->numberBetween(1,3)):(null),
				'strike_data' => ($strike==3)?($faker->dateTimeBetween('0 days', '+30 days')->format('Y/m/d H:i:s')):(null),
				'pontuacao_total' => 0,
				'pontuacao_mensal' => 0,
				'confirmacao_hash' => hash('sha256', $faker->shuffle('qq4luzck97cvndxdg74m234wteq6yxyf940he2j')),
				'criado' => $faker->dateTimeBetween('-9999 days', '-365 days')->format('Y/m/d H:i:s'),
				'atualizado' => $faker->dateTimeThisYear('-1 days')->format('Y/m/d H:i:s'),
				'confirmado_data' => ($confirmado<8)?($faker->dateTimeBetween('-1000 days', '-1 days')->format('Y/m/d H:i:s')):(null),
				'excluido' => ($faker->randomDigit()>7 && $confirmado<8)?($faker->dateTimeBetween('-10 days', '-1 days')->format('Y/m/d H:i:s')):(null)
			];

			$this->db->table('colaboradores')->insert($data);
		}
	}
}
