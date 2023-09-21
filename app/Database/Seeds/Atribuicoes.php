<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Atribuicoes extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');
		$this->db->table('atribuicoes')->insert(array('nome'=>'Colaborador','cor'=>'primary'));
		$this->db->table('atribuicoes')->insert(array('nome'=>'Escritor','cor'=>'secondary'));
		$this->db->table('atribuicoes')->insert(array('nome'=>'Revisor','cor'=>'success'));
		$this->db->table('atribuicoes')->insert(array('nome'=>'Narrador','cor'=>'danger'));
		$this->db->table('atribuicoes')->insert(array('nome'=>'Produtor','cor'=>'warning'));
		$this->db->table('atribuicoes')->insert(array('nome'=>'Publicador','cor'=>'info'));
		$this->db->table('atribuicoes')->insert(array('nome'=>'Administrador','cor'=>'light'));
		$this->db->table('atribuicoes')->insert(array('nome'=>'Pagador','cor'=>'dark'));
		$this->db->table('atribuicoes')->insert(array('nome'=>'Recrutador','cor'=>'white'));
		$this->db->table('atribuicoes')->insert(array('nome'=>'Pautador','cor'=>'info'));
	}
}
