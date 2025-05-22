<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->post('login/autenticar', 'LoginController::autenticar');
$routes->get('login/logout', 'LoginController::logout');

// Dashboard
$routes->get('home', 'HomeController::index');
$routes->get('home/usuarios', 'HomeController::usuarios');
$routes->post('home/usuarios/salvar', 'HomeController::salvarUsuario');
$routes->get('home/usuarios/editar/(:num)', 'HomeController::editarUsuario/$1');
$routes->post('home/usuarios/atualizar/(:num)', 'HomeController::atualizarUsuario/$1');

$routes->get('home/produtos', 'HomeController::produtos');
$routes->get('home/fornecedores', 'HomeController::fornecedores');
$routes->get('home/entrada', 'HomeController::entrada');
$routes->get('home/saida', 'HomeController::saida');
$routes->get('home/relatorios', 'HomeController::relatorios');

