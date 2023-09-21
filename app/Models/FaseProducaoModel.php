<?php

namespace App\Models;

use CodeIgniter\Model;

class FaseProducaoModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'fase_producao';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = false;
	protected $protectFields    = true;
	protected $allowedFields    = ['nome','etapa_anterior','etapa_posterior','mostrar_site'];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	//protected $createdField  = 'created_at';
	//protected $updatedField  = 'updated_at';
	//protected $deletedField  = 'deleted_at';

	// Validation
	//protected $validationRules      = [];
	//protected $validationMessages   = [];
	//protected $skipValidation       = false;
	//protected $cleanValidationRules = true;

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
}
