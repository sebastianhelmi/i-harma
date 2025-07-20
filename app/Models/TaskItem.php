<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskItem extends Model
{
    protected $fillable = [
        'task_id',
        'nama_barang',
        'jumlah',
        'satuan',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
