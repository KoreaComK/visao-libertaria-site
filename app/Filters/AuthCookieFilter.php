<?php

namespace App\Filters;

use App\Libraries\ColaboradoresHistoricos;
use App\Models\ColaboradoresAtribuicoesModel;
use App\Models\ColaboradoresModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthCookieFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		helper('cookie');

		$session = \Config\Services::session();
		$session->start();
		$isOptional = in_array('optional', (array) $arguments, true);

		$colaboradorSession = $session->get('colaboradores');
		if (is_array($colaboradorSession) && !empty($colaboradorSession['id'])) {
			return;
		}

		$hashCookie = get_cookie('hash');
		if ($hashCookie !== null && $hashCookie !== '') {
			if ($this->logarComCookie($hashCookie, $session) === true) {
				// Sliding expiration: extend cookie lifetime on successful cookie login.
				set_cookie('hash', $hashCookie, 60 * 60 * 24 * 7);
				return;
			}
		}

		if ($isOptional) {
			return;
		}

		$url = current_url();
		return redirect()->to(site_url('site') . '?openLogin=1&url=' . urlencode($url));
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
	}

	private function logarComCookie(string $hashCookie, $session): bool
	{
		$hashDecriptado = $this->securedDecrypt($hashCookie);
		if ($hashDecriptado === false || $hashDecriptado === null || $hashDecriptado === '') {
			delete_cookie('hash');
			return false;
		}

		$colaboradoresModel = new ColaboradoresModel();
		$colaboradoresModel->where("'" . $hashDecriptado . "' = MD5(CONCAT(email,senha))");
		$colaborador = $colaboradoresModel->get()->getResultArray();
		if (empty($colaborador)) {
			delete_cookie('hash');
			return false;
		}

		$colaborador = $colaborador[0];
		$estruturaSession = [
			'colaboradores' => [
				'id' => $colaborador['id'],
				'nome' => $colaborador['apelido'],
				'email' => $colaborador['email'],
				'avatar' => ($colaborador['avatar'] != null) ? ($colaborador['avatar']) : (site_url('public/assets/avatar-default.png')),
				'permissoes' => [],
			],
		];

		$permissoes = [];
		$atribuicoesModel = new ColaboradoresAtribuicoesModel();
		$atribuicoes = $atribuicoesModel->getAtribuicoesColaborador($colaborador['id']);
		foreach ($atribuicoes as $atribuicao) {
			$permissoes[] = $atribuicao['atribuicoes_id'];
		}
		$estruturaSession['colaboradores']['permissoes'] = $permissoes;

		$historico = new ColaboradoresHistoricos();
		$historico->cadastraHistorico($colaborador['id'], 'acessar', null, null);

		$session->set($estruturaSession);
		return true;
	}

	private function securedDecrypt(string $input)
	{
		$firstKey = getenv('FIRSTKEY');
		$secondKey = getenv('SECONDKEY');
		$mix = base64_decode($input);
		if ($mix === false) {
			return false;
		}

		$method = getenv('METHOD');
		$methodHmac = getenv('METHOD_HMAC');
		if (empty($method) || empty($methodHmac)) {
			return false;
		}

		$ivLength = openssl_cipher_iv_length($method);
		$iv = substr($mix, 0, $ivLength);
		$secondEncrypted = substr($mix, $ivLength, 64);
		$firstEncrypted = substr($mix, $ivLength + 64);
		if ($firstEncrypted === false || $firstEncrypted === '') {
			return false;
		}

		$data = openssl_decrypt($firstEncrypted, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
		$secondEncryptedNew = hash_hmac($methodHmac, $firstEncrypted, $secondKey, true);

		if ($data !== false && hash_equals($secondEncrypted, $secondEncryptedNew)) {
			return $data;
		}

		return false;
	}
}
