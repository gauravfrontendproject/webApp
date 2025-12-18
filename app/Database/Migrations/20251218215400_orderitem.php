<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'item_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'item_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00
            ],
            'items_qty' => [
                'type' => 'INT',
                'null' => false,
            ],
            'fkorder_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'order_date' => [
                'type' => 'DATETIME'
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        // $this->forge->addForeignKey('fk_orderid', 'order', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}
