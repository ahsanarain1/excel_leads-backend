<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'domain'];
    /**
     * Relationship with leads.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Utility method to fetch campaigns by domain.
     */
    public static function findByDomain(string $domain)
    {
        return self::where('domain', $domain)->first();
    }
}
