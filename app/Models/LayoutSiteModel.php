<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class LayoutSiteModel{
	protected $db;

	public function __construct(ConnectionInterface &$db){
		$this->db =& $db;
	}

	function widgetEsteiraProducao(){
		$builder = $this->db->table('fase_producao')->where('mostrar_site','S');
		$builder->join('artigos','artigos.fase_producao_id = fase_producao.id');
		$builder->groupBy('fase_producao_id');
		$builder->select('count(1) AS quantidade, fase_producao.nome AS nome');
		$fase_producao = $builder->get()->getResult();
		return $fase_producao;
	}
}
