<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadDetail extends Model
{
    use HasFactory;
    protected $fillable = ['lead_id', 'key', 'value'];

    /**
     * Relationship with lead.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
