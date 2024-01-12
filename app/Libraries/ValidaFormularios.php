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
				'rules' => 'required|max_length[40]|string|is_unique[colaboradores.apelido]|alpha_numeric_space|min_length[6]',
				'errors' => [
					//'required' => 'O campo ({value}) for {field} must have at least {param} characters.',
					'required' => 'O campo {field} é obrigatório.',
					'alpha_numeric_space' => 'O campo {field} aceita apenas letras, números e espaço. Sem acentuação.',
					'max_length' => 'O campo {field} tem que ter menos que {param} caracteres.',
					'min_length' => 'O campo {field} tem que ter pelo menos 6 caracteres.',
					'is_unique' => 'O apelido {value} já está sendo utilizado por outro colaborador.',
				],
			],
			'email' => [
				'label' => 'E-mail',
				'rules' => 'required|max_length[255]|valid_email|is_unique[colaboradores.email]',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} tem que ter menos que 255 caracteres.',
					'valid_email' => 'O campo {field} deve ser um e-mail válido.',
					'is_unique' => 'O e-mail {value} já está cadastrado em nossa base de dados.',
				],
			],
			'senha' => [
				'label' => 'Senha',
				'rules' => 'required|max_length[5000]|min_length[10]|matches[senhaconfirmacao]',
				'errors' => [
					'min_length' => 'Sua {field} é muito pequena. Quer ser hackeado?',
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'Sua {field} é muito grande. O máximo é {param} caracteres',
					'matches' => 'Senhas não coincidem.',
				],
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
				'rules' => 'required|max_length[255]|valid_email|is_not_unique[colaboradores.email]',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} tem que ter menos que 255 caracteres.',
					'valid_email' => 'O campo {field} deve ser um e-mail válido.',
					'is_not_unique' => 'O e-mail não já está cadastrado em nossa base de dados.',
				],
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
				'rules' => 'required|max_length[5000]|min_length[10]|matches[senhaconfirmacao]',
				'errors' => [
					'min_length' => 'Sua {field} é muito pequena. Quer ser hackeado?',
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'Sua {field} é muito grande. O máximo é {param} caracteres',
					'matches' => 'Senhas não coincidem.',
				],
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
				'rules' => 'required|valid_email|is_not_unique[colaboradores.email]',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'valid_email' => 'O campo {field} deve ser um e-mail válido.',
					'is_not_unique' => 'O e-mail não está cadastrado em nossa base de dados.',
				],
			],
			'senha' => [
				'label' => 'Senha',
				'rules' => 'required',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
				],
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
				'rules' => 'required|max_length[40]|alpha_numeric_space|is_unique[colaboradores.apelido,id,' . $id . ']',
				'errors' => [
					'max_length' => 'Apelido grande demais. O tamanho máximo é de {param} caracteres.',
					'alpha_numeric_space' => '{field} aceita apenas letras, números e espaço. Sem acentuação.',
					'required' => 'O campo {field} é obrigatório.',
					'is_unique' => 'O apelido já está sendo utilizado por outro colaborador.',
				],
			],
			'twitter' => [
				'label' => 'Twitter',
				'rules' => 'max_length[255]|is_unique[colaboradores.twitter]',
				'errors' => [
					'max_length' => 'O campo {field} tem que ter menos que 255 caracteres.',
					'is_unique' => 'O @{value} já está cadastrado em nossa base de dados.',
				],
			],
			'carteira' => [
				'label' => 'Carteira Bitcoin',
				'rules' => 'permit_empty|max_length[255]|alpha_numeric',
				'errors' => [
					'alpha_numeric' => 'Apenas letras e números são permitidos.',
					'max_length' => 'Carteira grande demais.',
				],
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
				'rules' => 'is_image[avatar]|ext_in[avatar,png]|max_size[avatar,1024]|max_dims[avatar,2048,2048]',
				'errors' => [
					'is_image' => 'Avatar precisa ser uma imagem.',
					'ext_in' => 'Avatar deve ser em extensão .png.',
					'max_size' => 'Avatar deve ter menos de 1MB de tamanho.',
					'max_dims' => 'Avatar deve ter tamanho de até 2048x2048 pixels.',
				],
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
				'rules' => 'required',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
				],
			],
			'senha_nova' => [
				'label' => 'Nova Senha',
				'rules' => 'required|max_length[5000]|min_length[10]|matches[senha_nova_confirmacao]',
				'errors' => [
					'min_length' => 'Sua {field} é muito pequena. Quer ser hackeado?',
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'Sua {field} é muito grande. O máximo é {param} caracteres',
					'matches' => 'Senhas não coincidem.',
				],
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
				//'rules' => 'required|max_length[255]|valid_url_strict|is_unique[pautas.link]',
				'rules' => 'required|max_length[255]|valid_url_strict',
				'errors' => [
					//'is_unique' => 'A pauta já foi cadastrada.',
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
					'valid_url_strict' => 'O campo {field} precisa ser uma URL válida.',
				],
			],
			'titulo' => [
				'label' => 'Título',
				'rules' => 'required|max_length[255]|min_length[10]',
				'errors' => [
					'min_length' => 'O campo {field} é muito pequeno. Escreva pelo menos 10 caracteres',
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
				],
			],
			'texto' => [
				'label' => 'Texto da pauta',
				'rules' => 'required|min_length[10]',
				'errors' => [
					'min_length' => 'O campo {field} é muito pequeno. Escreva pelo menos 10 caracteres',
					'required' => 'O campo {field} é obrigatório.',
				],
			],
			'imagem' => [
				'label' => 'Link da Imagem',
				'rules' => 'required|max_length[255]|valid_url_strict',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
					'valid_url_strict' => 'O campo {field} precisa ser uma URL válida.',
				],
			]
		]);
		$validation->run($post);
		return $validation;
	}

	public function validaFormularioArtigo($post, $pauta = false)
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'titulo' => [
				'label' => 'Título do Artigo',
				'rules' => 'required|max_length[255]|min_length[10]',
				'errors' => [
					'min_length' => 'O campo {field} é muito pequeno. Escreva pelo menos 10 caracteres',
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
				],
			],
			'gancho' => [
				'label' => 'Gancho',
				'rules' => 'required|max_length[255]|min_length[25]',
				'errors' => [
					'min_length' => 'O campo {field} é muito pequeno. Escreva pelo menos 10 caracteres',
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
				],
			],
			'texto_original' => [
				'label' => 'Texto do Artigo',
				'rules' => 'required',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
				],
			],
			'referencias' => [
				'label' => 'Referências',
				'rules' => 'required',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
				],
			],
			'imagem' => [
				'label' => 'Link da Imagem',
				'rules' => 'required|max_length[255]|valid_url_strict',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
					'valid_url_strict' => 'O campo {field} precisa ser uma URL válida.',
				],
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

	public function validaFormularioArtigoNarracaoFile()
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'audio' => [
				'label' => 'Arquivo de Áudio',
				'rules' => 'uploaded[audio]|ext_in[audio,mp3]|max_size[audio,30720]',
				'errors' => [
					'uploaded' => 'O campo {field} é obrigatório e não pode ser maior que 30MB',
					'ext_in' => 'Arquivo de áudio aceito apenas em .mp3',
					'max_size' => 'Tamanho do arquivo de áudio deve ser menor que 30MB.',
				],
			],
		]);
		$validation->run();
		return $validation;
	}

	public function validaFormularioAdministracaoGerais()
	{
		$validation = \Config\Services::validation();
		$validation->setRules([
			'banner' => [
				'label' => 'Arquivo do Banner',
				'rules' => 'ext_in[banner,png]|max_size[banner,3072]',
				'errors' => [
					'ext_in' => '{field}  precisa ser .png.',
					'max_size' => 'Tamanho do arquivo deve ser menor que 3MB.',
				],
			],
			'rodape' => [
				'label' => 'Imagem Rodapé',
				'rules' => 'ext_in[rodape,png]|max_size[rodape,1024]',
				'errors' => [
					'ext_in' => '{field} precisa ser .png.',
					'max_size' => 'Tamanho do arquivo deve ser menor que 1MB.',
				],
			],
			'favicon' => [
				'label' => 'Imagem Favicon',
				'rules' => 'ext_in[favicon,ico]|max_size[favicon,100]',
				'errors' => [
					'ext_in' => '{field} precisa ser .ico.',
					'max_size' => 'Tamanho do arquivo deve ser menor que 100KB.',
				],
			],
			'estilos' => [
				'label' => 'Arquivo de Estilos',
				'rules' => 'ext_in[estilos,css]|max_size[estilos,3072]',
				'errors' => [
					'ext_in' => '{field} precisa ser .css.',
					'max_size' => 'Tamanho do arquivo deve ser menor que 3MB.',
				],
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
				'rules' => 'required|max_length[255]|valid_url_strict',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
					'valid_url_strict' => 'O campo {field} precisa ser uma URL válida.',
				],
			],
			'shorts_link' => [
				'label' => 'Link do Shorts',
				'rules' => 'permit_empty|max_length[255]|valid_url_strict',
				'errors' => [
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
					'valid_url_strict' => 'O campo {field} precisa ser uma URL válida.',
				],
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
				'rules' => 'required|max_length[255]|valid_url_strict',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} é muito grande. O máximo é {param} caracteres',
					'valid_url_strict' => 'O campo {field} precisa ser uma URL válida.',
				],
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
				'rules' => 'required|decimal',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'decimal' => 'O campo {field} tem que decimal.'
				],
			],
			'multiplicador_escrito' => [
				'label' => 'Multiplicador Escrito',
				'rules' => 'required|integer',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} precisa ser um número inteiro.'
				],
			],
			'multiplicador_revisado' => [
				'label' => 'Multiplicador Revisado',
				'rules' => 'required|integer',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} precisa ser um número inteiro.'
				],
			],'multiplicador_narrado' => [
				'label' => 'Multiplicador Narrado',
				'rules' => 'required|integer',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} precisa ser um número inteiro.'
				],
			],'multiplicador_produzido' => [
				'label' => 'Multiplicador Produzido',
				'rules' => 'required|integer',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.',
					'max_length' => 'O campo {field} precisa ser um número inteiro.'
				],
			],
			'hash_transacao' => [
				'label' => 'Hash da Transação de Pagamento',
				'rules' => 'required',
				'errors' => [
					'required' => 'O campo {field} é obrigatório.'
				],
			]
		]);
		$validation->run($post);
		return $validation;
	}
}