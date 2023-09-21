<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtigosComentariosModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'artigos_comentarios';
	protected $primaryKey       = 'id';
	// protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = true;
	protected $protectFields    = false;
	// protected $allowedFields    = [];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'criado';
	protected $updatedField  = 'atualizado';
	protected $deletedField  = 'excluido';

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

	public function getComentarios($idArtigo)
	{
		$query = $this->db->query("
		SELECT
			A.*,
			B.apelido AS apelido,
			B.avatar AS avatar
		FROM
			artigos_comentarios A
		INNER JOIN 
			colaboradores B
		ON 
			A.colaboradores_id = B.id
		WHERE
			A.excluido IS NULL
		AND
			A.artigos_id = '$idArtigo'
		ORDER BY
			A.criado DESC
		");
		return $query->getResult('array');
	}

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
