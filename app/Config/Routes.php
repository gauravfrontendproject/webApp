<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'noauth']);
$routes->get('/login', 'Home::login', ['filter' => 'noauth']);
$routes->match(['GET', 'POST'], 'signup', 'Home::signup', ['filter' => 'noauth']);
$routes->match(['GET', 'POST'], 'editUser/(:any)', 'Home::editUser/$1', ['filter' => 'auth']);
// Login should accept POST for authentication
$routes->match(['GET','POST'], 'login', 'Home::login', ['filter' => 'noauth']);
$routes->get('/dashboard', 'Home::dashboard', ['filter' => 'auth']);
$routes->get('/logout', 'Home::logout', ['filter' => 'auth']);