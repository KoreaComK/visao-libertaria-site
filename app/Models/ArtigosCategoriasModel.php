<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtigosCategoriasModel extends Model
{
	protected $DBGroup = 'default';
	protected $table = 'artigos_categorias';
	protected $primaryKey = 'artigos_id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $useSoftDeletes = false;
	protected $protectFields = false;
	protected $allowedFields = [];

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

	public function getCategoriasArtigo($id)
	{
		$query = $this->db->query("
		SELECT
			A.nome,
			A.id
		FROM
			artigos_categorias
		INNER JOIN categorias A ON artigos_categorias.categorias_id = A.id
		WHERE artigos_id = '$id'");
		return $query->getResult('array');
	}

	public function insertArtigoCategoria($artigoId, $categoriaId)
	{
		$query = $this->db->query("
		INSERT INTO
			artigos_categorias
			(artigos_id,categorias_id)
		VALUES
			(" . $this->db->escape($artigoId) . "," . $this->db->escape($categoriaId) . ")
		");
		return $query;
	}

	public function deleteArtigoCategoria($artigoId)
	{
		$query = $this->db->query("
		DELETE FROM 
			artigos_categorias
		WHERE
			artigos_id = '$artigoId' ");
		return $query;
	}
}