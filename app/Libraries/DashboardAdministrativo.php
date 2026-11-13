<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\I18n\Time;

/**
 * Números do painel administrativo: fila atual, publicação, colaboradores e contatos.
 */
class DashboardAdministrativo
{
	private BaseConnection $db;

	private string $ha7;

	private string $ha30;

	private string $ha60;

	public function __construct(?BaseConnection $db = null)
	{
		$this->db = $db ?? db_connect();
		$this->ha7 = (new Time('-7 days'))->toDateTimeString();
		$this->ha30 = (new Time('-30 days'))->toDateTimeString();
		$this->ha60 = (new Time('-60 days'))->toDateTimeString();
	}

	/**
	 * @param list<string>|array<int, string> $permissoes
	 * @return array<string, mixed>
	 */
	public function montar(array $permissoes): array
	{
		$permissoes = array_map('strval', $permissoes);
		$bloqueio = $this->bloqueioMarcacao();

		return [
			'fila' => $this->fila($bloqueio),
			'funil' => $this->funil(),
			'ritmo' => $this->ritmo(),
			'colaboradores' => $this->colaboradores(),
			'sugestoes' => $this->sugestoesNoticias(),
			'contatos' => $this->contatos(),
			'bloqueio' => $bloqueio['legivel'],
			'pode_colaboradores' => in_array('9', $permissoes, true),
			'pode_pautas' => in_array('10', $permissoes, true),
			'pode_financeiro' => in_array('8', $permissoes, true),
		];
	}

	/**
	 * @param array{limite: string, legivel: string} $bloqueio
	 * @return array<string, mixed>
	 */
	private function fila(array $bloqueio): array
	{
		$publicar = $this->faseResumo(5);
		$pagar = $this->db->table('artigos')
			->select('COUNT(*) AS total, COALESCE(SUM(palavras_escritor + palavras_revisor + palavras_narrador + palavras_produtor), 0) AS pontos', false)
			->where('fase_producao_id', 6)
			->where('descartado', null)
			->get()
			->getRowArray();
		$pagarAntigo = $this->artigoMaisAntigo(6);

		$travado = $this->db->table('artigos')
			->select('id, titulo, marcado')
			->where('descartado', null)
			->whereIn('fase_producao_id', [1, 2, 3, 4, 5])
			->where('marcado <', $bloqueio['limite'])
			->orderBy('marcado', 'ASC')
			->limit(1)
			->get()
			->getRowArray();
		$travadosTotal = $this->db->table('artigos')
			->where('descartado', null)
			->whereIn('fase_producao_id', [1, 2, 3, 4, 5])
			->where('marcado <', $bloqueio['limite'])
			->countAllResults();

		$pautas = $this->db->table('pautas')
			->where('excluido', null)
			->where('reservado !=', null)
			->countAllResults();

		return [
			'publicar' => $publicar,
			'pagar' => [
				'total' => (int) ($pagar['total'] ?? 0),
				'pontos' => (int) ($pagar['pontos'] ?? 0),
				'titulo' => $pagarAntigo['titulo'] ?? null,
				'desde' => $pagarAntigo['atualizado'] ?? null,
				'id' => $pagarAntigo['id'] ?? null,
			],
			'travados' => [
				'total' => $travadosTotal,
				'titulo' => $travado['titulo'] ?? null,
				'desde' => $travado['marcado'] ?? null,
				'id' => $travado['id'] ?? null,
			],
			'pautas' => $pautas,
		];
	}

	/**
	 * @return array{total: int, titulo: ?string, desde: ?string, id: ?string}
	 */
	private function faseResumo(int $fase): array
	{
		$total = $this->db->table('artigos')
			->where('fase_producao_id', $fase)
			->where('descartado', null)
			->countAllResults();
		$antigo = $this->artigoMaisAntigo($fase);

		return [
			'total' => $total,
			'titulo' => $antigo['titulo'] ?? null,
			'desde' => $antigo['atualizado'] ?? null,
			'id' => $antigo['id'] ?? null,
		];
	}

	/**
	 * @return array{id: string, titulo: string, atualizado: string}|null
	 */
	private function artigoMaisAntigo(int $fase): ?array
	{
		$row = $this->db->table('artigos')
			->select('id, titulo, atualizado')
			->where('fase_producao_id', $fase)
			->where('descartado', null)
			->orderBy('atualizado', 'ASC')
			->limit(1)
			->get()
			->getRowArray();

		return $row === null ? null : $row;
	}

	/**
	 * @return list<array{id: int, nome: string, icone: string, total: int, desde: ?string}>
	 */
	private function funil(): array
	{
		$fases = [
			1 => ['nome' => 'Escrevendo', 'icone' => 'far fa-file-lines'],
			2 => ['nome' => 'Revisando', 'icone' => 'fas fa-pen-to-square'],
			3 => ['nome' => 'Narrando', 'icone' => 'fas fa-microphone'],
			4 => ['nome' => 'Produzindo', 'icone' => 'fas fa-video'],
			5 => ['nome' => 'Publicando', 'icone' => 'fab fa-youtube'],
			6 => ['nome' => 'Pagando', 'icone' => 'fab fa-bitcoin'],
		];

		$rows = $this->db->table('artigos')
			->select('fase_producao_id, COUNT(*) AS total, MIN(atualizado) AS desde')
			->where('descartado', null)
			->whereIn('fase_producao_id', array_keys($fases))
			->groupBy('fase_producao_id')
			->get()
			->getResultArray();

		$porFase = [];
		foreach ($rows as $row) {
			$porFase[(int) $row['fase_producao_id']] = $row;
		}

		$funil = [];
		foreach ($fases as $id => $meta) {
			$funil[] = [
				'id' => $id,
				'nome' => $meta['nome'],
				'icone' => $meta['icone'],
				'total' => (int) ($porFase[$id]['total'] ?? 0),
				'desde' => $porFase[$id]['desde'] ?? null,
			];
		}

		return $funil;
	}

	/**
	 * @return array<string, mixed>
	 */
	private function ritmo(): array
	{
		$row = $this->db->query(
			"SELECT
				COALESCE(SUM(publicado >= ?), 0) AS sete,
				COALESCE(SUM(publicado >= ?), 0) AS trinta,
				COALESCE(SUM(publicado >= ? AND publicado < ?), 0) AS anterior,
				COALESCE(SUM(publicado >= ? AND tipo_artigo = 'T'), 0) AS teoricos,
				COALESCE(SUM(publicado >= ? AND tipo_artigo = 'N'), 0) AS noticias
			FROM artigos
			WHERE descartado IS NULL AND publicado IS NOT NULL",
			[$this->ha7, $this->ha30, $this->ha60, $this->ha30, $this->ha30, $this->ha30]
		)->getRowArray();

		$trinta = (int) ($row['trinta'] ?? 0);
		$anterior = (int) ($row['anterior'] ?? 0);
		$variacao = $anterior > 0 ? (int) round((($trinta - $anterior) / $anterior) * 100) : null;

		$ultimos = $this->db->table('artigos')
			->select('artigos.id, artigos.titulo, artigos.publicado, colaboradores.apelido')
			->join('colaboradores', 'colaboradores.id = artigos.escrito_colaboradores_id', 'left')
			->where('artigos.descartado', null)
			->where('artigos.publicado !=', null)
			->orderBy('artigos.publicado', 'DESC')
			->limit(5)
			->get()
			->getResultArray();

		$descartados = $this->db->table('artigos')
			->where('descartado >=', $this->ha30)
			->countAllResults();

		return [
			'sete' => (int) ($row['sete'] ?? 0),
			'trinta' => $trinta,
			'anterior' => $anterior,
			'teoricos' => (int) ($row['teoricos'] ?? 0),
			'noticias' => (int) ($row['noticias'] ?? 0),
			'variacao' => $variacao,
			'descartados' => $descartados,
			'ultimos' => $ultimos,
		];
	}

	/**
	 * @return array<string, int>
	 */
	private function colaboradores(): array
	{
		$base = static function ($db) {
			return $db->table('colaboradores')->where('excluido', null);
		};

		$produziram = $this->db->query(
			"SELECT COUNT(*) AS total FROM (
				SELECT escrito_colaboradores_id AS colaborador_id
				FROM artigos
				WHERE descartado IS NULL AND publicado >= ? AND escrito_colaboradores_id IS NOT NULL
				UNION
				SELECT revisado_colaboradores_id
				FROM artigos
				WHERE descartado IS NULL AND publicado >= ? AND revisado_colaboradores_id IS NOT NULL
				UNION
				SELECT narrado_colaboradores_id
				FROM artigos
				WHERE descartado IS NULL AND publicado >= ? AND narrado_colaboradores_id IS NOT NULL
				UNION
				SELECT produzido_colaboradores_id
				FROM artigos
				WHERE descartado IS NULL AND publicado >= ? AND produzido_colaboradores_id IS NOT NULL
			) pessoas",
			[$this->ha30, $this->ha30, $this->ha30, $this->ha30]
		)->getRowArray();

		$marcados = $this->db->query(
			"SELECT COUNT(DISTINCT marcado_colaboradores_id) AS total
			FROM artigos
			WHERE descartado IS NULL
				AND marcado_colaboradores_id IS NOT NULL
				AND fase_producao_id IN (1, 2, 3, 4, 5)"
		)->getRowArray();

		return [
			'ativos' => $base($this->db)->where('bloqueado', 'N')->where('confirmado_data !=', null)->countAllResults(),
			'pendentes' => $base($this->db)->where('confirmado_data', null)->countAllResults(),
			'bloqueados' => $base($this->db)->where('bloqueado', 'S')->countAllResults(),
			'contratados' => $base($this->db)->where('contratado', 'S')->countAllResults(),
			'novos' => $base($this->db)->where('criado >=', $this->ha30)->countAllResults(),
			'produziram' => (int) ($produziram['total'] ?? 0),
			'marcados' => (int) ($marcados['total'] ?? 0),
		];
	}

	/**
	 * Quem sugeriu notícias que viraram artigo e seguiram (não descartadas).
	 * Publicadas são as que já receberam data de publicação.
	 *
	 * @return list<array<string, mixed>>
	 */
	private function sugestoesNoticias(): array
	{
		return $this->db->query(
			"SELECT c.id, c.apelido,
				SUM(a.descartado IS NULL) AS usadas,
				SUM(a.descartado IS NULL AND a.publicado IS NOT NULL) AS publicadas
			FROM artigos a
			INNER JOIN colaboradores c ON c.id = a.sugerido_colaboradores_id
			WHERE a.tipo_artigo = 'N'
			GROUP BY c.id, c.apelido
			HAVING usadas > 0
			ORDER BY usadas DESC, publicadas DESC, c.apelido ASC
			LIMIT 8"
		)->getResultArray();
	}

	/**
	 * @return array<string, mixed>
	 */
	private function contatos(): array
	{
		$semResposta = $this->db->table('contatos')
			->where('excluido', null)
			->groupStart()
				->where('resposta', null)
				->orWhere('resposta', '')
			->groupEnd()
			->countAllResults();

		$antigos = $this->db->table('contatos')
			->select('contatos.email, contatos.criado, contatos_assuntos.assunto')
			->join('contatos_assuntos', 'contatos_assuntos.id = contatos.contatos_assuntos_id', 'left')
			->where('contatos.excluido', null)
			->groupStart()
				->where('contatos.resposta', null)
				->orWhere('contatos.resposta', '')
			->groupEnd()
			->orderBy('contatos.criado', 'ASC')
			->limit(5)
			->get()
			->getResultArray();

		return [
			'sem_resposta' => $semResposta,
			'sete' => $this->db->table('contatos')->where('excluido', null)->where('criado >=', $this->ha7)->countAllResults(),
			'trinta' => $this->db->table('contatos')->where('excluido', null)->where('criado >=', $this->ha30)->countAllResults(),
			'antigos' => $antigos,
		];
	}

	/**
	 * @return array{limite: string, legivel: string}
	 */
	private function bloqueioMarcacao(): array
	{
		$registro = (new \App\Models\ConfiguracaoModel())->find('artigo_tempo_bloqueio');
		$bruto = is_array($registro) ? trim((string) ($registro['config_valor'] ?? '')) : '';
		if (!preg_match('/^(\d+)\s+(hours|days)$/', $bruto, $partes)) {
			$bruto = '2 days';
			$partes = [0 => '2 days', 1 => '2', 2 => 'days'];
		}

		$unidade = $partes[2] === 'hours' ? 'hora' : 'dia';
		if ((int) $partes[1] !== 1) {
			$unidade .= 's';
		}

		return [
			'limite' => (new Time('-' . $bruto))->toDateTimeString(),
			'legivel' => $partes[1] . ' ' . $unidade,
		];
	}
}
