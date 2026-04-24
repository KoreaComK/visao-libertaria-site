<?php

namespace App\Models;

use CodeIgniter\Model;

class PautasFechadasModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'pautas_fechadas';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = true;
	protected $protectFields    = false;
	// protected $allowedFields    = [];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'criado';
	// protected $updatedField  = 'updated_at';
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

	/**
	 * Colunas da listagem admin com contagem de pautas e temas (tags) agregados.
	 *
	 * @return $this
	 */
	public function aplicarSelectListagemComAgregados()
	{
		return $this->select('pautas_fechadas.id, pautas_fechadas.titulo, pautas_fechadas.criado')
			->select('(SELECT COUNT(*) FROM pautas_pautas_fechadas WHERE pautas_pautas_fechadas.pautas_fechadas_id = pautas_fechadas.id) AS qtd_pautas', false)
			->select("(SELECT GROUP_CONCAT(DISTINCT TRIM(p.tag_fechamento) ORDER BY TRIM(p.tag_fechamento) SEPARATOR '|||') FROM pautas_pautas_fechadas ppf INNER JOIN pautas p ON p.id = ppf.pautas_id WHERE ppf.pautas_fechadas_id = pautas_fechadas.id AND TRIM(IFNULL(p.tag_fechamento,'')) <> '') AS temas_csv", false);
	}

	/**
	 * Pesquisa parcial no título do fechamento (nome).
	 *
	 * @return $this
	 */
	public function filtroTituloContem(?string $trecho)
	{
		if ($trecho !== null && $trecho !== '') {
			$this->like('titulo', $trecho);
		}

		return $this;
	}

	/**
	 * Evita título duplicado: se já existir o mesmo, usa " (1)", " (2)", …
	 * (ignora registros com soft delete).
	 */
	public function tituloUnicoParaInsercao(string $titulo): string
	{
		$titulo = trim($titulo);
		if ($titulo === '') {
			return $titulo;
		}
		$candidato = $titulo;
		$n = 1;
		while ($this->where('titulo', $candidato)->countAllResults() > 0) {
			$candidato = $titulo . ' (' . $n . ')';
			$n++;
		}

		return $candidato;
	}

	/**
	 * @return $this
	 */
	public function filtroTema(?string $tema)
	{
		if ($tema === null || $tema === '') {
			return $this;
		}
		$sub = $this->db->table('pautas_pautas_fechadas sub_ppf')
			->select('sub_ppf.pautas_fechadas_id')
			->join('pautas sub_p', 'sub_p.id = sub_ppf.pautas_id')
			->like('sub_p.tag_fechamento', $tema)
			->groupBy('sub_ppf.pautas_fechadas_id');
		$this->whereIn('pautas_fechadas.id', $sub, false);

		return $this;
	}

}
