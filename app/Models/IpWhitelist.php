<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpWhitelist extends Model
{
    protected $table = 'ip_whitelist'; // Use snake_case table name
    use HasFactory;
    protected $fillable = ['ip_address'];
}
