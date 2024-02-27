<?php

namespace App\Controllers;
use CodeIgniter\I18n\Time;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class Ancapsubot extends BaseController
{
	use ResponseTrait;
	/*HOME PAGE*/
	public function pauta()
	{
		$method = $this->request->getMethod();
		if ($method == 'post') {
			$post = $this->request->getpost();
			if ($post === false || !empty($post)) {
				$configuracaoModel = new \App\Models\ConfiguracaoModel();
				$data['config'] = array();
				$data['config']['pauta_bot_hash'] = $configuracaoModel->find('pauta_bot_hash')['config_valor'];
				if(!isset($post['hash']) || $data['config']['pauta_bot_hash'] != $post['hash']) {
					return $this->fail('Erro. Hash de acesso não informada ou inválida.');
				}
				$validaFormularios = new \App\Libraries\ValidaFormularios();
				$valida = $validaFormularios->validaFormularioPauta($post);
				if (empty($valida->getErrors())) {
					$gerenciadorTextos = new \App\Libraries\GerenciadorTextos();
					$data = array();
					$data['config']['pauta_tamanho_maximo'] = $configuracaoModel->find('pauta_tamanho_maximo')['config_valor'];
					$data['config']['pauta_tamanho_minimo'] = $configuracaoModel->find('pauta_tamanho_minimo')['config_valor'];
					$data['config']['limite_pautas_diario'] = $configuracaoModel->find('limite_pautas_diario')['config_valor'];
					$data['config']['limite_pautas_semanal'] = $configuracaoModel->find('limite_pautas_semanal')['config_valor'];
					if ($gerenciadorTextos->contaPalavras($post['texto']) > $data['config']['pauta_tamanho_maximo'] || $gerenciadorTextos->contaPalavras($post['texto']) < $data['config']['pauta_tamanho_minimo']) {
						return $this->fail('O tamanho do texto está fora dos limites.');
					}

					if(!isset($post['twitterHandle']) || $post['twitterHandle'] == '' || $post['twitterHandle'] == NULL) {
						return $this->fail('Erro. O usuário não foi informado.');
					}

					$colaboradoresModel = new \App\Models\ColaboradoresModel();
					$pautasModel = new \App\Models\PautasModel();
					$colaborador = $colaboradoresModel->where('twitter',$gerenciadorTextos->simplificaString($post['twitterHandle']))->get()->getResultArray();
					if(empty($colaborador)) {
						return $this->fail('Erro. Usuário não encontrado no site.');
					}
					$colaborador = $colaborador[0]['id'];
					
					$time = new Time('-1 days');
					$time = $time->toDateTimeString();
					$quantidade_pautas = $pautasModel->getPautasPorUsuario($time,$colaborador)[0]['contador'];
					if($quantidade_pautas >= $data['config']['limite_pautas_diario']) {
						return $this->fail('Erro. O limite diário de pautas foi atingido. Tente novamente amanhã.');
					}

					$time = new Time('-7 days');
					$time = $time->toDateTimeString();
					$quantidade_pautas = $pautasModel->getPautasPorUsuario($time,$colaborador)[0]['contador'];
					if($quantidade_pautas >= $data['config']['limite_pautas_semanal']) {
						return $this->fail('Erro. O limite semanal de pautas foi atingido. Tente novamente outro dia.');
					}

					$pautasModel = new \App\Models\PautasModel();
					$dados = array();
					$dados['colaboradores_id'] = $colaborador;
					$dados['id'] = $pautasModel->getNovaUUID();
					$dados['link'] = $post['link'];
					$dados['titulo'] = $post['titulo'];
					$dados['texto'] = $post['texto'];
					$dados['imagem'] = $post['imagem'];
					$dados['pauta_antiga'] = 'N';
					$retorno = $pautasModel->insert($dados);
					if ($retorno !== false) {
						return $this->respondCreated('Pauta cadastrada com sucesso.');
					} else {
						return $this->fail('Erro na gravação das informações.');
					}
				} else {
					$erros = $valida->getErrors();
					$string_erros = '';
					foreach ($erros as $erro) {
						$string_erros .= $erro . "<br/>";
					}
					return $this->fail($string_erros);
				}
			} else {
				return $this->fail('Erro. Os dados não foram informados.');
			}
		}
		return $this->fail('Acesso proibido. Utilize apenas o método POST.');
	}

}
