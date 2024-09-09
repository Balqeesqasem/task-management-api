<?php

namespace App\Controllers;

class TaskController extends BaseController
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
    public function index()
    {
        if (!$this->authenticateUser()) {
            // If authentication fails, this line prevents further execution
            return;
        }

        try {
            $tasks = $this->model->findAll();
            return $this->respond($tasks);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    // Create a new task
    public function create()
    {
        if (!$this->authenticateUser()) {
            return; // Authentication failed, do nothing
        }

        $input = $this->request->getJSON(true);

        if ($this->model->save($input)) {
            $id = $this->model->getInsertID();
            $task = $this->getTaskById($id);
            return $this->respondCreated($task);
        } else {
            return $this->failValidationErrors($this->model->errors());
        }
    }

    // Update an existing task
    public function update($id = null)
    {
        if (!$this->authenticateUser()) {
            return; // Authentication failed, do nothing
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
    public function delete($id = null)
    {
        if (!$this->authenticateUser()) {
            return; // Authentication failed, do nothing
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
    public function show($id = null)
    {
        if (!$this->authenticateUser()) {
            return; // Authentication failed, do nothing
        }

        $task = $this->getTaskById($id);
        if (!is_array($task)) {
            return $task;
        }

        return $this->respond($task);
    }
}
