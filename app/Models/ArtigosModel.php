<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtigosModel extends Model
{
	protected $DBGroup = 'default';
	protected $table = 'artigos';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $useSoftDeletes = true;
	protected $protectFields = false;
	protected $allowedFields = [];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat = 'datetime';
	protected $createdField = 'criado';
	protected $updatedField = 'atualizado';
	protected $deletedField = 'descartado';

	// Validation
	// protected $validationRules      = [];
	// protected $validationMessages   = [];
	// protected $skipValidation       = false;
	// protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks = true;
	// protected $beforeInsert   = [];
	protected $afterInsert    = ['cadastraHistoricoUsuarioInserir'];
	// protected $beforeUpdate   = [];
	protected $afterUpdate    = ['cadastraHistoricoUsuarioAlterar'];
	// protected $beforeFind     = [];
	// protected $afterFind      = [];
	// protected $beforeDelete   = [];
	protected $afterDelete    = ['cadastraHistoricoUsuarioExcluir'];

	public function getArtigosHome($limit = 21){
		$this->whereIn('artigos.fase_producao_id', array(6,7));
		$this->orderBy('artigos.criado', 'DESC');
		$this->limit($limit);
		return $this->get()->getResultArray();
	}

	public function getArtigosHomeRand($limit = 3){
		$this->whereIn('artigos.fase_producao_id', array(6,7));
		$this->orderBy('RAND()');
		$this->limit($limit);
		return $this->get()->getResultArray();
	}

	public function getColaboradoresArtigo($artigoId)
	{
		$query = $this->db->query("
		SELECT
		A.apelido AS sugerido,
		B.apelido AS escrito,
		C.apelido AS revisado,
		D.apelido AS narrado,
		E.apelido AS produzido,
		F.apelido AS publicado,
		G.apelido AS descartado
		FROM artigos
		LEFT JOIN colaboradores A ON artigos.sugerido_colaboradores_id = A.id
		LEFT JOIN colaboradores B ON artigos.escrito_colaboradores_id = B.id
		LEFT JOIN colaboradores C ON artigos.revisado_colaboradores_id = C.id
		LEFT JOIN colaboradores D ON artigos.narrado_colaboradores_id = D.id
		LEFT JOIN colaboradores E ON artigos.produzido_colaboradores_id = E.id
		LEFT JOIN colaboradores F ON artigos.publicado_colaboradores_id = F.id
		LEFT JOIN colaboradores G ON artigos.descartado_colaboradores_id = G.id
		WHERE artigos.id = '$artigoId'");
		return $query->getResult('array');
	}

	public function getArtigosColaboradores($colaboradorId, $data = null, $fases = array())
	{
		$where = ' ';
		if ($data !== null) {
			$where .= ' AND DATE_FORMAT(artigos.publicado,"%m-%Y") = DATE_FORMAT("' . $data . '","%m-%Y") ';
		}

		if (!empty($fases)) {
			$fases = implode(',', $fases);
			$where .= " AND artigos.fase_producao_id IN ($fases)";
		}

		$query = $this->db->query("
		SELECT
		artigos.titulo AS titulo,
		artigos.url_friendly AS url_friendly,
		artigos.palavras_escritor AS pontos_escritor,
		artigos.palavras_narrador AS pontos_narrador,
		artigos.palavras_revisor AS pontos_revisor,
		artigos.palavras_produtor AS pontos_produtor,
		B.id AS escrito,
		C.id AS revisado,
		D.id AS narrado,
		E.id AS produzido
		FROM artigos
		LEFT JOIN colaboradores B ON artigos.escrito_colaboradores_id = B.id
		LEFT JOIN colaboradores C ON artigos.revisado_colaboradores_id = C.id
		LEFT JOIN colaboradores D ON artigos.narrado_colaboradores_id = D.id
		LEFT JOIN colaboradores E ON artigos.produzido_colaboradores_id = E.id
		WHERE artigos.descartado is null AND (B.id = '$colaboradorId' OR C.id = '$colaboradorId' OR D.id = '$colaboradorId' OR E.id = '$colaboradorId') $where");

		return $query->getResult('array');
	}

	public function getQuantidadeColaboracoesArtigosEscritos($id = null, $data = null, $fases = array())
	{
		$fases = implode(',', $fases);

		$where = ' ';
		if ($id !== null) {
			$where .= ' AND colaboradores.id = ' . $id;
		}

		if ($data !== null) {
			$where .= ' AND DATE_FORMAT(publicado,"%m-%Y") = DATE_FORMAT("' . $data . '","%m-%Y") ';
		}

		$resultado_colaboracoes = array();
		$resultado_pontuacoes = array();


		$query = $this->db->query("
		SELECT
			COALESCE(count(1),0) AS total,
			COALESCE(SUM(palavras_escritor),0) AS pontos
		FROM 
			artigos
		INNER JOIN 
			colaboradores 
		ON
			artigos.escrito_colaboradores_id = colaboradores.id
		WHERE
			artigos.descartado is null AND
			artigos.fase_producao_id IN ($fases)
		" . $where);

		$resultado_colaboracoes[] = $query->getResult('array')[0]['total'];
		$resultado_pontuacoes[] = $query->getResult('array')[0]['pontos'];

		$query = $this->db->query("
		SELECT
			COALESCE(count(1),0) AS total,
			COALESCE(SUM(palavras_revisor),0) AS pontos
		FROM 
			artigos
		INNER JOIN 
			colaboradores 
		ON
			artigos.revisado_colaboradores_id = colaboradores.id
		WHERE
			artigos.descartado is null AND
			artigos.fase_producao_id IN ($fases)
		" . $where);

		$resultado_colaboracoes[] = $query->getResult('array')[0]['total'];
		$resultado_pontuacoes[] = $query->getResult('array')[0]['pontos'];

		$query = $this->db->query("
		SELECT
			COALESCE(count(1),0) AS total,
			COALESCE(SUM(palavras_narrador),0) AS pontos
		FROM 
			artigos
		INNER JOIN 
			colaboradores 
		ON
			artigos.narrado_colaboradores_id = colaboradores.id
		WHERE
			artigos.descartado is null AND
			artigos.fase_producao_id IN ($fases)
		" . $where);

		$resultado_colaboracoes[] = $query->getResult('array')[0]['total'];
		$resultado_pontuacoes[] = $query->getResult('array')[0]['pontos'];

		$query = $this->db->query("
		SELECT
			COALESCE(count(1),0) AS total,
			COALESCE(SUM(palavras_produtor),0) AS pontos
		FROM 
			artigos
		INNER JOIN 
			colaboradores 
		ON
			artigos.produzido_colaboradores_id = colaboradores.id
		WHERE
			artigos.descartado is null AND
			artigos.fase_producao_id IN ($fases)
		" . $where);

		$resultado_colaboracoes[] = $query->getResult('array')[0]['total'];
		$resultado_pontuacoes[] = $query->getResult('array')[0]['pontos'];

		$total_colaboracoes = 0;
		$total_pontos = 0;

		foreach ($resultado_colaboracoes as $rc) {
			$total_colaboracoes += $rc;
		}
		foreach ($resultado_pontuacoes as $rp) {
			$total_pontos += $rp;
		}

		$retorno = [
			'pontos' => $total_pontos,
			'colaboracoes' => $total_colaboracoes
		];

		return $retorno;
	}

	public function getArtigosUsuario($usuariosId)
	{
		$this
			->select('
				artigos.*,
				A.apelido AS sugerido,
				B.apelido AS escrito,
				C.apelido AS revisado,
				D.apelido AS narrado,
				E.apelido AS produzido,
				F.apelido AS publicado,
				G.apelido AS descartado
			')
			->join('colaboradores A', 'artigos.sugerido_colaboradores_id = A.id', 'LEFT')
			->join('colaboradores B', 'artigos.escrito_colaboradores_id = B.id', 'LEFT')
			->join('colaboradores C', 'artigos.revisado_colaboradores_id = C.id', 'LEFT')
			->join('colaboradores D', 'artigos.narrado_colaboradores_id = D.id', 'LEFT')
			->join('colaboradores E', 'artigos.produzido_colaboradores_id = E.id', 'LEFT')
			->join('colaboradores F', 'artigos.publicado_colaboradores_id = F.id', 'LEFT')
			->join('colaboradores G', 'artigos.descartado_colaboradores_id = G.id', 'LEFT')
			->where('artigos.escrito_colaboradores_id', $usuariosId)
			->orderBy('artigos.criado', 'DESC');
		$this->withDeleted();
		return $this;
	}

	public function getArtigos($id)
	{
		$this
			->select('
				artigos.*,
				A.apelido AS sugerido,
				B.apelido AS escrito,
				C.apelido AS revisado,
				D.apelido AS narrado,
				E.apelido AS produzido,
				F.apelido AS publicado,
				G.apelido AS descartado,
				H.apelido AS marcado
			')
			->join('colaboradores A', 'artigos.sugerido_colaboradores_id = A.id', 'LEFT')
			->join('colaboradores B', 'artigos.escrito_colaboradores_id = B.id', 'LEFT')
			->join('colaboradores C', 'artigos.revisado_colaboradores_id = C.id', 'LEFT')
			->join('colaboradores D', 'artigos.narrado_colaboradores_id = D.id', 'LEFT')
			->join('colaboradores E', 'artigos.produzido_colaboradores_id = E.id', 'LEFT')
			->join('colaboradores F', 'artigos.publicado_colaboradores_id = F.id', 'LEFT')
			->join('colaboradores G', 'artigos.descartado_colaboradores_id = G.id', 'LEFT')
			->join('colaboradores H', 'artigos.marcado_colaboradores_id = H.id', 'LEFT')
			
			->orderBy('artigos.atualizado', 'ASC');
		if (!is_array($id)) {
			$this->where('artigos.fase_producao_id', $id);
		} else {
			$this->whereIn('artigos.id', $id);
		}
		return $this;
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
			'objeto' => 'artigos',
			'objeto_id' => $dados_inseridos['id'],
			'criado' => $colaboradoresHistoricosModel->getNow()
		];
		$colaboradoresHistoricosModel->insert($inserirArray);
		return $dados_inseridos;
	}
}