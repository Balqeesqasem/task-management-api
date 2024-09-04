<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'status'      => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'in-progress', 'completed'],
                'default'    => 'pending',
            ],
            'due_date'    => [
                'type'       => 'DATE',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true); // Primary key
        $this->forge->createTable('tasks');
    }

    public function down()
    {
        $this->forge->dropTable('tasks');
    }
}
