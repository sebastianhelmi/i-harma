<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementHistory extends Model
{
    protected $fillable = [
        'spb_id',
        'po_id',
        'status',
        'actor',
        'description',
        'document_type',
        'document_number'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function spb()
    {
        return $this->belongsTo(Spb::class);
    }

    public function po()
    {
        return $this->belongsTo(Po::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor');
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            'ordered' => 'primary',
            default => 'secondary'
        };
    }

    public function getDocumentTypeLabel()
    {
        return match ($this->document_type) {
            'spb' => 'SPB',
            'po' => 'PO',
            default => 'Unknown'
        };
    }
}
