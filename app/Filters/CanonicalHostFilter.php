<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\App;

class CanonicalHostFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		$appConfig = config(App::class);
		$canonicalHost = parse_url($appConfig->baseURL, PHP_URL_HOST);
		if (!is_string($canonicalHost) || $canonicalHost === '') {
			return;
		}

		$currentHost = $request->getUri()->getHost();
		if ($currentHost === $canonicalHost) {
			return;
		}

		if (in_array($currentHost, $appConfig->allowedHostnames, true)) {
			return;
		}

		$currentBare = $this->stripWww($currentHost);
		$canonicalBare = $this->stripWww($canonicalHost);
		if (strcasecmp($currentBare, $canonicalBare) !== 0) {
			return;
		}

		$scheme = parse_url($appConfig->baseURL, PHP_URL_SCHEME);
		if (!is_string($scheme) || $scheme === '') {
			$scheme = $request->getUri()->getScheme();
		}

		$target = $scheme . '://' . $canonicalHost . $request->getUri()->getPath();
		$query = $request->getUri()->getQuery();
		if ($query !== '') {
			$target .= '?' . $query;
		}

		return redirect()->to($target, 301);
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
	}

	private function stripWww(string $host): string
	{
		if (stripos($host, 'www.') === 0) {
			return substr($host, 4);
		}

		return $host;
	}
}
