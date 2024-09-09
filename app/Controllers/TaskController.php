<?php

namespace App\Controllers;

use App\Libraries\JWTAuth;

use CodeIgniter\RESTful\ResourceController;

class TaskController extends ResourceController
{
    protected $modelName = "App\Models\TaskModel";
    protected $format = "json";

    // Common function to fetch the task by ID
    private function getTaskById($id)
    {
        $task = $this->model->find($id);
        if (!$task) {
            return $this->failNotFound("Task not found.");
        }
        return $task;
    }

    // Authenticate and authorize the user
    private function authenticateUser()
    {
        $auth = JWTAuth::authenticate($this->request);
        if ($auth['status'] !== 200) {
            return $this->response->setStatusCode($auth['status'], $auth['message']);
        }
        return $auth['user'];
    }

    // Get all tasks
    // /tasks, GET
    public function index()
    {
        $user = $this->authenticateUser();
        if (!$user) {
            return $this->response->setStatusCode(401, 'Unauthorized');
        }

        try {
            $tasks = $this->model->findAll();
            return $this->respond($tasks);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    // Create a new task
    // /tasks, POST
    public function create()
    {
        $user = $this->authenticateUser();
        if (!$user) {
            return $this->response->setStatusCode(401, 'Unauthorized');
        }

        $input = $this->request->getJSON(true);

        if ($this->model->save($input)) {
            // Fetch the newly created record to ensure all fields are returned
            $id = $this->model->getInsertID(); // Get the ID of the newly created record
            $task = $this->getTaskById($id);

            return $this->respondCreated($task);
        } else {
            // Return validation errors if they exist
            return $this->failValidationErrors($this->model->errors());
        }
    }

    // Update an existing task
    // /tasks/:id, PUT
    public function update($id = null)
    {
        $user = $this->authenticateUser();
        if (!$user) {
            return $this->response->setStatusCode(401, 'Unauthorized');
        }

        $task = $this->getTaskById($id);
        if (!is_array($task)) {
            return $task;
        }

        $input = $this->request->getJSON(true);
        
        $data = [
            'title' => $input['title'] ?? $task['title'],
            'description' => $input['description'] ?? $task['description'],
            'status' => $input['status'] ?? $task['status'],
            'due_date' => $input['due_date'] ?? $task['due_date'],
        ];

        if ($this->model->update($id, $data)) {
            return $this->respond([$this->model->find($id)]);
        } else {
            return $this->failValidationErrors($this->model->errors());
        }
    }

    // Delete a task
    // /tasks/:id, DELETE
    public function delete($id = null)
    {
        $user = $this->authenticateUser();
        if (!$user) {
            return $this->response->setStatusCode(401, 'Unauthorized');
        }

        $task = $this->getTaskById($id);
        if (!is_array($task)) {
            return $task;
        }

        if ($this->model->delete($id)) {
            return $this->respond(null, 204); // Return 204 No Content
        }
    }

    // Show a specific task
    // /tasks/:id, GET
    public function show($id = null)
    {
        $user = $this->authenticateUser();
        if (!$user) {
            return $this->response->setStatusCode(401, 'Unauthorized');
        }

        $task = $this->getTaskById($id);
        if (!is_array($task)) {
            return $task;
        }

        return $this->respond($task);
    }
}
