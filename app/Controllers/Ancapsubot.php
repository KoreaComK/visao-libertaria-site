<?php

namespace App\Controllers;

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
					$pautasModel = new \App\Models\PautasModel();
					$dados = array();
					$dados['colaboradores_id'] = 0;
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
						return $this->fail('Erro na hora da gravação das informações.');
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
