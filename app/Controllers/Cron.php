<?php

namespace App\Controllers;

use Config\App;
use CodeIgniter\I18n\Time;

class Cron extends BaseController
{
	/*HOME PAGE*/
	public function index($hash = NULL)
	{
		/*VERIFICANDO SE O CRON ESTÁ SENDO PROCESSADO PELO SISTEMA E NÃO POR UM USUÁRIO QUALQUER*/
		if($hash === null) {
			return redirect()->to(base_url());
		}
		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$hash_base = $configuracaoModel->find('cron_hash')['config_valor'];
		if($hash != $hash_base) {
			return redirect()->to(base_url());
		}

		/*PARTE RELACIONADA A LIMPEZA DAS PAUTAS*/
		$cronPautas = $configuracaoModel->find('cron_pautas_status_delete')['config_valor'];
		if($cronPautas == '1') {
			$cronDataPautas = $configuracaoModel->find('cron_pautas_data_delete')['config_valor'];
			
			$time = new Time('-'.$cronDataPautas);
			$pautasModel = new \App\Models\PautasModel();
			$pautasModel->where("criado <= '".$time->toDateTimeString()."'");
			$pautasModel->where('reservado',null);
			$pautasModel->where('tag_fechamento',null);
			$pautasModel->withDeleted();
			$pautas = $pautasModel->get()->getResultArray();
			foreach($pautas as $pauta) {
				$pautasModel->delete($pauta['id'],true);
			}
		}
				
		return 'Cron Finalizado';
	}

}