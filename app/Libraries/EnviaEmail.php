<?php

namespace App\Libraries;

class EnviaEmail
{
	public function enviaEmail($destinatario = null, $titulo = null, $mensagem = null, $copia = false)
	{
		$email = \Config\Services::email();
		$email->setTo($destinatario);
		if($copia !== false) {
			$email->setCC($copia);
		}
		$email->setFrom($email->SMTPUser, 'Visão Libertária');

		$email->setSubject($titulo);
		$email->setMessage($mensagem);
		if ($email->send()) {
			// $data = $email->printDebugger(['headers']);
			// print_r($data);
			return true;
		} else {
			// $data = $email->printDebugger(['headers']);
			// print_r($data);
			return false;
		}
	}

	public function getMensagemCadastro($hash)
	{
		$mensagem = "Bem-vindo ao Visão Libertária. \r\nPara acessar a área de colaborador, é necessário confirmar o seu e-mail.\r\nPara tal, clique no link abaixo.\r\n" . site_url('site/confirmacao/' . $hash);
		return $mensagem;
	}

	public function getMensagemEsqueciSenha($hash)
	{
		$mensagem = "Olá. Você solicitou a redefinição de senha no site Visão Libertária.\r\nPara alterar a senha, clique no link abaixo.\r\n" . site_url('site/esqueci/' . $hash);
		return $mensagem;
	}

	public function getMensagemExcluirConta($hash)
	{
		$mensagem = "Olá. Você solicitou a exclusão da sua conta no site Visão Libertária.\r\nPara confirmar a exclusão, clique no link abaixo.\r\n" . site_url('site/excluir/' . $hash);
		return $mensagem;
	}

	public function getMensagemContato($mensagem,$email)
	{
		$mensagem = "Contato feito através do formulário do site (".site_url()."):\r\nEmail: ".$email."\r\n".$mensagem;
		return $mensagem;
	}

}