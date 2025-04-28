<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['lead_id', 'ip_address', 'country', 'state', 'city'];

    /**
     * Relationship with lead.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);  // Belongs to Lead
    }
}
