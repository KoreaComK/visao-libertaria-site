<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Hcaptcha extends BaseConfig
{
	/**
	 * Chave pública do widget hCaptcha (site key).
	 * Definir no .env: HCAPTCHA_SITEKEY=sua-chave
	 */
	public string $siteKey;

	public function __construct()
	{
		parent::__construct();
		$this->siteKey = (string) (env('HCAPTCHA_SITEKEY', '') ?? '');
	}
}
