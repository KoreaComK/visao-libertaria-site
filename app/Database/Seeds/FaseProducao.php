<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class FaseProducao extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');
		$this->db->table('fase_producao')->insert(array('nome'=>'Escrever','etapa_anterior'=>null,'etapa_posterior'=>null,'mostrar_site'=>'N'));
		$this->db->table('fase_producao')->insert(array('nome'=>'Revisar','etapa_anterior'=>1,'etapa_posterior'=>null,'mostrar_site'=>'S'));
		$this->db->table('fase_producao')->insert(array('nome'=>'Narrar','etapa_anterior'=>2,'etapa_posterior'=>null,'mostrar_site'=>'S'));
		$this->db->table('fase_producao')->insert(array('nome'=>'Produzir','etapa_anterior'=>3,'etapa_posterior'=>null,'mostrar_site'=>'S'));
		$this->db->table('fase_producao')->insert(array('nome'=>'Publicar','etapa_anterior'=>4,'etapa_posterior'=>null,'mostrar_site'=>'N'));
		$this->db->table('fase_producao')->insert(array('nome'=>'Pagar','etapa_anterior'=>5,'etapa_posterior'=>null,'mostrar_site'=>'N'));
		$this->db->table('fase_producao')->insert(array('nome'=>'Finalizado','etapa_anterior'=>6,'etapa_posterior'=>null,'mostrar_site'=>'N'));
		$this->db->query('UPDATE fase_producao SET etapa_posterior=2 WHERE id = 1');
		$this->db->query('UPDATE fase_producao SET etapa_posterior=3 WHERE id = 2');
		$this->db->query('UPDATE fase_producao SET etapa_posterior=4 WHERE id = 3');
		$this->db->query('UPDATE fase_producao SET etapa_posterior=5 WHERE id = 4');
		$this->db->query('UPDATE fase_producao SET etapa_posterior=6 WHERE id = 5');
		$this->db->query('UPDATE fase_producao SET etapa_posterior=7 WHERE id = 6');
	}
}

