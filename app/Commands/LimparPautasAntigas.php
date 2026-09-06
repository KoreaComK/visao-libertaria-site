<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\CacheImagemPauta;
use App\Models\ArtigosModel;
use App\Models\ConfiguracaoModel;
use App\Models\PautasCategoriasModel;
use App\Models\PautasModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\I18n\Time;

class LimparPautasAntigas extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:limpar-pautas-antigas';

	protected $description = 'Remove pautas antigas não reservadas e pautas de redator com artigo já criado.';

	protected $usage = 'cron:limpar-pautas-antigas';

	public function run(array $params)
	{
		$configuracaoModel = new ConfiguracaoModel();
		$cronPautas = $configuracaoModel->find('cron_pautas_status_delete')['config_valor'];
		if ($cronPautas != '1') {
			return $this->encerrar('Limpeza de pautas desativada na configuração. Nada a fazer.');
		}

		$cronDataPautas = $configuracaoModel->find('cron_pautas_data_delete')['config_valor'];
		$limiteCriacao = new Time('-' . $cronDataPautas);

		$pautasModel = new PautasModel();
		$pautasAntigas = $pautasModel
			->where('criado <=', $limiteCriacao->toDateTimeString())
			->where('reservado', null)
			->where('tag_fechamento', null)
			->where('redator_colaboradores_id', null)
			->withDeleted()
			->findAll();

		$removidasAntigas = 0;
		if ($pautasAntigas !== []) {
			$idsPautasAntigas = array_column($pautasAntigas, 'id');
			$this->removerThumbsPautas($idsPautasAntigas);
			$pautasModel->db->table('pautas_comentarios')
				->whereIn('pautas_id', $idsPautasAntigas)
				->delete();
			(new PautasCategoriasModel())->deletePorPautas($idsPautasAntigas);

			$pautasExclusao = new PautasModel();
			foreach ($idsPautasAntigas as $idPauta) {
				$pautasExclusao->delete($idPauta, true);
			}
			$removidasAntigas = count($idsPautasAntigas);
		}

		$pautasRedator = (new PautasModel())
			->where('redator_colaboradores_id IS NOT NULL', null, false)
			->findAll();

		$removidasRedator = 0;
		if ($pautasRedator !== []) {
			$artigosRedator = (new ArtigosModel())
				->select('link, escrito_colaboradores_id')
				->where('escrito_colaboradores_id IS NOT NULL', null, false)
				->where('link IS NOT NULL', null, false)
				->where('link !=', '')
				->findAll();

			$artigosPorLinkRedator = [];
			foreach ($artigosRedator as $artigo) {
				$chave = $artigo['link'] . "\0" . $artigo['escrito_colaboradores_id'];
				$artigosPorLinkRedator[$chave] = true;
			}

			$pautasExclusaoRedator = new PautasModel();
			foreach ($pautasRedator as $pauta) {
				$chave = $pauta['link'] . "\0" . $pauta['redator_colaboradores_id'];
				if (! isset($artigosPorLinkRedator[$chave])) {
					continue;
				}
				$this->removerThumbsPautas([(string) $pauta['id']]);
				$pautasExclusaoRedator->delete($pauta['id']);
				$removidasRedator++;
			}
		}

		return $this->encerrar(
			'Concluído: ' . $removidasAntigas . ' pauta(s) antiga(s), '
			. $removidasRedator . ' pauta(s) de redator removida(s).'
		);
	}

	private function removerThumbsPautas(array $ids): void
	{
		$cache = new CacheImagemPauta();
		foreach ($ids as $id) {
			$cache->removerArquivo((string) $id);
		}
	}

	private function encerrar(string $mensagem, string $nivel = 'info'): int
	{
		$this->registrar($mensagem, $nivel);

		return EXIT_SUCCESS;
	}

	private function registrar(string $mensagem, string $nivel = 'info'): void
	{
		if ($nivel === 'error') {
			CLI::error($mensagem);
			$this->logger->error('cron:limpar-pautas-antigas: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:limpar-pautas-antigas: ' . $mensagem);
	}
}
