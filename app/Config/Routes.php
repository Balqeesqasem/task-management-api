<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('tasks', 'TaskController::create');       // Create
$routes->get('tasks', 'TaskController::index');         // Read all tasks
$routes->get('tasks/(:id)', 'TaskController::show/$1'); // Read single task
$routes->put('tasks/(:id)', 'TaskController::update/$1'); // Update
$routes->delete('tasks/(:id)', 'TaskController::delete/$1'); // Delete
