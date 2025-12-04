<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAdminTable extends Migration
{
    public function up()
    {
        // Increase password size and add timestamps
        $fields = [
            'password' => [
                'name' => 'password',
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
        ];

        // Modify password column
        $this->forge->modifyColumn('admin', $fields);

        // Add created_at and updated_at if they don't exist
        $cols = [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('admin', $cols);
    }

    public function down()
    {
        // Revert password size to 100
        $fields = [
            'password' => [
                'name' => 'password',
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
        ];

        $this->forge->modifyColumn('admin', $fields);

        // Drop created_at and updated_at
        $this->forge->dropColumn('admin', ['created_at', 'updated_at']);
    }
}
