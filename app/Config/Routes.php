<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('register', 'Auth::register');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

$routes->get('tasks', 'Task::index');
$routes->post('tasks', 'Task::create');
$routes->post('tasks/update/(:num)', 'Task::update/$1');
$routes->get('tasks/delete/(:num)', 'Task::delete/$1');

$routes->get('register', function() {
    return view('register');
});

$routes->get('login', function() {
    return view('login');
});

$routes->get('dashboard', function() {
    return view('dashboard');
}, ['filter' => 'auth']);