<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAdminTable extends Migration
{
    public function up()
    {
        // Only modify password column
        $fields = [
            'password' => [
                'name' => 'password',
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
        ];

        $this->forge->modifyColumn('admin', $fields);
    }

    public function down()
    {
        // Revert password size
        $fields = [
            'password' => [
                'name' => 'password',
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
        ];

        $this->forge->modifyColumn('admin', $fields);
    }
}
