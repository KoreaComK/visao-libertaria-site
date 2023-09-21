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

	public function getPautasFechadas($excluido = false)
	{
		$this->builder();
		if ($excluido === true) {
			$this->withDeleted();
		}
		$this->builder()->orderBy('criado', 'DESC');
		return $this;
	}
}
