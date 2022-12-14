<?php

namespace Config;

use App\Controllers\Home;
use App\Controllers\News;
use App\Controllers\News3;

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
// Use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', [Home::class, 'index']);

$routes->match(['get', 'post'], 'news/create', [News::class, 'create']);
$routes->get('news/edit/(:num)', [News::class, 'edit']);
$routes->post('news/update', [News::class, 'update']);
$routes->get('news/(:segment)', [News::class, 'view']);
$routes->get('news', [News::class, 'index']);

$routes->match(['get', 'post'], 'news3/create', [News3::class, 'create']);
$routes->get('news3/edit/(:num)', [News3::class, 'edit']);
$routes->post('news3/update', [News3::class, 'update']);
$routes->get('news3/(:segment)', [News3::class, 'view']);
$routes->get('news3', [News3::class, 'index']);

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
