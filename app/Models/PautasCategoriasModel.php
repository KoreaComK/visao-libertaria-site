<?php

namespace App\Models;

use CodeIgniter\Model;

class PautasCategoriasModel extends Model
{
	protected $DBGroup = 'default';
	protected $table = 'pautas_categorias';
	protected $primaryKey = 'pautas_id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $useSoftDeletes = false;
	protected $protectFields = false;
	protected $allowedFields = ['pautas_id', 'categorias_id'];

	public function getCategoriasPauta($id)
	{
		$query = $this->db->query("
		SELECT
			A.nome,
			A.id
		FROM
			pautas_categorias
		INNER JOIN categorias A ON pautas_categorias.categorias_id = A.id
		WHERE pautas_id = ?", [$id]);
		return $query->getResult('array');
	}

	public function insertPautaCategoria($pautaId, $categoriaId)
	{
		return $this->db->table($this->table)->insert([
			'pautas_id' => $pautaId,
			'categorias_id' => $categoriaId,
		]);
	}

	public function deletePautaCategoria($pautaId)
	{
		return $this->db->table($this->table)
			->where('pautas_id', $pautaId)
			->delete();
	}

	public function deletePorCategoria(int $categoriaId): bool
	{
		return $this->db->table($this->table)
			->where('categorias_id', $categoriaId)
			->delete() !== false;
	}
}
