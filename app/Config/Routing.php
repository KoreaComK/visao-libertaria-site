<?php

namespace Config;

use CodeIgniter\Config\Routing as BaseRouting;

/**
 * Routing configuration
 */
class Routing extends BaseRouting
{
    /**
     * @var list<string>
     */
    public array $routeFiles = [
        APPPATH . 'Config/Routes.php',
    ];

    public string $defaultNamespace = 'App\Controllers';

    public string $defaultController = 'Home';

    public string $defaultMethod = 'index';

    public bool $translateURIDashes = false;

    public ?string $override404 = null;

    public bool $autoRoute = true;

    public bool $prioritize = false;

    public bool $multipleSegmentsOneParam = false;

    /**
     * @var array<string, string>
     */
    public array $moduleRoutes = [];

    /**
     * For Auto Routing (Improved) only.
     */
    public bool $translateUriToCamelCase = false;
}
