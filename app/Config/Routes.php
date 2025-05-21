<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->post('login/autenticar', 'LoginController::autenticar');
$routes->get('dashboard', 'LoginController::dashboard');
$routes->get('login/logout', 'LoginController::logout');
