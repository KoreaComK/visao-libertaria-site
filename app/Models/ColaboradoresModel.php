<?php

namespace App\Models;

use CodeIgniter\Model;

class ColaboradoresModel extends Model
{
	protected $DBGroup = 'default';
	protected $table = 'colaboradores';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = true;
	protected $returnType = 'array';
	protected $useSoftDeletes = false;
	protected $protectFields = false;
	protected $allowedFields = [];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat = 'datetime';
	protected $createdField = 'criado';
	protected $updatedField = 'atualizado';
	protected $deletedField = 'excluido';

	// // Validation
	// protected $validationRules = [];
	// protected $validationMessages = [];
	// protected $skipValidation = false;
	// protected $cleanValidationRules = true;

	// // Callbacks
	// protected $allowCallbacks = true;
	// protected $beforeInsert = [];
	// protected $afterInsert = [];
	// protected $beforeUpdate = [];
	// protected $afterUpdate = [];
	// protected $beforeFind = [];
	// protected $afterFind = [];
	// protected $beforeDelete = [];
	// protected $afterDelete = [];

	public function getColaboradores($email, $senha)
	{
		$query = $this->db->query("SELECT * FROM colaboradores WHERE email = '" . $this->db->escapeLikeString($email) . "' AND senha = '" . $this->db->escapeLikeString($senha) . "'");
		return $query->getResult('array');
	}

	public function getColaboradorPeloHash($hash = false)
	{
		if ($hash === false) {
			return false;
		}
		$query = $this->db->query("SELECT * FROM colaboradores WHERE confirmacao_hash = '" . $this->db->escapeLikeString($hash) . "'");
		if(!isset($query->getResult('array')[0]))
		{
			return array();
		}
		return $query->getResult('array')[0];
	}

	public function getColaboradorPeloEmail($email)
	{
		$query = $this->db->query("SELECT * FROM colaboradores WHERE email = '" . $this->db->escapeLikeString($email) . "'");
		if(!isset($query->getResult('array')[0]))
		{
			return array();
		}
		return $query->getResult('array')[0];
	}

	public function getNow()
	{
		$query = $this->db->query("SELECT now() AS now");
		return $query->getResult('array')[0]['now'];
	}

	public function getTodosColaboradores($apelido = '', $email = '', $atribuicao = '', $status = 'A')
	{
		$this->select('*');
		$this->join('colaboradores_atribuicoes','colaboradores_atribuicoes.colaboradores_id = colaboradores.id');
		if($apelido !== ''){
			$this->like('apelido', $this->db->escapeLikeString($apelido));
		}
		if($email !== ''){
			$this->like('email', $this->db->escapeLikeString($email));
		}
		if($atribuicao != ''){
			$this->where('colaboradores_atribuicoes.atribuicoes_id',$atribuicao);
		}
		if($status != 'A'){
			$this->onlyDeleted();
		} else {
			$this->where('colaboradores.excluido',NULL);
		}
		$this->groupBy('colaboradores.id');
		$this->orderBy('apelido','ASC');
		return $this;
	}
}