<?php

namespace App\Models;

use CodeIgniter\Model;

class ContatosAssuntosModel extends Model
{
	protected $DBGroup = 'default';
	protected $table = 'contatos_assuntos';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = true;
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


}