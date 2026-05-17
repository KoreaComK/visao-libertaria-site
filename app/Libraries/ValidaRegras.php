<?php

namespace App\Libraries;

class ValidaRegras
{
	public function string_com_acentos($str, ?string &$error = null): bool
	{
		return preg_match('/\A[A-Z0-9À-ú ~!#$%\&\*\-_+=|:,.]+\z/i', $str);
	}

	public function audio_narracao_tamanho($str, string $field, array $data, ?string &$error = null): bool
	{
		$file = service('request')->getFile($field);

		if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
			return true;
		}

		if (!$file->isValid()) {
			return true;
		}

		$ext = strtolower($file->getClientExtension());
		$sizeKb = $file->getSize() / 1024;

		if ($ext === 'mp3' && $sizeKb > 40960) {
			$error = 'O arquivo .mp3 deve ser menor do que 40 MB.';
			return false;
		}

		if ($ext === 'mp4' && $sizeKb > 460800) {
			$error = 'O arquivo .mp4 deve ser menor do que 450 MB.';
			return false;
		}

		return true;
	}
}