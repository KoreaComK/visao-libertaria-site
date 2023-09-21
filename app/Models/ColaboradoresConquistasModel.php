<?php

namespace App\Models;

use CodeIgniter\Model;

class ColaboradoresConquistasModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'colaboradores_conquistas';
	protected $primaryKey       = 'id';
	// protected $useAutoIncrement = true;
	// protected $returnType       = 'array';
	// protected $useSoftDeletes   = false;
	// protected $protectFields    = true;
	// protected $allowedFields    = [];

	// // Dates
	// protected $useTimestamps = false;
	// protected $dateFormat    = 'datetime';
	// protected $createdField  = 'created_at';
	// protected $updatedField  = 'updated_at';
	// protected $deletedField  = 'deleted_at';

	// // Validation
	// protected $validationRules      = [];
	// protected $validationMessages   = [];
	// protected $skipValidation       = false;
	// protected $cleanValidationRules = true;

	// // Callbacks
	// protected $allowCallbacks = true;
	// protected $beforeInsert   = [];
	// protected $afterInsert    = [];
	// protected $beforeUpdate   = [];
	// protected $afterUpdate    = [];
	// protected $beforeFind     = [];
	// protected $afterFind      = [];
	// protected $beforeDelete   = [];
	// protected $afterDelete    = [];
	
	public function getNomeConquistasColaborador($id = null)
	{   if($id == null)
		{  return null;  }
		$query = $this->db->query("
			SELECT 
				conquistas.nome AS nome,
				conquistas.descricao AS descricao,
				conquistas.imagem AS imagem
			FROM 
				colaboradores_conquistas
			INNER JOIN
				conquistas
			ON
				colaboradores_conquistas.conquistas_id = conquistas.id
			WHERE
				colaboradores_id = $id");
		return $query->getResult('array');
	}
}
