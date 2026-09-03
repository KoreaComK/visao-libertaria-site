<?php

namespace App\Models;

use CodeIgniter\Model;

class ColaboradoresRemuneracoesModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'colaboradores_remuneracoes';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = false;
	protected $protectFields    = false;

	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'criado';
	protected $updatedField  = 'atualizado';
}
