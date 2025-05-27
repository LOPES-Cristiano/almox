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
$routes->post('home/produtos/inserirProduto', 'HomeController::inserirProduto');
$routes->post('home/produtos/atualizarProduto/(:num)', 'HomeController::atualizarProduto/$1');
$routes->post('home/produtos/inserirCategoria', 'HomeController::inserirCategoria');
$routes->post('home/produtos/inserirUnidadeMedida', 'HomeController::inserirUnidadeMedida');

$routes->get('home/movimentos', 'HomeController::movimentos');
$routes->get('home/movimentos/(:num)', 'HomeController::movimentos/$1');
$routes->post('home/salvarMovimento', 'HomeController::salvarMovimento');
$routes->post('home/salvarTipoMovimento', 'HomeController::salvarTipoMovimento');

$routes->get('relatorios', 'RelatoriosController::index');
$routes->get('relatorios/(:segment)', 'RelatoriosController::filtros/$1');
$routes->post('relatorios/gerar/(:segment)', 'RelatoriosController::gerar/$1');

