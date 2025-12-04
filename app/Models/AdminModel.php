<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id';

    // Updated to match the database migration columns
    protected $allowedFields = [
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'created_at',
        'updated_at',
    ];

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data)
    {
        $data = $this->passwordHash($data);

        // Set created_at timestamp
        if (! isset($data['data']['created_at'])) {
            $data['data']['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data = $this->passwordHash($data);

        // Set updated_at timestamp
        if (! isset($data['data']['updated_at'])) {
            $data['data']['updated_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    protected function passwordHash(array $data)
    {
        // Hash password if it exists and is not empty
        if (isset($data['data']['password']) && ! empty($data['data']['password'])) {
            // Only hash if it's not already hashed (check for bcrypt hash pattern)
            if (strpos($data['data']['password'], '$2') !== 0) {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            }
        }
        return $data;
    }
}
