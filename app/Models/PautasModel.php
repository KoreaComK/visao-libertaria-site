<?php

namespace App\Models;

use CodeIgniter\Model;

class PautasModel extends Model
{
	protected $DBGroup = 'default';
	protected $table = 'pautas';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $useSoftDeletes = true;
	protected $protectFields = false;
	protected $allowedFields = [];

	// // Dates
	// protected $useTimestamps = false;
	protected $dateFormat = 'datetime';
	protected $createdField = 'criado';
	protected $updatedField = 'atualizado';
	protected $deletedField = 'excluido';

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

	public function isPautaCadastrada($link)
	{
		$query = $this->db->query("SELECT count(1) as contador FROM pautas WHERE link = '" . $this->db->escapeString($link) . "'");
		return $query->getResult('array')[0]['contador'];
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

	public function getPautasFechamento()
	{
		$query = $this->db->query("SELECT * FROM pautas WHERE reservado IS NOT NULL AND excluido IS NULL ORDER BY tag_fechamento ASC");
		return $query->getResult('array');
	}

	public function getPautas($reservado = false, $excluido = false)
	{
		$this->builder()
			->select('pautas.*, colaboradores.apelido AS apelido')
			->join('colaboradores', 'pautas.colaboradores_id = colaboradores.id');
		if ($reservado === false) {
			$this->builder()->where('pautas.reservado IS NULL');
		}
		if ($excluido === true) {
			$this->withDeleted();
		}
		$this->builder()->orderBy('pautas.criado', 'DESC');
		return $this;
	}
}