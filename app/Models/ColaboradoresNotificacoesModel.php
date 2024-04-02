<?php

namespace App\Models;

use CodeIgniter\Model;

class ColaboradoresNotificacoesModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'colaboradores_notificacoes';
	protected $returnType       = 'array';
	protected $protectFields    = false;
	protected $allowCallbacks = true;
	public function getNovaUUID()
	{
		$query = $this->db->query("SELECT uuid() AS id");
		return $query->getResult('array')[0]['id'];
	}

	public function getNow()
	{
		$query = $this->db->query("SELECT now() AS now");
		return $query->getResult('array')[0]['now'];
	}
}
