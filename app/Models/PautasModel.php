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

	// Callbacks
	protected $allowCallbacks = true;
	// protected $beforeInsert   = [];
	protected $afterInsert = ['cadastraHistoricoUsuarioInserir'];
	// protected $beforeUpdate   = [];
	protected $afterUpdate = ['cadastraHistoricoUsuarioAlterar'];
	// protected $beforeFind     = [];
	// protected $afterFind      = [];
	// protected $beforeDelete   = [];
	// protected $afterDelete = ['cadastraHistoricoUsuarioExcluir'];

	public function isPautaCadastrada($link, $id = null)
	{
		$where = '';
		if ($id != null) {
			$where = " AND id <> '$id'";
		}
		$query = $this->db->query("SELECT count(1) as contador FROM pautas WHERE link = '" . $this->db->escapeString($link) . "' $where");
		return $query->getResultArray()[0]['contador'];
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

	public function getPautasPesquisa($pesquisa = NULL)
	{
		$this->builder()
			->select('pautas.*, colaboradores.apelido AS apelido, pautas_fechadas.titulo AS nome_pauta_fechada')
			->join('colaboradores', 'pautas.colaboradores_id = colaboradores.id')
			->join('pautas_pautas_fechadas','pautas.id = pautas_pautas_fechadas.pautas_id','LEFT')
			->join('pautas_fechadas','pautas_fechadas.id = pautas_pautas_fechadas.pautas_fechadas_id','LEFT');
		$this->builder()->orderBy('pautas.criado', 'DESC');
		if($pesquisa !== NULL) {
			$this->builder()->where("(pautas.link like '%$pesquisa%' or pautas.titulo like '%$pesquisa%' or pautas.texto like '%$pesquisa%')");
			return $this->withDeleted();
		}
		return $this;
		
	}

	public function getPautasPorUsuario($data, $usuario)
	{
		$query = $this->db->query("SELECT count(1) as contador FROM pautas WHERE colaboradores_id = $usuario AND criado >= '$data'");
		return $query->getResult('array');
	}

	protected function cadastraHistoricoUsuarioInserir(array $dados)
	{
		return $this->cadastraHistoricoUsuario($dados, 'inserir');
	}

	protected function cadastraHistoricoUsuarioAlterar(array $dados)
	{
		return $this->cadastraHistoricoUsuario($dados, 'alterar');
	}

	protected function cadastraHistoricoUsuarioExcluir(array $dados)
	{
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
			'objeto' => 'pautas',
			'objeto_id' => $dados_inseridos['id'],
			'criado' => $colaboradoresHistoricosModel->getNow()
		];
		$colaboradoresHistoricosModel->insert($inserirArray);
		return $dados_inseridos;
	}
}