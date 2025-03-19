<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'generated_by',
        'type',
        'description',
        'report_data',
        'generated_at',
    ];

    protected $casts = [
        'report_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
