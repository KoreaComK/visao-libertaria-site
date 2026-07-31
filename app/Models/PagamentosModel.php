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

	/**
	 * Pagamentos em que o colaborador participou, com a soma dos pontos dele no lote.
	 */
	public function getPagamentosColaborador(int $colaboradorId): array
	{
		$colaboradorId = (int) $colaboradorId;
		$pontosExpr = "
			SUM(
				IF(a.escrito_colaboradores_id = {$colaboradorId}, a.palavras_escritor * p.multiplicador_escrito / 100, 0)
				+ IF(a.revisado_colaboradores_id = {$colaboradorId}, a.palavras_revisor * p.multiplicador_revisado / 100, 0)
				+ IF(a.narrado_colaboradores_id = {$colaboradorId}, a.palavras_narrador * p.multiplicador_narrado / 100, 0)
				+ IF(a.produzido_colaboradores_id = {$colaboradorId}, a.palavras_produtor * p.multiplicador_produzido / 100, 0)
			)
		";

		return $this->db->table('pagamentos p')
			->select('p.*')
			->select("{$pontosExpr} AS pontos_colaborador", false)
			->join('pagamentos_artigos pa', 'pa.pagamentos_id = p.id')
			->join('artigos a', 'a.id = pa.artigos_id')
			->groupStart()
				->where('a.escrito_colaboradores_id', $colaboradorId)
				->orWhere('a.revisado_colaboradores_id', $colaboradorId)
				->orWhere('a.narrado_colaboradores_id', $colaboradorId)
				->orWhere('a.produzido_colaboradores_id', $colaboradorId)
			->groupEnd()
			->groupBy('p.id')
			->orderBy('p.atualizado', 'DESC')
			->get()
			->getResultArray();
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
