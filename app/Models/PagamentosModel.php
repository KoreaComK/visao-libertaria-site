<?php

namespace App\Models;

use CodeIgniter\Model;

class PagamentosModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'pagamentos';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = false;
	protected $protectFields    = false;
	// protected $allowedFields    = [];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'criado';
	protected $updatedField  = 'atualizado';
	// protected $deletedField  = 'deleted_at';

	// Validation
	// protected $validationRules      = [];
	// protected $validationMessages   = [];
	// protected $skipValidation       = false;
	// protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks = true;
	// protected $beforeInsert   = [];
	//protected $afterInsert = ['cadastraHistoricoUsuarioInserir'];
	// protected $beforeUpdate   = [];
	// protected $afterUpdate = ['cadastraHistoricoUsuarioAlterar'];
	// protected $beforeFind     = [];
	// protected $afterFind      = [];
	// protected $beforeDelete   = [];

	public function getPagamentos($titulo = '', $quantidadeBitcoinMin = '', $quantidadeBitcoinMax = '', $hashTransacao = '')
	{
		$this->select("pagamentos.*, (SELECT COUNT(*) FROM pagamentos_artigos pa WHERE pa.pagamentos_id = pagamentos.id) AS total_artigos");
		if ($titulo !== '') {
			$this->like('titulo', $titulo);
		}
		if ($quantidadeBitcoinMin !== '') {
			$this->where('quantidade_bitcoin >=', (float) $quantidadeBitcoinMin);
		}
		if ($quantidadeBitcoinMax !== '') {
			$this->where('quantidade_bitcoin <=', (float) $quantidadeBitcoinMax);
		}
		if ($hashTransacao !== '') {
			$this->like('hash_transacao', $hashTransacao);
		}
		$this->builder()->orderBy('atualizado','DESC');
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
			'objeto' => 'pagamentos',
			'objeto_id' => $dados_inseridos['id'],
			'criado' => $colaboradoresHistoricosModel->getNow()
		];
		$colaboradoresHistoricosModel->insert($inserirArray);
		return $dados_inseridos;
	}
}
