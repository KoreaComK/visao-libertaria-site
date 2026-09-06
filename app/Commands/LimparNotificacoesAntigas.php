<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\ColaboradoresNotificacoesModel;
use App\Models\ConfiguracaoModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\I18n\Time;

class LimparNotificacoesAntigas extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:limpar-notificacoes-antigas';

	protected $description = 'Remove notificações antigas (visualizadas e cadastradas) conforme prazos da config.';

	protected $usage = 'cron:limpar-notificacoes-antigas';

	public function run(array $params)
	{
		$configuracaoModel = new ConfiguracaoModel();
		if ($configuracaoModel->find('cron_notificacoes_status_delete')['config_valor'] != '1') {
			return $this->encerrar('Limpeza de notificações desativada na configuração. Nada a fazer.');
		}

		$prazoVisualizadas = $configuracaoModel->find('cron_notificacoes_data_visualizado')['config_valor'];
		$limiteVisualizadas = new Time('-' . $prazoVisualizadas);

		$notificacoesModel = new ColaboradoresNotificacoesModel();
		$notificacoesModel
			->where('data_visualizado <=', $limiteVisualizadas->toDateTimeString())
			->delete();
		$removidasVisualizadas = $notificacoesModel->db->affectedRows();

		$prazoCadastradas = $configuracaoModel->find('cron_notificacoes_data_cadastrado')['config_valor'];
		$limiteCadastradas = new Time('-' . $prazoCadastradas);

		$notificacoesModel = new ColaboradoresNotificacoesModel();
		$notificacoesModel
			->where('criado <=', $limiteCadastradas->toDateTimeString())
			->delete();
		$removidasCadastradas = $notificacoesModel->db->affectedRows();

		return $this->encerrar(
			'Concluído: ' . $removidasVisualizadas . ' visualizada(s), '
			. $removidasCadastradas . ' cadastrada(s) removida(s).'
		);
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
			$this->logger->error('cron:limpar-notificacoes-antigas: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:limpar-notificacoes-antigas: ' . $mensagem);
	}
}
