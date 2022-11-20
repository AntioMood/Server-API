<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelevent extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'id';
    protected $allowedFields = [
    'namaEvent','alamat','tgl'
    ];
}
