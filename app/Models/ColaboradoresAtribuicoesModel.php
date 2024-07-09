<?php

namespace App\Models;

use CodeIgniter\Model;

class ColaboradoresAtribuicoesModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'colaboradores_atribuicoes';
	// protected $primaryKey       = 'id';
	// protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	// protected $useSoftDeletes   = false;
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
	protected $allowCallbacks = true;
	// protected $beforeInsert   = [];
	protected $afterInsert = ['cadastraHistoricoUsuarioInserir'];
	// protected $beforeUpdate   = [];
	// protected $afterUpdate = ['cadastraHistoricoUsuarioAlterar'];
	// protected $beforeFind     = [];
	// protected $afterFind      = [];
	// protected $beforeDelete   = [];
	protected $afterDelete = ['cadastraHistoricoUsuarioExcluir'];

	public function getAtribuicoesColaborador($id = null)
	{
		if ($id == null) {
			return null;
		}
		$query = $this->db->query("SELECT * FROM colaboradores_atribuicoes WHERE colaboradores_id = $id");
		return $query->getResult('array');
	}
	public function getNomeAtribuicoesColaborador($id = null,$mostraAdmin = true)
	{
		if ($id == null) {
			return null;
		}
		$sql = '';
		if($mostraAdmin === false) {
			$sql = ' AND atribuicoes.id NOT IN (7,8,9,10,11)';
		}
		$query = $this->db->query("
			SELECT 
				atribuicoes.id AS id,
				atribuicoes.nome AS nome,
				atribuicoes.cor AS cor
			FROM 
				colaboradores_atribuicoes 
			INNER JOIN
				atribuicoes
			ON
				colaboradores_atribuicoes.atribuicoes_id = atribuicoes.id
			WHERE
				colaboradores_id = $id $sql");
		return $query->getResult('array');
	}

	public function deletarAtribuicoesColaborador($idColaborador)
	{
		$query = $this->db->query("DELETE FROM colaboradores_atribuicoes WHERE colaboradores_id = $idColaborador");
		return $query;
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
		
		$dados_inseridos = $dados['data'];
		if(!isset($dados_inseridos['atribuicoes_id']) && isset($dados['atribuicoes_id'])) {
			$dados_inseridos['atribuicoes_id'] = $dados['atribuicoes_id'];
		}

		$dados_inseridos['colaboradores_id'] = $this->session->get('colaboradores')['id'];

		if (!isset($dados_inseridos['colaboradores_id'])) {
			return $dados;
		}

		$inserirArray = [
			'id' => $colaboradoresHistoricosModel->getNovaUUID(),
			'colaboradores_id' => $dados_inseridos['colaboradores_id'],
			'acao' => $acao,
			'objeto' => 'colaboradores_atribuicoes',
			'objeto_id' => $dados_inseridos['atribuicoes_id'],
			'criado' => $colaboradoresHistoricosModel->getNow()
		];
		$colaboradoresHistoricosModel->insert($inserirArray);
		return $dados_inseridos;
	}
}
