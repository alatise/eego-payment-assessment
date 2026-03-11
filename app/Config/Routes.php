<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// ---------------------------------------------------------------------------
// Payment link generation
// ---------------------------------------------------------------------------
$routes->post('links', 'LinksController::store');
$routes->get('links/success/(:segment)', 'LinksController::success/$1');

// ---------------------------------------------------------------------------
// Payment pages
// ---------------------------------------------------------------------------
$routes->get('pay/(:segment)', 'PayController::show/$1');
$routes->post('pay/(:segment)/process', 'PayController::process/$1');
// $routes->get('pay/(:segment)/complete', 'PayController::complete/$1');