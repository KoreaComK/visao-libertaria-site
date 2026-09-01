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

	/**
	 * Grava o vínculo; se a PK composta já existir (lote concorrente), segue sem erro.
	 */
	public function insertPautaCategoriaIgnorarDuplicata(string $pautaId, int $categoriaId): bool
	{
		try {
			$ok = $this->db->table($this->table)
				->ignore(true)
				->insert([
					'pautas_id' => $pautaId,
					'categorias_id' => $categoriaId,
				]);

			return $ok !== false;
		} catch (\Throwable $e) {
			if ($this->ehDuplicataChave($e)) {
				return true;
			}

			throw $e;
		}
	}

	private function ehDuplicataChave(\Throwable $e): bool
	{
		$codigo = (int) ($this->db->error()['code'] ?? 0);
		if ($codigo === 1062) {
			return true;
		}

		return str_contains(strtolower($e->getMessage()), 'duplicate');
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

	/**
	 * Remove vínculos de uma ou mais pautas (necessário antes de apagar a pauta de vez).
	 *
	 * @param list<mixed> $pautaIds
	 */
	public function deletePorPautas(array $pautaIds): bool
	{
		$ids = [];
		foreach ($pautaIds as $id) {
			$id = trim((string) $id);
			if ($id !== '') {
				$ids[] = $id;
			}
		}
		if ($ids === []) {
			return true;
		}

		return $this->db->table($this->table)
			->whereIn('pautas_id', $ids)
			->delete() !== false;
	}
}
