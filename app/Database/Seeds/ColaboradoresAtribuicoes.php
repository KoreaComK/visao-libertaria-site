<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ColaboradoresAtribuicoes extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');

		for ($i = 1; $i < 1000; $i++) {

			$rand = $faker->numberBetween(1, 100);
			
			$this->db->table('colaboradores_atribuicoes')->insert(array('atribuicoes_id'=>1,'colaboradores_id'=>$i));
			
			if ($rand <= 90){	
				$this->db->table('colaboradores_atribuicoes')->insert(array('atribuicoes_id'=>2,'colaboradores_id'=>$i));
			}

			if ($rand <= 10){	
				$this->db->table('colaboradores_atribuicoes')->insert(array('atribuicoes_id'=>3,'colaboradores_id'=>$i));
			}

			if ($rand <= 30){	
				$this->db->table('colaboradores_atribuicoes')->insert(array('atribuicoes_id'=>4,'colaboradores_id'=>$i));
			}
			
			if ($rand <= 30){	
				$this->db->table('colaboradores_atribuicoes')->insert(array('atribuicoes_id'=>5,'colaboradores_id'=>$i));
			}
			
			if ($rand <= 5){	
				$this->db->table('colaboradores_atribuicoes')->insert(array('atribuicoes_id'=>6,'colaboradores_id'=>$i));
			}

			if ($rand <= 5){	
				$this->db->table('colaboradores_atribuicoes')->insert(array('atribuicoes_id'=>7,'colaboradores_id'=>$i));
			}

			if ($rand <= 5){	
				$this->db->table('colaboradores_atribuicoes')->insert(array('atribuicoes_id'=>8,'colaboradores_id'=>$i));
			}
		}
	}
}