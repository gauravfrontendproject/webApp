<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageUploadModel extends Model
{
    protected $table = 'imageupload';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fkuserid', 'image'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
