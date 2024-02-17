<?php

namespace App\Models;

use CodeIgniter\Model;

class PautasPautasFechadasModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'pautas_pautas_fechadas';
	//protected $primaryKey       = '';
	// protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = false;
	protected $protectFields    = false;
	// protected $allowedFields    = [];

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

	public function getPautasPorPautaFechada($idPautaFechada)
	{
		$query = $this->db->query("SELECT pautas.*, colaboradores.apelido AS apelido, 
		(select count(1) from pautas_comentarios where pautas.id = pautas_comentarios.pautas_id and pautas_comentarios.excluido is null) as qtde_comentarios
		FROM pautas INNER JOIN pautas_pautas_fechadas ON pautas.id = pautas_pautas_fechadas.pautas_id
		INNER JOIN colaboradores ON colaboradores.id = pautas.colaboradores_id WHERE pautas_pautas_fechadas.pautas_fechadas_id = ".$this->db->escapeString($idPautaFechada)."
		ORDER BY pautas.tag_fechamento ASC
		");
		return $query->getResult('array');
	}
}
