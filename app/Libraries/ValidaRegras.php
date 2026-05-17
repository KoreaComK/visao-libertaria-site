<?php

namespace App\Libraries;

class ValidaRegras
{
	public function string_com_acentos($str, ?string &$error = null): bool
	{
		return preg_match('/\A[A-Z0-9À-ú ~!#$%\&\*\-_+=|:,.]+\z/i', $str);
	}
}