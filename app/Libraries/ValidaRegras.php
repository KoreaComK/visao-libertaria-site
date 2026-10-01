<?php

namespace App\Libraries;

class ValidaRegras
{
	public function string_com_acentos($str, ?string &$error = null): bool
	{
		return preg_match('/\A[A-Z0-9À-ú ~!#$%\&\*\-_+=|:,.]+\z/i', $str) === 1;
	}

	/**
	 * Valida endereço Bitcoin on-chain: P2PKH (1...), P2SH (3...) ou SegWit (bc1...).
	 * Confere o checksum. Campo vazio é aceito (usar com permit_empty).
	 */
	public function carteira_bitcoin($str, ?string &$error = null): bool
	{
		if ($str === null || $str === '') {
			return true;
		}

		$str = trim((string) $str);
		if ($str === '') {
			return true;
		}

		$inicial = $str[0];
		if ($inicial === '1' || $inicial === '3') {
			$versao = $inicial === '1' ? 0x00 : 0x05;
			if ($this->base58CheckValido($str, $versao)) {
				return true;
			}

			$error = 'Esse endereço Bitcoin está incorreto. Copie a carteira inteira, sem espaços ou caracteres a mais.';

			return false;
		}

		$minusculo = strtolower($str);
		if (str_starts_with($minusculo, 'lnurl') || str_starts_with($minusculo, 'lnbc')) {
			$error = 'Endereços da Lightning não são aceitos. Cadastre uma carteira Bitcoin on-chain, que começa com 1, 3 ou bc1.';

			return false;
		}

		if ($this->bech32BitcoinValido($str)) {
			return true;
		}

		if (str_starts_with($minusculo, 'bc1')) {
			$error = 'Esse endereço bc1 está incorreto. Copie a carteira inteira, toda em minúsculas ou toda em maiúsculas.';

			return false;
		}

		$error = 'Cadastre uma carteira Bitcoin on-chain. O endereço deve começar com 1, 3 ou bc1.';

		return false;
	}

	private function base58CheckValido(string $endereco, int $versao): bool
	{
		$decodificado = $this->base58Decode($endereco);
		if ($decodificado === null || strlen($decodificado) !== 25) {
			return false;
		}

		$payload = substr($decodificado, 0, 21);
		$checksum = substr($decodificado, 21, 4);
		$hash = substr(hash('sha256', hash('sha256', $payload, true), true), 0, 4);

		return hash_equals($checksum, $hash) && ord($payload[0]) === $versao;
	}

	private function base58Decode(string $endereco): ?string
	{
		$alfabeto = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
		$bytes = [0];
		$tamanho = strlen($endereco);

		for ($i = 0; $i < $tamanho; $i++) {
			$resto = strpos($alfabeto, $endereco[$i]);
			if ($resto === false) {
				return null;
			}

			for ($j = 0, $n = count($bytes); $j < $n; $j++) {
				$resto += $bytes[$j] * 58;
				$bytes[$j] = $resto & 0xff;
				$resto >>= 8;
			}

			while ($resto > 0) {
				$bytes[] = $resto & 0xff;
				$resto >>= 8;
			}
		}

		$zeros = 0;
		for ($i = 0; $i < $tamanho && $endereco[$i] === '1'; $i++) {
			$zeros++;
		}

		$resultado = str_repeat("\x00", $zeros);
		for ($i = count($bytes) - 1; $i >= 0; $i--) {
			$resultado .= chr($bytes[$i]);
		}

		return $resultado;
	}

	private function bech32BitcoinValido(string $endereco): bool
	{
		if (preg_match('/[a-z]/', $endereco) === 1 && preg_match('/[A-Z]/', $endereco) === 1) {
			return false;
		}

		$endereco = strtolower($endereco);
		$separador = strrpos($endereco, '1');
		if ($separador === false || $separador < 1 || $separador + 7 > strlen($endereco)) {
			return false;
		}

		$hrp = substr($endereco, 0, $separador);
		if ($hrp !== 'bc') {
			return false;
		}

		$dados = substr($endereco, $separador + 1);
		$charset = 'qpzry9x8gf2tvdw0s3jn54khce6mua7l';
		$valores = [];
		$tamanhoDados = strlen($dados);
		for ($i = 0; $i < $tamanhoDados; $i++) {
			$posicao = strpos($charset, $dados[$i]);
			if ($posicao === false) {
				return false;
			}
			$valores[] = $posicao;
		}

		if (count($valores) < 6) {
			return false;
		}

		$programa5 = array_slice($valores, 0, -6);
		$versao = array_shift($programa5);
		if ($versao === null || $versao > 16) {
			return false;
		}

		$programa = $this->bech32ConverteBits($programa5);
		if ($programa === null) {
			return false;
		}

		$tamanhoPrograma = strlen($programa);
		if ($versao === 0) {
			if ($tamanhoPrograma !== 20 && $tamanhoPrograma !== 32) {
				return false;
			}
			$constante = 1;
		} else {
			if ($versao === 1 && $tamanhoPrograma !== 32) {
				return false;
			}
			if ($tamanhoPrograma < 2 || $tamanhoPrograma > 40) {
				return false;
			}
			$constante = 0x2bc830a3;
		}

		return $this->bech32Polymod(array_merge($this->bech32ExpandeHrp($hrp), $valores)) === $constante;
	}

	private function bech32ExpandeHrp(string $hrp): array
	{
		$expandido = [];
		$tamanho = strlen($hrp);
		for ($i = 0; $i < $tamanho; $i++) {
			$expandido[] = ord($hrp[$i]) >> 5;
		}
		$expandido[] = 0;
		for ($i = 0; $i < $tamanho; $i++) {
			$expandido[] = ord($hrp[$i]) & 31;
		}

		return $expandido;
	}

	private function bech32Polymod(array $valores): int
	{
		$geradores = [0x3b6a57b2, 0x26508e6d, 0x1ea119fa, 0x3d4233dd, 0x2a1462b3];
		$chk = 1;
		foreach ($valores as $valor) {
			$b = $chk >> 25;
			$chk = (($chk & 0x1ffffff) << 5) ^ $valor;
			for ($i = 0; $i < 5; $i++) {
				if ((($b >> $i) & 1) === 1) {
					$chk ^= $geradores[$i];
				}
			}
		}

		return $chk;
	}

	private function bech32ConverteBits(array $dados): ?string
	{
		$acc = 0;
		$bits = 0;
		$saida = '';
		foreach ($dados as $valor) {
			if ($valor < 0 || $valor > 31) {
				return null;
			}
			$acc = (($acc << 5) | $valor) & 0xfff;
			$bits += 5;
			while ($bits >= 8) {
				$bits -= 8;
				$saida .= chr(($acc >> $bits) & 0xff);
			}
		}

		if ($bits >= 5 || ($bits > 0 && (($acc << (8 - $bits)) & 0xff) !== 0)) {
			return null;
		}

		return $saida;
	}
}
