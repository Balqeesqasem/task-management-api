<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('tasks', 'TaskController::create');       // Create
$routes->get('tasks', 'TaskController::index');         // Read all tasks
$routes->get('tasks/(:num)', 'TaskController::show/$1');            // Read single task
$routes->put('tasks/(:num)', 'TaskController::update/$1');          // Update
$routes->delete('tasks/(:num)', 'TaskController::delete/$1'); 
