<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TaskModel;

class TaskController extends ResourceController
{
    protected $modelName = 'App\Models\TaskModel';
    protected $format = 'json';

    public function index()
    {
        try {
            $tasks = $this->model->findAll();
            return $this->respond($tasks);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function create()
    {
        $input = $this->request->getJSON(true);
        
        // Debug the input data
        log_message('debug', 'Request Input: ' . print_r($input, true));
        
        // Validate input data
        if (!$this->validate([
            'title'       => 'required|string|max_length[255]',
            'status'      => 'permit_empty|in_list[pending,in-progress,completed]',
            'due_date'    => 'permit_empty|valid_date'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
    
        // Prepare data for saving
        $data = [
            'title'       => $input['title'], // Title is required
            'description' => $input['description'] ?? null,
            'status'      => $input['status'] ?? 'pending',// Status can be null and will use DB default
            'due_date'    => $input['due_date'] ?? null,
        ];
    
        try {
            // Attempt to save data to the model
            if ($this->model->save($data)) {
                // Return success response with the saved data
                return $this->respondCreated($data);
            } else {
                // Handle model validation errors
                return $this->failValidationErrors($this->model->errors());
            }
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return $this->failServerError('Failed to create task: ' . $e->getMessage());
        }
    }

    public function update($id = null)
    {
        $input = $this->request->getRawInput();
        
        // Validate input data
        if (!$this->validate([
            'title'       => 'required|string|max_length[255]',
            'status'      => 'permit_empty|in_list[pending,in-progress,completed]',
            'due_date'    => 'permit_empty|valid_date'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Prepare data for updating
        $data = [
            'id'          => $id,
            'title'       => $input['title'],
            'description' => $input['description'] ?? null,
            'status'      => $input['status'] ?? null, // Status can be null and will use DB default
            'due_date'    => $input['due_date'] ?? null,
        ];

        try {
            if ($this->model->save($data)) {
                return $this->respond($data);
            } else {
                return $this->failValidationErrors($this->model->errors());
            }
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function delete($id = null)
    {
        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['id' => $id]);
            } else {
                return $this->failNotFound('Task not found.');
            }
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}
