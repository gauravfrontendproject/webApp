<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Home::login');
$routes->match(['GET', 'POST'], 'signup', 'Home::signup');
$routes->match(['GET', 'POST'], 'editUser/(:any)', 'Home::editUser/$1', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'upload/(:any)', 'Home::upload/$1', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'exportuserdata', 'Home::exportuserdata', ['filter' => 'auth']);

// Login should accept POST for authentication
$routes->match(['GET', 'POST'], 'login', 'Home::login');
$routes->get('/dashboard', 'Home::dashboard', ['filter' => 'auth']);
$routes->get('/logout', 'Home::logout', ['filter' => 'auth']);
$routes->get('deleteUser/(:any)', 'Home::deleteUser/$1', ['filter' => 'auth']);

// Sigin Routes
$routes->match(['GET', 'POST'], 'signin', 'Home::signin');