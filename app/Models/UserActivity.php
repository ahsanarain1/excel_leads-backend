<?php

namespace App\Models;

use App\Enum\ActivityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ip_address',
        'activity_type',
        'lead_id',
    ];
    // protected $casts = [
    //     'activity_type' => ActivityType::class,
    // ];

    /**
     * Get the user associated with the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Scope a query to include activities for a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Utility method to log user activity.
     */
    public static function logActivity($userId, $type, $leadId = null, $ipAddress = null)
    {
        return self::create([
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'activity_type' => $type,
            'lead_id' => $leadId,
        ]);
    }
}
