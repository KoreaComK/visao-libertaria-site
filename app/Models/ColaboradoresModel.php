<?php

namespace App\Models;

use CodeIgniter\Model;

class ColaboradoresModel extends Model
{
	protected $DBGroup = 'default';
	protected $table = 'colaboradores';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = true;
	protected $returnType = 'array';
	protected $useSoftDeletes = false;
	protected $protectFields = false;
	protected $allowedFields = [];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat = 'datetime';
	protected $createdField = 'criado';
	protected $updatedField = 'atualizado';
	protected $deletedField = 'excluido';

	// // Validation
	// protected $validationRules = [];
	// protected $validationMessages = [];
	// protected $skipValidation = false;
	// protected $cleanValidationRules = true;

	// // Callbacks
	protected $allowCallbacks = true;
	// protected $beforeInsert = [];
	// protected $afterInsert = [];
	// protected $beforeUpdate = [];
	protected $afterUpdate = ['cadastraHistoricoUsuarioAlterar'];
	// protected $beforeFind = [];
	// protected $afterFind = [];
	// protected $beforeDelete = [];
	protected $afterDelete = ['cadastraHistoricoUsuarioExcluir'];

	public function getColaboradores($email, $senha)
	{
		$query = $this->db->query("SELECT * FROM colaboradores WHERE email = '" . $this->db->escapeLikeString($email) . "' AND senha = '" . $this->db->escapeLikeString($senha) . "'");
		return $query->getResult('array');
	}

	public function getColaboradorPeloHash($hash = false)
	{
		if ($hash === false) {
			return false;
		}
		$query = $this->db->query("SELECT * FROM colaboradores WHERE confirmacao_hash = '" . $this->db->escapeLikeString($hash) . "'");
		if(!isset($query->getResult('array')[0]))
		{
			return array();
		}
		return $query->getResult('array')[0];
	}

	public function getColaboradorPeloEmail($email)
	{
		$query = $this->db->query("SELECT * FROM colaboradores WHERE email = '" . $this->db->escapeLikeString($email) . "'");
		if(!isset($query->getResult('array')[0]))
		{
			return array();
		}
		return $query->getResult('array')[0];
	}

	public function getNow()
	{
		$query = $this->db->query("SELECT now() AS now");
		return $query->getResult('array')[0]['now'];
	}

	public function getTodosColaboradores($apelido = '', $email = '', $atribuicao = '', $status = 'A')
	{
		$this->select('*');
		$this->join('colaboradores_atribuicoes','colaboradores_atribuicoes.colaboradores_id = colaboradores.id');
		if($apelido !== ''){
			$this->like('apelido', $this->db->escapeLikeString($apelido));
		}
		if($email !== ''){
			$this->like('email', $this->db->escapeLikeString($email));
		}
		if($atribuicao != ''){
			$this->where('colaboradores_atribuicoes.atribuicoes_id',$atribuicao);
		}
		if($status != 'A'){
			$this->onlyDeleted();
		} else {
			$this->where('colaboradores.excluido',NULL);
		}
		$this->groupBy('colaboradores.id');
		$this->orderBy('apelido','ASC');
		return $this;
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
			'objeto' => 'colaboradores',
			'objeto_id' => $dados_inseridos['id'],
			'criado' => $colaboradoresHistoricosModel->getNow()
		];
		$colaboradoresHistoricosModel->insert($inserirArray);
		return $dados_inseridos;
	}
}