<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriasModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'categorias';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = true;
	protected $protectFields    = true;
	protected $allowedFields    = ['nome'];

	// Dates
	protected $useTimestamps = true;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'criado';
	protected $updatedField  = 'atualizado';
	protected $deletedField  = 'excluido';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks = true;
	protected $beforeInsert   = [];
	protected $afterInsert    = [];
	protected $beforeUpdate   = [];
	protected $afterUpdate    = [];
	protected $beforeFind     = [];
	protected $afterFind      = [];
	protected $beforeDelete   = [];
	protected $afterDelete    = [];

	/**
	 * Colunas da listagem admin com contagem de artigos vinculados.
	 *
	 * @return $this
	 */
	public function aplicarSelectListagemComAgregados()
	{
		return $this->select('categorias.id, categorias.nome, categorias.criado, categorias.atualizado, categorias.excluido')
			->select('(SELECT COUNT(*) FROM artigos_categorias WHERE artigos_categorias.categorias_id = categorias.id) AS qtd_artigos', false);
	}

	/**
	 * Pesquisa parcial no nome da categoria.
	 *
	 * @return $this
	 */
	public function filtroNomeContem(?string $trecho)
	{
		if ($trecho !== null && $trecho !== '') {
			$this->like('nome', $trecho);
		}

		return $this;
	}

	/**
	 * Filtra por situação: ativas, inativadas ou todas (inclui inativadas).
	 *
	 * @return $this
	 */
	public function filtroSituacao(?string $situacao)
	{
		if ($situacao === 'inativadas') {
			$this->onlyDeleted();
		} elseif ($situacao !== 'ativas') {
			$this->withDeleted();
		}

		return $this;
	}

	public function contarArtigosVinculados(int $categoriaId): int
	{
		return (int) $this->db->table('artigos_categorias')
			->where('categorias_id', $categoriaId)
			->countAllResults();
	}

	public function reativar(int $categoriaId): bool
	{
		return $this->db->table($this->table)
			->where('id', $categoriaId)
			->update([
				'excluido' => null,
				'atualizado' => date('Y-m-d H:i:s'),
			]) !== false;
	}

	/**
	 * Verifica duplicidade de nome entre categorias ativas e inativadas.
	 */
	public function nomeJaExiste(string $nome, ?int $excetoId = null): bool
	{
		$consulta = new self();
		$consulta->withDeleted()->where('nome', $nome);
		if ($excetoId !== null) {
			$consulta->where('id !=', $excetoId);
		}

		return $consulta->countAllResults() > 0;
	}
}
