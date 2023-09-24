<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtigosComentariosModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'artigos_comentarios';
	protected $primaryKey       = 'id';
	// protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = true;
	protected $protectFields    = false;
	// protected $allowedFields    = [];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'criado';
	protected $updatedField  = 'atualizado';
	protected $deletedField  = 'excluido';

	// Validation
	// protected $validationRules      = [];
	// protected $validationMessages   = [];
	// protected $skipValidation       = false;
	// protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks = true;
	// protected $beforeInsert   = [];
	protected $afterInsert = ['cadastraHistoricoUsuarioInserir'];
	// protected $beforeUpdate   = [];
	 protected $afterUpdate = ['cadastraHistoricoUsuarioAlterar'];
	// protected $beforeFind     = [];
	// protected $afterFind      = [];
	protected $beforeDelete   = ['cadastraHistoricoUsuarioExcluir'];

	public function getComentarios($idArtigo)
	{
		$query = $this->db->query("
		SELECT
			A.*,
			B.apelido AS apelido,
			B.avatar AS avatar
		FROM
			artigos_comentarios A
		INNER JOIN 
			colaboradores B
		ON 
			A.colaboradores_id = B.id
		WHERE
			A.excluido IS NULL
		AND
			A.artigos_id = '$idArtigo'
		ORDER BY
			A.criado DESC
		");
		return $query->getResult('array');
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

	protected function cadastraHistoricoUsuarioInserir(array $dados) {
		return $this->cadastraHistoricoUsuario($dados, 'inserir');
	}

	protected function cadastraHistoricoUsuarioAlterar(array $dados) {
		return $this->cadastraHistoricoUsuario($dados, 'alterar');
	}

	protected function cadastraHistoricoUsuarioExcluir(array $dados) {
		return $this->cadastraHistoricoUsuario($dados, 'excluir');
	}

	private function cadastraHistoricoUsuario(array $dados, $acao)
	{	
		$colaboradoresHistoricosModel = new \App\Models\ColaboradoresHistoricosModel();
		$this->session = \Config\Services::session();
		$this->session->start();
		
		if(isset($dados['data'])) {
			$dados_inseridos = $dados['data'];
		} else {
			$dados_inseridos = $dados;
		}
		
		if(!isset($dados_inseridos['id']) && isset($dados['id'])) {
			$dados_inseridos['id'] = $dados['id'][0];
		}

		$dados_inseridos['colaboradores_id'] = $this->session->get('colaboradores')['id'];

		if (!isset($dados_inseridos['colaboradores_id'])) {
			return $dados;
		}

		$inserirArray = [
			'id' => $colaboradoresHistoricosModel->getNovaUUID(),
			'colaboradores_id' => $dados_inseridos['colaboradores_id'],
			'acao' => $acao,
			'objeto' => 'artigos_comentarios',
			'objeto_id' => $dados_inseridos['id'],
			'criado' => $colaboradoresHistoricosModel->getNow()
		];
		$colaboradoresHistoricosModel->insert($inserirArray);
		return $dados_inseridos;
	}
}
