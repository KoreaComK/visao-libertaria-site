<?php

namespace App\Libraries;

class ValidaRegras
{
	public function string_com_acentos($str, ?string &$error = null): bool
	{
		return preg_match('/\A[A-Z0-9À-ú ~!#$%\&\*\-_+=|:,.]+\z/i', $str) === 1;
	}

	/**
	 * Valida endereço Bitcoin: Legacy (1/3 + Base58) ou Bech32 (bc1).
	 * Campo vazio é aceito (usar com permit_empty).
	 */
	public function carteira_bitcoin($str, ?string &$error = null): bool
	{
		if ($str === null || $str === '') {
			return true;
		}

		$str = trim((string) $str);

		// P2PKH (1...) ou P2SH (3...), Base58Check sem 0/O/I/l
		if (preg_match('/\A[13][a-km-zA-HJ-NP-Z1-9]{25,34}\z/', $str) === 1) {
			return true;
		}

		// Bech32 / Bech32m (bc1q... / bc1p...), charset bech32
		if (preg_match('/\Abc1[qpzry9x8gf2tvdw0s3jn54khce6mua7l]{11,71}\z/', strtolower($str)) === 1) {
			return true;
		}

		return false;
	}
}
