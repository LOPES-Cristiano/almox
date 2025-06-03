<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->post('login/autenticar', 'LoginController::autenticar');
$routes->get('login/logout', 'LoginController::logout');

// Dashboard e relatórios
$routes->get('home', 'HomeController::index');
$routes->get('home/relatorios', 'HomeController::relatorios');
$routes->get('home/gerarRelatorio', 'HomeController::gerarRelatorio');

// Pessoa
$routes->get('pessoa', 'PessoaController::index');
$routes->post('pessoa/salvar', 'PessoaController::salvar');
$routes->post('pessoa/atualizar/(:num)', 'PessoaController::atualizar/$1');
$routes->post('pessoa/inserirTipoPessoa', 'PessoaController::inserirTipoPessoa');

// Produto
$routes->get('produto', 'ProdutoController::index');
$routes->post('produto/inserirProduto', 'ProdutoController::inserirProduto');
$routes->post('produto/atualizarProduto/(:num)', 'ProdutoController::atualizarProduto/$1');
$routes->post('produto/inserirCategoria', 'ProdutoController::inserirCategoria');
$routes->post('produto/inserirUnidadeMedida', 'ProdutoController::inserirUnidadeMedida');

// Movimento
$routes->get('movimento', 'MovimentoController::index');
$routes->get('movimento/(:num)', 'MovimentoController::index/$1');
$routes->post('movimento/salvar', 'MovimentoController::salvar');
$routes->post('movimento/salvarTipoMovimento', 'MovimentoController::salvarTipoMovimento');

