<?php

namespace App\Libraries;

use App\Controllers\BaseController;

class ValidaFormularios extends BaseController
{

	public function validaFormularioCadastroColaborador($post)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'apelido' => [
				'label' => 'Apelido',
				'rules' => 'required|max_length[40]|is_unique[colaboradores.apelido]|string_com_acentos|min_length[6]'
			],
			'email' => [
				'label' => 'E-mail',
				'rules' => 'required|max_length[255]|valid_email|is_unique[colaboradores.email]',
			],
			'senha' => [
				'label' => 'Senha',
				'rules' => 'required|max_length[5000]|min_length[10]|matches[senhaconfirmacao]'
			],
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioEsqueciSenhaEmailColaborador($post)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'email' => [
				'label' => 'E-mail',
				'rules' => 'required|max_length[255]|valid_email|is_not_unique[colaboradores.email]'
			],
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioContato($post)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'email' => [
				'label' => 'E-mail',
				'rules' => 'required|valid_email'
			],
			'assunto' => [
				'label' => 'Assunto',
				'rules' => 'required'
			],
			'mensagem' => [
				'label' => 'Mensagem',
				'rules' => 'required'
			],
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioEsqueciSenhaSenhaColaborador($post)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'senha' => [
				'label' => 'Senha',
				'rules' => 'required|max_length[5000]|min_length[10]|matches[senhaconfirmacao]'
			],
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioLoginColaborador($post)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'email' => [
				'label' => 'E-mail',
				'rules' => 'required|valid_email|is_not_unique[colaboradores.email]'
			],
			'senha' => [
				'label' => 'Senha',
				'rules' => 'required'
			],
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioPerfilColaborador($post, $id)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'apelido' => [
				'label' => 'Nome Público (Apelido)',
				'rules' => 'required|max_length[40]|min_length[6]|string_com_acentos|is_unique[colaboradores.apelido,id,' . $id . ']'
			],
			'twitter' => [
				'label' => 'Twitter',
				'rules' => 'permit_empty|max_length[255]|is_unique[colaboradores.twitter,id,' . $id . ']'
			],
			'carteira' => [
				'label' => 'Carteira Bitcoin',
				'rules' => 'permit_empty|max_length[255]|alpha_numeric|is_unique[colaboradores.carteira,id,' . $id . ']'
			]
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioPerfilColaboradorFile()
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'avatar' => [
				'label' => 'Avatar',
				'rules' => 'is_image[avatar]|ext_in[avatar,png]|max_size[avatar,1024]|max_dims[avatar,2048,2048]'
			],
		]);
		$validation->run();
		return $validation;
	}

	public function validaFormularioTrocarSenhaColaborador($post)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'senha_antiga' => [
				'label' => 'Senha Antiga',
				'rules' => 'required'
			],
			'senha_nova' => [
				'label' => 'Nova Senha',
				'rules' => 'required|max_length[5000]|min_length[10]|matches[senha_nova_confirmacao]'
			]
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioPauta($post)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'link' => [
				'label' => 'Link da Notícia',
				'rules' => 'required|max_length[255]|valid_url_strict'
			],
			'titulo' => [
				'label' => 'Título',
				'rules' => 'required|max_length[255]|min_length[10]'
			],
			'texto' => [
				'label' => 'Texto da pauta',
				'rules' => 'required|min_length[10]|max_length[600]'
			],
			'imagem' => [
				'label' => 'Link da Imagem',
				'rules' => 'required|max_length[255]|valid_url_strict'
			]
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioArtigoSalvar($post, $pauta = false)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'tipo_artigo' => [
				'label' => 'Tipo do Artigo',
				'rules' => 'required'
			],
			'titulo' => [
				'label' => 'Título do Artigo',
				'rules' => 'required|max_length[255]|min_length[10]'
			],
			'texto_original' => [
				'label' => 'Texto do Artigo',
				'rules' => 'required'
			],
			// 'categorias' => [
			// 	'label' => 'Categorias',
			// 	'rules' => 'required',
			// 	'errors' => [
			// 		'required' => 'O campo {field} é obrigatório.',
			// 	],
			// ]
		]);
		if ($pauta === false) {
			$validation->setRule(
				'link',
				'Link da Notícia',
				'required|max_length[255]|valid_url_strict',
				[
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
					'valid_url_strict' => 'O campo {field} precisa ser uma URL válida.',
				]
			);
		}
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioArtigo($post, $pauta = false)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'tipo_artigo' => [
				'label' => 'Tipo do Artigo',
				'rules' => 'required'
			],
			'titulo' => [
				'label' => 'Título do Artigo',
				'rules' => 'required|max_length[255]|min_length[10]'
			],
			'gancho' => [
				'label' => 'Gancho',
				'rules' => 'required|max_length[255]|min_length[25]'
			],
			'texto_original' => [
				'label' => 'Texto do Artigo',
				'rules' => 'required'
			],
			'referencias' => [
				'label' => 'Referências',
				'rules' => 'required'
			],
			'imagem' => [
				'label' => 'Imagem',
				'rules' => 'required'
			]
			// 'categorias' => [
			// 	'label' => 'Categorias',
			// 	'rules' => 'required',
			// 	'errors' => [
			// 		'required' => 'O campo {field} é obrigatório.',
			// 	],
			// ]
		]);
		if ($pauta === false) {
			$validation->setRule(
				'link',
				'Link da Notícia',
				'required|max_length[255]|valid_url_strict',
				[
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
					'valid_url_strict' => 'O campo {field} precisa ser uma URL válida.',
				]
			);
		}
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioArtigoImagem()
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'imagem' => [
				'label' => 'Imagem',
				'rules' => 'uploaded[imagem]|is_image[imagem]|ext_in[imagem,jpg,png,jpeg]|max_size[imagem,5120]'
			],
		]);
		$validation->run();
		return $validation;
	}

	public function validaFormularioArtigoNarracaoFile()
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'audio' => [
				'label' => 'Arquivo de Áudio',
				'rules' => 'uploaded[audio]|ext_in[audio,mp3]|max_size[audio,30720]'
			],
		]);
		$validation->run();
		return $validation;
	}

	public function validaFormularioAdministracaoGerais()
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			// 'banner' => [
			// 	'label' => 'Arquivo do Banner',
			// 	'rules' => 'ext_in[banner,png]|max_size[banner,3072]'
			// ],
			'rodape' => [
				'label' => 'Imagem Rodapé',
				'rules' => 'ext_in[rodape,png]|max_size[rodape,1024]'
			],
			'favicon' => [
				'label' => 'Imagem Favicon',
				'rules' => 'ext_in[favicon,ico]|max_size[favicon,100]'
			],
			'estilos' => [
				'label' => 'Arquivo de Estilos',
				'rules' => 'ext_in[estilos,css]|max_size[estilos,3072]'
			],
		]);
		$validation->run();
		return $validation;
	}
	
	public function validaFormularioProducao($post, $pauta = false)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'video_link' => [
				'label' => 'Link do Vídeo',
				'rules' => 'required|max_length[255]|valid_url_strict'
			],
			'shorts_link' => [
				'label' => 'Link do Shorts',
				'rules' => 'required|permit_empty|max_length[255]|valid_url_strict'
			]
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioPublicacao($post, $pauta = false)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'link_video_youtube' => [
				'label' => 'Link do Vídeo',
				'rules' => 'required|max_length[255]|valid_url_strict'
			]
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioCadastroPagamento($post)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'quantidade_bitcoin' => [
				'label' => 'Quantidade de Bitcoin',
				'rules' => 'required|decimal'
			],
			'multiplicador_escrito' => [
				'label' => 'Multiplicador Escrito',
				'rules' => 'required|integer'
			],
			'multiplicador_revisado' => [
				'label' => 'Multiplicador Revisado',
				'rules' => 'required|integer'
			],'multiplicador_narrado' => [
				'label' => 'Multiplicador Narrado',
				'rules' => 'required|integer'
			],'multiplicador_produzido' => [
				'label' => 'Multiplicador Produzido',
				'rules' => 'required|integer'
			],
			'hash_transacao' => [
				'label' => 'Hash da Transação de Pagamento',
				'rules' => 'required'
			]
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioAvisos($post)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'aviso' => [
				'label' => 'Aviso',
				'rules' => 'required|max_length[511]|min_length[10]'
			]
		]);
		$validation->run($post);
		return $validation;
	}
}