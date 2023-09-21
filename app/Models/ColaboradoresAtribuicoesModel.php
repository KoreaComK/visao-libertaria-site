<?php

namespace App\Models;

use CodeIgniter\Model;

class ColaboradoresAtribuicoesModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'colaboradores_atribuicoes';
	// protected $primaryKey       = 'id';
	// protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	// protected $useSoftDeletes   = false;
	protected $protectFields    = false;
	// protected $allowedFields    = [];

	// Dates
	// protected $useTimestamps = false;
	// protected $dateFormat    = 'datetime';
	// protected $createdField  = 'created_at';
	// protected $updatedField  = 'updated_at';
	// protected $deletedField  = 'deleted_at';

	// Validation
	// protected $validationRules      = [];
	// protected $validationMessages   = [];
	// protected $skipValidation       = false;
	// protected $cleanValidationRules = true;

	// Callbacks
	// protected $allowCallbacks = true;
	// protected $beforeInsert   = [];
	// protected $afterInsert    = [];
	// protected $beforeUpdate   = [];
	// protected $afterUpdate    = [];
	// protected $beforeFind     = [];
	// protected $afterFind      = [];
	// protected $beforeDelete   = [];
	// protected $afterDelete    = [];

	public function getAtribuicoesColaborador($id = null)
	{
		if ($id == null) {
			return null;
		}
		$query = $this->db->query("SELECT * FROM colaboradores_atribuicoes WHERE colaboradores_id = $id");
		return $query->getResult('array');
	}
	public function getNomeAtribuicoesColaborador($id = null)
	{
		if ($id == null) {
			return null;
		}
		$query = $this->db->query("
			SELECT 
				atribuicoes.id AS id,
				atribuicoes.nome AS nome,
				atribuicoes.cor AS cor
			FROM 
				colaboradores_atribuicoes 
			INNER JOIN
				atribuicoes
			ON
				colaboradores_atribuicoes.atribuicoes_id = atribuicoes.id
			WHERE
				colaboradores_id = $id");
		return $query->getResult('array');
	}

	public function deletarAtribuicoesColaborador($idColaborador)
	{
		$query = $this->db->query("DELETE FROM colaboradores_atribuicoes WHERE colaboradores_id = $idColaborador");
		return $query;
	}
}
