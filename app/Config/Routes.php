<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Public pages (optional cookie login, no redirect when absent).
$routes->group('', ['filter' => 'authCookie:optional'], static function ($routes) {
    $routes->get('/', 'Site::index');
    $routes->get('links', 'Site::links');
    $routes->get('site/noticias', 'Site::noticias');
    $routes->match(['get', 'post'], 'site/cadastre-se', 'Site::cadastrar');
    $routes->match(['get', 'post'], 'site/esqueci-senha', 'Site::esqueci');
    $routes->match(['get', 'post'], 'site/esqueci-senha/(:any)', 'Site::esqueci/$1');
    $routes->get('site/confirmacao/(:any)', 'Site::confirmacao/$1');
    $routes->get('site/videos', 'Site::videos');
    $routes->get('site/videos/(:any)', 'Site::videos/$1');
    $routes->get('site/artigos', 'Site::artigos');
    $routes->get('site/artigos/(:any)', 'Site::artigos/$1');
    $routes->get('site/pauta/(:any)', 'Site::pauta/$1');
    $routes->match(['get', 'post'], 'site/contato', 'Site::contato');
    $routes->get('site/pagina/(:any)', 'Site::pagina/$1');
    $routes->get('site/escritor/(:any)', 'Site::escritor/$1');
    $routes->get('site/escritorList/(:any)', 'Site::escritorList/$1');
    $routes->get('site/colaborador/(:any)', 'Site::colaborador/$1');
    $routes->get('site/colaboradorList/(:any)', 'Site::colaboradorList/$1');
    $routes->get('site/calculadoras', 'Site::calculadoras');
    $routes->get('colaboradores/pautas', static fn () => redirect()->to(site_url('site/noticias')));
});

// Login endpoint (AJAX and cookie flow).
$routes->match(['get', 'post'], 'site/login', 'Site::login', ['filter' => 'authCookie:optional']);

// Protected actions outside /colaboradores.
$routes->group('site', ['filter' => 'authCookie'], static function ($routes) {
    $routes->get('logout', 'Site::logout');
    $routes->match(['get', 'post'], 'excluir', 'Site::excluir');
    $routes->match(['get', 'post'], 'excluir/(:any)', 'Site::excluir/$1');
});

// Protected collaborator area.
$routes->group('colaboradores', ['namespace' => 'App\Controllers\Colaboradores', 'filter' => 'authCookie'], static function ($routes) {
    $routes->get('/', 'Perfil::index');
    $routes->get('perfil', 'Perfil::index');
    $routes->add('perfil/(:any)', 'Perfil::$1');
    $routes->add('artigos/(:any)', 'Artigos::$1');
    $routes->add('artigos/(:any)/(:any)', 'Artigos::$1/$2');
    $routes->add('pautas/(:any)', 'Pautas::$1');
    $routes->add('pautas/(:any)/(:any)', 'Pautas::$1/$2');
    $routes->add('admin/(:any)', 'Admin::$1');
    $routes->add('admin/(:any)/(:any)', 'Admin::$1/$2');
    $routes->add('admin/(:any)/(:any)/(:any)', 'Admin::$1/$2/$3');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
