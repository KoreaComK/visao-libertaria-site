<?php

declare(strict_types=1);

/**
 * Entrada para tarefa agendada do Plesk (tipo "Executar um script PHP").
 * O "Executar um comando" no domínio cai no chroot e não acha o PHP do /opt/plesk.
 */

use CodeIgniter\Boot;
use Config\Paths;

$sapi = PHP_SAPI;
$permitido = in_array($sapi, ['cli', 'phpdbg', 'cgi', 'cgi-fcgi'], true);
if (! $permitido) {
	http_response_code(403);
	exit('Somente tarefa agendada / CLI.');
}

$_SERVER['argv'] = [__FILE__, 'sugerir:categorias-pautas'];
$_SERVER['argc'] = 2;

$minPhpVersion = '8.2';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
	exit(sprintf(
		'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s%s',
		$minPhpVersion,
		PHP_VERSION,
		PHP_EOL
	));
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);

require FCPATH . '../app/Config/Paths.php';

$paths = new Paths();

require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootSpark($paths));
