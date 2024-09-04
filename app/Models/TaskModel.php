<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';

    protected $allowedFields = ['title', 'description', 'status', 'due_date'];

    protected $validationRules = [
        'title'       => 'required|string|max_length[255]',
        'description' => 'permit_empty|string',
        'status'      => 'permit_empty|in_list[pending,in-progress,completed]',
        'due_date'    => 'permit_empty|valid_date'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'The title field is required.',
            'string'   => 'The title field must be a string.',
            'max_length' => 'The title field cannot exceed 255 characters.'
        ],
        'status' => [
            'permit_empty'   => 'The status field can be empty and will use the default value if not provided.',
            'in_list'        => 'The status field must be one of the following: pending, in-progress, completed.'
        ],
        'due_date' => [
            'valid_date' => 'The due date field must be a valid date.'
        ]
    ];

    protected $skipValidation = false; // Set to true to skip validation
}
