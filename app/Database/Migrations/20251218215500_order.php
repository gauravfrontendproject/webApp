<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrders extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'order_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'order_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'order_date' => [
                'type' => 'DATETIME'
            ],
            'order_status' => [
                // Use matching default values that exist in the ENUM
                'type' => 'ENUM("Pending", "Accept", "Out_for_delivery", "Delivered")',
                'default' => 'Pending',
                'null' => false
            ],
            'order_type' => [
                // Payment type: allow COD or Online and default to COD
                'type' => 'ENUM("Online", "COD")',
                'default' => 'COD',
                'null' => false
            ],
            'fk_user_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        // $this->forge->addForeignKey('fk_user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        // Use plural table name to avoid reserved-word conflicts
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
