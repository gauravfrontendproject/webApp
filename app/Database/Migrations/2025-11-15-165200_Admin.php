<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Admin extends \CodeIgniter\Database\Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type' => 'varchar',
                'constraint' => '100',
            ],
            'phone' => [
                'type' => 'varchar',
                'constraint' => '100',
            ],
            'password' => [
                'type' => 'varchar',
                'constraint' => '255',
            ],
            'user_type' => [
                'type' => 'ENUM("admin", "user")',
                'default' => 'user',
                'null' => false,
            ],
            // Add timestamps so model can save created_at/updated_at
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('admin', true);
    }

    public function down()
    {
        $this->forge->dropTable('admin');
    }
}