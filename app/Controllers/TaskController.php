<?php

namespace App\Controllers;

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

    // Get all tasks
    // /tasks, get
    public function index()
    {
        try {
            $tasks = $this->model->findAll();
            return $this->respond($tasks);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    // Create a new task
    // /tasks, post
    public function create()
    {
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
    // /tasks/:id, put
    public function update($id = null)
    {
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
    public function delete($id = null)
    {
        $task = $this->getTaskById($id);
        if (!is_array($task)) {
            return $task;
        }

        if ($this->model->delete($id)) {
            return $this->respond(null, 204); // Return 204 No Content
        }
    }

    // Show a specific task
    public function show($id = null)
    {
        $task = $this->getTaskById($id);
        if (!is_array($task)) {
            return $task;
        }

        return $this->respond($task);
    }
}
