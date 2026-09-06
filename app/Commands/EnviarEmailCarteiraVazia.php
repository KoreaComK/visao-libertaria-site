<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\EnviaEmail;
use App\Models\ColaboradoresModel;
use App\Models\ConfiguracaoModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\I18n\Time;

class EnviarEmailCarteiraVazia extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:enviar-email-carteira-vazia';

	protected $description = 'Envia e-mail mensal para colaboradores com artigos publicados sem carteira cadastrada.';

	protected $usage = 'cron:enviar-email-carteira-vazia';

	public function run(array $params)
	{
		$configuracaoModel = new ConfiguracaoModel();
		$cronDiasEmailCarteira = $configuracaoModel->find('cron_email_carteira_data')['config_valor'];
		$time = new Time('+' . $cronDiasEmailCarteira);
		$ultimoEnvio = app_time($configuracaoModel->find('cron_email_carteira')['config_valor']);

		if ($time->getMonth() === $time->today()->getMonth() || $ultimoEnvio->getMonth() === $time->getMonth()) {
			return $this->encerrar('Fora da janela de envio ou já enviado neste mês. Nada a fazer.');
		}

		$colaboradores = (new ColaboradoresModel())
			->distinct()
			->select('colaboradores.email')
			->join(
				'artigos',
				'artigos.fase_producao_id = 6 AND artigos.descartado IS NULL AND (
					artigos.escrito_colaboradores_id = colaboradores.id
					OR artigos.revisado_colaboradores_id = colaboradores.id
					OR artigos.narrado_colaboradores_id = colaboradores.id
					OR artigos.produzido_colaboradores_id = colaboradores.id
				)',
				'inner',
				false
			)
			->where('(colaboradores.carteira IS NULL OR colaboradores.carteira = \'\')', null, false)
			->findAll();

		$enviados = 0;
		if ($colaboradores !== []) {
			$emails = array_column($colaboradores, 'email');
			$enviaEmail = new EnviaEmail();
			$enviaEmail->enviaEmail(
				null,
				'VISÃO LIBERTÁRIA - CARTEIRA NÃO CADASTRADA',
				$enviaEmail->getMensagemCarteiraVazia(),
				false,
				$emails
			);
			$enviados = count($emails);
		}

		$configuracaoModel->update('cron_email_carteira', ['config_valor' => $time->toDateString()]);

		return $this->encerrar('Concluído: ' . $enviados . ' e-mail(s) de carteira vazia.');
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
			$this->logger->error('cron:enviar-email-carteira-vazia: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:enviar-email-carteira-vazia: ' . $mensagem);
	}
}
